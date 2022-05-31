<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\ProductImportType;
use App\Form\ProductAvailibilityType;
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
use App\Manager\FactoryOrderManager;
use App\Entity\OrderItem;
use App\Entity\Notice;
use App\Entity\Order;
use App\Entity\Factory;
use App\Entity\Option;
use App\Entity\OrderFactoryItem;
use App\Entity\FactoryOrder;
use App\Entity\ProductCategory;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController {

    /**
     * @Route("/type/{type}", defaults={"type" = "cart"}, name="product_index", methods={"GET", "POST"})
     */
    public function index(ProductRepository $productRepository, Request $request, CartManager $cartManager, string $type): Response {
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
        $is_parameter = false;
        foreach($parameters as $parameter => $value){
   
            if($value !== null){
                $is_parameter =  true;
            }
        }
        var_dump($parameters);
        if($data || $is_parameter){
             $products = $repository->findByNameField($data, $parameters['factory']);
        }   
        else{
            $products = $productRepository->findAllPriceGreaterThanZero();
        }
        
        return $this->render('product/index.html.twig', [
                    'products' => $products,
                    'parameters' => $parameters,
                    'type' => $type,
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
                $product->setIsOnPalet($value['paletowane']);
                $product->setIsSellCost($value['prowizja na sztuki']);
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
     * @Route("/show/{price}/{pickup}/{id}/{type}", name="product_show", methods={"GET","POST"}, requirements={"price"=".+"})
     */
    public function show(Product $product, string $pickup, string $price, string $type, Request $request, CartManager $cartManager, FactoryOrderManager $factoryOrderManager): Response {

    $data = $request->get('search');
    $parameters = array();
    $parameters['transportPrice'] = $request->get('inputprice');
    $parameters['select1'] = $request->get('select1');
    $parameters['select2'] = $request->get('select2');
    $parameters['select3'] = $request->get('select3');
    $parameters['name'] = $data;

    $form = $this->createForm(AddToCartType::class);
    $form->handleRequest($request);
    
     $optionRepository = $this->getDoctrine()->getRepository(Option::class);
        $nknm = $optionRepository->findOneBy(['shortcode' => 'nknm']);
        $nknm = ($nknm->getValue() / 100) + 1;
    
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
        $fullPallets = floor($quantity/$productPackaging);
        $productFactoryName = $product->getManufacture();
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $paletaProduct = $repository->findOneBySomeName('paleta ' . $productFactoryName);
        
        if($type=='cart'){
             $cart = $cartManager->getCurrentCart();
            if($product->getIsOnPalet()){
            $item2 = new OrderItem();
            $item2->setProduct($paletaProduct);
            $item2->setQuantity($palets);
            $item2->setPrice($paletaProduct->getSellPriceFactoryDetal());

            if($paletaProduct){
                $cart
                ->addItem($item2)
                ->setUpdatedAt(new \DateTime());
            }
            }
            if($fullPallets == $palets){
                 $cart
                    ->addItem($item)
                    ->setUpdatedAt(new \DateTime());
            }else{
                if($item->getProduct()->getIsSellCost() == true){
                    $optionRepository = $this->getDoctrine()->getRepository(Option::class);
                    $nknm = $optionRepository->findOneBy(['shortcode' => 'nknm']);
                    $nknm = ($nknm->getValue() / 100) + 1;
                    $item->setQuantity($fullPallets*$product->getPackaging());
                    $cart
                        ->addItem($item)
                        ->setUpdatedAt(new \DateTime());
                    $extraQuantity =$quantity-($fullPallets*$product->getPackaging());
                    $item3 = new OrderItem();
                    $item3->setProduct($product);
                    $item3->setQuantity($extraQuantity);
                    $item3->setPrice(round($price) * $nknm, 2);
                    $cart
                        ->addItem($item3)
                        ->setUpdatedAt(new \DateTime());
                }else{
                    $cart
                        ->addItem($item)
                        ->setUpdatedAt(new \DateTime());
                }
                
            }
            
            

            $cartManager->save($cart);
            $this->addFlash('success',
                'Produkt został dodadny do oferty/zamówienia'
            );
        }
        if($type=='factory_order'){
            $factoryOrderItem = new OrderFactoryItem();
            $factoryOrderItem->setProduct($item->getProduct());
            $factoryOrderItem->setIsConfirmed(false);
            $factoryOrderItem->setQuantity($item->getQuantity());
            $factoryOrder = $factoryOrderManager->getCurrentFactoryOrder();
            $factoryOrder->addOrderFactoryItem($factoryOrderItem);
            $factoryOrderManager->save($factoryOrder);
            $this->addFlash('success',
                'Produkt został dodadny do zamówienia na fabrykę'
            );
        }
        if($type=='pitch_order'){
            $this->addFlash('success',
                'Produkt został dodadny do przygotowania zamówienia na magazynie'
            );
        }
        
        return $this->redirectToRoute('product_index',['type'=>$type]);
    }


    return $this->render('product/show.html.twig', [
        'product' => $product,
        'form' => $form->createView(),
        'nknm' => $nknm,
        'parameters' => $parameters,
        'price' => $pricefloat,
        'pricetype' => $price,
        'type' => $type
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
    public function changeNotAvailability(Request $request, Product $product): Response {
        
        $entityManager = $this->getDoctrine()->getManager();
        $product->setIsNotAvailable(true);
        $entityManager->persist($product);
        $entityManager->flush();
        
        $form = $this->createForm(ProductAvailibilityType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/set_not_availiable.html.twig', [
                    'product' => $product,
                    'form' => $form,
                ]);
}

/**
     * @Route("/availabilityenable/{id}/", name="product_available", methods={"GET", "POST"})
     */
    public function changeAvailability(Request $request, Product $product): Response {
        
        $entityManager = $this->getDoctrine()->getManager();
        $product->setIsNotAvailable(false);
            $usersToNotify = $product->getNotifyUserIfAvaible();
            foreach ($usersToNotify as $user) {
                $notice = new Notice;
                $notice->setOwner($user);
                $notice->setIsReaded(false);
                $notice->setType('product-'.$product->getId());
                $notice->setDateCreated(new DateTime('NOW'));
                $notice->setText('produkt '.$product->getName().' jest już dostępny');
                $entityManager->persist($notice);
            }
        $entityManager->persist($product);
        $entityManager->flush();
        
        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);

}


            
    
    
    
    /**
     * @Route("/productpricecalculate/{page<\d+>}", name="product_group_price_count", methods={"GET","POST"})
     */
    public function changeProductsPrices(Request $request, int $page = 1): Response {
        $nazwa = $request->query->get('search_nazwa');
        $factory = $request->query->get('search_producent');
        $nbrows = $request->query->get('rows');
        $searchCategory = $request->query->get('search_category');
        $discountPercent = $request->query->get('count_buy_price_from_catalog_price_discount');
        $discountPercentUp = $request->query->get('count_buy_price_from_catalog_price_discount_up');
        $is_netto = $request->query->get('is_netto');
        $repository = $this->getDoctrine()->getRepository(Product::class);
        
        if($searchCategory == NULL){
            $products = $repository->findAllPriceGreaterThanZeroNotOnPromotion($nazwa,$factory);
        }else{
            $products = $repository->findAllPriceGreaterThanZeroNotOnPromotionCategory($nazwa,$factory,$searchCategory);
        }

        $repositoryFactory = $this->getDoctrine()->getRepository(Factory::class);
        $factories = $repositoryFactory->findAll();
        if(empty($nbrows)){
            $nbrows = 50;
        }
        $repositoryCategory = $this->getDoctrine()->getRepository(ProductCategory::class);
        $categories = $repositoryCategory->findAll(); 
        
        $pagerfanta = new Pagerfanta(new QueryAdapter($products));
        $pagerfanta->setMaxPerPage($nbrows);
        $pagerfanta->setCurrentPage($page);
        
        $factoryArray = [];
        $entityManager = $this->getDoctrine()->getManager();
    
        foreach($factories as $factory){
            $factoryArray[strtolower($factory->getName())] = $factory->getPitchTransportPrice();
        }
        if($request->query->get('calculate_prices') !== null){
            foreach($request->query->all() as $key => $req){
                 if(strpos($req, ',') !== false){
                     $req = str_replace(",", ".", $req);
                 }
                if(strpos($key, '-') !== false){
                    $varNameArray = explode('-',$key);
                    $productId = $varNameArray[0];
                    $inputType = $varNameArray[1];
                    
                    if($inputType == 'selected' && $request->query->get('types') == 'buy_price'){
                        foreach($pagerfanta->getCurrentPageResults() as $product){
                            if($productId == $product->getId() ) {
                                $catalogPrice = $product->getCatalogPrice();
                                $buyPrice = $product->getBuyPrice();
                                if($discountPercent > 0){
                                    $productBuyPrice = $catalogPrice-($catalogPrice/100*$discountPercent);
                                    $product->setBuyPrice(round($productBuyPrice,2));
                                    $product->setUpdateDate(new DateTime('NOW'));
                                    $entityManager->persist($product);
                                }
                                if($discountPercentUp > 0){
                                    $productBuyPrice = $buyPrice * ((100+$discountPercentUp)/100);
                                    $product->setBuyPrice(round($productBuyPrice,2));
                                    $product->setUpdateDate(new DateTime('NOW'));
                                    $entityManager->persist($product);
                                }
                            }
                        }
                    }
                    if($inputType == 'marzadetal'){
                        if($request->query->get('round')){
                                    $roundprice = 0.5;
                                }else{
                                    $roundprice = 0.1;
                                }
                        $minDetal = $request->query->get('min_narzut_detal');
                        foreach($pagerfanta->getCurrentPageResults() as $product){
                            if($productId == $product->getId()) {
                                $productBuyPrice = $product->getBuyPrice();
                                if($minDetal < 10){
                                  $percentValue = floatval('1.0'.$minDetal); 
                                }else{
                                  $percentValue = floatval('1.'.$minDetal); 
                                }
                                if($minDetal){
                                    $productPrice =  $this->ceiling(($productBuyPrice*floatval('1.'.$minDetal)),$roundprice);
                                }else{
                                    $productPrice = $this->ceiling(($productBuyPrice*floatval('1.'.$req)),$roundprice);
                                }
                                $product->setSellPriceFactoryDetal($productPrice);
                                $product->setUpdateDate(new DateTime('NOW'));
                                $productFactory = strtolower($product->getManufacture());
                                $factoryNettoTransportPrice = $factoryArray[$productFactory];
                                $productPckaging = $product->getPackaging();
                                if(empty($productPckaging)){
                                    $productPckaging = 1;
                                }
                                $factoryNettoTransportPricePerUnit = ($factoryNettoTransportPrice/14/$productPckaging)*1.23;
                                if($productBuyPrice > 0){
                                    $pricePitch = $this->ceiling(($productPrice+$factoryNettoTransportPricePerUnit),$roundprice);
                                    $product->setSellPricePitchDetal($pricePitch);
                                    $product->setUpdateDate(new DateTime('NOW'));
                                }else{
                                    $product->setSellPricePitchDetal(0);
                                    $product->setUpdateDate(new DateTime('NOW'));
                                }
                                
                            }
                                $entityManager->persist($product);
    
                            }
                        }
                        if($inputType == 'marzahurt'){
                            if($request->query->get('round')){
                                    $roundprice = 0.5;
                                }else{
                                    $roundprice = 0.1;
                                }
                        $minDetal = $request->query->get('min_narzut_hurt');
                        foreach($pagerfanta->getCurrentPageResults() as $product){
                            if($productId == $product->getId()) {
                                $productBuyPrice = $product->getBuyPrice();
                                if($minDetal < 10){
                                  $percentValue = floatval('1.0'.$minDetal); 
                                }else{
                                  $percentValue = floatval('1.'.$minDetal); 
                                }
                                if($minDetal){
                                    $productPrice =  $this->ceiling(($productBuyPrice*floatval('1.'.$minDetal)),$roundprice);
                                }else{
                                    $productPrice = $this->ceiling(($productBuyPrice*floatval('1.'.$req)),$roundprice);
                                }
                                $product->setSellPriceFactoryContractors($productPrice);$product->setUpdateDate(new DateTime('NOW'));
                                $productFactory = strtolower($product->getManufacture());
                                $factoryNettoTransportPrice = $factoryArray[$productFactory];
                                $productPckaging = $product->getPackaging();
                                if(empty($productPckaging)){
                                    $productPckaging = 1;
                                }
                                $factoryNettoTransportPricePerUnit = ($factoryNettoTransportPrice/14/$productPckaging)*1.23;
                                 if($productBuyPrice > 0){
                                $pricePitch = $this->ceiling(($productPrice+$factoryNettoTransportPricePerUnit),$roundprice);
                                $product->setSellPricePitchContractors($pricePitch);$product->setUpdateDate(new DateTime('NOW'));
                                 }else{
                                   $product->setSellPricePitchContractors(0);  $product->setUpdateDate(new DateTime('NOW'));
                                 }
                            }
                                $entityManager->persist($product);
    
                            }
                        }
                        if($inputType == 'marzaspecjal'){
                        $minDetal = $request->query->get('min_narzut_specjal');
                        if($request->query->get('round')){
                                    $roundprice = 0.5;
                                }else{
                                    $roundprice = 0.1;
                                }
                        foreach($pagerfanta->getCurrentPageResults() as $product){
                            if($productId == $product->getId()) {
                                $productBuyPrice = $product->getBuyPrice();
                                if($minDetal < 10){
                                  $percentValue = floatval('1.0'.$minDetal); 
                                }else{
                                  $percentValue = floatval('1.'.$minDetal); 
                                }
                                if($minDetal){
                                    $productPrice =  $this->ceiling(($productBuyPrice*$percentValue),$roundprice);
                                }else{
                                    $productPrice = $this->ceiling(($productBuyPrice*floatval('1.'.$req)),$roundprice);
                                }
                                $product->setSellPriceFactoryWholesale($productPrice);$product->setUpdateDate(new DateTime('NOW'));
                                $productFactory = strtolower($product->getManufacture());
                                $factoryNettoTransportPrice = $factoryArray[$productFactory];
                                $productPckaging = $product->getPackaging();
                                if(empty($productPckaging)){
                                    $productPckaging = 1;
                                }
                                $factoryNettoTransportPricePerUnit = ($factoryNettoTransportPrice/14/$productPckaging)*1.23;
                                 if($productBuyPrice > 0){
                                $pricePitch = $this->ceiling(($productPrice+$factoryNettoTransportPricePerUnit),$roundprice);
                                $product->setSellPricePitchWholesale($pricePitch);$product->setUpdateDate(new DateTime('NOW'));
                                 }else{
                                     $product->setSellPricePitchWholesale(0);$product->setUpdateDate(new DateTime('NOW'));
                                 }
                            }
                                $entityManager->persist($product);
    
                            }
                        }
                    }
                }
            }
        if($request->query->get('change_prices') !== null){
   
            foreach($request->query->all() as $key => $req){
                 if(strpos($req, ',') !== false){
                     $req = str_replace(",", ".", $req);
                 }
                 
                if(strpos($key, '-') !== false){
                    $varNameArray = explode('-',$key);
                    $productId = $varNameArray[0];
                    $inputType = $varNameArray[1];
                    
                    if($inputType == 'catalogprice'){
                        if($is_netto){
                                $req = round(floatval($req)*1.23, 2);
                            }
                        foreach($pagerfanta->getCurrentPageResults() as $product){

                            if($productId == $product->getId() && $product->getCatalogPrice() !== $req) {
                                
                                $product->setCatalogPrice($req);$product->setUpdateDate(new DateTime('NOW'));
                                $entityManager->persist($product);
    
                            }
                        }
                    }
                    if($inputType == 'buyprice'){
                        foreach($pagerfanta->getCurrentPageResults() as $product){
                            if($productId == $product->getId() && $product->getBuyPrice() !== $req) {
                                if($is_netto){
                                    $product->setBuyPrice(round(floatval($req)*1.23, 2));$product->setUpdateDate(new DateTime('NOW'));
                            }else{
                                $product->setBuyPrice($req);$product->setUpdateDate(new DateTime('NOW'));
                            }
                                $entityManager->persist($product);
                            }
                        }
                    }
                    if($inputType == 'detalprice'){
                        foreach($pagerfanta->getCurrentPageResults() as $product){
                            if($productId == $product->getId() && $product->getSellPriceFactoryDetal() !== $req) {
                                $productFactory = strtolower($product->getManufacture());
                                $factoryNettoTransportPrice = $factoryArray[$productFactory];
                                $productPckaging = $product->getPackaging();
                                if($product->getPackaging() == 0){
                                    $productPckaging = 1;
                                }
                                $factoryNettoTransportPricePerUnit = ($factoryNettoTransportPrice/14/$productPckaging)*1.23;
                                $pricePitch = $factoryNettoTransportPricePerUnit;
                                if($request->query->get('round')){
                                    $pricePitch = $this->ceiling($req+$factoryNettoTransportPricePerUnit, 0.5);
                                    $req = $this->ceiling($req,0.5);
                                }
                         
                                $product->setSellPriceFactoryDetal($req);
                                $product->setSellPricePitchDetal($pricePitch);$product->setUpdateDate(new DateTime('NOW'));
                                $entityManager->persist($product);
    
                            }
                        }
                    }
                    if($inputType == 'hurtprice'){
                        foreach($pagerfanta->getCurrentPageResults() as $product){
                            if($productId == $product->getId() && $product->getSellPriceFactoryContractors() !== $req) {
                                $productFactory = strtolower($product->getManufacture());
                                $factoryNettoTransportPrice = $factoryArray[$productFactory];
                                $productPckaging = $product->getPackaging();
                                if($product->getPackaging() == 0){
                                    $productPckaging = 1;
                                }
                                $factoryNettoTransportPricePerUnit = ($factoryNettoTransportPrice/14/$productPckaging)*1.23;
                                $pricePitch = $factoryNettoTransportPricePerUnit;
                                if($request->query->get('round')){
                                    $pricePitch = $this->ceiling($req+$factoryNettoTransportPricePerUnit, 0.5);
                                    $req = $this->ceiling($req,0.5);
                                }
                                $product->setSellPriceFactoryContractors($req);
                                $product->setSellPricePitchContractors($pricePitch);$product->setUpdateDate(new DateTime('NOW'));
                                $entityManager->persist($product);
    
                            }
                        }
                    }
                    if($inputType == 'specjalprice'){
                        foreach($pagerfanta->getCurrentPageResults() as $product){
                            if($productId == $product->getId() && $product->getSellPriceFactoryWholesale() !== $req) {
                                $productFactory = strtolower($product->getManufacture());
                                $factoryNettoTransportPrice = $factoryArray[$productFactory];
                                $productPckaging = $product->getPackaging();
                                if($product->getPackaging() == 0){
                                    $productPckaging = 1;
                                }
                                $factoryNettoTransportPricePerUnit = ($factoryNettoTransportPrice/14/$productPckaging)*1.23;
                                $pricePitch = $factoryNettoTransportPricePerUnit;
                                if($request->query->get('round')){
                                    $pricePitch = $this->ceiling($req+$factoryNettoTransportPricePerUnit, 0.5);
                                    $req = $this->ceiling($req,0.5);
                                }
                                $product->setSellPriceFactoryWholesale($req);
                                $product->setSellPricePitchWholesale($pricePitch);$product->setUpdateDate(new DateTime('NOW'));
                                $entityManager->persist($product);
    
                            }
                        }
                    }
                    
                    }
                
            }
            
        }
        $entityManager->flush();
        
//        $optionRepository = $this->getDoctrine()->getRepository(Option::class);
//        $nknm = $optionRepository->findOneBy(['shortcode' => 'nknm']);
//        $nknm = ($nknm->getValue() / 100) + 1;
        
        

        return $this->renderForm('product/edit_prices.html.twig', [
            'products' => $pagerfanta,
            'factories' => $factoryArray,
            'categories' => $categories,
//            'nknm' => $nknm,
        ]);
    }
    
    public function ceiling($number, $significance = 1)
    {
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
    }
    
    
/**
 * @Route("/saveexport/", name="export_save", methods={"GET","POST"})
 */
public function saveExport(Request $request): Response {
 $repository = $this->getDoctrine()->getRepository(Product::class);
 $products = $repository->findAll();
 $productsArray = [];
 $entityManager = $this->getDoctrine()->getManager();
 foreach($products as $product){
     $productsArray[$product->getId()] = $product;
 }
 if($request->query->get('save_data') !== null){
            foreach($request->query->all() as $key => $req){
                 if(strpos($key, 'wpid') !== false){
                $productId = explode('-',$key);
                $productId = $productId[1];
            if(!empty($req)){
                $productWpId = $productsArray[$productId]->getWpId();
                if($productWpId !== $req){
                    $productsArray[$productId]->setWpId($req);
                    $entityManager->persist($productsArray[$productId]);
                }
            }
                 }
    }
 }
 
 $entityManager->flush();

return $this->render('product/edit_wp_export.html.twig', [
        'products' => $products,
        ]);
}

/**
 * @Route("/saveexportfile/", name="saveexportfile", methods={"GET","POST"})
 */
public function saveExportFile(Request $request): Response {
 $repository = $this->getDoctrine()->getRepository(Product::class);
 $products = $repository->findAll();

 $entityManager = $this->getDoctrine()->getManager();


            $em = $this->getDoctrine()->getManager();

    $repository = $this->getDoctrine()->getRepository(Product::class);
    $products = $repository->findAll();
    $titles = ['ID','Nazwa','Opublikowany','Cena promocyjna', 'Cena'];
    $rows = array();
    $rows[] = implode(',', $titles);
    foreach ($products as $product) {
        if(empty($product->getSellPriceFactoryDetal()) || $product->getSellPriceFactoryDetal() == 0){
            $productIsEnabled = -1;
        }else{
            $productIsEnabled = 1; 
        }
        if($product->getManufacture()== 'POLBRUK' || $product->getManufacture()== 'LIBET' || $product->getManufacture()== 'CHYŻ-BET'){
            $price = $product->getSellPriceFactoryDetal();
        }else{
            $price = $product->getSellPricePitchDetal();
        }
        $data = $product->getWpId().',"'. $product->getName().'",'.$productIsEnabled.','.$price.','.$product->getCatalogPrice();

        $rows[] = $data;
    }

        $content = implode("\n", $rows);
            $response = new Response($content);
            $response->headers->set('Content-Type', 'text/csv');

    return $response;
 }


    /**
     * @Route("/updatewpid", name="wpid_update", methods={"GET"})
     */
    
    public function updatewpid(ProductRepository $productRepository): Response {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();
        foreach($products as $product){
            $productsArray[$product->getName()] = $product;
        }
        $entityManager = $this->getDoctrine()->getManager();
        $finder = new Finder();
        $finder->files()->in(__DIR__)->name('wpid.csv');
        set_time_limit(1200);

// decoding CSV contents
        foreach ($finder as $file) {
            $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
            // instantiation, when using it inside the Symfony framework
            $contents = $file->getContents();
            // decoding CSV contents
            $products_csv = $serializer->decode($contents, 'csv');

            foreach ($products_csv as $key => $value) {
                $wpid = $value['ID'];
                $productName = $value['Nazwa'];
                foreach($products as $product){
                    if($productName == $product->getName()){
                        $product->setWpId($wpid);
                $entityManager->persist($product);
                }
                
                }
 
        }
        $entityManager->flush();
        }
        
        return $this->render('product/edit_wp_export.html.twig', [
        'products' => $products,
        ]);
}

/**
     * @Route("/exportexcel",  name="exportexcel")
     */
    public function export()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Lista Produktów');

        $sheet->getCell('A1')->setValue('Nazwa');
        $sheet->getCell('B1')->setValue('Producent');
        $sheet->getCell('C1')->setValue('Pakowanie');
        $sheet->getCell('D1')->setValue('Cena katalogowa');
        $sheet->getCell('E1')->setValue('Cena zakupu');
        $sheet->getCell('F1')->setValue('Cena detal Fabryka');
        $sheet->getCell('G1')->setValue('Cena detal Plac');
        $sheet->getCell('H1')->setValue('Cena Wykonawcy Fabryka');
        $sheet->getCell('I1')->setValue('Cena Wykonawcy Plac');
        $sheet->getCell('J1')->setValue('Cena Hurtownie Fabryka');
        $sheet->getCell('K1')->setValue('Cena Hurtownie Plac');

        // Increase row cursor after header write
            $sheet->fromArray($this->getData(),null, 'A2', true);
        

        $writer = new Xlsx($spreadsheet);

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="ExportScan.xls"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }
    
    private function getData(): array
    {
        /**
         * @var $user User[]
         */
        $list = [];
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository(Product::class)->findAll();

        foreach ($products as $product) {
            if($product->getBuyPrice() > 0){
            $list[] = [
                $product->getName(),
                $product->getManufacture(),
                $product->getPackaging(),
                $product->getCatalogPrice(),
                $product->getBuyPrice(),
                $product->getSellPriceFactoryDetal(),
                $product->getSellPricePitchDetal(),
                $product->getSellPriceFactoryContractors(),
                $product->getSellPricePitchContractors(),
                $product->getSellPriceFactoryWholesale(),
                $product->getSellPricePitchWholesale(),
                
                
                
            ];
            }
        }
        return $list;
    }
    
    /**
     * @Route("/availabilitynotice/{id}/", name="product_available_notice", methods={"GET", "POST"})
     */
    public function setAvailabilityNotice(Request $request, Product $product): Response {
        
        $user = $this->getUser();
        $product->addNotifyUserIfAvaible($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();
        
        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
 * @Route("/createexportfile/", name="createexportfile", methods={"GET","POST"})
 */
public function saveExportFileAllData(Request $request): Response {
 $repository = $this->getDoctrine()->getRepository(Product::class);
 $products = $repository->findAll();

 $entityManager = $this->getDoctrine()->getManager();

    $repository = $this->getDoctrine()->getRepository(Product::class);
    $products = $repository->findAll();
    $titles = ['Nazwa','ID','Fabryka','Pakowanie', 'Waga Paczki', 'Waga Jednostki', 'Cena katalogowa', 'Cena zakupu'
        , 'Cena Detal Fabryka', 'Cena Detal Plac'
        , 'Cena Wykonawcy Fabryka', 'Cena Wykonawcy Plac'
        , 'Cena Hurt Fabryka', 'Cena Hurt Plac'
        , 'Czy Kurier', 'Cena Kurier', 'Towar Niedostępny', 'Przewidywany czas dostępnosci', 'Uwagi', 
        'Sprzedaz jednostkowa', 'Szerokosc', 'Na Promocji', 'Na Palecie', 'Czy Koszt Sprzedazy', 'wpid'];
    $rows = array();
    $rows[] = implode(',', $titles);
    foreach ($products as $product) {

        $data = '"'. $product->getName().'",'.
                $product->getId().','.
                $product->getManufacture().','.
                $product->getPackaging().','.
                $product->getPackageWeight().','.
                $product->getUnitWeight().','.
                $product->getCatalogPrice().','.
                $product->getBuyPrice().','.
                $product->getSellPriceFactoryDetal().','.
                $product->getSellPricePitchDetal().','.
                $product->getSellPriceFactoryContractors().','.
                $product->getSellPricePitchContractors().','.
                $product->getSellPriceFactoryWholesale().','.
                $product->getSellPricePitchWholesale().','.
                $product->getIsCourier().','.
                $product->getCourierCost().','.
                $product->getIsNotAvailable().',';
                if($product->getEstimatedAvailabilityDate() != null){
                    $data = $data . $product->getEstimatedAvailabilityDate()->format('Y-m-d H:i:s').',';
                }else{
                    $data = $data . $product->getEstimatedAvailabilityDate().',';
                }
                $data = $data . $product->getNotices().','.
                $product->getSprzedazJednostkowa().','.
                $product->getWidth().','.
                $product->getIsOnPromotion().','.
                $product->getIsOnPalet().','.
                $product->getIsSellCost().','.
                $product->getWpid();

        $rows[] = $data;
    }

        $content = implode("\n", $rows);
            $response = new Response($content);
            $response->headers->set('Content-Type', 'text/csv');

    return $response;
 }
 
     /**
 * @Route("/import/", name="product_import", methods={"GET","POST"})
 */
public function import(Request $request): Response {
 
    $form = $this->createForm(ProductImportType::class);
    $form->handleRequest($request);
    $entityManager = $this->getDoctrine()->getManager();
    $repository = $this->getDoctrine()->getRepository(Product::class);
    $products = $repository->findAll();
    $existingProducts = [];
    foreach($products as $product){
        $existingProducts[$product->getId()] = $product;
    }
    
    if ($form->isSubmitted() && $form->isValid()) {
        $someNewFilename = 'products_'.date('m-d-Y_His').'.csv';

        $file = $form->get('file')->getData();
        
        
            try {$file->move(
                        $this->getParameter('import_upload_directory'),
                        $someNewFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
        
                $finder = new Finder();
                $finder->files()->in($this->getParameter('import_upload_directory'))->name($someNewFilename);
                set_time_limit(1200);

// decoding CSV contents
        foreach ($finder as $file) {
            $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
            // instantiation, when using it inside the Symfony framework
            $contents = $file->getContents();
            // decoding CSV contents
            $context = [",", '"', "\\", "." ];
            $products_csv = $serializer->decode($contents, 'csv', $context);

            foreach ($products_csv as $key => $value) {

                $id = $value['ID'];
               
                if(key_exists($id, $existingProducts)){
                    $existingProducts[$id]->setName($value['Nazwa']);
                    $existingProducts[$id]->setManufacture($value['Fabryka']);
                    $existingProducts[$id]->setPackaging(floatval($value['Pakowanie']));
                    $existingProducts[$id]->setPackageWeight(floatval($value['Waga Paczki']));
                    $existingProducts[$id]->setUnitWeight(floatval($value['Waga Jednostki']));
                    $existingProducts[$id]->setCatalogPrice(floatval($value['Cena katalogowa']));
                    $existingProducts[$id]->setBuyPrice(floatval($value['Cena zakupu']));
                    $existingProducts[$id]->setSellPriceFactoryDetal(floatval($value['Cena Detal Fabryka']));
                    $existingProducts[$id]->setSellPricePitchDetal(floatval($value['Cena Detal Plac']));
                    $existingProducts[$id]->setSellPriceFactoryContractors(floatval($value['Cena Wykonawcy Fabryka']));
                    $existingProducts[$id]->setSellPricePitchContractors(floatval($value['Cena Wykonawcy Plac']));
                    $existingProducts[$id]->setSellPriceFactoryWholesale(floatval($value['Cena Hurt Fabryka']));
                    $existingProducts[$id]->setSellPricePitchWholesale(floatval($value['Cena Hurt Plac']));
                    $existingProducts[$id]->setIsCourier($value['Czy Kurier']);
                    $existingProducts[$id]->setCourierCost(floatval($value['Cena Kurier']));
                    $existingProducts[$id]->setIsNotAvailable($value['Towar Niedostępny']);
                    $existingProducts[$id]->setEstimatedAvailabilityDate(new DateTime($value['Przewidywany czas dostępnosci']));
                    $existingProducts[$id]->setNotices($value['Uwagi']);
                    $existingProducts[$id]->setSprzedazJednostkowa(floatval($value['Sprzedaz jednostkowa']));
                    $existingProducts[$id]->setWidth(intval($value['Szerokosc']));
                    $existingProducts[$id]->setIsOnPromotion(floatval($value['Na Promocji']));
                    $existingProducts[$id]->setIsOnPalet($value['Na Palecie']);
                    $existingProducts[$id]->setIsSellCost($value['Czy Koszt Sprzedazy']);
                    $existingProducts[$id]->setWpid(intval($value['wpid']));
                    $entityManager->persist($existingProducts[$id]);
                }else{
                    $newProduct = new Product();
                    $newProduct->setId($id);
                    $newProduct->setName($value['Nazwa']);
                    $newProduct->setManufacture($value['Fabryka']);
                    $newProduct->setPackaging(floatval($value['Pakowanie']));
                    $newProduct->setPackageWeight(floatval($value['Waga Paczki']));
                    $newProduct->setUnitWeight(floatval($value['Waga Jednostki']));
                    $newProduct->setCatalogPrice(floatval($value['Cena katalogowa']));
                    $newProduct->setBuyPrice(floatval($value['Cena zakupu']));
                    $newProduct->setSellPriceFactoryDetal(floatval($value['Cena Detal Fabryka']));
                    $newProduct->setSellPricePitchDetal(floatval($value['Cena Detal Plac']));
                    $newProduct->setSellPriceFactoryContractors(floatval($value['Cena Wykonawcy Fabryka']));
                    $newProduct->setSellPricePitchContractors(floatval($value['Cena Wykonawcy Plac']));
                    $newProduct->setSellPriceFactoryWholesale(floatval($value['Cena Hurt Fabryka']));
                    $newProduct->setSellPricePitchWholesale(floatval($value['Cena Hurt Plac']));
                    $newProduct->setIsCourier($value['Czy Kurier']);
                    $newProduct->setCourierCost(floatval($value['Cena Kurier']));
                    $newProduct->setIsNotAvailable($value['Towar Niedostępny']);
                    $newProduct->setEstimatedAvailabilityDate(new DateTime($value['Przewidywany czas dostępnosci']));
                    $newProduct->setNotices($value['Uwagi']);
                    $newProduct->setSprzedazJednostkowa(floatval($value['Sprzedaz jednostkowa']));
                    $newProduct->setWidth(intval($value['Szerokosc']));
                    $newProduct->setIsOnPromotion(floatval($value['Na Promocji']));
                    $newProduct->setIsOnPalet($value['Na Palecie']);
                    $newProduct->setIsSellCost($value['Czy Koszt Sprzedazy']);
                    $newProduct->setWpid(intval($value['wpid']));
                    $entityManager->persist($newProduct);
                }
                
                
 
        }
        
        }
$entityManager->flush();

    return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
}

return $this->renderForm('product/new_product_import.html.twig', [
            'form' => $form,
        ]);
 }
 
  /**
     * @Route("/productdisable/{id}/", name="disable_product", methods={"GET", "POST"})
     */
    public function setDisableProduct(Request $request, Product $product): Response {
        
        $user = $this->getUser();
        $product->addNotifyUserIfAvaible($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();
        
        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }
    
    
    
     /**
     * @Route("/productduplicate/{id}/", name="duplicate_product", methods={"GET", "POST"})
     */
    public function duplicateProduct(Request $request, Product $product): Response {
        
        $newProduct = new Product();
        $newProduct->setName($product->getName().'(Kopia)');
        $newProduct->setManufacture($product->getManufacture());
        $newProduct->setPackaging($product->getPackaging());
        $newProduct->setPackageWeight($product->getPackageWeight());
        $newProduct->setUnitWeight($product->getUnitWeight());
        $newProduct->setCatalogPrice($product->getCatalogPrice());
        $newProduct->setBuyPrice($product->getBuyPrice());
        $newProduct->setSellPriceFactoryDetal($product->getSellPriceFactoryDetal());
        $newProduct->setSellPricePitchDetal($product->getSellPricePitchDetal());
        $newProduct->setSellPriceFactoryContractors($product->getSellPriceFactoryContractors());
        $newProduct->setSellPricePitchContractors($product->getSellPricePitchContractors());
        $newProduct->setSellPriceFactoryWholesale($product->getSellPriceFactoryWholesale());
        $newProduct->setSellPricePitchWholesale($product->getSellPricePitchWholesale());
        $newProduct->setIsCourier($product->getIsCourier());
        $newProduct->setCourierCost($product->getCourierCost());
        $newProduct->setIsNotAvailable($product->getIsNotAvailable());
        $newProduct->setEstimatedAvailabilityDate($product->getEstimatedAvailabilityDate());
        $newProduct->setNotices($product->getNotices());
        $newProduct->setSprzedazJednostkowa($product->getSprzedazJednostkowa());
        $newProduct->setWidth($product->getWidth());
        $newProduct->setIsOnPromotion($product->getIsOnPromotion());
        $newProduct->setIsOnPalet($product->getIsOnPalet());
        $newProduct->setIsSellCost($product->getIsSellCost());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newProduct);
        $entityManager->flush();
        
        $id = $newProduct->getId();
        return $this->redirectToRoute('product_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
    }
}
