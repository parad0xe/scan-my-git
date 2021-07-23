<?php
namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use App\Security\Exception\NotVerifiedEmailException;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use Symfony\Component\HttpFoundation\RedirectResponse;
use KnpU\OAuth2ClientBundle\Client\Provider\GithubClient;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class GithubAuthenticator extends AbstractAuthenticator
{

    use TargetPathTrait;

    private RouterInterface $router;
    private ClientRegistry $clientRegistry;
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    public function __construct (RouterInterface $router, ClientRegistry $clientRegistry, UserRepository $userRepository, EntityManagerInterface $em) {

        $this->router = $router;
        $this->clientRegistry = $clientRegistry;
        $this->userRepository = $userRepository;
        $this->em = $em;

    }

    /**
     * Si la route correspond à celle attendue, alors on déclenche cet authenticator
    **/
    public function supports(Request $request): ?bool
    {
        return 'oauth_check' === $request->attributes->get('_route') && $request->get('service') === 'github';
    }

    public function authenticate(Request $request): PassportInterface
    {
        $provider = new \League\OAuth2\Client\Provider\Github([
            'clientId'          => $_ENV['OAUTH_GITHUB_CLIENT_ID'],
            'clientSecret'      => $_ENV['OAUTH_GITHUB_CLIENT_SECRET'],
            'redirectUri'       => $_ENV['URI_GITHUB_REDIRECT'],
        ]);
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $request->get('code')
        ]);
        $token = $accessToken->getToken();
        $owner = $provider->getResourceOwner($accessToken);
        if (null === $token) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        $user = $this->userRepository->findOneBy(['username' => $owner->toArray()['login']]);
        if(!$user){
            $user = new User();
            $now = new \DateTimeImmutable();
            $user->setLastRegisteredAt($now);
            $user->setGithubId($owner->toArray()['id']);
            $user->setGithubToken($token);
            $user->setUsername($owner->toArray()['login']);
            $this->em->persist($user);
            $this->em->flush();
        };
            
        return new SelfValidatingPassport(new UserBadge($token,function ($userIdentifier) use ($user) {
            return $user;
        }));
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response

    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
       return null;
    }

}