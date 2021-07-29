<?php

namespace App\DataFixtures;

use App\Entity\ModuleCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ModuleCategoryFixtures extends Fixture {
    public function load(ObjectManager $manager) {
        $category = (new ModuleCategory())->setName(ModuleCategory::PHP);
        $manager->persist($category);

        $manager->flush();
    }
}
