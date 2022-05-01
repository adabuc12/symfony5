<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Order;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/message")
 */
class MessageController extends AbstractController {

    /**
     * @Route("/cart/{id}", name="message_index", methods={"GET"})
     */
    public function index(Order $order, MessageRepository $messageRepository): Response {
        return $this->render('message/index.html.twig', [
                    'messages' => $messageRepository->findAll(),
                    'type' => '',
                    'order' => $order,
        ]);
    }

    /**
     * @Route("/type/{type}/{id}", name="message_index_type", methods={"GET"})
     */
    public function indexType(string $type, Order $order, MessageRepository $messageRepository, MailerInterface $mailer): Response {
        if ($type == 'send_calculations') {
            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');

            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);

            $optionRepository = $this->getDoctrine()->getRepository(Option::class);
            $order_agreements_option = $optionRepository->findOneBy(['shortcode' => 'order_agreements']);

            $order_agreements_value = $order_agreements_option->getValue();

            $html = $this->renderView('./pdf/client_order_standard.html.twig', [
            'cart' => $order,
            'agreements' => '',
            ]);
            // Load HTML to Dompdf
            $dompdf->loadHtml($html, 'UTF-8');

            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();
            $replecedNumber = str_replace('/', '-', $order->getNumber());
            $name = $replecedNumber . '-' . $order->getId();


            $output = $dompdf->output();
            file_put_contents('uploads/offers/'.$name.'.pdf', $output);

            $contentText = 'Witam, <br/> W zalączeniu przesyłam wycenę na materiały : <br/><br/>';
            $email = (new Email())
            ->from('biuro@kolodomu.pl')
            ->to('abadambuczek@gmail.com')
            ->cc()
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->attachFromPath('uploads/offers/'.$name.'.pdf')
            ->subject('Wycena '.$order->getNumber())
            ->text('Wycena')
            ->html('<p>' . $contentText . '</p>');

            $mailer->send($email);
            $this->addFlash('success', 'Wycena została wysłana');
        }
        $filesystem = new Filesystem();
        $filesystem->remove('uploads/offers/'.$name.'.pdf');
        return $this->render('message/index.html.twig', [
        'messages' => $messageRepository->findAll(),
        'type' => $type,
        'order' => $order,
        ]);
    }

    /**
     * @Route("/new", name="message_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
    $message = new Message();
    $form = $this->createForm(MessageType::class, $message);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
    $entityManager->persist($message);
    $entityManager->flush();

    return $this->redirectToRoute('message_index', [], Response::HTTP_SEE_OTHER);
}

return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
}

/**
 * @Route("/{id}", name="message_show", methods={"GET"})
 */
public function show(Message $message): Response {
return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
}

/**
 * @Route("/{id}/edit", name="message_edit", methods={"GET", "POST"})
 */
public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response {
$form = $this->createForm(MessageType::class, $message);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $entityManager->flush();

    return $this->redirectToRoute('message_index', [], Response::HTTP_SEE_OTHER);
}

return $this->renderForm('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
}

/**
 * @Route("/{id}", name="message_delete", methods={"POST"})
 */
public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response {
if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
    $entityManager->remove($message);
    $entityManager->flush();
}

return $this->redirectToRoute('message_index', [], Response::HTTP_SEE_OTHER);
}

}
