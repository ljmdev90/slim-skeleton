<?php
namespace SlimServer;

use Swoole\Process;
use Swoole\WebSocket\Server as HttpServer;

class SlimServer extends HttpServer
{
    private static $instance = null;

    public $setting = [
        'daemonize' =>  1,
        'pid_file'  =>  '/run/slim-server.pid',
        'log_file'  =>  '/tmp/slim-server.log',
        'task_worker_num'   =>  10,
    ];

    public $tasks_ns = '\Application\Tasks';
    public $decollator = '|';

    private function __construct($host = '0.0.0.0', $port = 9501)
    {
        try {
            $this->process();
            parent::__construct($host, $port);
            $this->set($this->setting);
            $this->on('workerStart', [$this, 'onWorkerStart']);
            $this->on('message', [$this, 'onMessage']);
            $this->on('request', [$this, 'onRequest']);
            $this->on('task', [$this, 'onTask']);
            $this->on('finish', [$this, 'onFinish']);
            $this->start();
        } catch (\Throwable $e) {
            // pass
        }
    }

    public static function run()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function process()
    {
        $opts = getopt('s:');
        if (!isset($opts['s'])) {
            $opts['s'] = 'start';
        }
        if (!in_array($opts['s'], ['start', 'stop', 'reload'])) {
            exit("Parameter -s is invalid!\n");
        }
        $oper = $opts['s'];
        $pid_file = $this->setting['pid_file'] ?? '/run/slim-server.pid';
        if (file_exists($pid_file) && $pid = file_get_contents($pid_file)) {
            $is_pid_exist = Process::kill($pid, 0);
            switch ($oper) {
                case 'start':
                    if ($is_pid_exist) {
                        exit("Swoole Server is Already Running\n");
                    }
                    break;
                case 'stop':
                    if ($is_pid_exist && Process::kill($pid)) {
                        exit("Stopped Swoole Server\n");
                    } elseif (!$is_pid_exist) {
                        exit("Swoole Server has Stopped\n");
                    } else {
                        exit("Stopping Swoole Server Error\n");
                    }
                    break;
                case 'reload':
                    if ($is_pid_exist && Process::kill($pid, SIGUSR1)) {
                        exit("Reloaded Swoole Server\n");
                    } elseif (!isset($ret) || !$ret) {
                        exit("Reloading Swoole Server Error\n");
                    }
                    break;
            }
        } elseif ($oper == 'stop') {
            exit("Swoole Server is not Running\n");
        }
    }

    public function onWorkerStart($server, $worker_id)
    {
        require __DIR__ . '/../vendor/autoload.php';
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $env = __DIR__ . '/../.env';
        if (file_exists($env)) {
            $dotenv = new \Dotenv\Dotenv(dirname($env));
            $dotenv->load();
        }
        $this->settings = require __DIR__ . '/../src/settings.php';
    }

    public function onRequest($request, $response)
    {
        if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
            return $response->end();
        }
        // 设置$_SERVER，使slim能够读取到其中的值
        foreach ($request->server as $key => $val) {
            // 如果不指定以下代码，$_SERVER中无REQUEST_METHOD等项，导致method not allow
            $_SERVER[strtoupper($key)] = $val;
        };
        foreach ($request->header as $key => $val) {
            $_SERVER[str_replace('-', '_', strtoupper($key))] = $val;
        };

        // 设置$_GET、$_POST、$_COOKIE、$_FILES
        $_GET = $request->get;
        $_POST = $request->post;
        $_COOKIE = $request->cookie;
        $_FILES = $request->files;

        $app = new \Slim\App($this->settings);
        require __DIR__ . '/../src/dependencies.php';
        require __DIR__ . '/../src/middleware.php';
        require __DIR__ . '/../src/routes.php';
        $slimResponse = $app->run(true);
        $response->end($slimResponse->getBody());
    }

    public function onMessage($server, $frame)
    {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        \Swoole\Timer::tick(1000, function () use ($server, $frame) {
            $server->push($frame->fd, date('Y-m-d H:i:s'));
        });
    }

    public function onTask($serv, $task_id, $from_id, $data)
    {
        list($name, $data) = explode($this->decollator, $data);
        $class = $this->tasks_ns . '\\' . $name;    // 直接new $this->tasks_ns . '\\' . $name不对，因为.的优先及低
        (new $class)->run($task_id, $from_id, $data);
        return true;
    }

    public function onFinish($serv, $task_id, $data)
    {
    }

    public function newTask($name, $data, $dst_worker_id = -1, $callback = null)
    {
        if (is_callable($callback)) {
            $this->task($name . $this->decollator . $data, $dst_worker_id, $callback);
        } else {
            $this->task($name . $this->decollator . $data, $dst_worker_id);
        }
    }
}

define('SWOOLE_MODE', true);
SlimServer::run();
