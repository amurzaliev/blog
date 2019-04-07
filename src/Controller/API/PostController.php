<?php

namespace App\Controller\API;

use App\Entity\Post;
use App\Entity\PostRating;
use App\Repository\PostRatingRepository;
use App\Repository\PostRepository;
use App\Service\API\ApiAuthenticatorService;
use App\Service\PostRatingService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 *
 * Class PostController
 * @package App\Controller\API
 */
class PostController
{
    /**
     * @Route("/posts", name="api_posts", methods={"GET"})
     *
     * @param Request $request
     * @param PostRepository $postRepository
     * @return JsonResponse
     */
    public function getPosts(Request $request, PostRepository $postRepository)
    {
        try {
            $page  = $request->query->getInt('page', 1);
            $limit = $request->query->getInt('limit', 5);
            $posts = $postRepository->getLastPostsApiQuery($page, $limit);

            return new JsonResponse(['posts' => $posts, 'success' => true]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * @Route("/post/add", name="api_post_add", methods={"POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ApiAuthenticatorService $apiAuthenticatorService
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function addPost(Request $request, EntityManagerInterface $manager, ApiAuthenticatorService $apiAuthenticatorService, ValidatorInterface $validator)
    {
        try {
            $user = $apiAuthenticatorService->auth($request);
            if (!$user) {
                return new JsonResponse(['success' => false, 'error' => 'Invalid credentials.']);
            }

            $post = new Post();
            $post
                ->setTitle($request->get('title'))
                ->setSlug($request->get('slug'))
                ->setContent($request->get('content'))
                ->setAuthor($user);

            $errors = $validator->validate($post);

            if (count($errors) > 0) {
                $errorsString = (string)$errors;

                return new JsonResponse(['success' => false, 'error' => $errorsString]);
            }

            $manager->persist($post);
            $manager->flush();

            return new JsonResponse(['success' => true]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * @Route("/post/{id}/remove", name="api_post_remove", methods={"DELETE"})
     *
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ApiAuthenticatorService $apiAuthenticatorService
     * @param PostRepository $postRepository
     * @return JsonResponse
     */
    public function removePost(int $id, Request $request, EntityManagerInterface $manager, ApiAuthenticatorService $apiAuthenticatorService, PostRepository $postRepository)
    {
        try {
            $post = $postRepository->find($id);
            $user = $apiAuthenticatorService->auth($request);

            if (!$post) {
                return new JsonResponse(['success' => false, 'error' => 'Post is not found']);
            }

            if ($user && $post->getAuthor() === $user) {
                $manager->remove($post);
                $manager->flush();

                return new JsonResponse(['success' => true]);
            } else {
                return new JsonResponse(['success' => false, 'error' => 'Invalid credentials.']);
            }
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * @Route("/post/{id}/rate", name="api_post_remove", methods={"POST"})
     *
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ApiAuthenticatorService $apiAuthenticatorService
     * @param PostRepository $postRepository
     * @param PostRatingService $postRatingService
     * @param PostRatingRepository $postRatingRepository
     * @return JsonResponse
     */
    public function ratePost(int $id, Request $request, EntityManagerInterface $manager, ApiAuthenticatorService $apiAuthenticatorService, PostRepository $postRepository, PostRatingService $postRatingService, PostRatingRepository $postRatingRepository)
    {
        try {
            $user = $apiAuthenticatorService->auth($request);

            if (!$user) {
                return new JsonResponse(['success' => false, 'error' => 'Invalid credentials.']);
            }

            $post = $postRepository->find($id);

            if (!$post) {
                return new JsonResponse(['success' => false, 'error' => 'Post is not found.']);
            }

            $rating = $request->request->getInt('rating');

            if ($rating <= 0 || $rating > 5) {
                return new JsonResponse(['success' => false, 'error' => 'Rating must be in 1 to 5 range.']);
            }

            if ($postRatingRepository->findUserPostRating($user) || $post->getAuthor() === $user) {
                return new JsonResponse(['success' => false, 'error' => 'Unable to leave a rating.']);
            }

            $postRating = new PostRating();
            $postRating
                ->setPost($post)
                ->setUser($user)
                ->setRating($rating);

            $manager->persist($postRating);
            $manager->flush();

            $postRatingService->recalculateRating($post);

            return new JsonResponse(['success' => true]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}