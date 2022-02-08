<?php

namespace App\Controller;

use App\Entity\Factory;
use App\Form\FactoryType;
use App\Repository\FactoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/factory")
 */
class FactoryController extends AbstractController
{
    /**
     * @Route("/", name="factory_index", methods={"GET"})
     */
    public function index(FactoryRepository $factoryRepository): Response
    {
        return $this->render('factory/index.html.twig', [
            'factories' => $factoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="factory_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $factory = new Factory();
        $form = $this->createForm(FactoryType::class, $factory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($factory);
            $entityManager->flush();

            return $this->redirectToRoute('factory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('factory/new.html.twig', [
            'factory' => $factory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="factory_show", methods={"GET"})
     */
    public function show(Factory $factory): Response
    {
        return $this->render('factory/show.html.twig', [
            'factory' => $factory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="factory_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Factory $factory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactoryType::class, $factory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('factory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('factory/edit.html.twig', [
            'factory' => $factory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="factory_delete", methods={"POST"})
     */
    public function delete(Request $request, Factory $factory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($factory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('factory_index', [], Response::HTTP_SEE_OTHER);
    }
}
