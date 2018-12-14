<?php
namespace Application\Cores;

/**
 * 命令行任务基类
 */
class Task extends Controller
{
    /**
     * 调用\cli\{method}相关方法,方便命令行展示
     */
    public function __call($method, $params)
    {
        $method = '\\cli\\' . $method;
        if (function_exists($method)) {
            return $method(...$params);
        }
        throw new \BadFunctionCallException('Call to undefined function ' . $method);
    }
}
