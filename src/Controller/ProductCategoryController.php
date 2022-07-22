<?php

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product/category")
 */
class ProductCategoryController extends AbstractController
{
    /**
     * @Route("/", name="product_category_index", methods={"GET"})
     */
    public function index(ProductCategoryRepository $productCategoryRepository): Response
    {
        return $this->render('product_category/index.html.twig', [
            'product_categories' => $productCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $productCategory = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($productCategory);
            $entityManager->flush();

            return $this->redirectToRoute('product_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_category/new.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_category_show", methods={"GET"})
     */
    public function show(ProductCategory $productCategory): Response
    {
        return $this->render('product_category/show.html.twig', [
            'product_category' => $productCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ProductCategory $productCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductCategoryType::class, $productCategory,['name'=>$productCategory->getName()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('product_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_category/edit.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_category_delete", methods={"POST"})
     */
    public function delete(Request $request, ProductCategory $productCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productCategory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($productCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_category_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/search", name="category_search")
     */
     public function searchAction(Request $request){
            
         
             $searchTerm = $request->get('q');  
             $em = $this->getDoctrine()->getManager();
             $results = $em->getRepository(ProductCategory::class)->findByNameField($searchTerm);
             //$results = $query->getResult();
 
             if(!$results) {
            $result['entities']['error'] = "brak kontrahenta";
        } else {
            $result['entities'] = $this->getRealEntities($results);
        }

        return new Response(json_encode($result));

             }
             
             public function getRealEntities($entities){

            foreach ($entities as $entity){
                $realEntities[$entity->getId()] = $entity->getName();
            }

      return $realEntities;
  }
}
