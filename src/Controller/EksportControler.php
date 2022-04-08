<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DbLog;
/**
 * Class ExportControler
 * @package App\Controller
 */
class ExportControler extends AbstractController {
    /**
     * @Route("/eksportwapro", name="eksport_wapro")
     */
    public function exportWapro(DbLog $logger): Response {
        
        
        
        return $this->render('dashboard/dash.html.twig', [
        ]);
        
    }
}
