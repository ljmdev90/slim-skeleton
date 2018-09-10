<?php
namespace Application\Cores;

abstract class Controller
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
        Model::setContainer($container);
        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    public function __invoke($req, $res, $args)
    {
        $actions = explode('.', isset($args['action']) ? $args['action'] : 'index');
        $action_name = reset($actions);
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

    protected function render($data = array(), $template_file = '')
    {
        if (empty($template_file)) {
            $uri = $this->get('request')->getUri();
            $path = $uri->getPath();
            $path = explode('/', ltrim($path, '/'));
            array_walk($path, function(&$item) {
                $item = \ucfirst(reset(explode('.', $item)));
            });
            $template_file = implode('/', $path) . '.php';
        }
        return $this->get('view')->render($this->get('response'), $template_file, $data);
    }
}