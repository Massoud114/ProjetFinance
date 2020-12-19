<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Entity\OperationSearch;
use App\Form\DepenseType;
use App\Form\OperationSearchType;
use App\Repository\DepenseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/depense")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class DepenseController extends AbstractController
{
	/**
	 * @Route("/", name="depense_index", methods={"GET"})
	 * @param DepenseRepository $depenseRepository
	 * @param Request $request
	 * @param PaginatorInterface $paginator
	 * @param Security $security
	 * @return Response
	 */
    public function index(DepenseRepository $depenseRepository, Request $request, PaginatorInterface $paginator, Security $security): Response
    {
    	$depenses = $paginator->paginate(
    		$depenseRepository->getLatestQuery($security->getUser()),
			$request->get('page', 1)
		);
    	return $this->render('depense/index.html.twig', [
    		'depenses' => $depenses,
			'active' => 'depense'
		]);
    }

	/**
	 * @Route("/new", name="depense_new", methods={"GET","POST"})
	 * @param Request $request
	 * @param Security $security
	 * @return Response
	 */
    public function new(Request $request, Security $security): Response
    {
        $depense = new Depense();
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);
		$user = $security->getUser();
		$depense->setUser($user);

        if ($form->isSubmitted() && $form->isValid()) {


        	if ($user->getSolde() < $depense->getAmount()){
				$this->addFlash("error", "Solde inférieure à la dépense émise");
				return $this->render('depense/new.html.twig', [
					'depense' => $depense,
					'form' => $form->createView(),
					'active' => 'depense_new'
				]);
			}

        	$user->setSolde(intval($user->getSolde() - $depense->getAmount()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($depense);
            $entityManager->flush();

			$this->addFlash("success", "Dépense enregistrée");
            return $this->redirectToRoute('depense_index');
		}

        return $this->render('depense/new.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
			'active' => 'depense_new'
        ]);
    }

	/**
	 * @Route("/{id}", name="depense_delete", methods={"DELETE"})
	 * @param Request $request
	 * @param Depense $depense
	 * @return Response
	 */
    public function delete(Request $request, Depense $depense): Response
    {
        if ($this->isCsrfTokenValid('delete'.$depense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($depense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('depense_index');
    }
}
