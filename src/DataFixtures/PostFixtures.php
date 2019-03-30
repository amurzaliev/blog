<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var User $bloggerOne */
        $bloggerOne = $this->getReference(UserFixtures::USER_BLOGGER_ONE);
        /** @var User $bloggerTwo */
        $bloggerTwo = $this->getReference(UserFixtures::USER_BLOGGER_TWO);

        $post = new Post();
        $post
            ->setAuthor($bloggerOne)
            ->setTitle('Blogger one post')
            ->setContent('<p>Content <strong>blogger</strong> one post</p>')
            ->setSlug('blogger-one-post')
            ->setDescription('Blogger one description')
            ->setKeywords('blogger one, post one');
        $manager->persist($post);

        $post = new Post();
        $post
            ->setAuthor($bloggerTwo)
            ->setTitle('Blogger two post')
            ->setContent('<p>Content <strong>blogger</strong> two post</p>')
            ->setSlug('blogger-two-post')
            ->setDescription('Blogger two description')
            ->setKeywords('blogger two, post two');
        $manager->persist($post);

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
