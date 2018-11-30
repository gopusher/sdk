<?php

namespace Gopusher\Sdk;

use Gopusher\Sdk\Notification\Handler;

/**
 * Class Gopusher
 * @package Gopusher\Sdk
 */
class Gopusher
{
    /**
     * @var array
     */
    protected $config = [
        'notificationUserAgent'  => 'Gopusher 1.0',
    ];

    /**
     * @var Handler
     */
    protected $notification;

    public function __construct($config = [])
    {
        $config += $this->config + $config;

        $this->notification = new Handler($config['notificationUserAgent']);
    }

    /**
     * @return Handler
     */
    public function notification()
    {
        return $this->notification;
    }
}
