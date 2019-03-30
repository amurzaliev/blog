<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_ADMIN = 'admin-user';
    public const USER_BLOGGER_ONE = 'user-blogger-one';
    public const USER_BLOGGER_TWO = 'user-blogger-two';

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
        $this->addReference(self::USER_ADMIN, $admin);

        $user = new User();
        $user
            ->setEmail('user1@mail.com')
            ->setUsername('user1')
            ->setPlainPassword('12345')
            ->setEnabled(true)
            ->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $this->addReference(self::USER_BLOGGER_ONE, $user);

        $user = new User();
        $user
            ->setEmail('user2@mail.com')
            ->setUsername('user2')
            ->setPlainPassword('12345')
            ->setEnabled(true)
            ->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $this->addReference(self::USER_BLOGGER_TWO, $user);

        $manager->flush();
    }
}
