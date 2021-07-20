<?php

namespace App\Service;

use App\Classes\ModuleProxy\Proxy__ModuleEntity__;
use App\Entity\Context;
use App\Entity\ContextModule;
use App\Entity\Module;
use App\Repository\ContextModuleRepository;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;

class ModuleManager {

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ModuleRepository $moduleRepository,
        private ContextModuleRepository $contextModuleRepository
    ) {
    }

    /** @return Proxy__ModuleEntity__[] */
    public function loadAll(Context $context = null): array {
        $modules = $this->moduleRepository->findAll();

        return array_map(function (Module $module) use ($context) {
            return $this->load($module->getId(), $context);
        }, $modules);
    }

    /**
     * Load a module with a specific ID or specific criteria.
     *
     * @param array|int    $criteria module id or array of criteria
     * @param Context|null $context specify the context for load definition data saved in database
     *
     * @return Proxy__ModuleEntity__|null
     */
    public function load(array|int $criteria, Context $context = null): ?Proxy__ModuleEntity__ {
        if (is_int($criteria)) {
            $criteria = ["id" => $criteria];
        }

        $module = $this->moduleRepository->findOneBy($criteria);

        if (!$module) {
            return null;
        }

        $proxy__ModuleEntity__ = new Proxy__ModuleEntity__($module);

        if ($context) {
            $context_module = $this->contextModuleRepository->findOneBy(['context' => $context, 'module' => $module]);

            if ($context_module) {
                $proxy__ModuleEntity__->getCliParameters()->bind($context_module->getParameters());
            }
        }

        return $proxy__ModuleEntity__;
    }

    /**
     * Specify module to attach to context.
     */
    public function attach(Context $context, Proxy__ModuleEntity__ $proxy__ModuleEntity__): ContextModule {
        $context_module = $this->contextModuleRepository->findOneBy(['context' => $context, 'module' => $proxy__ModuleEntity__->getModule()]);

        if (!$context_module) {
            $context_module = new ContextModule();
            $context_module->setModule($proxy__ModuleEntity__->getModule())
                ->setContext($context)
                ->setCommand($proxy__ModuleEntity__->getCliParameters()->generateCommand())
                ->setParameters($proxy__ModuleEntity__->getCliParameters()->extractValues());
        } else {
            $context_module->setCommand($proxy__ModuleEntity__->getCliParameters()->generateCommand())
                ->setParameters($proxy__ModuleEntity__->getCliParameters()->extractValues());
        }

        $this->entityManager->persist($context_module);
        $this->entityManager->flush();

        return $context_module;
    }

    /**
     * Specify module to detach from context.
     */
    public function detach(Context $context, Proxy__ModuleEntity__ $proxy__ModuleEntity__) {
        $context_module = $this->contextModuleRepository->findOneBy(['context' => $context, 'module' => $proxy__ModuleEntity__->getModule()]);

        if (!$context_module) {
            return;
        }

        $this->entityManager->remove($context_module);
        $this->entityManager->flush();
    }
}
