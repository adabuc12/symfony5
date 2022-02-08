<?php

namespace App\Service;


use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class DbLog {
    
    public $em;
    
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    
    
    public function write($type,$typeId,$message,$user){
        
        $log = new Log();
        $log->setType($type);
        $log->setTypeId($typeId);
        $log->setMessage($message);
        $log->setCreatedBy($user);
        $log->setCreatedAt(new DateTime('NOW'));
        $this->em->persist($log);
        $this->em->flush();
        
    }
}
