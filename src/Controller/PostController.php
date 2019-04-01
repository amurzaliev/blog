<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
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
     * @return Response
     */
    public function showAction(Post $post, Request $request, EntityManagerInterface $manager)
    {
        $formView = null;

        if ($this->getUser()) {
            $comment = new Comment();

            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setAuthor($this->getUser());
                $comment->setPost($post);

                $manager->persist($comment);
                $manager->flush();

                return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
            }

            $formView = $form->createView();
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'form' => $formView
        ]);
    }
}
