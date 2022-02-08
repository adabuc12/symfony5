<?php

namespace App\Controller;

use App\Entity\Notice;
use App\Entity\Product;
use App\Form\NoticeType;
use App\Repository\NoticeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

/**
 * @Route("/notice")
 */
class NoticeController extends AbstractController
{
    /**
     * @Route("/", name="notice_index", methods={"GET"})
     */
    public function index(NoticeRepository $noticeRepository): Response
    {
        return $this->render('notice/index.html.twig', [
            'notices' => $noticeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="notice_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $notice = new Notice();
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($notice);
            $entityManager->flush();

            return $this->redirectToRoute('notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('notice/new.html.twig', [
            'notice' => $notice,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="notice_show", methods={"GET"})
     */
    public function show(Notice $notice, EntityManagerInterface $entityManager): Response
    {
        $notice->setIsReaded(true);
        $notice->setDateReaded(new DateTime('NOW'));
        $noticeType = $notice->getType();
        $noticeType=explode('-',$noticeType);
        if($noticeType[0] == 'product'){
            $repository = $this->getDoctrine()->getRepository(Product::class);
            $product = $repository->findOneBy(['id'=>$noticeType[1]]);
            $product->removeNotifyUserIfAvaible($this->getUser());
            $entityManager->persist($product);
        }
        $entityManager->persist($notice);
            $entityManager->flush();
        return $this->render('notice/show.html.twig', [
            'notice' => $notice,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="notice_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Notice $notice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('notice/edit.html.twig', [
            'notice' => $notice,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="notice_delete", methods={"POST"})
     */
    public function delete(Request $request, Notice $notice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($notice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('notice_index', [], Response::HTTP_SEE_OTHER);
    }
}
