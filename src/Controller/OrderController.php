<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Option;
use App\Form\CartType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use App\Service\DbLog;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController {

    /**
     * @Route("/order/{page<\d+>}", name="order_index", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository, int $page = 1): Response {
        $orders = $orderRepository->findByType('order');
        
        $pagerfanta = new Pagerfanta(new QueryAdapter($orders));
        $pagerfanta->setMaxPerPage(100);
        $pagerfanta->setCurrentPage($page);
        
        return $this->render('order/index.html.twig', [
                    'orders' => $pagerfanta,
        ]);
    }

    /**
     * @Route("/offers/{page<\d+>}", defaults={"page" = 1}, name="offer_index", methods={"GET"})
     */
    public function offerindex(OrderRepository $orderRepository, int $page = 1,Request $request): Response {
        
        $number = $request->get('search_number');
        $kontrahent = $request->get('search_kontrachent');
        $orderDate = $request->get('search_order_date');
        $status = $request->get('search_status');
        $deliverydate = $request->get('search_delivery_date');
        $createdBy = $request->get('search_created_by');
        $orders = $orderRepository->findByType('offer');
        
        $pagerfanta = new Pagerfanta(new QueryAdapter($orders));
        $pagerfanta->setMaxPerPage(100);
        $pagerfanta->setCurrentPage($page);
        
        return $this->render('order/index.html.twig', [
                'orders' => $pagerfanta,
            ]);
        }

        /**
         * @Route("/new", name="order_new", methods={"GET","POST"})
         */
        public function new(Request $request): Response
        {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/new.html.twig', [
                    'order' => $order,
                    'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="order_show", methods={"GET"})
     */
    public function show(Order $order): Response {
        return $this->render('order/show.html.twig', [
                    'order' => $order,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Order $order): Response {
        $form = $this->createForm(CartType::class, $order);
        $form->handleRequest($request);
 
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cart/index.html.twig', [
                    'cart' => $order,
                    'form' => $form,
                    'kontrahent' => $order->getKontrahent(),
                    'route_image' => '',
                    'dystans' => '',
                    'time' => '',
        ]);
    }

    /**
     * @Route("/delete/{id}", name="order_delete", methods={"POST"})
     */
    public function delete(Request $request, Order $order): Response {
        if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/create_order_pdf/{id}", name="create_order_pdf")
     */
    public function createorderpdf(Order $order): Response {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $optionRepository = $this->getDoctrine()->getRepository(Option::class);
        $order_agreements_option = $optionRepository->findOneBy(['shortcode' => 'order_agreements']);
        
        $order_agreements_value = $order_agreements_option ->getValue();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('./pdf/client_order.html.twig', [
            'cart' => $order,
            'agreements' => $order_agreements_value,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html, 'UTF-8');

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("client_order.pdf", [
            "Attachment" => false
        ]);
        exit;
    }
    
    

}
