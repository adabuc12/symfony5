<?php

namespace App\Controller;

use App\Entity\Notice;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetNotifications extends AbstractController{
    
     public function getNotifications($user)
    {
        $repository = $this->getDoctrine()->getRepository(Notice::class);
        $notifications = $repository->findByUserNotReaded($user);
        
        return $notifications;
    }
}
