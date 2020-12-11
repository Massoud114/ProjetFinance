<?php

namespace App\Controller;

use App\Entity\OperationSearch;
use App\Entity\Revenu;
use App\Form\OperationSearchType;
use App\Form\RevenuType;
use App\Repository\RevenuRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/revenu")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class RevenuController extends AbstractController
{
	/**
	 * @Route("/", name="revenu_index", methods={"GET"})
	 * @param RevenuRepository $revenuRepository
	 * @param Request $request
	 * @return Response
	 */
    public function index(RevenuRepository $revenuRepository, Request $request): Response
    {
		$search = new OperationSearch();
		$form = $this->createForm(OperationSearchType::class, $search);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			if ($search->getFirstDate() == $search->getSecondDate()){
				$search->setSecondDate($search->getSecondDate()->add(new \DateInterval('P1D')));
			}
			return $this->render('revenu/index.html.twig', [
				'revenus' => $revenuRepository->findByPeriod($search),
				'active' => 'consultation'
			]);
		}

		return $this->render('revenu/form.html.twig', [
			'form' => $form->createView(),
			'active' => 'consultation'
		]);
    }

	/**
	 * @Route("/new", name="revenu_new", methods={"GET","POST"})
	 * @param Request $request
	 * @param Security $security
	 * @return Response
	 */
    public function new(Request $request, Security $security): Response
    {
        $revenu = new Revenu();
        $form = $this->createForm(RevenuType::class, $revenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($revenu);
            $entityManager->flush();

            $user = $security->getUser();

            // On met à jour le solde de l'utilisateur
            $user->setSolde(intval($user->getSolde()) + intval($revenu->getAmount()));

            return $this->redirectToRoute('revenu_index');
        }

        return $this->render('revenu/new.html.twig', [
            'revenu' => $revenu,
            'form' => $form->createView(),
			'active' => 'new_consultation'
        ]);
    }

	/**
	 * @Route("/{id}", name="revenu_delete", methods={"DELETE"})
	 * @param Request $request
	 * @param Revenu $revenu
	 * @return Response
	 */
    public function delete(Request $request, Revenu $revenu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$revenu->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($revenu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('revenu_index');
    }
}
