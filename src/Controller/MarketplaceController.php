<?php

namespace App\Controller;

use App\Entity\Marketplace;
use App\Form\MarketplaceType;
use App\Repository\MarketplaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/marketplace")
 */
class MarketplaceController extends AbstractController
{
    /**
     * @Route("/", name="marketplace_index", methods={"GET"})
     */
    public function index(MarketplaceRepository $marketplaceRepository): Response
    {
        if(sizeof($this->getUser()->getRoles())==2){
            $markets=$marketplaceRepository->findAll();
            }else{
                $markets=$marketplaceRepository->findBy(['user' => $this->getUser()]);
            }
        return $this->render('marketplace/index.html.twig', [
            'marketplaces' => $markets,
        ]);
    }

    /**
     * @Route("/new", name="marketplace_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $marketplace = new Marketplace();
        $form = $this->createForm(MarketplaceType::class, $marketplace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $marketplace->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($marketplace);
            $entityManager->flush();

            return $this->redirectToRoute('marketplace_index');
        }

        return $this->render('marketplace/new.html.twig', [
            'marketplace' => $marketplace,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="marketplace_show", methods={"GET"})
     */
    public function show(Marketplace $marketplace): Response
    {
        return $this->render('marketplace/show.html.twig', [
            'marketplace' => $marketplace,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="marketplace_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Marketplace $marketplace): Response
    {
        $form = $this->createForm(MarketplaceType::class, $marketplace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('marketplace_index');
        }

        return $this->render('marketplace/edit.html.twig', [
            'marketplace' => $marketplace,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="marketplace_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Marketplace $marketplace): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marketplace->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($marketplace);
            $entityManager->flush();
        }

        return $this->redirectToRoute('marketplace_index');
    }
}
