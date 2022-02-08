<?php

namespace App\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RequestProcessor
 * @package App\Util
 */
class RequestProcessor
{
    /**
     * @var RequestStack
     */
    protected $request;

    /**
     * RequestProcessor constructor.
     * @param RequestStack $request
     * @param ContainerInterface $container
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    /**
     * @param array $record
     * @return array
     */
    public function processRecord(array $record)
    {
        $req = $this->request->getCurrentRequest();
        $record['extra']['client_ip']       = $req->getClientIp();
        $record['extra']['client_port']     = $req->getPort();
        $record['extra']['uri']             = $req->getUri();
        $record['extra']['query_string']    = $req->getQueryString();
        $record['extra']['method']          = $req->getMethod();
        $record['extra']['request']         = $req->request->all();

        return $record;
    }
}
