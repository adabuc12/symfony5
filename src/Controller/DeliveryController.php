<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Entity\Order;
use App\Form\DeliveryType;
use App\Repository\DeliveryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/delivery")
 */
class DeliveryController extends AbstractController
{
    /**
     * @Route("/", name="delivery_index", methods={"GET"})
     */
    public function index(DeliveryRepository $deliveryRepository): Response
    {
        return $this->render('delivery/index.html.twig', [
            'deliveries' => $deliveryRepository->findAll(),
        ]);
    }
    
     /**
     * @Route("/order/{id}", name="delivery_index_order", methods={"GET"})
     */
    public function indexOrder(Order $order, DeliveryRepository $deliveryRepository): Response
    {
        return $this->render('delivery/index_order.html.twig', [
            'deliveries' => $deliveryRepository->findAllByOrder($order),
            'order' => $order,
        ]);
    }

    /**
     * @Route("/new/order/{id}", name="delivery_new", methods={"GET", "POST"})
     */
    public function new(Order $order, Request $request, EntityManagerInterface $entityManager): Response
    {
        $delivery = new Delivery();
        $form = $this->createForm(DeliveryType::class, $delivery,['orderId'=>$order->getId()]);
        $form->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(Delivery::class);
        $lastDelivery = $repository->findBy(array(),array('id'=>'DESC'),1,0);
        if($lastDelivery){
            $lastNumber = $lastDelivery[0]->getNumber();
        }else{
            $lastNumber = 1;
        }
        $delivery->setNumber($lastNumber);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($delivery);
            $entityManager->flush();

            return $this->redirectToRoute('delivery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('delivery/new.html.twig', [
            'delivery' => $delivery,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delivery_show", methods={"GET"})
     */
    public function show(Delivery $delivery): Response
    {
        return $this->render('delivery/show.html.twig', [
            'delivery' => $delivery,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="delivery_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Delivery $delivery, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('delivery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('delivery/edit.html.twig', [
            'delivery' => $delivery,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delivery_delete", methods={"POST"})
     */
    public function delete(Request $request, Delivery $delivery, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$delivery->getId(), $request->request->get('_token'))) {
            $entityManager->remove($delivery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('delivery_index', [], Response::HTTP_SEE_OTHER);
    }
}
