<?php

namespace App\Controller\Profile;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 *
 * Class PostController
 * @package App\Controller\Profile
 */
class PostController extends Controller
{
    /**
     * @Route("/posts", name="profile_posts")
     *
     * @param PostRepository $postRepository
     * @return Response
     */
    public function listAction(PostRepository $postRepository)
    {
        $adapter    = new DoctrineORMAdapter($postRepository->getLastPostsByUserQuery($this->getUser()));
        $pagerFanta = new Pagerfanta($adapter);

        return $this->render('profile/post/list.html.twig', [
            'pagination' => $pagerFanta
        ]);
    }

    /**
     * @Route("/post/add", name="profile_post_add")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, EntityManagerInterface $manager)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('profile_posts');
        }

        return $this->render('profile/post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/{id}/edit", name="profile_post_edit")
     *
     * @param Request $request
     * @param Post $post
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function editAction(Request $request, Post $post, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('edit', $post);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('profile_posts');
        }

        return $this->render('profile/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/{id}/remove", name="profile_post_remove")
     *
     * @param Post $post
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function removeAction(Post $post, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('remove', $post);
        $manager->remove($post);
        $manager->flush();

        return $this->redirectToRoute('profile_posts');
    }
}