<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Entity\OperationSearch;
use App\Form\DepenseType;
use App\Form\OperationSearchType;
use App\Repository\DepenseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
	 * @return Response
	 */
    public function index(DepenseRepository $depenseRepository, Request $request): Response
    {
		$search = new OperationSearch();
		$form = $this->createForm(OperationSearchType::class, $search);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			if ($search->getFirstDate() == $search->getSecondDate()){
				$search->setSecondDate($search->getSecondDate()->add(new \DateInterval('P1D')));
			}
			return $this->render('depense/index.html.twig', [
				'depenses' => $depenseRepository->findByPeriod($search),
				'active' => 'depense'
			]);
		}

		return $this->render('depense/form.html.twig', [
			'form' => $form->createView(),
			'active' => 'depense'
		]);
    }

	/**
	 * @Route("/new", name="depense_new", methods={"GET","POST"})
	 * @param Request $request
	 * @return Response
	 */
    public function new(Request $request): Response
    {
        $depense = new Depense();
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($depense);
            $entityManager->flush();

			$this->addFlash("sucess", "Dépense enregistrée");
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

	/**
	 * @Route("/moy", name="moyenne_depense", methods={"GET"})
	 * @param DepenseRepository $repository
	 * @return Response
	 */
	public function moyenne (DepenseRepository $repository): Response
	{
		return $this->render('depense/moyenne.html.twig', [
			'moyenne' => $repository->getMoyenne(),
			'active' => 'moyenne_depense'
		]);
	}
}
