<?php
namespace Application\Cores;

abstract class Controller
{
    protected $container;

    public function __construct($container) {
        $this->container = $container;
        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    public function __invoke($req, $res, $args) {
        $container = $this->container;
        $this->controller = isset($args['controller']) ? $args['controller'] : 'Home';
        $this->action = $action_name = isset($args['action']) ? $args['action'] : 'index';
        $params = [
            'request'   =>  $req,
            'response'  =>  $res,
            'args'      =>  $args
        ];
        return call_user_func_array(array($this, $action_name), $params);
    }
    
    protected function get($name)
    {
        return $this->container->get($name);
    }
}