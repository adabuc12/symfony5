<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use App\Entity\OrderItem;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController {

    /**
     * @Route("/", name="product_index", methods={"GET", "POST"})
     */
    public function index(ProductRepository $productRepository, Request $request, CartManager $cartManager): Response {
        $data = $request->get('search');
        $parameters = array();
        $parameters['transportPrice'] = $request->get('inputprice');
        $parameters['select1'] = $request->get('select1');
        $parameters['select2'] = $request->get('select2');
        $parameters['select3'] = $request->get('select3');
        $parameters['factory'] = $request->get('factory');
        $parameters['name'] = $data;
        $repository = $this->getDoctrine()->getRepository(Product::class);
        
//        $form = $this->createForm(AddToCartType::class);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $item = $form->getData();
//            $item->setProduct($product);
//
//            $cart = $cartManager->getCurrentCart();
//            $cart
//                ->addItem($item)
//                ->setUpdatedAt(new \DateTime());
//
//            $cartManager->save($cart);
//
//            return $this->redirectToRoute('product.show', ['id' => $product->getId()]);
//        }
        
        $exploded = explode(' ',$data);

        if(count($exploded) > 4){
            $this->addFlash('danger',
                'Wyszukiwanie działa tylko do czterech wyrazów'
            );
        }
        if($data){
             $products = $repository->findByNameField($data, $parameters['factory']);
        }   
        else{
            $products = $productRepository->findAllPriceGreaterThanZero();
        }
        
        return $this->render('product/index.html.twig', [
                    'products' => $products,
                    'parameters' => $parameters,
//                    'form' => $form->createView(),
        ]);
    }
    
     /**
     * @Route("/", name="product_index_search", methods={"GET", "POST"})
     */
    public function searchByName(Request $request): Response {
        $data = $request->get('search');
        $parameters = array();
        $parameters['transportPrice'] = $request->get('inputprice');
        $parameters['select1'] = $request->get('select1');
        $parameters['select2'] = $request->get('select2');
        $parameters['select3'] = $request->get('select3');
        $parameters['factory'] = $request->get('factory');
        $parameters['name'] = $data;
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findByNameField($data);
        return $this->render('product/index.html.twig', [
                    'products' => $products,
                    'parameters' => $parameters,
        ]);
    }
    
    /**
     * @Route("/filter", name="product_filter", methods={"GET", "POST"})
     */
    public function ajaxSearch(Request $request): Response {
        $transportPrice = $request->get('inputprice');
        $name = $request->get('name');
        $select1 = $request->get('select1');
        $select2 = $request->get('select2');
        $select3 = $request->get('select3');
        $factory = $request->get('factory');
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findByFilters($name,$select1,$select2,$select3,$transportPrice,$factory);

        

      return $this->render('product/index.html.twig', [
                    'products' => $products,
        ]);
    }
    
    

    /**
     * @Route("/update", name="product_update", methods={"GET"})
     */
    
    public function update(ProductRepository $productRepository): Response {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $entityManager = $this->getDoctrine()->getManager();
        $finder = new Finder();
        $finder->files()->in(__DIR__)->name('produkty_listopad.csv');
        set_time_limit(1200);
        if ($finder->hasResults()) {
            $products = $repository->findAll();
            foreach ($products as $product) {
                    
                    $entityManager->remove($product);
                    
                }
                $entityManager->flush();
        }else{
            echo ('brak pliku importu, skontaktuj się z Adamem');
        }
        $objDateTime = new DateTime('NOW');
// decoding CSV contents
        foreach ($finder as $file) {
            $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
            // instantiation, when using it inside the Symfony framework
            $contents = $file->getContents();
            // decoding CSV contents
            $products_csv = $serializer->decode($contents, 'csv');

            foreach ($products_csv as $key => $value) {
                $packaging = (float)(str_replace(',', '.', $value['PAKOWANIE m2/szt']));
                $packageWeight = (float)(str_replace(',', '.', $value['WAGA PALETY']));
                if($packageWeight > 0 && $packaging > 0){
                    $unitWeight = $packageWeight/$packaging;
                }else {
                    $unitWeight = 0;
                }
                $catalogPrice = (float)(str_replace(',', '.', $value['KATALOGOWA']));
                $buyPrice = (float)(str_replace(',', '.', $value['cena zakupu brutto fabryka']));
                $sell1 = (float)(str_replace(',', '.', $value['FABRYKA DETAL']));
                $sell2 = (float)(str_replace(',', '.', $value['PLAC DETAL']));
                $sell3 = (float)(str_replace(',', '.', $value['FABRYKA HURT']));
                $sell4 = (float)(str_replace(',', '.', $value['PLAC HURT']));
                $sell5 = (float)(str_replace(',', '.', $value['FABRYKA SPECJALNA']));
                $sell6 = (float)(str_replace(',', '.', $value['PLAC SPECJALNA']));
                $sell7 = (float)(str_replace(',', '.', $value['minimalna ilosc']));

                $product = new Product();
                $product->setName($value['Nazwa']);
                $product->setManufacture($value['PRODUCENT']);
                $product->setPackaging($packaging);
                $product->setPackageWeight($packageWeight);
                $product->setUnitWeight($unitWeight);
                $product->setCatalogPrice($catalogPrice);
                $product->setBuyPrice($buyPrice);
                $product->setSellPriceFactoryDetal($sell1);
                $product->setSellPricePitchDetal($sell2);
                $product->setSellPriceFactoryContractors($sell3);
                $product->setSellPricePitchContractors($sell4);
                $product->setSellPriceFactoryWholesale($sell5);
                $product->setSellPricePitchWholesale($sell6);
                $product->setSprzedazJednostkowa($sell7);
                $product->setIsCourier(0);
                $product->setCourierCost(0);
                $product->setIsNotAvailable(0);
                $product->setEstimatedAvailabilityDate($objDateTime);
                $product->setNotices('');
                $entityManager->persist($product);

 
        }
        $entityManager->flush();
        }
        return $this->render('product/index.html.twig', [
        'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
    $product = new Product();
    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($product);
    $entityManager->flush();

    return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
}

return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
}

    /**
     * @Route("/show/{price}/{pickup}/{id}/", name="product_show", methods={"GET","POST"}, requirements={"price"=".+"})
     */
    public function show(Product $product, string $pickup, string $price, Request $request, CartManager $cartManager): Response {

    $data = $request->get('search');
    $parameters = array();
    $parameters['transportPrice'] = $request->get('inputprice');
    $parameters['select1'] = $request->get('select1');
    $parameters['select2'] = $request->get('select2');
    $parameters['select3'] = $request->get('select3');
    $parameters['name'] = $data;

    $form = $this->createForm(AddToCartType::class);

    $form->handleRequest($request);
    
    if($price == 'detal' && $pickup == 'factory'){
            $pricefloat  = $product->getSellPriceFactoryDetal();
        }
        if($price == 'hurt' && $pickup == 'factory'){
            $pricefloat  = $product->getSellPriceFactoryContractors();
        }
        if($price == 'specjal' && $pickup == 'factory' ){
            $pricefloat  = $product->getSellPriceFactoryWholesale();
        }
        if($price == 'detal' && $pickup == 'pitch'){
            $pricefloat  = $product->getSellPricePitchDetal();
        }
        if($price == 'hurt' && $pickup == 'pitch'){
            $pricefloat  = $product->getSellPricePitchContractors();
        }
        if($price == 'specjal' && $pickup == 'pitch' ){
            $pricefloat  = $product->getSellPricePitchWholesale();
        }

    if ($form->isSubmitted()) {
//        if($form->get('remove')->isClicked()){
//            $item = $form->getData();
//        }
        $pricefloat = 0;
        $item = $form->getData();
        $item->setProduct($product);
        $quantity = $item->getQuantity();
        $price = $item->getPrice();
        $productPackaging = $product->getPackaging();
        $palets = ceil($quantity/$productPackaging);
        
        $productFactoryName = $product->getManufacture();
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $paletaProduct = $repository->findOneBySomeName('paleta ' . $productFactoryName);
        $item2 = new OrderItem();
        $item2->setProduct($paletaProduct);
        $item2->setQuantity($palets);
        $item2->setPrice($paletaProduct->getSellPriceFactoryDetal());

        $cart = $cartManager->getCurrentCart();
        if($paletaProduct){
            $cart
            ->addItem($item2)
            ->setUpdatedAt(new \DateTime());
        }
        
        $cart
            ->addItem($item)
            ->setUpdatedAt(new \DateTime());

        $cartManager->save($cart);
        
        $this->addFlash('success',
                'Produkt został dodadny'
            );
        return $this->redirectToRoute('product_index');
    }


    return $this->render('product/show.html.twig', [
        'product' => $product,
        'form' => $form->createView(),
        'parameters' => $parameters,
        'price' => $pricefloat
    ]);
}

/**
 * @Route("/edit/{id}", name="product_edit", methods={"GET","POST"})
 */
public function edit(Request $request, Product $product): Response {
$form = $this->createForm(ProductType::class, $product);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $this->getDoctrine()->getManager()->flush();

    return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
}

return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
}

/**
 * @Route("/delete/{id}", name="product_delete", methods={"POST"})
 */
public function delete(Request $request, Product $product): Response {
if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($product);
    $entityManager->flush();
}

return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
}

 /**
     * @Route("/availability/{id}/", name="product_not_available", methods={"GET", "POST"})
     */
    public function changeAvailability(Request $request, Product $product): Response {
        
        $availibility = $product->getIsNotAvailable();
        if($availibility){
            $product->setIsNotAvailable(false);
        }else{
            $product->setIsNotAvailable(true);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();
        
        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }

}
