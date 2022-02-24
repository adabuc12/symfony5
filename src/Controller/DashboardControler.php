<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DbLog;
/**
 * Class CartController
 * @package App\Controller
 */
class DashboardControler extends AbstractController {
    /**
     * @Route("/", name="dashboard")
     */
    public function index(DbLog $logger): Response {
        return $this->render('dashboard/dash.html.twig', [
        ]);
        
    }
}
