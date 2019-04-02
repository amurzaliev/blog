<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

class PostRatingService
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $manager)
    {
        $this->postRepository = $postRepository;
        $this->manager        = $manager;
    }

    public function recalculateRating(Post $post)
    {
        $avgRating = $this->postRepository->getAverageRating($post);
        $post->setAvgRating($avgRating);
        $this->manager->flush();
    }
}