<?php

namespace App\Controller\Profile;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 *
 * Class CommentController
 * @package App\Controller\Profile
 */
class CommentController extends Controller
{
    /**
     * @Route("/comments", name="profile_comments")
     *
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function listAction(CommentRepository $commentRepository)
    {
        $adapter    = new DoctrineORMAdapter($commentRepository->getLastCommentsByUserQuery($this->getUser()));
        $pagerFanta = new Pagerfanta($adapter);

        return $this->render('profile/comment/list.html.twig', [
            'pagination' => $pagerFanta
        ]);
    }

    /**
     * @Route("/comment/{id}/remove", name="profile_comment_remove")
     *
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function removeAction(Comment $comment, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('remove', $comment);
        $comment->setDeleted(true);
        $manager->flush();

        return $this->redirectToRoute('profile_comments');
    }
}