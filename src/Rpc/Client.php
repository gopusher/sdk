<?php

namespace Gopusher\Sdk\Rpc;

use Exception;

class Client
{
    /**
     * @var array
     */
    protected static $obj = [];

    /**
     * @param $host
     * @param $port
     * @return mixed
     * @throws Exception
     */
    protected function getConnect($host, $port)
    {
        $key = $host . ':' . $port;
        if (! isset(self::$obj[$key])) {
            $conn = fsockopen($host, $port, $errNo, $errStr, 3);
            if (! $conn) {
                throw new \Exception(sprintf('connect failed, errorNo: %s, errStr: %s', $errNo, $errStr));
            }
            self::$obj[$key] = $conn;
        }
        return self::$obj[$key];
    }

    /**
     * @param $host
     * @param $port
     * @param $data
     * @return mixed|null
     * @throws Exception
     */
    public function execute($host, $port, $data) {
        $conn = $this->getConnect($host, $port);

        $err = fwrite($conn, json_encode(array_merge($data, ['id' => 0])) . "\n");

        if ($err === false) {
            throw new \Exception('rpc call failed');
        }

        stream_set_timeout($conn, 0, 3000);
        $line = fgets($conn);
        if ($line === false) {
            return NULL;
        }
        return json_decode($line,true);
    }

    /**
     * @param $host
     * @param $port
     * @param $token
     * @param array $connections
     * @param $msg
     * @return mixed|null
     * @throws Exception
     */
    public function SendToConnections($host, $port, $token, array $connections, $msg)
    {
        $data = array(
            'method' => "Server.SendToConnections",
            'params' => [[
                'connections'   => array_values(array_unique($connections)),
                'msg'           => $msg,
                'token'         => $token
            ]],
        );

        $ret = $this->execute($host, $port, $data);

        // if (! empty($ret['result']['error'])) {
        //     throw new \Exception($ret['result']['error']);
        // }

        return $ret;
    }

    /**
     * @param $host
     * @param $port
     * @param $token
     * @param $msg
     * @return mixed|null
     * @throws Exception
     */
    public function Broadcast($host, $port, $token, $msg)
    {
        $data = array(
            'method' => "Server.Broadcast",
            'params' => [[
                'msg'           => $msg,
                'token'         => $token
            ]],
        );

        return $this->execute($host, $port, $data);
    }

    /**
     * @param $host
     * @param $port
     * @param $token
     * @param array $connections
     * @return mixed|null
     * @throws Exception
     */
    public function CheckConnectionsOnline($host, $port, $token, array $connections)
    {
        $data = array(
            'method' => "Server.CheckConnectionsOnline",
            'params' => [[
                'connections'   => array_values(array_unique($connections)),
                'token'         => $token
            ]],
        );

        return $this->execute($host, $port, $data);
    }

    /**
     * @param $host
     * @param $port
     * @param $token
     * @return mixed|null
     * @throws Exception
     */
    public function GetAllConnections($host, $port, $token)
    {
        $data = array(
            'method' => "Server.GetAllConnections",
            'params' => [[
                'token'         => $token
            ]],
        );

        return $this->execute($host, $port, $data);
    }

    /**
     * @param $host
     * @param $port
     * @param $token
     * @param array $connections
     * @return mixed|null
     * @throws Exception
     */
    public function KickConnections($host, $port, $token, array $connections)
    {
        $data = array(
            'method' => "Server.KickConnections",
            'params' => [[
                'connections'   => array_values(array_unique($connections)),
                'token'         => $token
            ]],
        );

        return $this->execute($host, $port, $data);
    }

    /**
     * @param $host
     * @param $port
     * @param $token
     * @return mixed|null
     * @throws Exception
     */
    public function KickAllConnections($host, $port, $token)
    {
        $data = array(
            'method' => "Server.KickAllConnections",
            'params' => [[
                'token'         => $token
            ]],
        );

        return $this->execute($host, $port, $data);
    }

    /**
     * @param $host
     * @param $port
     * @param $token
     * @return mixed|null
     * @throws Exception
     */
    public function GetNodeId($host, $port, $token)
    {
        $data = array(
            'method' => "Server.GetNodeId",
            'params' => [[
                'token'         => $token
            ]],
        );

        return $this->execute($host, $port, $data);
    }

    /**
     * destruct
     */
    public function __destruct()
    {
        foreach (self::$obj as $conn) {
            fclose($conn);
        }
    }
}

//$client = new Client();
//$r = $client->SendToConnections("message.demo.com", 8901, 'token', array_slice($argv, 2), $argv[1]);
//var_export($r);

// $client = new Client();
// $r = $client->Broadcast("message.demo.com", 8901, 'token', $argv[1]);
// var_export($r);

// $client = new Client();
// $r = $client->CheckConnectionsOnline("message.demo.com", 8901, 'token', array_slice($argv, 1));
// var_export($r);

// $client = new Client();
// $r = $client->GetAllConnections("message.demo.com", 8901, 'token');
// var_export($r);

// $client = new Client();
// $r = $client->KickConnections("message.demo.com", 8901, 'token', array_slice($argv, 1));
// var_export($r);

// $client = new Client();
// $r = $client->KickAllConnections("message.demo.com", 8901, 'token');
// var_export($r);

// $client = new Client();
// $r = $client->GetNodeId("message.demo.com", 8901, 'token');
// var_export($r);
