<?php

namespace App\Controller;

use App\Entity\OrderFactoryItem;
use App\Form\OrderFactoryItemType;
use App\Repository\OrderFactoryItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order/factory/item")
 */
class OrderFactoryItemController extends AbstractController
{
    /**
     * @Route("/", name="order_factory_item_index", methods={"GET"})
     */
    public function index(OrderFactoryItemRepository $orderFactoryItemRepository): Response
    {
        return $this->render('order_factory_item/index.html.twig', [
            'order_factory_items' => $orderFactoryItemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="order_factory_item_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $orderFactoryItem = new OrderFactoryItem();
        $form = $this->createForm(OrderFactoryItemType::class, $orderFactoryItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($orderFactoryItem);
            $entityManager->flush();

            return $this->redirectToRoute('order_factory_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order_factory_item/new.html.twig', [
            'order_factory_item' => $orderFactoryItem,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="order_factory_item_show", methods={"GET"})
     */
    public function show(OrderFactoryItem $orderFactoryItem): Response
    {
        return $this->render('order_factory_item/show.html.twig', [
            'order_factory_item' => $orderFactoryItem,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_factory_item_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, OrderFactoryItem $orderFactoryItem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderFactoryItemType::class, $orderFactoryItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('order_factory_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order_factory_item/edit.html.twig', [
            'order_factory_item' => $orderFactoryItem,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="order_factory_item_delete", methods={"POST"})
     */
    public function delete(Request $request, OrderFactoryItem $orderFactoryItem, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderFactoryItem->getId(), $request->request->get('_token'))) {
            $entityManager->remove($orderFactoryItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_factory_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
