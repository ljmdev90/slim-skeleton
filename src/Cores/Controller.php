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
        $action_name = isset($args['action']) ? $args['action'] : 'index';
        if (is_callable(array($this, $action_name))) {
            $params = [
                'request'   =>  $req,
                'response'  =>  $res,
                'args'      =>  $args
            ];
            return call_user_func_array(array($this, $action_name), $params);
        }
        
        throw new NotFoundException($req, $res);
    }
    
    protected function get($name)
    {
        return $this->container->get($name);
    }
}