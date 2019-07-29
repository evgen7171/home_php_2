<?php

namespace App\controllers;

use App\services\renders\IRenderService;
use App\services\Request;

abstract class Controller
{
    protected $defaultAction;
    protected $action;
    protected $renderer;
    protected $request;

    public function __construct(IRenderService $renderer, Request $request)
    {
        $this->renderer = $renderer;
        $this->request = $request;
        $this->startSession();
    }

    private function startSession()
    {
        session_start();
    }

    private function sessionAdd($key, $value)
    {
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }
        $_SESSION[$key][] = $value;
    }

    public function run($action)
    {
        $this->action = $action ?: $this->defaultAction;
        $method = $this->action . 'Action';
        if (method_exists($this, $method)) {
            $this->sessionAdd('method', $method);
            $this->$method();
        } else {
            echo '404';
        }
    }

    public function render($template, $params = [])
    {
        $content = $this->renderTmpl($template, $params);
        $menu = $this->renderTmpl('layouts/menu', $this->getMenuParams());
        return $this->renderTmpl('layouts/main', [
            'controllerPic' => $this->getControllerPic(),
            'menu' => $menu,
            'content' => $content
        ]);
    }

    public function renderTmpl($template, $params = [])
    {
        return $this->renderer->renderTmpl($template, $params);
    }

    protected function getId()
    {
        return $this->request->getId();
    }

    protected function get($param)
    {
        return $this->request->get($param);
    }

    protected function post($param)
    {
        return $this->request->post($param);
    }

    protected function getControllerPic()
    {
        switch ($this->defaultAction) {
            case 'users':
                return '/img/1.png';
            case 'goods':
                return '/img/2.png';
        }
    }

    protected function getMenuParams()
    {
        switch ($this->defaultAction) {
            case 'users':
                return [
                    'linkAll' => '/user/users',
                    'linkOne' => '/user/user',
                    'linkInsert' => '/user/insert',
                    'all' => 'Пользователи',
                    'one' => 'Пользователь',
                    'insert' => 'Добавить пользователя'
                ];
            case 'goods':
                return [
                    'linkAll' => '/good/goods',
                    'linkOne' => '/good/good',
                    'linkInsert' => '/good/insert',
                    'all' => 'Товары',
                    'one' => 'Товар',
                    'insert' => 'Добавить товар'
                ];
        }

    }
}
