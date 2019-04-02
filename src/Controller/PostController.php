<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\PostRating;
use App\Form\CommentType;
use App\Form\PostRatingType;
use App\Repository\PostRatingRepository;
use App\Repository\PostRepository;
use App\Service\PostRatingService;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 *
 * Class PostController
 * @package App\Controller
 */
class PostController extends Controller
{
    /**
     * @Route("/", name="post_index")
     *
     * @param PostRepository $postRepository
     * @return Response
     */
    public function indexAction(PostRepository $postRepository)
    {
        $adapter    = new DoctrineORMAdapter($postRepository->getLastPostsQuery());
        $pagerFanta = new Pagerfanta($adapter);

        return $this->render('post/index.html.twig', [
            'pagination' => $pagerFanta
        ]);
    }

    /**
     * @Route("/{slug}", name="post_show")
     *
     * @param Post $post
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param PostRatingRepository $postRatingRepository
     * @param PostRatingService $postRatingService
     * @return Response
     */
    public function showAction(Post $post, Request $request, EntityManagerInterface $manager, PostRatingRepository $postRatingRepository, PostRatingService $postRatingService)
    {
        $comment     = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setPost($post);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        }

        $ratingFormView = null;

        if (!$this->getUser()) {
            $ratingFormView = null;
        } elseif ($postRatingRepository->findUserPostRating($this->getUser()) || $post->getAuthor() === $this->getUser()) {
            $ratingFormView = null;
        } else {
            $postRating = new PostRating();
            $ratingForm = $this->createForm(PostRatingType::class, $postRating);
            $ratingForm->handleRequest($request);

            if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {
                $postRating->setPost($post);
                $postRating->setUser($this->getUser());

                $manager->persist($postRating);
                $manager->flush();

                $postRatingService->recalculateRating($post);

                return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
            }

            $ratingFormView = $ratingForm->createView();
        }

        return $this->render('post/show.html.twig', [
            'post'        => $post,
            'commentForm' => $commentForm->createView(),
            'ratingForm'  => $ratingFormView
        ]);
    }
}
