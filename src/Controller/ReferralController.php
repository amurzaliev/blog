<?php

namespace App\Controller;

use App\Entity\ReferralVisitor;
use App\Repository\ReferralVisitorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/referral")
 *
 * Class ReferralController
 * @package App\Controller
 */
class ReferralController extends Controller
{
    /**
     * @Route("/", name="referral_index")
     *
     * @param Request $request
     * @param ReferralVisitorRepository $referralVisitorRepository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function index(Request $request, ReferralVisitorRepository $referralVisitorRepository, EntityManagerInterface $manager)
    {
        if ($hash = $request->get('referral')) {
            if (!$referralVisitorRepository->findOneBy(['referralHash' => $hash])) {
                $referralVisitor = new ReferralVisitor();
                $referralVisitor
                    ->setReferralHash($hash)
                    ->setVisitorIp($request->getClientIp());

                $manager->persist($referralVisitor);
                $manager->flush();
            }
        }

        $referralHash = md5($request->getClientIp());
        $visits = $referralVisitorRepository->findAll();

        return $this->render('referral/index.html.twig', [
            'referralHash' => $referralHash,
            'visits'       => $visits,
        ]);
    }
}
