<?php

namespace App\DataFixtures;

use App\Entity\Module;
use App\Entity\ModuleCategory;
use App\Repository\ModuleCategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ModuleFixtures extends Fixture implements DependentFixtureInterface {
    public function __construct(
        private ModuleCategoryRepository $moduleCategoryRepository
    ) {
    }

    public function load(ObjectManager $manager) {
        $category = $this->moduleCategoryRepository->findOneBy(['name' => ModuleCategory::PHP]);

        // >>> PHP-SECURITY-CHECKER
        $module = (new Module())
            ->setName('php-security-checker')
            ->setCategory($category);
        $manager->persist($module);
        // <<< PHP-SECURITY-CHECKER

        // >>> PHPSTAN
        $module = (new Module())
            ->setName('phpstan')
            ->setCategory($category);
        $manager->persist($module);
        // <<< PHPSTAN

        $manager->flush();
    }

    public function getDependencies(): array {
        return [
            ModuleCategoryFixtures::class,
        ];
    }
}
