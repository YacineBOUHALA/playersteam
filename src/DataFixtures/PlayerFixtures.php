<?php

namespace App\DataFixtures;

use App\Entity\NationalTeam;
use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class PlayerFixtures extends Fixture implements OrderedFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i <50 ; $i++)
        {
            $entity = new Player();
            $entity
                ->setFirstname('Firtstname'.$i)
                ->setLastname('Lasttname'.$i)
                ->setNumber(random_int(1, 11))
                ->setPortrait('img'.$i.'.jpg')
                ->setBirthday(new \DateTime('2000-01-01'))
                ;
            $entity->setTeam(
                $this->getReference("refTeam".random_int(0,4))
            );
            $entity->setNationalTeam(
                $this->getReference("refNationalTeam".random_int(0,49))
            );

            $manager->persist($entity);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getOrder(): array
    {
        return [
            Team::class,
            NationalTeam::class,
        ];
    }
}
