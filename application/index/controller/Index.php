<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return '123';
    }

    public function hello($name = 'ThinkPHP5')
    {
        $res = input('get.cs');
        return 'hello,' . $res;
    }
}
