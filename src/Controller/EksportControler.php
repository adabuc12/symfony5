<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DbLog;
use App\Entity\Order;
use App\Repository\ProductRepository;
use DateTime;

/**
 * Class EksportControler
 * @package App\Controller
 */
class EksportControler extends AbstractController {

    /**
     * @Route("/eksportwapro/{id}", name="eksport_wapro")
     */
    public function exportWapro(Order $order, DbLog $logger): Response {

        $url = "127.0.0.1:5000/api/table/?tablename=firma";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('api_key: 039100D6-D42B-4A06-875D-4E5466B12D45'));
        $json = curl_exec($ch);
        if(!$json) {
            echo curl_error($ch);
        }
        curl_close($ch);
        $result = json_decode($json);
        var_dump($result);
        
        return $this->redirectToRoute('cart');
    }
    
     /**
     * @Route("/syncproducts/{type}", name="products_sync")
     */
    public function productsSync(string $type, DbLog $logger, ProductRepository $productRepository): Response {

        //get products from wapro
        $url = "127.0.0.1:5000/api/Table/?tablename=artykul";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('api_key: 039100D6-D42B-4A06-875D-4E5466B12D45'));
        $json = curl_exec($ch);
        if(!$json) {
            echo curl_error($ch);
        }
        curl_close($ch);
        $products_wapro = json_decode($json);
        
        //get products from kolodomu panel
        $products_kolo = $productRepository->findAllPriceGreaterThanZero();
        
        //check if products is in specific type database
        if($type == 'wapro'){
            var_dump($products_wapro);
        exit();
        }
        if($type == 'kolo_panel'){
            var_dump($products_kolo);
        exit();
        }
        //print result of synchronisation
        
        
        
        return $this->redirectToRoute('cart');
    }

}
