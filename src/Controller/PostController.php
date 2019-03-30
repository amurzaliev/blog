<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
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
     * @param Request $request
     * @param PostRepository $postRepository
     * @return Response
     */
    public function indexAction(Request $request, PostRepository $postRepository)
    {
        $adapter    = new DoctrineORMAdapter($postRepository->createQueryBuilder('p'));
        $pagerFanta = new Pagerfanta($adapter);

        return $this->render('post/index.html.twig', [
            'pagination' => $pagerFanta
        ]);
    }

    /**
     * @Route("/show/{slug}", name="post_show")
     *
     * @param Post $post
     * @return Response
     */
    public function showAction(Post $post)
    {
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }
}
