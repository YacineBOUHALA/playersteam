<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i =0; $i < 5; $i++){
            $entity = new Team();
            $entity
                ->setName('team'.$i);
            $this->addReference("refTeam".$i, $entity);

            $manager->persist($entity);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

}
