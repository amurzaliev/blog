<?php

namespace App\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 *
 * Class MainController
 * @package App\Controller\Profile
 */
class MainController extends Controller
{
    /**
     * @Route("/", name="profile_index")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('profile/main/index.html.twig');
    }
}