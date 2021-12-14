<?php

namespace App\Controller;

use App\Form\CartType;
use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Transport;

/**
 * Class CartController
 * @package App\Controller
 * @Route("/cart")
 */
class CartController extends AbstractController {

    /**
     * @Route("/", name="cart")
     */
    public function index(CartManager $cartManager, Request $request): Response {

        $cart = $cartManager->getCurrentCart();
        $api_key = 'C2tSPlUlHi5779W6X6_vA25C6xJOj-YE7FVnam-mhK8';
        $rest_api_keys = '8ILeU-w11wFpjz8LxUndZPSr4mfvVjScBww0mhKxNbc';
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);
        $adress_oryginal = '';
        $kontrahent = $this->get('session')->get('kontrahent');
        $telefon = $this->get('session')->get('telefon');
        $adress_oryginal = $this->get('session')->get('adress');
        $truck_route_distance = 0;

        $route_image = '';
        $document_type = '';
        $price_group = '';
        $pickup = '';
        $is_from_wieliczka = '';
        $is_additional_delivery = '';
        $is_hds = '';
        $auto1 = '';
        $auto2 = '';
        $auto3 = '';
        $auto4 = '';
        $hds = '';
        $transport_time = '';
        $cost = 0;
        $transport_bag = [];
        $is_from_wieliczka = $this->get('session')->get('doladunek');
        if (isset($_GET['select1'])) {
            $document_type = $_GET['select1'];
        }
        if (isset($_GET['select2'])) {
            $price_group = $_GET['select2'];
        }
        if (isset($_GET['select3'])) {
            $pickup = $_GET['select3'];
        }
        if (isset($_GET['doladunek'])) {
            $is_from_wieliczka = $_GET['doladunek'];
            $this->get('session')->set('doladunek', true);
        }
        if (isset($_GET['przeladunek'])) {
            $is_additional_delivery = $_GET['przeladunek'];
        }
        if (isset($_GET['is_hds'])) {
            $is_hds = $_GET['is_hds'];
        }
        if (isset($_GET['auto1'])) {
            $auto1 = $_GET['auto1'];
        }
        if (isset($_GET['auto2'])) {
            $auto2 = $_GET['auto2'];
        }
        if (isset($_GET['auto3'])) {
            $auto3 = $_GET['auto3'];
        }
        if (isset($_GET['auto4'])) {
            $auto4 = $_GET['auto4'];
        }
        if (isset($_GET['is_hds'])) {
            $hds = $_GET['is_hds'];
        }
        if (isset($_GET['Telefon'])) {
            $telefon = $_GET['Telefon'];
            $this->get('session')->set('telefon', $telefon);
        }
        if (isset($_GET['Telefon'])) {
            $telefon = $_GET['Telefon'];
            $this->get('session')->set('telefon', $telefon);
        }
        if (isset($_GET['Adress'])) {
            $adress_oryginal = $_GET['Adress'];
            $this->get('session')->set('adress', $adress_oryginal);
            $polskie = array(" ", " - ", "-", "ó", "ę", "ś", "ć", "ż", "ź", "ą", "ł");
            $miedzyn = array("-", "-", "-", "o", "e", "s", "c", "z", "z", "a", "l");
            $adress = str_replace($polskie, $miedzyn, $adress_oryginal);
            $city_dest_url = 'https://geocoder.api.here.com/6.2/geocode.json?app_id=M0PxytSZGoWKBljk4JYb&app_code=QH26LmstHlS-dR_gaIeuDQ&searchtext='
                    . urldecode($adress);
            $city_desc_geodata = $this->curl_get_response($city_dest_url);
            $route_image = null;
            if (!$city_desc_geodata) {
                $this->addFlash(
                        'danger', 'Nie znalazłem adresu, lub brak internetu. Sprawedź połączenie internetowe lub poprawność adresu'
                );
            }

            if (isset($city_desc_geodata['Response']['View'][0]['Result'][0]['Location']['NavigationPosition'][0]['Latitude'])) {
                $city_desce_lat = $city_desc_geodata['Response']['View'][0]['Result'][0]['Location']['NavigationPosition'][0]['Latitude'];
                $city_desc_long = $city_desc_geodata['Response']['View'][0]['Result'][0]['Location']['NavigationPosition'][0]['Longitude'];
                $delivery_coordinates = $city_desce_lat . ',' . $city_desc_long;
                switch ($pickup) {
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
                        $coordinates = '49.99042,20.08208';
                        break;
                    case 'factory_st':
                        $coordinates = '49.99042,20.08208';
                        break;
                    case 'factory_pa':
                        $coordinates = '49.99042,20.08208';
                        break;
                    case 'factory_ko':
                        $coordinates = '49.99042,20.08208';
                        break;
                }

                $city_wieliczka_lat = 49.99042;
                $city_wieliczka_long = 20.08208;
                $city_niepolomice_lat = 50.0430599;
                $city_niepolomice_long = 20.1899;

                $car_type = 'truck';
                $here_api_route_url = 'https://router.hereapi.com/v8/routes?language=pl&transportMode=' . $car_type .
                        '&apiKey=' . $rest_api_keys . '&origin=' . $coordinates . '&destination=' .
                        $delivery_coordinates . '&return=summary,typicalDuration';

                if ($is_from_wieliczka !== 'on') {
                    $distance_here_url = $here_api_route_url;


//                            'https://route.api.here.com/routing/7.2/calculateroute.json?app_id=M0PxytSZGoWKBljk4JYb&app'
//                            . '_code=QH26LmstHlS-dR_gaIeuDQ&waypoint0=geo!' . $coordinates .
//                            '&waypoint1=geo!' . $city_desce_lat . ',' . $city_desc_long . '&mode=fastest;truck;traffic:disabled&limitedWeight=30.5&height=4.25'
//                            . '&shippedHazardousGoods=harmfulToWater';
                    $route_image = 'https://image.maps.ls.hereapi.com/mia/1.6/routing?language=pl&transportMode=' . $car_type . '&apiKey=' . $api_key
                            . '&waypoint0=' . $coordinates .
                            '&waypoint1=' . $city_desce_lat . ',' . $city_desc_long . '&lc=1652B4&lw=6&t=0&ppi=320&w=600&h=450';
                } elseif ($is_from_wieliczka == 'on') {
                    if ($city_wieliczka_long >= $city_desc_long) {
                        $distance_here_url = $here_api_route_url;


//                                'https://route.api.here.com/routing/7.2/calculateroute.json?app_id=M0PxytSZGoWKBljk4JYb&app'
//                                . '_code=QH26LmstHlS-dR_gaIeuDQ&waypoint0=geo!' . $coordinates .
//                                '&waypoint1=geo!' . $city_wieliczka_lat . ',' . $city_wieliczka_long .
//                                '&waypoint2=geo!' . $city_desce_lat . ',' . $city_desc_long . '&mode=fastest;truck;traffic:disabled&limitedWeight=30.5&height=4.25'
//                                . '&shippedHazardousGoods=harmfulToWater';
                        $route_image = 'https://image.maps.ls.hereapi.com/mia/1.6/routing?apiKey=' . $api_key
                                . '&waypoint0=' . $coordinates .
                                '&waypoint1=' . $city_wieliczka_lat . ',' . $city_wieliczka_long .
                                '&waypoint2=' . $city_desce_lat . ',' . $city_desc_long .
                                '&lc=1652B4&lw=6&t=0&ppi=320&w=600&h=450';
                    } else {
                        $distance_here_url = $here_api_route_url;



//                                'https://route.api.here.com/routing/7.2/calculateroute.json?app_id=M0PxytSZGoWKBljk4JYb&app'
//                                . '_code=QH26LmstHlS-dR_gaIeuDQ' .
//                                '&waypoint0=geo!' . $city_wieliczka_lat . ',' . $city_wieliczka_long .
//                                '&waypoint1=geo!' . $coordinates .
//                                '&waypoint2=geo!' . $city_desce_lat . ',' . $city_desc_long . '&mode=fastest;truck;traffic:disabled&limitedWeight=30.5&height=4.25'
//                                . '&shippedHazardousGoods=harmfulToWater';
                        $route_image = 'https://image.maps.ls.hereapi.com/mia/1.6/routing?apiKey=' . $api_key
                                . '&waypoint0=' . $city_wieliczka_lat . ',' . $city_wieliczka_long .
                                '&waypoint1=' . $coordinates .
                                '&waypoint2=' . $city_desce_lat . ',' . $city_desc_long .
                                '&lc=1652B4&lw=6&t=0&ppi=320&w=600&h=450';
                    }
                }

                $truck_route = $this->curl_get_response($distance_here_url);

                $truck_route_distance = ($truck_route['routes'][0]['sections'][0]['summary']['length']) / 1000;
                $transport_time = ceil(($truck_route['routes'][0]['sections'][0]['summary']['typicalDuration']) / 60);

                $cartItems = $cart->getItem();
                $packages = 0;
                $extra_weight = 0;

                foreach ($cartItems as $item) {
                    $productName = $item->getProduct()->getName();
                    if (strpos($productName, 'paleta') !== false) {
                        
                    } else {
                        $pickupbolean = false;
                        if (strpos($pickup, 'factory') !== false) {
                            $pickupbolean = true;
                        }

                        $quantity = $item->getQuantity();
                        $packaging = $item->getProduct()->getPackaging();
                        $unit_weight = $item->getProduct()->getUnitWeight();

                        if ($price_group == 'detal' && $pickupbolean) {
                            $pricefloat = $item->getProduct()->getSellPriceFactoryDetal();
                        }
                        if ($price_group == 'hurt' && $pickupbolean) {
                            $pricefloat = $item->getProduct()->getSellPriceFactoryContractors();
                        }
                        if ($price_group == 'specjal' && $pickupbolean) {
                            $pricefloat = $item->getProduct()->getSellPriceFactoryWholesale();
                        }
                        if ($price_group == 'detal' && !$pickupbolean) {
                            $pricefloat = $item->getProduct()->getSellPricePitchDetal();
                        }
                        if ($price_group == 'hurt' && !$pickupbolean) {
                            $pricefloat = $item->getProduct()->getSellPricePitchContractors();
                        }
                        if ($price_group == 'specjal' && !$pickupbolean) {
                            $pricefloat = $item->getProduct()->getSellPricePitchWholesale();
                        }
                        $checkedFullPacks = $item->getQuantity() / $item->getProduct()->getPackaging();
                        $floredPacks = floor($item->getQuantity() / $item->getProduct()->getPackaging());

                        $priceProductPitchDetal = $item->getProduct()->getSellPricePitchDetal();
                        if ($checkedFullPacks - $floredPacks == 0) {
                            $item->setPrice($pricefloat);
                        } else {
                            $item->setPrice(round($priceProductPitchDetal * 1.25, 2));
                            $adress = $this->get('session')->set('doladunek', true);
                            $is_from_wieliczka = true;
                        }
                        $cartManager->save($cart);
                        $value = $quantity / $packaging;
                        if ($value < 1) {
                            $extra_weight = $extra_weight + ($unit_weight * $quantity);
                        } else {
                            $packages = $packages + floor($value);
                            $extra_weight = $extra_weight + ($value * floor($value) * $unit_weight);
                        }
                    }
                }
                $extra_weight_packages = $extra_weight / 1500;
                $packages = $packages + ceil($extra_weight_packages);

                $transport_type = [];


                if ($truck_route_distance > 100 || empty($truck_route_distance)) {
                    $this->addFlash(
                            'danger', 'Trasa większa niż 100 km, lub brak trasy'
                    );
                    return $this->redirectToRoute('cart');
                }

                for ($i = 1; $i < 5; $i++) {
                    if (isset($_GET['auto' . $i])) {
                        $auto = $_GET['auto' . $i];
                        $this->get('session')->set('auto' . $i, $auto);
                    } else {
                        $this->get('session')->set('auto' . $i, 'off');
                    }
                }



                for ($i = $packages; $i > 0; $i--) {

                    if (($packages >= 9) && ($auto1 == true) && ($truck_route_distance < 100)) {
                        array_push($transport_type, 14);
                        $packages = $packages - 14;
                    } elseif ($packages >= 7 && $auto2 == true && $truck_route_distance < 100) {
                        array_push($transport_type, 8);
                        $packages = $packages - 8;
                    } elseif (($packages >= 3) && $auto3 == true && $truck_route_distance < 100) {
                        array_push($transport_type, 6);
                        $packages = $packages - 6;
                    } elseif (($packages >= 1) && ($truck_route_distance > 40) && $auto3 == true) {
                        array_push($transport_type, 6);
                        $packages = $packages - 6;
                    } elseif (($packages >= 1) && ($truck_route_distance < 41) && $auto4 == true) {
                        array_push($transport_type, 2);
                        $packages = $packages - 2;
                    } elseif (($packages >= 1) && ($truck_route_distance < 100) && $auto3 == true) {
                        array_push($transport_type, 6);
                        $packages = $packages - 6;
                    } elseif (($packages >= 1) && ($truck_route_distance < 100) && $auto2 == true) {
                        array_push($transport_type, 8);
                        $packages = $packages - 8;
                    } elseif (($packages >= 1) && ($truck_route_distance < 100) && $auto1 == true) {
                        array_push($transport_type, 14);
                        $packages = $packages - 14;
                    }
                }
                if ($packages > 0) {
                    $this->addFlash(
                            'danger', '1 - Trasa większa niż 100 km, lub brak trasy'
                    );
                }

                $transportRepository = $this->getDoctrine()->getRepository(Transport::class);


                $roud_up_by_10_distance = ((ceil($truck_route_distance / 5)) * 5);
                if ($roud_up_by_10_distance < 5) {
                    $roud_up_by_10_distance = 5;
                }

                $cost = 0;

                if (!empty($transport_type)) {
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

                            $transport_bag[$value]['cost'] = $transport_bag[$value]['cost'] + $transport_price;
                        } else {
                            $transport_bag[$value]['cost'] = $transport_price;
                        }
                        $cost = $cost + $transport_price;
                        if ($is_from_wieliczka && $pickup !== 'pitch') {
                            $transport_bag['przeladunek']['cost'] = 50;
                            $transport_bag['przeladunek']['count'] = 1;
                            $cost = $cost + 50;
                        }
                        if ($is_additional_delivery) {
                            $transport_bag['doladunek']['cost'] = 150;
                            $transport_bag['doladunek']['count'] = 1;
                            $cost = $cost + 150;
                        }
                    }
                }
            }
        }


        if ($form->isSubmitted()) {
            foreach ($form->get('item') as $entry) {
                $toRemove = $entry->get('remove')->isClicked();
                if ($toRemove) {
                    
                }
                $this->addFlash(
                        'success', 'Produkt usunięty');
            }

            if ($form->getClickedButton() === $form->get('clear')) {
                foreach ($cart->getItem() as $item) {
                    $cart->removeItem($item);
                }
                $cartManager->save($cart);
            } else {
                $cart->setKontrahent($kontrahent);
                $cart->setAllowedCarSize('auto1=' . $auto1 . ',' . 'auto2=' . $auto2 . ',' . 'auto3=' . $auto3 . ',' .
                        'auto4=' . $auto4 . ',' . 'doladunek=' . $is_from_wieliczka . ',' . 'przeladunek=' . $is_additional_delivery . ',' . 'is_hds=' . $is_hds);
                $cart->setPhone($telefon);
                $cart->setAdress($adress_oryginal);
                $cart->setUpdatedAt(new \DateTime());
                $cartManager->save($cart);
            }
        }

        return $this->render('cart/index.html.twig', [
                    'cart' => $cart,
                    'form' => $form->createView(),
                    'telefon' => $telefon,
                    'adress' => $adress_oryginal,
                    'kontrahent' => $kontrahent,
                    'route_image' => $route_image,
                    'dystans' => $truck_route_distance,
                    'time' => $transport_time,
                    'doladunek' => $is_from_wieliczka,
                    'przeladunek' => $is_additional_delivery,
                    'deliverypickup' => $pickup,
                    'pricetype' => $price_group,
                    'offertype' => $document_type,
                    'delivery_cost' => $transport_bag,
                    'transporttotal' => $cost,
                    'auto1' => $auto1,
                    'auto2' => $auto2,
                    'auto3' => $auto3,
                    'auto4' => $auto4,
                    'hds' => $hds
        ]);
    }

    /**
     * @Route("/clear", name="cart_clear")
     */
    public function clear(CartManager $cartManager, Request $request): Response {


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

}
