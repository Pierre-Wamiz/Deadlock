<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserDataFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (['alice@example.com', 'bob@example.com'] as $email) {
            $user = (new User())
                ->setEmail($email)
            ;
            $manager->persist($user);
        }
        $manager->flush();
    }
}
