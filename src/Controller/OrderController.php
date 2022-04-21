<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\Option;
use App\Form\CartType;
use App\Manager\CartManager;
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
    public function index(OrderRepository $orderRepository, int $page = 1, Request $request): Response {
        $user = $this->getUser();
        $number = $request->get('search_number');
        $kontrahent = $request->get('search_kontrahent');
        $orderDate = $request->get('search_order_date');
        $status = $request->get('search_status');
        $deliverydate = $request->get('search_delivery_date');
        $createdBy = $request->get('search_created_by');

        if ($request->get('reset') !== NULL) {
            $filerParameters = [
                'number' => '',
                'kontrahent' => '',
                'status' => '',
                'createdBy' => '',
                'orderdate' => '',
                'deliverydate' => '',
                'user' => ''
            ];
        } else {
            $filerParameters = [
                'number' => $number,
                'kontrahent' => $kontrahent,
                'status' => $status,
                'createdBy' => $createdBy,
                'orderdate' => $orderDate,
                'deliverydate' => $deliverydate,
                'user' => $createdBy
            ];
        }
        if ($request->get('submit') !== NULL) {
            $orders = $orderRepository->findByFilters('order', $number, $kontrahent, $orderDate, $status, $deliverydate, $createdBy);
        } else {
            $orders = $orderRepository->findByType('order', $user);
        }

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();

        $pagerfanta = new Pagerfanta(new QueryAdapter($orders));
        $pagerfanta->setMaxPerPage(100);
        $pagerfanta->setCurrentPage($page);

        return $this->render('order/index.html.twig', [
                    'orders' => $pagerfanta,
                    'users' => $users,
                    'parameters' => $filerParameters,
        ]);
    }

    /**
     * @Route("/offers/{page<\d+>}", defaults={"page" = 1}, name="offer_index", methods={"GET"})
     */
    public function offerindex(OrderRepository $orderRepository, int $page = 1, Request $request): Response {

        $user = $this->getUser();
        $number = $request->get('search_number');
        $kontrahent = $request->get('search_kontrahent');
        $orderDate = $request->get('reportrange');
        $status = $request->get('search_status');
        $deliverydate = $request->get('reportrange1');
        $createdBy = $request->get('search_created_by');
        if ($request->get('reset') !== NULL) {
            $filerParameters = [
                'number' => '',
                'kontrahent' => '',
                'status' => '',
                'createdBy' => '',
                'orderdate' => '',
                'deliverydate' => '',
                'user' => ''
            ];
        } else {
            $filerParameters = [
                'number' => $number,
                'kontrahent' => $kontrahent,
                'status' => $status,
                'createdBy' => $createdBy,
                'orderdate' => $orderDate,
                'deliverydate' => $deliverydate,
                'user' => $createdBy
            ];
        }
        if ($request->get('submit') !== NULL) {
            $orders = $orderRepository->findByFilters('offer', $number, $kontrahent, $orderDate, $status, $deliverydate, $createdBy);
        } else {
            $orders = $orderRepository->findByType('offer', $user);
        }
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();

        $pagerfanta = new Pagerfanta(new QueryAdapter($orders));
        $pagerfanta->setMaxPerPage(100);
        $pagerfanta->setCurrentPage($page);

        return $this->render('order/index.html.twig', [
        'orders' => $pagerfanta,
        'users' => $users,
        'parameters' => $filerParameters,
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
public function edit(Request $request, Order $order, CartManager $cartManager): Response {
$form = $this->createForm(CartType::class, $order);
$form->handleRequest($request);

$this->get('session')->set('cart_id', $order->getId());

return $this->redirectToRoute('cart');


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
            'time' => ''
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
 * @Route("/prints/{id}", name="order_prints")
 */
public function prints(Order $order): Response {
return $this->render('order/prints.html.twig', [
            'order' => $order,
        ]);
}

/**
 * @Route("/create_order_pdf/{id}/{template}", name="create_order_pdf")
 */
public function createorderpdf(Order $order, string $template = 'standard'): Response {
// Configure Dompdf according to your needs
$pdfOptions = new Options();
$pdfOptions->set('defaultFont', 'Arial');

// Instantiate Dompdf with our options
$dompdf = new Dompdf($pdfOptions);

$optionRepository = $this->getDoctrine()->getRepository(Option::class);
$order_agreements_option = $optionRepository->findOneBy(['shortcode' => 'order_agreements']);

$order_agreements_value = $order_agreements_option->getValue();

if ($template == 'show_discount') {
    // Retrieve the HTML generated in our twig file
    $html = $this->renderView('./pdf/client_order_discount.html.twig', [
        'cart' => $order,
        'agreements' => $order_agreements_value,
    ]);
} else if ($template == 'standard') {
    $html = $this->renderView('./pdf/client_order_standard.html.twig', [
        'cart' => $order,
        'agreements' => $order_agreements_value,
    ]);
} else if ($template == 'standard_noagrements') {
    $html = $this->renderView('./pdf/client_order_standard.html.twig', [
        'cart' => $order,
        'agreements' => '',
    ]);
}
// Load HTML to Dompdf
$dompdf->loadHtml($html, 'UTF-8');

// (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser (inline view)
$dompdf->stream(str_replace('/', '-', $order->getNumber()) . '-' . $order->getId() . ".pdf", [
    "Attachment" => false
]);
exit;
}

/**
 * @Route("/changetype/{id}/{type}", name="change_type", methods={"GET"})
 */
public function changeType(Order $order, string $type): Response {

$order->setType($type);
$entityManager = $this->getDoctrine()->getManager();
$entityManager->persist($order);
$entityManager->flush();

return $this->redirectToRoute('cart', ['id'=>$order->getId()], Response::HTTP_SEE_OTHER);
//if ($order->getStatus() == 'offer') {
//    return $this->redirectToRoute('offer_index', [], Response::HTTP_SEE_OTHER);
//} elseif ($order->getStatus() == 'order') {
//    return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
//}
}

/**
 * @Route("/changestatus/{id}/{status}", name="change_status", methods={"GET"})
 */
public function changeStatus(Order $order, string $status): Response {

$order->setStatus($status);
$entityManager = $this->getDoctrine()->getManager();
$entityManager->persist($order);
$entityManager->flush();

return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
}

}
