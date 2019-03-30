<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin
            ->setEmail('admin@mail.com')
            ->setUsername('admin')
            ->setPlainPassword('12345')
            ->setEnabled(true)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $user = new User();
        $user
            ->setEmail('user@mail.com')
            ->setUsername('user')
            ->setPlainPassword('12345')
            ->setEnabled(true)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $manager->flush();
    }
}
