<?php

namespace App\DataFixtures;

use App\Entity\NationalTeam;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NationalTeamFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i <50; $i++){
            $entity = new NationalTeam();
            $entity->setName('NationalTeam'.$i);
            $entity->setDrapeau('drapeau'.$i);

            $this->addReference("refNationalTeam".$i, $entity);

            $manager->persist($entity);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
