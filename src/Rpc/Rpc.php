<?php

namespace Gopusher\Sdk\Rpc;

use Exception;

class Rpc
{
    /**
     * @var array
     */
    protected static $obj = [];

    /**
     * @param $host
     * @param $port
     * @return mixed
     * @throws \Exception
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
     * @throws \Exception
     */
    public function execute($host, $port, $data) {
        $conn = $this->getConnect($host, $port);
        $err = fwrite($conn, json_encode(array_merge($data, ['id' => 0])) . "\n");
        if ($err === false) {
            throw new \Exception('rpc call failed');
        }
        stream_set_timeout($conn, 1);

        $line = fgets($conn);
        if ($line === false) {
            $info = stream_get_meta_data($conn);
            if ($info['eof']) {
                $this->close($host, $port);
            }
            throw new Exception('rpc 获取数据失败: ' . var_export($info, true));
        }
        $ret = json_decode($line,true);

        if (is_null($ret)) {
            throw new Exception('rpc 获取数据失败');
        }
        if (is_null($ret['result'])) {
            throw new Exception('rpc 获取数据失败: ' . $ret['error']);
        }

        $data = json_decode($ret['result'], true);
        if ($data['code'] != '0') {
            throw new Exception('rpc 获取数据失败: ' . $data['error']);
        }

        return $data;
    }

    public function close($host, $port)
    {
        $key = $host . ':' . $port;
        if (isset(self::$obj[$key])) {
            @fclose(self::$obj[$key]);
            unset(self::$obj[$key]);
        }
    }

//    public function __destruct()
//    {
//        foreach (self::$obj as $key => $conn) {
//            fclose($conn);
//            unset(self::$obj[$key]);
//        }
//    }

    public function __call($method, $args)
    {
        $params = [
            'token' => $args[2],
        ];
        if (! empty($args[3])) {
            $params += $args[3];
        }

        $data = array(
            'method' => "Server." . $method,
            'params' => [$params],
        );
        return $this->execute($args[0], $args[1], $data);
    }
}
