<?php

namespace Gopusher\Sdk\Monitor;

use Gopusher\Sdk\Rpc\Client;

class Monitor
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function run()
    {
//        while (true) {
//            //todo check error
//            $this->client->GetNodeId();
//        }
    }
}
