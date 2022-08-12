<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DbLog;
use App\Entity\Order;
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
        print_r(json_decode($json));
        exit;
        
        return $this->redirectToRoute('cart');
    }

}
