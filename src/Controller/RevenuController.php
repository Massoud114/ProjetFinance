<?php

namespace App\Controller;

use App\Entity\OperationSearch;
use App\Entity\Revenu;
use App\Form\OperationSearchType;
use App\Form\RevenuType;
use App\Repository\RevenuRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
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
	 * @param Security $security
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @return Response
	 */
    public function index(RevenuRepository $revenuRepository, Security $security, PaginatorInterface $paginator, Request $request):
	Response
    {
    	$revenus = $paginator->paginate(
    		$revenuRepository->getLatestQuery($security->getUser()),
			$request->get('page', 1)
		);
    	return $this->render('revenu/index.html.twig', [
    		'revenus' => $revenus,
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
		$user = $security->getUser();
		$revenu->setUser($user);

		if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setSolde(intval($user->getSolde() + $revenu->getAmount()));
            $entityManager->persist($revenu);
            $entityManager->flush();

			$this->addFlash("success", "Revenu crée");
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
		$this->addFlash("success", "Revenu supprimé");
        return $this->redirectToRoute('revenu_index');
    }
}
