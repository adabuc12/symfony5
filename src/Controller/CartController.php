<?php

namespace App\Controller;

use App\Form\CartType;
use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DbLog;
use App\Entity\Transport;
use App\Entity\Order;
use App\Entity\Promotion;
use App\Entity\Log;
use App\Entity\Delivery;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\Option;
use DateTime;

/**
 * Class CartController
 * @package App\Controller
 * @Route("/cart")
 */
class CartController extends AbstractController {

    public function getAdressCoordinates($adress_oryginal) {


        $delivery_coordinates = '';
        $polskie = array(" ", " - ", "-", "ó", "ę", "ś", "ć", "ż", "ź", "ą", "ł");
        $miedzyn = array("-", "-", "-", "o", "e", "s", "c", "z", "z", "a", "l");
        $adress = str_replace($polskie, $miedzyn, $adress_oryginal);

        $city_dest_url = 'https://geocoder.api.here.com/6.2/geocode.json?app_id=M0PxytSZGoWKBljk4JYb&app_code=QH26LmstHlS-dR_gaIeuDQ&searchtext='
                . urldecode($adress);
        $city_desc_geodata = $this->curl_get_response($city_dest_url);

        if (!$city_desc_geodata) {
            $this->addFlash(
                    'danger', 'Nie znalazłem adresu, lub brak internetu. Sprawedź połączenie internetowe lub poprawność adresu'
            );
        }
        if (isset($city_desc_geodata['Response']['View'][0]['Result'][0]['Location']['NavigationPosition'][0]['Latitude'])) {
            $city_desce_lat = $city_desc_geodata['Response']['View'][0]['Result'][0]['Location']['NavigationPosition'][0]['Latitude'];
            $city_desc_long = $city_desc_geodata['Response']['View'][0]['Result'][0]['Location']['NavigationPosition'][0]['Longitude'];
            $delivery_coordinates = $city_desce_lat . ',' . $city_desc_long;
        }
        return $delivery_coordinates;
    }

    public function routeCalculate($car_type, $coordinates, $delivery_coordinates, $is_from_wieliczka) {
        $api_key = 'C2tSPlUlHi5779W6X6_vA25C6xJOj-YE7FVnam-mhK8';
        $rest_api_keys = '8ILeU-w11wFpjz8LxUndZPSr4mfvVjScBww0mhKxNbc';
        $city_wieliczka_lat = 49.99042;
        $city_wieliczka_long = 20.08208;
        $here_api_route_url = 'https://router.hereapi.com/v8/routes?language=pl&transportMode=' . $car_type .
                '&apiKey=' . $rest_api_keys . '&origin=' . $coordinates . '&destination=' .
                $delivery_coordinates . '&return=summary,typicalDuration';

        if (!$is_from_wieliczka) {

            $distance_here_url = $here_api_route_url;
            $route_image = 'https://image.maps.ls.hereapi.com/mia/1.6/routing?language=pl&transportMode=' . $car_type . '&apiKey=' . $api_key
                    . '&waypoint0=' . $coordinates .
                    '&waypoint1=' . $delivery_coordinates . '&lc=1652B4&lw=6&t=0&ppi=320&w=500&h=450';
        } elseif ($is_from_wieliczka) {
            if ($city_wieliczka_long >= explode(',', $delivery_coordinates)[1]) {
                $distance_here_url = 'https://router.hereapi.com/v8/routes?language=pl&transportMode=' . $car_type .
                        '&apiKey=' . $rest_api_keys . '&origin=' . $coordinates . '&destination=' .
                        $city_wieliczka_lat . ',' . $city_wieliczka_long . '&via=' . $delivery_coordinates . '&return=summary,typicalDuration';
                $route_image = 'https://image.maps.ls.hereapi.com/mia/1.6/routing?apiKey=' . $api_key
                        . '&waypoint0=' . $coordinates .
                        '&waypoint1=' . $city_wieliczka_lat . ',' . $city_wieliczka_long .
                        '&waypoint2=' . $delivery_coordinates .
                        '&lc=1652B4&lw=6&t=0&ppi=320&w=500&h=450';
            } else {
                $distance_here_url = 'https://router.hereapi.com/v8/routes?language=pl&transportMode=' . $car_type .
                        '&apiKey=' . $rest_api_keys . '&origin=' . $city_wieliczka_lat . ',' . $city_wieliczka_long . '&destination=' .
                        $delivery_coordinates . '&via=' . $coordinates . '&return=summary,typicalDuration';
                $route_image = 'https://image.maps.ls.hereapi.com/mia/1.6/routing?apiKey=' . $api_key
                        . '&waypoint0=' . $city_wieliczka_lat . ',' . $city_wieliczka_long .
                        '&waypoint1=' . $coordinates .
                        '&waypoint2=' . $delivery_coordinates .
                        '&lc=1652B4&lw=6&t=0&ppi=320&w=500&h=450';
            }
        }
        $truck_route = $this->curl_get_response($distance_here_url);
        $truck_route['image'] = $route_image;

        return $truck_route;
    }

    public function removeAutoHds($cart) {

        $cartItems = $cart->getItem();
        foreach ($cartItems as $item) {
            $productName = $item->getProduct()->getName();
            if (strpos($productName, 'Auto HDS') !== false) {
                $cart->removeItem($item);
            }
        }
        return $cart;
    }

    public function removepallets($cart) {

        $cartItems = $cart->getItem();
        foreach ($cartItems as $item) {
            $productName = $item->getProduct()->getName();
            if (strpos($productName, 'paleta') !== false) {
                $cart->removeItem($item);
            }
        }
        return $cart;
    }

    public function getProductPrice($price_group, $pickupbolean, $item, $activeOnPromotions) {
        $pricefloat = 0;
//        $activeOnPromotions[$promotion->getId()] = [$itemsOnPromotion, $promotion];
        $promotionsTypes = [];
        foreach ($activeOnPromotions as $value) {
            $itemsOnPromotion = $value[0];
            $promotion = $value[1];
            $pricetypes = $promotion->getPriceTypes();
            if (in_array($item, $itemsOnPromotion)) {
                if (in_array('detal', $pricetypes)) {
                    $promotionsTypes['detal'] = $promotion;
                }
                if (in_array('hurt', $pricetypes)) {
                    $promotionsTypes['hurt'] = $promotion;
                }
                if (in_array('specjal', $pricetypes)) {
                    $promotionsTypes['specjal'] = $promotion;
                }
            }
        }
     
        if ($price_group == 'detal' && $pickupbolean && key_exists('detal', $promotionsTypes)) {
            $whatToDo = $promotionsTypes['detal']->getCalculationCountType();
            $howMuch = $promotionsTypes['detal']->getCalculationCountValue();
            if ($whatToDo == '-') {
                $pricefloat = $item->getProduct()->getSellPriceFactoryDetal() - $howMuch;
            }
        } else if ($price_group == 'detal' && $pickupbolean) {
            $pricefloat = $item->getProduct()->getSellPriceFactoryDetal();
        }
        if ($price_group == 'hurt' && $pickupbolean && key_exists('hurt', $promotionsTypes)) {
            $whatToDo = $promotionsTypes['hurt']->getCalculationCountType();
            $howMuch = $promotionsTypes['hurt']->getCalculationCountValue();
            if ($whatToDo == '-') {
                $pricefloat = $item->getProduct()->getSellPriceFactoryContractors() - $howMuch;
            }
        } else if ($price_group == 'hurt' && $pickupbolean) {
            $pricefloat = $item->getProduct()->getSellPriceFactoryContractors();
        }
        if ($price_group == 'specjal' && $pickupbolean && key_exists('specjal', $promotionsTypes)) {
            $whatToDo = $promotionsTypes['specjal']->getCalculationCountType();
            $howMuch = $promotionsTypes['specjal']->getCalculationCountValue();
          
            if ($whatToDo == '-') {
                $pricefloat = $item->getProduct()->getSellPriceFactoryWholesale() - $howMuch;
            }
        } else if ($price_group == 'specjal' && $pickupbolean) {
            
            $pricefloat = $item->getProduct()->getSellPriceFactoryWholesale();

        }
        if ($price_group == 'detal' && !$pickupbolean && key_exists('detal', $promotionsTypes)) {
            $whatToDo = $promotionsTypes['detal']->getCalculationCountType();
            $howMuch = $promotionsTypes['detal']->getCalculationCountValue();
            if ($whatToDo == '-') {
                $pricefloat = $item->getProduct()->getSellPricePitchDetal() - $howMuch;
            }
        } else if ($price_group == 'detal' && !$pickupbolean) {
           
            $pricefloat = $item->getProduct()->getSellPricePitchDetal();
             
        }
        if ($price_group == 'hurt' && !$pickupbolean && key_exists('hurt', $promotionsTypes)) {
            $whatToDo = $promotionsTypes['hurt']->getCalculationCountType();
            $howMuch = $promotionsTypes['hurt']->getCalculationCountValue();
            if ($whatToDo == '-') {
                $pricefloat = $item->getProduct()->getSellPricePitchContractors() - $howMuch;
            }
        } else if ($price_group == 'hurt' && !$pickupbolean) {
            $pricefloat = $item->getProduct()->getSellPricePitchContractors();
        }
        if ($price_group == 'specjal' && !$pickupbolean && key_exists('specjal', $promotionsTypes)) {
            $whatToDo = $promotionsTypes['specjal']->getCalculationCountType();
            $howMuch = $promotionsTypes['specjal']->getCalculationCountValue();
            if ($whatToDo == '-') {
                $pricefloat = $item->getProduct()->getSellPricePitchWholesale();
            }
        } else if ($price_group == 'specjal' && !$pickupbolean) {
            $pricefloat = $item->getProduct()->getSellPricePitchWholesale();
        }
        return $pricefloat;
    }

    public function getPalletsCount($form, $cartManager, $activeOnPromotions, $cart) {

        $cartItems = $cart->getItem();
        $extra_weight_packages = 0;
        $packages = 0;
        $extra_weight = 0;
       
        foreach ($cartItems as $item) {
            if (strpos($item->getProduct()->getName(), 'paleta') == false && strpos($item->getProduct()->getName(), 'Auto') == false) {
                $pickupbolean = false;
                if (strpos($form->get('pickup')->getData(), 'factory') !== false) {
                    $pickupbolean = true;
                }
                if($item->getIsLocked() == false){
                    $pricefloat = $this->getProductPrice($form->get('kontrahent_group')->getData(), $pickupbolean, $item, $activeOnPromotions);
                }else{
                    $pricefloat = $item->getPrice();
                }
                $quantity = $item->getQuantity();
                $packaging = $item->getProduct()->getPackaging();
                $unit_weight = $item->getProduct()->getUnitWeight();

                $checkedFullPacks = $item->getQuantity() / $item->getProduct()->getPackaging();
                $checkedFullPacks = round($checkedFullPacks,2);
                
                $floredPacks = floor($checkedFullPacks);
            
                if ($form->get('count_pallets')->getData() == true && $item->getProduct()->getIsOnPalet() == true) {
                   
                    $manufactureName = $item->getProduct()->getManufacture();
                    $repository = $this->getDoctrine()->getRepository(Product::class);
                    $paletaProduct = $repository->findOneBySomeName('paleta ' . $manufactureName);
                    $item2 = new OrderItem();
                    $item2->setProduct($paletaProduct);
                    $item2->setQuantity(ceil($checkedFullPacks));
                    $item2->setPrice($paletaProduct->getSellPriceFactoryDetal());


                    if ($paletaProduct) {
                        $cart
                                ->addItem($item2)
                                ->setUpdatedAt(new \DateTime());
                    }
                }
                
                if($form->get('kontrahent_group')->getData() == 'detal'){
                    $priceProductPitchDetal = $item->getProduct()->getSellPricePitchDetal();
                }
                if($form->get('kontrahent_group')->getData() == 'hurt'){
                    $priceProductPitchDetal = $item->getProduct()->getSellPricePitchContractors();
                }
                if($form->get('kontrahent_group')->getData() == 'specjal'){
                    $priceProductPitchDetal = $item->getProduct()->getSellPricePitchWholesale();
                }
                $optionRepository = $this->getDoctrine()->getRepository(Option::class);
                $nknm = $optionRepository->findOneBy(['shortcode' => 'nknm']);
                $nknm = ($nknm->getValue() / 100) + 1;

                if ($item->getProduct()->getIsSellCost() == false) {
                    $nknm = 1;
                }

                if ($checkedFullPacks - $floredPacks == 0) {
                    $item->setPrice($pricefloat);
                } elseif ($form->get('pickup')->getData() !== 'pitch' ) {
                    $item->setPrice(round(($priceProductPitchDetal * $nknm), 2));
                    $cart->setIsPickupWieliczka(true);
                }else{
                    $item->setPrice(round(($pricefloat * $nknm), 2)); 
                }

                $value = $quantity / $packaging;
                if ($value < 1) {
                    $extra_weight = $extra_weight + ($unit_weight * $quantity);
                } else {
                    $packages = $packages + floor($value);
                    $extra_weight = $extra_weight + ($value * floor($value) * $unit_weight);
                }
            }
        }

        $extra_weight_packages = $extra_weight / 1400;
        $packages = $packages + ceil($extra_weight_packages);
        
        $cartManager->save($cart);


        return $packages;
    }

    public function getPickupCoordinates($name) {
        $coordinates = '';
        switch ($name) {
            case 'pitch':
                $coordinates = '49.99042,20.08208';
                break;
            case 'factory_po':
                $coordinates = '50.0430599,20.1899';
                break;
            case 'factory_li':
                $coordinates = '49.99042,20.08208';
                break;
            case 'factory_jo':
                $coordinates = '49.734947,20.3158562';
                break;
            case 'factory_chi':
                $coordinates = '50.0691498,20.1005593';
                break;
            case 'factory_chs':
                $coordinates = '19.86598791587132,19.86598791587132';
                break;
            case 'factory_br':
                $coordinates = '50.03723322493308,20.020320915872276';
                break;
            case 'factory_ko':
                $coordinates = '50.75874367298347,18.956585915892052';
                break;
        }

        return $coordinates;
    }

    public function getTransportTypes($packages, $form, $truck_route_distance) {
        $transport_type = [];
        $transportNames = [];
        foreach ($form->get('relation')->getData() as $transport) {
            array_push($transportNames, $transport->getName());
        }

        for ($i = $packages; $i > 0; $i--) {
            if (($packages >= 9) && in_array("auto-14", $transportNames) && ($truck_route_distance < 100)) {
                array_push($transport_type, 14);
                $packages = $packages - 14;
            } elseif ($packages >= 7 && in_array("auto-8", $transportNames) && $truck_route_distance < 100) {
                array_push($transport_type, 8);
                $packages = $packages - 8;
            } elseif (($packages >= 3) && in_array("auto-6", $transportNames) && $truck_route_distance < 100) {
                array_push($transport_type, 6);
                $packages = $packages - 6;
            } elseif (($packages >= 1) && ($truck_route_distance > 40) && in_array("auto-6", $transportNames)) {
                array_push($transport_type, 6);
                $packages = $packages - 6;
            } elseif (($packages >= 1) && ($truck_route_distance < 41) && in_array("auto-2", $transportNames)) {
                array_push($transport_type, 2);
                $packages = $packages - 2;
            } elseif (($packages >= 1) && ($truck_route_distance < 100) && in_array("auto-6", $transportNames)) {
                array_push($transport_type, 6);
                $packages = $packages - 6;
            } elseif (($packages >= 1) && ($truck_route_distance < 100) && in_array("auto-8", $transportNames)) {
                array_push($transport_type, 8);
                $packages = $packages - 8;
            } elseif (($packages >= 1) && ($truck_route_distance < 100) && in_array("auto-14", $transportNames)) {
                array_push($transport_type, 14);
                $packages = $packages - 14;
            }
        }
        if ($packages > 0) {
            $this->addFlash(
                    'danger', '1 - Trasa większa niż 100 km, lub brak trasy'
            );
        }
        return $transport_type;
    }

    public function getTransportsArray($transport_type, $roud_up_by_10_distance, $form) {

        $transport_bag = [];
        $transportRepository = $this->getDoctrine()->getRepository(Transport::class);

        if (!empty($transport_type) && $form->get('own_pickup')->getData() == false) {

            foreach ($transport_type as $value) {
                if (isset($transport_bag[$value])) {
                    $transport_bag[$value]['count'] = $transport_bag[$value]['count'] + 1;
                } else {
                    $transport_bag[$value]['count'] = 1;
                }
                $transport = $transportRepository->findOneBy(['name' => 'auto-' . $value]);

                if ($transport) {
                    switch ($roud_up_by_10_distance) {
                        case 5:
                            $transport_price = $transport->getPrice5();
                            break;
                        case 10:
                            $transport_price = $transport->getPrice10();
                            break;
                        case 15:
                            $transport_price = $transport->getPrice15();
                            break;
                        case 20:
                            $transport_price = $transport->getPrice20();
                            break;
                        case 25:
                            $transport_price = $transport->getPrice25();
                            break;
                        case 30:
                            $transport_price = $transport->getPrice30();
                            break;
                        case 35:
                            $transport_price = $transport->getPrice35();
                            break;
                        case 40:
                            $transport_price = $transport->getPrice40();
                            break;
                        case 45:
                            $transport_price = $transport->getPrice45();
                            break;
                        case 50:
                            $transport_price = $transport->getPrice50();
                            break;
                        case 55:
                            $transport_price = $transport->getPrice55();
                            break;
                        case 60:
                            $transport_price = $transport->getPrice60();
                            break;
                        case 65:
                            $transport_price = $transport->getPrice65();
                            break;
                        case 70:
                            $transport_price = $transport->getPrice70();
                            break;
                        case 75:
                            $transport_price = $transport->getPrice75();
                            break;
                        case 80:
                            $transport_price = $transport->getPrice80();
                            break;
                        case 85:
                            $transport_price = $transport->getPrice85();
                            break;
                        case 90:
                            $transport_price = $transport->getPrice90();
                            break;
                        case 95:
                            $transport_price = $transport->getPrice95();
                            break;
                        case 100:
                            $transport_price = $transport->getPrice100();
                            break;
                    }
                }
                if (isset($transport_bag[$value]['cost'])) {

                    $transport_bag[$value]['cost'] = $transport_price;
                } else {
                    $transport_bag[$value]['cost'] = $transport_price;
                }

                if ($form->get('is_pickup_wieliczka')->getData() && $form->get('pickup')->getData() !== 'pitch') {
                    $optionRepository = $this->getDoctrine()->getRepository(Option::class);
                    $doladunek = $optionRepository->findOneBy(['shortcode' => 'doladunek']);
                    $transport_bag['przeladunek']['cost'] = $doladunek->getValue();
                    $transport_bag['przeladunek']['count'] = 1;
                }
                if ($form->get('is_extra_delivery')->getData()) {
                    $optionRepository = $this->getDoctrine()->getRepository(Option::class);
                    $przeladunek = $optionRepository->findOneBy(['shortcode' => 'przeladunek']);
                    $transport_bag['doladunek']['cost'] = $przeladunek->getValue();
                    $transport_bag['doladunek']['count'] = 1;
                }
            }
        }
        return $transport_bag;
    }

    public function saveAutoHds($entityManager, $form, $transport_bag, $cartManager, $cart) {


        $repositoryProduct = $entityManager->getRepository(Product::class);
        if (!$form->get('own_pickup')->getData()) {

            foreach ($transport_bag as $name => $value) {
                $product = $repositoryProduct->findBy(array('name' => 'Auto HDS ' . $name . ' pal.'), array('name' => 'ASC'), 1, 0);
                if (empty($product)) {
                    $newProduct = new Product();
                    $newProduct->setName('Auto HDS ' . $name . ' pal.');
                    $newProduct->setManufacture('Kołodomu.pl s.c.');
                    $newProduct->setPackaging(1);
                    $newProduct->setPackageWeight(1);
                    $newProduct->setUnitWeight(1);
                    $newProduct->setCatalogPrice($value['count']);
                    $newProduct->setBuyPrice($value['count']);
                    $newProduct->setSellPriceFactoryDetal($value['count']);
                    $newProduct->setSellPricePitchDetal($value['count']);
                    $newProduct->setSellPriceFactoryContractors($value['count']);
                    $newProduct->setSellPricePitchContractors($value['count']);
                    $newProduct->setSellPriceFactoryWholesale($value['count']);
                    $newProduct->setSellPricePitchWholesale($value['count']);
                    $newProduct->setSprzedazJednostkowa($value['count']);
                    $newProduct->setIsCourier(0);
                    $newProduct->setCourierCost(0);
                    $newProduct->setIsNotAvailable(0);
                    $newProduct->setEstimatedAvailabilityDate(new DateTime('NOW'));
                    $newProduct->setNotices('');
                    $entityManager->persist($newProduct);

                    $orderItem = new OrderItem();
                    $orderItem->setProduct($newProduct);
                    $orderItem->setQuantity($value['count']);
                    if($cart->getCarPriceNetto()){
                        $orderItem->setPrice($value['cost']);
                    }else{
                        $orderItem->setPrice($value['cost'] * 1.23);
                    }
                    
                    $cart->addItem($orderItem)->setUpdatedAt(new \DateTime());
                    $cartManager->save($cart);
                } else {
                    $orderItem = new OrderItem();
                    $orderItem->setProduct($product[0]);
                    $orderItem->setQuantity($value['count']);
                    if($cart->getCarPriceNetto()){
                        $orderItem->setPrice($value['cost']);
                    }else{
                        $orderItem->setPrice($value['cost'] * 1.23);
                    }
                    $cart->addItem($orderItem)->setUpdatedAt(new \DateTime());
                    $cartManager->save($cart);
                }
            }
        }
    }

    public function getPromotions($entityManager, $cart) {
        $repositoryPromotion = $this->getDoctrine()->getRepository(Promotion::class);
        $promotions = $repositoryPromotion->findEnabled();
        $activeOnPromotions = [];

        foreach ($promotions as $promotion) {
            $productCategory = $promotion->getProductCategory();
            $categoriesInPromotion = [];
            $itemsOnPromotion = [];
            if ($promotion->getCartCondition() != null) {
                $conditionType = 'cart';
                $conditionmeasure = $promotion->getCartConditionType();
                $condition = $promotion->getCartCondition();
                $conditionValue = $promotion->getCartConditionValue();
            }
            if ($promotion->getProductCondition() != null) {
                $conditionType = 'product';
                $conditionmeasure = $promotion->getProductConditionType();
                $condition = $promotion->getProductCondition();
                $conditionValue = $promotion->getProductConditionValue();
            }
            if ($promotion->getPriceCondition() != 'null') {
                $conditionType = 'price';
                $conditionmeasure = $promotion->getPriceConditionType();
                $condition = $promotion->getPriceCondition();
                $conditionValue = $promotion->getPriceConditionValue();
            }
            foreach ($productCategory as $promotionProductCategory) {
                array_push($categoriesInPromotion, $promotionProductCategory->getId());
            }
            $itemQuantity = 0;
            foreach ($cart->getItem() as $item) {
                $productCategories = $item->getProduct()->getProductCategories();
                foreach ($productCategories as $cartItemProductCategory) {
                    if (in_array($cartItemProductCategory->getId(), $categoriesInPromotion)) {
                        $itemQuantity = $itemQuantity + $item->getQuantity();
                        array_push($itemsOnPromotion, $item);
                    }
                }
            }
            $toCalculateConditions = [
                $promotion->getCalculationType(),
                $promotion->getCalculationCountType(),
                $promotion->getCalculationCountValue(),
                $promotion->getcalculationCountIsPercent()
            ];

            if ($condition == '>') {
                if ($itemQuantity > $conditionValue) {
                    $activeOnPromotions[$promotion->getId()] = [$itemsOnPromotion, $promotion];
                }
            }
            if ($condition == '<') {
                if ($itemQuantity < $conditionValue) {
                    $activeOnPromotions[$promotion->getId()] = [$itemsOnPromotion, $promotion];
                }
            }
            if ($condition == '=') {
                if ($conditionValue == $itemQuantity) {
                    $activeOnPromotions[$promotion->getId()] = [$itemsOnPromotion, $promotion];
                }
            }
        }
        $samePriceType = [];
        $samePriceType['detal'] = [];
        $samePriceType['hurt'] = [];
        $samePriceType['specjal'] = [];
        $sameConditionType = [];
        foreach ($activeOnPromotions as $promotion) {
            if (in_array('detal', $promotion[1]->getPriceTypes())) {
                array_push($samePriceType['detal'], $promotion);
            }
            if (in_array('hurt', $promotion[1]->getPriceTypes())) {
                array_push($samePriceType['hurt'], $promotion);
            }
            if (in_array('specjal', $promotion[1]->getPriceTypes())) {
                array_push($samePriceType['specjal'], $promotion);
            }
        }
        foreach ($samePriceType as $key => $value) {
            foreach ($value as $promotion) {
                if (key_exists($key . $promotion[1]->getCartConditionType() . $promotion[1]->getCartCondition(), $sameConditionType)) {
                    if ($promotion[1]->getCartConditionValue() > $sameConditionType[$key . $promotion[1]->getCartConditionType() . $promotion[1]->getCartCondition()][0]) {
                        $sameConditionType[$key . $promotion[1]->getCartConditionType() . $promotion[1]->getCartCondition()] = [$promotion[1]->getCartConditionValue(), $promotion];
                    }
                } else {
                    $sameConditionType[$key . $promotion[1]->getCartConditionType() . $promotion[1]->getCartCondition()] = [$promotion[1]->getCartConditionValue(), $promotion];
                }
            }
        }
        $promotionIds = [];

        foreach ($sameConditionType as $key => $value) {
            array_push($promotionIds, $value[1][1]->getId());
        }

        foreach ($activeOnPromotions as $key => $promotion) {
            if (in_array($key, $promotionIds)) {
                
            } else {
                unset($activeOnPromotions[$key]);
            }
        }
        return $activeOnPromotions;
    }

    /**
     * @Route("/", name="cart")
     */
    public function index(CartManager $cartManager, Request $request, DbLog $logger): Response {
        
        $entityManager = $this->getDoctrine()->getManager();
       
        if($this->get('session')->has('cart_id')){
            $repositoryCart = $entityManager->getRepository(Order::class);
            $cart = $repositoryCart->findOneBy(['id'=>$this->get('session')->get('cart_id')]);
            $cartManager->setCart($cart);
        }else{
            $cart = $cartManager->getCurrentCart();
        }
        
        
        
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);
        $repositoryLog = $entityManager->getRepository(Log::class);
        $logs = $repositoryLog->findByType($cart->getType(), $cart->getId());

        $activeOnPromotions = $this->getPromotions($entityManager, $cart);

        if (!$this->get('session')->has('transport_time')) {
            $transport_time = null;
        } else {
            $transport_time = $this->get('session')->get('transport_time');
        }
        if (!$this->get('session')->has('route_image')) {
            $route_image = null;
        } else {
            $route_image = $this->get('session')->get('route_image');
        }
        if (!$this->get('session')->has('truck_route_distance')) {
            $truck_route_distance = null;
        } else {
            $truck_route_distance = $this->get('session')->get('truck_route_distance');
        }

        $kontrahent = $this->get('session')->get('kontrahent');

        if ($form->isSubmitted()) {

            foreach ($form->get('item') as $entry) {

                $toRemove = $entry->get('remove')->isClicked();
                if ($toRemove) {
                    $this->addFlash(
                            'success', 'Produkt usunięty');
                }
            }

            if ($form->getClickedButton() === $form->get('count')) {
                
                $formKontrahent = $form->get('kontrahent')->getData();
                if ($formKontrahent !== $cart->getKontrahent()) {
                    $logger->write($cart->getType(), $cart->getId(), 'Zmiana kontrahenta z ' . $cart->getKontrahent()->getName() . ' na ' . $formKontrahent->getName(), $this->getUser());
                    $cart->setKontrahent($formKontrahent);
                }
                if($form->get('own_pickup')->getData() == false){
                $delivery_coordinates = $this->getAdressCoordinates($form->get('adress')->getData());
                $coordinates = $this->getPickupCoordinates($form->get('pickup')->getData());

                $car_type = 'truck';
                
                $truck_route = $this->routeCalculate($car_type, $coordinates, $delivery_coordinates, $form->get('is_pickup_wieliczka')->getData());
                
                if (isset($truck_route['routes'])) {
                    $route_image = $truck_route['image'];
                    $this->get('session')->set('route_image', $route_image);
                    $truck_route_distance = ($truck_route['routes'][0]['sections'][0]['summary']['length']) / 1000;
                    $this->get('session')->set('truck_route_distance', $truck_route_distance);
                    $transport_time = ceil(($truck_route['routes'][0]['sections'][0]['summary']['typicalDuration']) / 60);
                    $this->get('session')->set('transport_time', $transport_time);
                }
                }
                $cart = $this->removeAutoHds($cart);
                $cart = $this->removepallets($cart);
   
                $packages = $this->getPalletsCount($form, $cartManager, $activeOnPromotions,$cart);
                    
                    if($form->get('own_pickup')->getData() == false){

                    if ($truck_route_distance > 100) {
                        $this->addFlash(
                                'danger', 'Trasa większa niż 100 km'
                        );
                        return $this->redirectToRoute('cart');
                    }
                    if (empty($truck_route_distance)) {
                        $this->addFlash(
                                'danger', 'Brak adresu lub adres jest błędny'
                        );
                        return $this->redirectToRoute('cart');
                    }
                    
                    $transport_type = $this->getTransportTypes($packages, $form, $truck_route_distance);
                    $roud_up_by_10_distance = ((ceil($truck_route_distance / 5)) * 5);
                    if ($roud_up_by_10_distance < 5) {
                        $roud_up_by_10_distance = 5;
                    }
                    
                    $transport_bag = $this->getTransportsArray($transport_type, $roud_up_by_10_distance, $form);
                    
                    $this->saveAutoHds($entityManager, $form, $transport_bag, $cartManager, $cart);
                 
                }
                

                return $this->redirectToRoute('cart');
            }


            if ($form->getClickedButton() === $form->get('clear')) {

                foreach ($cart->getItem() as $item) {

                    $cart->removeItem($item);
                }
                $logger->write($cart->getType(), $cart->getId(), 'Usunięto produkty', $this->getUser());
                $cartManager->save($cart);
                return $this->redirectToRoute('cart');
            } else {
                $cart->setStatus($form->get('status')->getData());
//                if (empty($cart->getNumber())) {
//                    $repository = $entityManager->getRepository(Order::class);
//                    $results = $repository->findBy(array(), array('id' => 'DESC'), 1, 0);
//                    if (!empty($results)) {
//                        $number = explode('/', $results[0]->getNumber());
//                        $number = $number[0];
//                        $number = $number + 1;
//                    } else {
//                        $number = 1;
//                    }
//                    $number = $number . '/' . date("Y");
//                    $cart->setNumber($number);
//                    var_dump($number);
//                    exit;
//                }


                $logger->write($cart->getType(), $cart->getId(), 'Utworzono nowy dokument', $this->getUser());

                if ($form->get('status')->getData() == 'order') {

                    $delivery = new Delivery();
                    $delivery->addDeliveryOrder($cart);
                    $entityManager->persist($delivery);
                    $entityManager->flush();

                    $logger->write($cart->getType(), $cart->getId(), 'Utworzono nową dostawę ' . $delivery->getId(), $this->getUser());
                }
                $cart->setUpdatedAt(new \DateTime());

                $entityManager->flush();

                $cartManager->save($cart);

                return $this->redirectToRoute('cart');
            }
        }


        return $this->render('cart/index.html.twig', [
                    'cart' => $cart,
                    'form' => $form->createView(),
//                    'adress' => $adress_oryginal,
                    'route_image' => $route_image,
                    'dystans' => $truck_route_distance,
                    'time' => $transport_time,
                    'promotions' => $activeOnPromotions,
                    'logs' => $logs,
//                    'doladunek' => $is_from_wieliczka,
//                    'przeladunek' => $is_additional_delivery,
//                    'deliverypickup' => $pickup,
//                    'pricetype' => $price_group,
//                    'offertype' => $document_type,
//                    'auto1' => $auto1,
//                    'auto2' => $auto2,
//                    'auto3' => $auto3,
//                    'auto4' => $auto4,
//                    'hds' => $hds,
//                    'osobisty' => $own_pick
        ]);
    }

    /**
     * @Route("/clear", name="cart_clear")
     */
    public function clear(CartManager $cartManager, Request $request, DbLog $logger): Response {
        $cart = $cartManager->getCart();

        

        if ($cart->getStatus() == 'offer') {
            $type = 'ofertę';
        } else if ($cart->getStatus() == 'order') {
            $type = 'zamówienie';
        }
        $logger->write($cart->getStatus(), $cart->getId(), 'Wyczyszczono ' . $type, $this->getUser());
        return $this->redirectToRoute('cart');
    }

    public function curl_get_response($url) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response = json_decode($response, true);

        return $response;
    }

    /**
     * @Route("/savecart/{id}", name="save_cart")
     */
    public function save_cart(Order $cart, CartManager $cartManager, Request $request, DbLog $logger): Response {

        if ($cart->getStatus() == 'offer' || $cart->getStatus() == null) {
            $type = 'ofertę';
            $cart->setStatus('offer');
        } else if ($cart->getStatus() == 'order') {
            $type = 'zamówienie';
        }else{
            $type = 'dokumnet';
        }

        $logger->write($cart->getStatus(), $cart->getId(), 'Zapisano ' . $type, $this->getUser());

        $cartType = $cart->getStatus();

        $cartManager->save($cart);
        // musi zebrać extra dane z request i zapisać je do $cart;

        if ($this->get('session')->has('cart_id')) {
            $this->get('session')->remove('cart_id');
        }
        if ($this->get('session')->has('route_image')) {
            $this->get('session')->remove('route_image');
        }
        if ($this->get('session')->has('transport_time')) {
            $this->get('session')->remove('transport_time');
        }
        if ($this->get('session')->has('truck_route_distance')) {
            $this->get('session')->remove('truck_route_distance');
        }

        if ($cartType == 'offer') {
            return $this->redirectToRoute('offer_index');
        } else if ($cartType == 'order') {
            return $this->redirectToRoute('order_index');
        }
        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/drop", name="drop_cart")
     */
    public function drop(CartManager $cartManager, Request $request, DbLog $logger): Response {
        $cart = $cartManager->getCart();
        if ($cart->getStatus() == 'offer') {
            $type = 'Oferta';
        } else if ($cart->getStatus() == 'order') {
            $type = 'Zamówienie';
        }
        $logger->write($cart->getStatus(), $cart->getId(), $type . ' zostało porzucone', $this->getUser());
        if ($this->get('session')->has('cart_id')) {
            $this->get('session')->remove('cart_id');
        }

        return $this->redirectToRoute('order_index');
    }

    /**
     * @Route("/orderoffer/{id}", name="offer_to_order")
     */
    public function orderoffer(Order $offer, Request $request, DbLog $logger): Response {

        
        $entityManager = $this->getDoctrine()->getManager();
        
        $repository = $entityManager->getRepository(Order::class);
        $results = $repository->findBy(array('status'=>'order'), array('id' => 'DESC'), 1, 0);
        $resultAll = $repository->findBy(array(), array('id' => 'DESC'), 1, 0);
        
        $offer->setIsOrdered(true);
        $offer->setOrderId($resultAll[0]->getId());
        $order = new Order;
        $entityManager->persist($offer);

        
      
        
        
        if (!empty($results)) {
            $number = explode('/', $results[0]->getNumber());
            $number = $number[0];
            $number = $number + 1;
        } else {
            $number = 1;
        }
        $number = $number . '/' . date("Y");
        
        $order->setNumber($number);
                $order->setIsOrdered(true);
                foreach($offer->getRelation() as $relation){
                   $order->addRelation($relation); 
                }
                $order->setDeliveryDate($offer->getDeliveryDate());
                foreach($offer->getItem() as $item){
                   $order->addItem($item); 
                }
                
                $order->setStatus('order');
                $order->setCreatedAt($offer->getCreatedAt());
                $order->setUpdatedAt($offer->getUpdatedAt());
                $order->setAdress($offer->getAdress());
                $order->setPhone($offer->getPhone());
                $order->setKontrahent($offer->getKontrahent());
//                $order->setAllowedCarSize($offer->getAllowedCarSize());
                foreach($offer->getDeliveries() as $delivery){
                   $order->addDelivery($delivery); 
                }
                
                $order->setKontrahentGroup($offer->getKontrahentGroup());
                $order->setPickup($offer->getPickup());
                $order->setIsPickupWieliczka($offer->getIsPickupWieliczka());
                $order->setIsExtraDelivery($offer->getIsExtraDelivery());
                $order->setOwnPickup($offer->getOwnPickup());
                $order->setType('new');
                $order->setNotice($offer->getNotice());
                $order->setCountPallets($offer->getCountPallets());
                $order->setUser($this->getUser());
                foreach($offer->getPitchOrders() as $po){
                   $order->addPitchOrder($po);
                }
                
                foreach($offer->getFactoryOrders() as $fo){
                   $order->addFactoryOrder($fo);
                }
                
                $entityManager->persist($order);
                
                $entityManager->flush();

        $logger->write($order->getType(), $order->getId(), 'Oferta '.$offer->getNumber().' została zmieniona na zamówienie', $this->getUser());
        

        return $this->redirectToRoute('order_index');
    }
    
    /**
     * @Route("/orderitem/togglelocked/{orderitemid}", name="orderitem_toggle", methods={"GET"})
     */
    public function toggleLocked(string $orderitemid): Response {
        
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $repository->find($orderitemid);
        $isLocked = $orderItem->getIsLocked();

        if($isLocked == false || $isLocked == null){
            $orderItem->setIsLocked(true);
        }else{
            $orderItem->setIsLocked(false);
        }

        $entityManager->persist($orderItem);
        $entityManager->flush();
        
        return $this->redirectToRoute('cart');
    }

}
