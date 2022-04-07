<?php

namespace App\Controller;

use App\Entity\FastDeal;
use App\Form\FastDealType;
use App\Repository\FastDealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fast/deal")
 */
class FastDealController extends AbstractController
{
    /**
     * @Route("/", name="fast_deal_index", methods={"GET"})
     */
    public function index(FastDealRepository $fastDealRepository): Response
    {
        return $this->render('fast_deal/index.html.twig', [
            'fast_deals' => $fastDealRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="fast_deal_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fastDeal = new FastDeal();
        $form = $this->createForm(FastDealType::class, $fastDeal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fastDeal);
            $entityManager->flush();

            return $this->redirectToRoute('fast_deal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fast_deal/new.html.twig', [
            'fast_deal' => $fastDeal,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="fast_deal_show", methods={"GET"})
     */
    public function show(FastDeal $fastDeal): Response
    {
        return $this->render('fast_deal/show.html.twig', [
            'fast_deal' => $fastDeal,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fast_deal_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, FastDeal $fastDeal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FastDealType::class, $fastDeal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('fast_deal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fast_deal/edit.html.twig', [
            'fast_deal' => $fastDeal,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="fast_deal_delete", methods={"POST"})
     */
    public function delete(Request $request, FastDeal $fastDeal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fastDeal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fastDeal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fast_deal_index', [], Response::HTTP_SEE_OTHER);
    }
}
