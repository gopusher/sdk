<?php

namespace Gopusher\Sdk\Notification;

interface Handler
{
    /**
     * @param $connId
     * @param $token
     * @param $nodeId
     *
     * @return bool
     * @throws \Exception
     */
    public function checkToken($connId, $token, $nodeId);

    /**
     * @param $connId
     * @param $nodeId
     *
     * @return bool
     * @throws \Exception
     */
    public function online($connId, $nodeId);

    /**
     * @param $connId
     * @param $nodeId
     *
     * @return bool
     * @throws \Exception
     */
    public function offline($connId, $nodeId);

    /**
     * @param $nodeId
     * @return bool
     * @throws \Exception
     */
    public function joinCluster($nodeId);
}
