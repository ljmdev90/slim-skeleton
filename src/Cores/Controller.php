<?php
namespace Application\Cores;

/**
 * 控制器基类
 */
abstract class Controller
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
        
        // 设置Model类的$container,以便Model中能方便的读取诸如系统配置等内容
        Model::setContainer($container);
        
        // 方便子类初始化,防止子类直接调用构造方法
        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    /**
     * 当以方法的方式调用对象时,调用该方法
     * 用于统一处理标准url格式(如:/Controller/Action)的请求
     */
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
    
    /**
     * 封装container类的get方法,方便调用
     */
    protected function get($name)
    {
        return $this->container->get($name);
    }

    /**
     * 渲染模板
     */
    protected function render($data = array(), $template_file = '')
    {
        if (empty($template_file)) {
            $uri = $this->get('request')->getUri();
            $path = $uri->getPath();
            $path = explode('/', ltrim($path, '/'));
            array_walk($path, function (&$item) {
                $item = \ucfirst(current(explode('.', $item)));
            });
            $template_file = implode('/', $path) . '.php';
        }
        return $this->get('view')->render($this->get('response'), $template_file, $data);
    }
}
