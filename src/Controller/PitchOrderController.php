<?php

namespace App\Controller;

use App\Entity\PitchOrder;
use App\Form\PitchOrderType;
use App\Repository\PitchOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pitch/order")
 */
class PitchOrderController extends AbstractController
{
    /**
     * @Route("/", name="pitch_order_index", methods={"GET"})
     */
    public function index(PitchOrderRepository $pitchOrderRepository): Response
    {
        return $this->render('pitch_order/index.html.twig', [
            'pitch_orders' => $pitchOrderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pitch_order_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pitchOrder = new PitchOrder();
        $form = $this->createForm(PitchOrderType::class, $pitchOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pitchOrder);
            $entityManager->flush();

            return $this->redirectToRoute('pitch_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pitch_order/new.html.twig', [
            'pitch_order' => $pitchOrder,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pitch_order_show", methods={"GET"})
     */
    public function show(PitchOrder $pitchOrder): Response
    {
        return $this->render('pitch_order/show.html.twig', [
            'pitch_order' => $pitchOrder,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pitch_order_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PitchOrder $pitchOrder, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PitchOrderType::class, $pitchOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('pitch_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pitch_order/edit.html.twig', [
            'pitch_order' => $pitchOrder,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pitch_order_delete", methods={"POST"})
     */
    public function delete(Request $request, PitchOrder $pitchOrder, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pitchOrder->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pitchOrder);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pitch_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
