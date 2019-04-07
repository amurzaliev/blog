<?php

namespace App\Controller\API;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Service\API\ApiAuthenticatorService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 *
 * Class CommentController
 * @package App\Controller\API
 */
class CommentController
{
    /**
     * @Route("/post/{postId}/comments", name="api_comment_get_comments", methods={"GET"})
     *
     * @param int $postId
     * @param CommentRepository $commentRepository
     * @param PostRepository $postRepository
     * @return JsonResponse
     */
    public function getComments(int $postId, CommentRepository $commentRepository, PostRepository $postRepository)
    {
        try {
            $post = $postRepository->find($postId);

            if (!$post) {
                return new JsonResponse(['success' => false, 'error' => 'Post is not found']);
            }

            $comments = $commentRepository->getPostsByPost($post);

            return new JsonResponse(['comments' => $comments, 'success' => true]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * @Route("/post/{postId}/comment/add", name="api_comment_add")
     *
     * @param int $postId
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ApiAuthenticatorService $apiAuthenticatorService
     * @param PostRepository $postRepository
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function addComment(int $postId, Request $request, EntityManagerInterface $manager, ApiAuthenticatorService $apiAuthenticatorService, PostRepository $postRepository, ValidatorInterface $validator)
    {
        try {
            $user = $apiAuthenticatorService->auth($request);
            if (!$user) {
                return new JsonResponse(['success' => false, 'error' => 'Invalid credentials.']);
            }

            $post = $postRepository->find($postId);

            if (!$post) {
                return new JsonResponse(['success' => false, 'error' => 'Post is not found']);
            }

            $comment = new Comment();
            $comment
                ->setAuthor($user)
                ->setPost($post)
                ->setContent($request->get('content'));

            $errors = $validator->validate($comment);

            if (count($errors) > 0) {
                $errorsString = (string)$errors;

                return new JsonResponse(['success' => false, 'error' => $errorsString]);
            }

            $manager->persist($comment);
            $manager->flush();

            return new JsonResponse(['success' => true]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * @Route("/comment/{id}/remove", name="api_comment_remove", methods={"DELETE"})
     *
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ApiAuthenticatorService $apiAuthenticatorService
     * @param CommentRepository $commentRepository
     * @return JsonResponse
     */
    public function removeComment(int $id, Request $request, EntityManagerInterface $manager, ApiAuthenticatorService $apiAuthenticatorService, CommentRepository $commentRepository)
    {
        try {
            $comment = $commentRepository->find($id);
            $user    = $apiAuthenticatorService->auth($request);

            if (!$comment) {
                return new JsonResponse(['success' => false, 'error' => 'Comment is not found']);
            }

            if ($user && $comment->getAuthor() === $user) {
                $manager->remove($comment);
                $manager->flush();

                return new JsonResponse(['success' => true]);
            } else {
                return new JsonResponse(['success' => false, 'error' => 'Invalid credentials.']);
            }
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}