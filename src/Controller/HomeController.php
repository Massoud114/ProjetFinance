<?php

namespace App\Controller;

use App\Repository\DepenseRepository;
use App\Repository\RevenuRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	 * @Route("/", name="home")
	 * @IsGranted("IS_AUTHENTICATED_FULLY")
	 * @param DepenseRepository $depenseRepository
	 * @param RevenuRepository $revenuRepository
	 * @return Response
	 */
    public function index(DepenseRepository $depenseRepository, RevenuRepository $revenuRepository)
    {
    	$sumRevenu = intval($revenuRepository->getSumOfRevenu());
    	$sumDepense = intval($depenseRepository->getSumOfDepense());
        return $this->render('home/index.html.twig', [
            'active' => 'solde',
			'solde' => ceil($sumRevenu - $sumDepense)
        ]);
    }
}
