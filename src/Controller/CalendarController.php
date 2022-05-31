<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\DeliveryType;
use App\Repository\DeliveryRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/calendar")
 */
class CalendarController extends AbstractController {



    /**
     * @Route("/index", name="index_calendar", methods={"GET"})
     */
    public function calendar(DeliveryRepository $deliveryRepository, TaskRepository $taskRepository): Response {
        $monthNumber = date('m');
        $yearNumber = date('Y');

        $daysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $monthNumber, $yearNumber);
        $daysLastMonth = cal_days_in_month(CAL_GREGORIAN, $monthNumber - 1, $yearNumber);

        $firstdayname = $yearNumber . '/' . $monthNumber . '/01';
        $day = date('l', strtotime($firstdayname));

        if ($day == 'Monday') {
            $minusdays = 1;
        }
        if ($day == 'Tuesday') {
            $minusdays = 2;
        }
        if ($day == 'Wednesday') {
            $minusdays = 3;
        }
        if ($day == 'Thursday') {
            $minusdays = 4;
        }
        if ($day == 'Friday') {
            $minusdays = 5;
        }
        if ($day == 'Saturday') {
            $minusdays = 6;
        }
        if ($day == 'Sunday') {
            $minusdays = 7;
        }
        $days = [];
        for ($i = 1 - $minusdays; $i <= $daysInThisMonth; $i++) {
            if ($i != 0) {
                if ($i < 0) {
                    $lastMontNumber = $monthNumber - 1;
                    $string = $i . '-' . $lastMontNumber . '-' . $yearNumber;
                    $days[$string] = [$daysLastMonth + $i + 1, true, [],[]];
                } else {
                    $string = $i . '-' . $monthNumber . '-' . $yearNumber;
                    $days[$string] = [$i, false, [],[]];
                }
            }
        }
        $startDate = ($daysLastMonth - $minusdays) . '-' . ($monthNumber - 1) . '-' . $yearNumber;
        $endDate = $daysInThisMonth . '-' . $monthNumber . '-' . $yearNumber;
        
        if (count($days) !== 42) {
            for ($i = 1; $i <= (46 - count($days)); $i++) {
                $nextMontNumber = $monthNumber + 1;
                $string = $i . '-' . $nextMontNumber . '-' . $yearNumber;
                $endDate = $string;
                $days[$string] = [$i, true, [],[]];
            }
        }

        $tasks = $taskRepository->findAllByDate($startDate, $endDate);
        $deliveries = $deliveryRepository->findAllByDate($startDate, $endDate);
        foreach ($deliveries as $delivery) {
            $deliveryDate = date_format($delivery->getDeliveryDate(), 'd-m-Y');
            array_push($days[$deliveryDate][2], $delivery);
        }
        foreach ($tasks as $task) {
            $taskDate = date_format($task->getDateToEnd(), 'd-m-Y');
            array_push($days[$taskDate][3], $task);
        }
        
        return $this->render('calendar/calendar.html.twig', [
                    'days' => $days,
                    'thisdate' => $monthNumber . '-' . $yearNumber,
        ]);
    }
}
