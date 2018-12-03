<?php

namespace Gopusher\Sdk\Notification;

interface Redis
{

}

class StatusHandler implements Handler
{
    /**
     * @var Redis
     */
    protected $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param string $connId connection id
     * @param string $token connection token
     * @param string $nodeId gateway server node,like:192.168.31.76:8901:1543712471359433000
     *
     * @return void
     * @throws \Exception
     */
    public function checkToken($connId, $token, $nodeId)
    {
        //$nodeIdArr = $this->getNodeIdArr($nodeId);
    }

    /**
     * @param $nodeId
     * @return array
     * @throws \Exception
     */
    private function getNodeIdArr($nodeId)
    {
        $nodeIdArr = explode('', $nodeId, 3);
        if (count($nodeIdArr) < 3) {
            throw new \Exception('error node id');
        }

        return $nodeIdArr;
    }

    /**
     * @param string $connId connection id
     * @param string $nodeId gateway server node,like:192.168.31.76:8901:1543712471359433000
     *
     * @return void
     * @throws \Exception
     */
    public function online($connId, $nodeId)
    {
        $nodeIdArr = $this->getNodeIdArr($nodeId);
        $ip = $nodeIdArr[0];
        $port = $nodeIdArr[0];
    }

    /**
     * @param string $connId connection id
     * @param string $nodeId gateway server node,like:192.168.31.76:8901:1543712471359433000
     *
     * @return void
     * @throws \Exception
     */
    public function offline($connId, $nodeId)
    {
        $nodeIdArr = $this->getNodeIdArr($nodeId);
    }
}
