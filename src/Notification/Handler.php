<?php

namespace Gopusher\Sdk\Notification;

class Handler
{
    /**
     * @var string
     */
    protected $userAgent = 'Gopusher 1.0';

    public function __construct($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @param $httpUserAgent
     * @param $body
     *
     * @return string
     */
    public function handleAndResponse($httpUserAgent, $body)
    {
        try {
            $this->handle($httpUserAgent, $body);

            return $this->success();
        } catch (\Exception $e) {
            return $this->fail();
        }
    }

    /**
     * @param $httpUserAgent
     * @param $body
     *
     * @return void
     * @throws \Exception
     */
    public function handle($httpUserAgent, $body)
    {
        if ($httpUserAgent != $this->userAgent) {
            throw new \Exception('error httpUserAgent');
        }

        $data = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception('json_decode error: ' . json_last_error_msg());
        }

        if (empty($data['method']) || ! isset($data['args'])) {
            throw new \Exception('bad request: empty method');
        }

        $method = is_string($data['method']) ? $data['method'] : '';
        $args = $data['args'];
        $argsLen = count($args);

        switch ($method) {
            case 'CheckToken':
                if ($argsLen != 3) {
                    throw new \Exception('bad request: ' . var_export($args, true));
                }
                $this->handleCheckToken(...$args);
                break;

            case 'Online':
                if ($argsLen != 2) {
                    throw new \Exception('bad request: ' . var_export($args, true));
                }
                $this->handleOnline(...$args);
                break;

            case 'Offline':
                if ($argsLen != 2) {
                    throw new \Exception('bad request: ' . var_export($args, true));
                }
                $this->handleOffline(...$args);
                break;

            default:
                throw new \Exception('bad request: undefined method:' . $data['method']);
        }
    }

    protected function handleCheckToken($connId, $token, $nodeId)
    {

    }

    protected function handleOnline($connId, $nodeId)
    {

    }

    protected function handleOffline($connId, $nodeId)
    {

    }

    public function success()
    {
        return json_encode([
            'code'      => 0,   //错误代码 0：正确，-1：服务器错误，1：请求错误
            'data'      => [], //返回数据体
            'error'     => "",//返回消息
        ]);
    }

    public function fail()
    {
        return json_encode([
            'code'      => 1,   //错误代码 0：正确，-1：服务器错误，1：请求错误
            'data'      => [], //返回数据体
            'error'     => "",//返回消息
        ]);
    }
}
