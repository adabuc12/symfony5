<?php

namespace App\Controller;

use App\Entity\WarehouseDocument;
use App\Entity\WarehouseStock;
use App\Form\WarehouseDocumentType;
use App\Repository\WarehouseDocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/warehouse/document/warehouse")
 */
class WarehouseDocumentController extends AbstractController
{
    /**
     * @Route("/", name="warehouse_document_index", methods={"GET"})
     */
    public function index(WarehouseDocumentRepository $warehouseDocumentRepository): Response
    {
        return $this->render('warehouse_document/index.html.twig', [
            'warehouse_documents' => $warehouseDocumentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="warehouse_document_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $warehouseDocument = new WarehouseDocument();
        $form = $this->createForm(WarehouseDocumentType::class, $warehouseDocument);
        $form->handleRequest($request);
        
        $stock1 = new WarehouseStock();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($warehouseDocument);
            $entityManager->flush();

            return $this->redirectToRoute('warehouse_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('warehouse_document/new.html.twig', [
            'warehouse_document' => $warehouseDocument,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="warehouse_document_show", methods={"GET"})
     */
    public function show(WarehouseDocument $warehouseDocument): Response
    {
        return $this->render('warehouse_document/show.html.twig', [
            'warehouse_document' => $warehouseDocument,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="warehouse_document_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, WarehouseDocument $warehouseDocument, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WarehouseDocumentType::class, $warehouseDocument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('warehouse_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('warehouse_document/edit.html.twig', [
            'warehouse_document' => $warehouseDocument,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="warehouse_document_delete", methods={"POST"})
     */
    public function delete(Request $request, WarehouseDocument $warehouseDocument, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$warehouseDocument->getId(), $request->request->get('_token'))) {
            $entityManager->remove($warehouseDocument);
            $entityManager->flush();
        }

        return $this->redirectToRoute('warehouse_document_index', [], Response::HTTP_SEE_OTHER);
    }
}
