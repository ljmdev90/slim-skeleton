<?php
namespace Application\Cores;

class Task extends Controller
{
    public function __call($method, $params)
    {
        $method = '\\cli\\' . $method;
        if (function_exists($method)) {
            return $method(...$params);
        }
        throw new \BadFunctionCallException('Call to undefined function ' . $method);
    }
}
