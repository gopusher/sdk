<?php

namespace Gopusher\Sdk\Notification;

class Notification
{
    /**
     * @var []Handler $handlers
     */
    protected $handlers;

    /**
     * @var string
     */
    protected $userAgent;

    public function __construct($userAgent = 'Gopusher 1.0')
    {
        $this->userAgent = $userAgent;
    }

    public function registerHandler(Handler $handler)
    {
        $this->handlers[] = $handler;
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
            return $this->fail((string) $e);
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
                foreach ($this->handlers as $handler) {
                    /** @var Handler $handler */
                    $handler->checkToken(...$args);
                }
                break;

            case 'Online':
                if ($argsLen != 2) {
                    throw new \Exception('bad request: ' . var_export($args, true));
                }
                foreach ($this->handlers as $handler) {
                    /** @var Handler $handler */
                    $handler->online(...$args);
                }
                break;

            case 'Offline':
                if ($argsLen != 2) {
                    throw new \Exception('bad request: ' . var_export($args, true));
                }
                foreach ($this->handlers as $handler) {
                    /** @var Handler $handler */
                    $handler->offline(...$args);
                }
                break;

            case 'JoinCluster':
                if ($argsLen != 1) {
                    throw new \Exception('bad request: ' . var_export($args, true));
                }
                foreach ($this->handlers as $handler) {
                    /** @var Handler $handler */
                    $handler->joinCluster(...$args);
                }
                break;

            default:
                throw new \Exception('bad request: undefined method:' . $data['method']);
        }
    }

    public function success()
    {
        return json_encode([
            'code'      => 0,   //response code, 0：success，other: fail
            'data'      => [],
            'error'     => "",
        ]);
    }

    public function fail($error)
    {
        return json_encode([
            'code'      => 1,   //response code, 0：success，other: fail
            'data'      => [],
            'error'     => $error,
        ]);
    }
}
