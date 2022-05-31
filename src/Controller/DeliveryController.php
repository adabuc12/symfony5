<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Entity\Order;
use App\Entity\OrderItem;
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
class DeliveryController extends AbstractController {

    /**
     * @Route("/", name="delivery_index", methods={"GET"})
     */
    public function index(DeliveryRepository $deliveryRepository): Response {
        return $this->render('delivery/index.html.twig', [
                    'deliveries' => $deliveryRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/addtocalendar/{date}", name="add_calendar_event", methods={"GET"})
     */
    public function addCalendar(DeliveryRepository $deliveryRepository): Response {
        
        return $this->render('delivery/add_event.html.twig', [
                    
        ]);
    }

    /**
     * @Route("/calendar", name="delivery_index_calendar", methods={"GET"})
     */
    public function calendar(DeliveryRepository $deliveryRepository): Response {
        $monthNumber = date('m');
        $yearNumber = date('Y');

        $daysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $monthNumber, $yearNumber);
        $daysLastMonth = cal_days_in_month(CAL_GREGORIAN, $monthNumber - 1, $yearNumber);

        $firstdayname = $yearNumber . '/' . $monthNumber . '/01';
        $day = date('l', strtotime($firstdayname));

        if ($day == 'Monday') {
            $minusdays = 1;
        }
        if ($day == 'Tuesday') {
            $minusdays = 2;
        }
        if ($day == 'Wednesday') {
            $minusdays = 3;
        }
        if ($day == 'Thursday') {
            $minusdays = 4;
        }
        if ($day == 'Friday') {
            $minusdays = 5;
        }
        if ($day == 'Saturday') {
            $minusdays = 6;
        }
        if ($day == 'Sunday') {
            $minusdays = 7;
        }
        $days = [];
        for ($i = 1 - $minusdays; $i <= $daysInThisMonth; $i++) {
            if ($i != 0) {
                if ($i < 0) {
                    $lastMontNumber = $monthNumber - 1;
                    $string = $i . '-' . $lastMontNumber . '-' . $yearNumber;
                    $days[$string] = [$daysLastMonth + $i + 1, true, []];
                } else {
                    $string = $i . '-' . $monthNumber . '-' . $yearNumber;
                    $days[$string] = [$i, false, []];
                }
            }
        }
        $startDate = ($daysLastMonth - $minusdays) . '-' . ($monthNumber - 1) . '-' . $yearNumber;
        $endDate = $daysInThisMonth . '-' . $monthNumber . '-' . $yearNumber;
        
        if (count($days) !== 42) {
            for ($i = 1; $i <= (46 - count($days)); $i++) {
                $nextMontNumber = $monthNumber + 1;
                $string = $i . '-' . $nextMontNumber . '-' . $yearNumber;
                $endDate = $string;
                $days[$string] = [$i, true, []];
            }
        }


        $deliveries = $deliveryRepository->findAllByDate($startDate, $endDate);

        foreach ($deliveries as $delivery) {
            $deliveryDate = date_format($delivery->getDeliveryDate(), 'd-m-Y');
            array_push($days[$deliveryDate][2], $delivery);
        }
        return $this->render('calendar/calendar.html.twig', [
                    'days' => $days,
                    'thisdate' => $monthNumber . '-' . $yearNumber,
        ]);
    }

    /**
     * @Route("/order/{id}", name="delivery_index_order", methods={"GET"})
     */
    public function indexOrder(Order $order, DeliveryRepository $deliveryRepository): Response {

        

        return $this->render('delivery/index_order.html.twig', [
                    'deliveries' => $deliveryRepository->findAllByOrder($order),
                    'order' => $order,
        ]);
    }

    /**
     * @Route("/order/{order}/delete/item/{id}/{delivery}", name="delivery_delete_item", methods={"GET"})
     */
    public function deleteItem(Order $order, OrderItem $orderItem, Delivery $delivery, EntityManagerInterface $entityManager): Response {

        $entityManager->remove($orderItem);
        $entityManager->flush();

        return $this->redirectToRoute('delivery_show', ['id' => $delivery->getId(),'order'=>$order->getId()], Response::HTTP_SEE_OTHER);
        }

        /**
         * @Route("/new/order/{id}", name="delivery_new", methods={"GET", "POST"})
         */
        public function new(Order $order, Request $request, EntityManagerInterface $entityManager): Response
        {
        $delivery = new Delivery();
        $form = $this->createForm(DeliveryType::class, $delivery, ['orderId' => $order->getId()]);
        $form->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(Delivery::class);
        $lastDelivery = $repository->findBy(array(), array('id' => 'DESC'), 1, 0);

        if($lastDelivery){
        $lastNumber = $lastDelivery[0]->getNumber();
        } else {
            $lastNumber = 1;
        }
        $delivery->setNumber($lastNumber + 1);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($delivery);
            $entityManager->flush();

            return $this->redirectToRoute('delivery_index_order', ['id' => $order->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('delivery/new.html.twig', [
                    'delivery' => $delivery,
                    'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/order/{order}", name="delivery_show", methods={"GET", "POST"})
     */
    public function show(Delivery $delivery, Order $order, Request $request, EntityManagerInterface $entityManager): Response {
        if ($request->query->get('add_to') !== null) {
            $content = [];
            $productNumber = null;
            foreach ($request->query->all() as $key => $req) {
                $keyExploded = explode('-', $key);

                if (count($keyExploded) > 1) {
                    if ($keyExploded[0] == 'check' && $req == 'on') {
                        $productNumber = $keyExploded[1];
                    }
                    if ($keyExploded[1] == $productNumber && $keyExploded[0] == 'product') {
                        $content[$productNumber]['product'] = $req;
                    }
                    if ($keyExploded[1] == $productNumber && $keyExploded[0] == 'quantity') {
                        $content[$productNumber]['quantity'] = $req;
                    }
                    if ($keyExploded[1] == $productNumber && $keyExploded[0] == 'quantitypal') {
                        $content[$productNumber]['quantitypal'] = $req;
                    }
                    if ($keyExploded[1] == $productNumber && $keyExploded[0] == 'item') {
                        $content[$productNumber]['item'] = $req;
                    }
                }
            }
            $deliveryOrders = $delivery->getDeliveryOrder();

            foreach ($deliveryOrders as $orderItems) {
                $items = $orderItems->getItem();
                foreach ($items as $item) {
                    if (key_exists($item->getId(), $content)) {
                        $newItem = new OrderItem();
                        $newItem->setProduct($item->getProduct());
                        $newItem->setPrice($item->getPrice());
                        $newItem->setPallets($item->getPallets());
                        if ($content[$item->getId()]['item'] == 'pal') {
                            $newItem->setQuantity($content[$item->getId()]['quantitypal'] * $item->getProduct()->getPackaging());
                        } else {
                            $newItem->setQuantity($content[$item->getId()]['quantity']);
                        }
                        $entityManager->persist($newItem);

                        $delivery->addItem($newItem);
                    }
                }
            }
            $entityManager->flush();
        }
        return $this->render('delivery/show.html.twig', [
                    'delivery' => $delivery,
                    'order' => $order,
        ]);
    }

    /**
     * @Route("/{id}/edit/order/{order}", name="delivery_edit", methods={"GET", "POST"})
     */
    public function edit(Order $order, Request $request, Delivery $delivery, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(DeliveryType::class, $delivery, ['orderId' => $order->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('delivery_index_order', ['id' => $order->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('delivery/edit.html.twig', [
                    'delivery' => $delivery,
                    'form' => $form,
                    'order' => $order
        ]);
    }

    /**
     * @Route("/{id}/order/{order}", name="delivery_delete", methods={"GET", "POST"})
     */
    public function delete(Order $order, Request $request, Delivery $delivery, EntityManagerInterface $entityManager): Response {

        if ($this->isCsrfTokenValid('delete' . $delivery->getId(), $request->request->get('_token'))) {
            $entityManager->remove($delivery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('delivery_index_order', ['id' => $order->getId()], Response::HTTP_SEE_OTHER);
    }

}
