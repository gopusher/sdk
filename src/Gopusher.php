<?php

namespace Gopusher\Sdk;

use Gopusher\Sdk\Notification\Handler;
use Gopusher\Sdk\Notification\Notification;

/**
 * Class Gopusher
 * @package Gopusher\Sdk
 */
class Gopusher
{
    /**
     * @param array $handlers
     *
     * @return Notification
     */
    public function notification($handlers)
    {
        $notification = new Notification();
        foreach ($handlers as $handler) {
            /** @var Handler $handler */
            $notification->registerHandler($handler);
        }

        return $notification;
    }
}
