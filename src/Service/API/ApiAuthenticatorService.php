<?php

namespace App\Service\API;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiAuthenticatorService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository  = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function auth(Request $request)
    {
        $user = $this->userRepository->findOneBy(['email' => $request->headers->get('php-auth-user')]);

        if (!$user) {
            return false;
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $request->headers->get('php-auth-pw'))) {
            return false;
        }

        return $user;
    }
}