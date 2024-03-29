<?php

namespace App\Controller;

use App\Entity\FactoryOrder;
use App\Entity\PitchOrder;
use App\Entity\Factory;
use App\Entity\Message;
use App\Entity\OrderFactoryItem;
use App\Entity\Order;
use App\Form\FactoryOrderType;
use App\Repository\MessageRepository;
use App\Form\PitchOrderType;
use App\Repository\FactoryOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use App\Manager\FactoryOrderManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @Route("/factoryorder")
 */
class FactoryOrderController extends AbstractController {

    /**
     * @Route("/", name="factory_order_index", methods={"GET"})
     */
    public function index(FactoryOrderRepository $factoryOrderRepository): Response {
        return $this->render('factory_order/index.html.twig', [
                    'factory_orders' => $factoryOrderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/sessionfactoryorder", name="session_factory_order_index", methods={"GET"})
     */
    public function sessionFactoryOrder(FactoryOrderManager $factoryOrderManager, Request $request): Response {
        $factoryOrder = $factoryOrderManager->getCurrentFactoryOrder();
        $factoryOrderManager->save($factoryOrder);

        return $this->render('factory_order/show.html.twig', [
        'factory_order' => $factoryOrder,
        ]);
        }

        /**
         * @Route("/new", name="factory_order_new", methods={"GET", "POST"})
         */
        public function new(Request $request, EntityManagerInterface $entityManager): Response
        {
        $factoryOrder = new FactoryOrder();

        $form = $this->createForm(FactoryOrderType::class, $factoryOrder);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($factoryOrder);
        $entityManager->flush();

        return $this->redirectToRoute('factory_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('factory_order/new.html.twig', [
                    'factory_order' => $factoryOrder,
                    'form' => $form,
        ]);
    }

    /**
     * @Route("/new/fromcart/{id}", name="factory_order_new_cart", methods={"GET", "POST"})
     */
    public function newFromCart(Order $order, Request $request, EntityManagerInterface $entityManager): Response {
        $factoryOrder = new FactoryOrder();
        $pitchOrder = new PitchOrder();
        $orderItems = $order->getItem();
        $date = new \DateTime();
        $repository = $this->getDoctrine()->getRepository(FactoryOrder::class);
        $lastEntity = $repository->findBy(array(), array('id' => 'DESC'), 1, 0);
        $lastNumber = 1;
        if (!empty($lastEntity)) {
            if ($lastEntity[0]) {
                $lastNumber = $lastEntity[0]->getNumber();
                $lastNumber = explode('/', $lastNumber);
                $lastNumber = $lastNumber[0] + 1;
            }
        }

        $lastNumber = $lastNumber . '/' . date('Y');
        $factoryOrder->setDateCreated($date);

        $factoryOrder->setCreatedBy($this->getUser());
        $factoryOrder->setNumber($lastNumber);
        $factoryOrder->setClientOrder($order);
        $presentFactoryOrders = $order->getFactoryOrders()->getValues();
        $orderedProducts = [];
        foreach ($presentFactoryOrders as $factoryOrderExisted) {
            $factoryItems = $factoryOrderExisted->getOrderFactoryItems();
            foreach ($factoryItems as $item) {
                $orderedProductName = $item->getProduct()->getName();
                $orderedProductQuantity = $item->getQuantity();
                if (array_key_exists($orderedProductName, $orderedProducts)) {
                    $orderedProducts[$orderedProductName] = $orderedProducts[$orderedProductName] + $orderedProductQuantity;
                } else {
                    $orderedProducts[$orderedProductName] = $orderedProductQuantity;
                }
            }
        }

        foreach ($orderItems as $item) {
            if (array_key_exists($item->getProduct()->getName(), $orderedProducts)) {
                $quantity = $item->getQuantity() - $orderedProducts[$item->getProduct()->getName()];
            } else {
                $quantity = $item->getQuantity();
            }
            $factory = $item->getProduct()->getManufacture();
            $productIsOnPalet = $item->getProduct()->getIsOnPalet();
            if ($productIsOnPalet) {
                $productPackaging = $item->getProduct()->getPackaging();

                if (strpos($item->getQuantity() / $productPackaging, '.') !== false) {
                    $isFullPallets = false;
                } else {
                    $isFullPallets = true;
                }
                if ($isFullPallets) {
                    $newFactoryItem = new OrderFactoryItem();
                    $newFactoryItem->setProduct($item->getProduct());
                    $newFactoryItem->setIsConfirmed(false);
                    $newFactoryItem->setQuantity($quantity);
                    $factoryOrder->addOrderFactoryItem($newFactoryItem);
                } else {
                    $newFactoryItem = new OrderFactoryItem();
                    $newFactoryItem->setProduct($item->getProduct());
                    $newFactoryItem->setQuantity($quantity);
                    $newFactoryItem->setIsConfirmed(false);
                    $pitchOrder->addRelation($newFactoryItem);
                }
            }
        }
        $form = $this->createForm(FactoryOrderType::class, $factoryOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($factoryOrder);
//            $entityManager->persist($pitchOrder);
            $entityManager->flush();

            return $this->redirectToRoute('factory_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('factory_order/new.html.twig', [
                    'factory_order' => $factoryOrder,
                    'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="factory_order_show", methods={"GET"})
     */
    public function show(FactoryOrder $factoryOrder): Response {
        return $this->render('factory_order/show.html.twig', [
                    'factory_order' => $factoryOrder,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="factory_order_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, FactoryOrder $factoryOrder, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(FactoryOrderType::class, $factoryOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('factory_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('factory_order/edit.html.twig', [
                    'factory_order' => $factoryOrder,
                    'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="factory_order_delete", methods={"POST"})
     */
    public function delete(Request $request, FactoryOrder $factoryOrder, EntityManagerInterface $entityManager): Response {
        if ($this->get('session')->has('factoryOrder_id')) {
            $sessionId = $this->get('session')->get('factoryOrder_id');
            if ($sessionId == $factoryOrder->getId()) {
                $this->get('session')->remove('factoryOrder_id');
            }
        }
        if ($this->isCsrfTokenValid('delete' . $factoryOrder->getId(), $request->request->get('_token'))) {
            $entityManager->remove($factoryOrder);
            $entityManager->flush();
        }


        return $this->redirectToRoute('factory_order_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/saveorder", name="save_order")
     */
    public function save_cart(Request $request): Response {

        if ($this->get('session')->has('factoryOrder_id')) {
            $this->get('session')->remove('factoryOrder_id');
        }
        return $this->redirectToRoute('factory_order_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/ask/{id}", name="factory_order_ask", methods={"GET", "POST"})
     */
    public function sendAsk(Order $factoryOrder, Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, MessageRepository $messageRepository): Response {
        $items = $factoryOrder->getItem();
        $newItems = [];


        foreach ($items as $item) {
            $pos = strpos($item->getProduct()->getName(), 'Auto');
            $pos2 = strpos($item->getProduct()->getName(), 'paleta');
            if ($pos === false && $pos2 === false) {
                if (key_exists($item->getProduct()->getName(), $newItems)) {
                    $newItems[$item->getProduct()->getName() . count($newItems)] = $item;
                } else {
                    $newItems[$item->getProduct()->getName()] = $item;
                }
            }
        }
        $itemsByManufacture = [];
        foreach ($newItems as $key => $item) {
            if (key_exists($item->getProduct()->getManufacture(), $itemsByManufacture)) {
                array_push($itemsByManufacture[$item->getProduct()->getManufacture()], $item);
            } else {
                $itemsByManufacture[$item->getProduct()->getManufacture()] = [$item];
            }
        }

        if ($request->query->get('send_ask') !== null) {
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
            $productCountByManufacture = [];
            foreach ($itemsByManufacture as $manufacture => $items) {
                $contentText = 'Witam, <br/> Proszę o potwierdzenie dostępności lub podanie spodziewanej daty dostępności materiałów : <br/><br/>';

                foreach ($items as $item) {
                    if (key_exists($item->getId(), $content)) {
                        if (key_exists($manufacture, $productCountByManufacture)) {
                            $productCountByManufacture[$manufacture] = $productCountByManufacture[$manufacture] + 1;
                        } else {
                            $productCountByManufacture[$manufacture] = 1;
                        }

                        $contentText = $contentText . $content[$item->getId()]['product'];
                        if ($content[$item->getId()]['item'] == 'pal') {
                            $contentText = $contentText . ' -- ' . $content[$item->getId()]['quantitypal'];
                        } else {
                            $contentText = $contentText . ' ' . $content[$item->getId()]['quantity'];
                        }
                        $contentText = $contentText . ' ' . $content[$item->getId()]['item'] . '<br/>';
                    }
                }
                $contentTextNoSignature = $contentText;
                if (key_exists($manufacture, $productCountByManufacture)) {
                    $contentText = $contentText . '<br/><br/> Z poważaniem <br/>' . $this->getUser()->getName() . ' ' . $this->getUser()->getSurname() . '<br/>' . $this->getUser()->getPhone() . '<br/> kołodomu.pl s.c. <br/> ul.Niepołomska 28a <br/> 32-020 Wieliczka';
                    $repository = $this->getDoctrine()->getRepository(Factory::class);
                    $factory = $repository->findOneByName($manufacture);

                    $emailFactory = $factory->getEmail();

                    $email = (new Email())
                            ->from('biuro@kolodomu.pl')
                            ->to($emailFactory)
                            ->bcc('biuro@kolodomu.pl')
                            //->cc('bcc@example.com')
                            //->replyTo('fabien@example.com')
                            //->priority(Email::PRIORITY_HIGH)
                            ->subject('Zapytanie o dostępność ' . $factoryOrder->getNumber())
                            ->text('Prosze o odpowiedź na temat dostępności')
                            ->html('<p>' . $contentText . '</p>');

                    $mailer->send($email);

                    $this->addFlash('success', 'Wiadomość została wysłana na ' . $manufacture);
                    $message = new Message();
                    $message->setType('Email');
                    $contentText = str_replace( '<br/>', ' ',$contentTextNoSignature);
                    $message->setText($contentText);
                    $message->setAdress($emailFactory);
                    $message->setDataCreated(new DateTime('NOW'));
                    $message->setCreatedBy($this->getUser());
                    $message->setStatus('zapytanie');
                    $message->setCart($factoryOrder);

                    $entityManager->persist($message);
                    $entityManager->flush();
                }
            }
        }

        return $this->render('factory_order/ask.html.twig', [
                    'messages' => $messageRepository->findByCartAndType($factoryOrder, 'zapytanie', 'Email'),
                    'items' => $newItems,
        ]);
    }

}
