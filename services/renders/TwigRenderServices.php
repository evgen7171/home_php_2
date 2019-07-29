<?php

namespace App\services\renders;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderServices implements IRenderService
{
    //TODO $params - если раскомментировать, то будет происходить кэширование страниц

    /**
     * @param $template
     * @param array $params
     * @return string
     * @throws
     */
    public function renderTmpl($template, $params = [])
    {
        $loader = new FilesystemLoader([
            $_SERVER['DOCUMENT_ROOT'] . '/../views/twig/',
            $_SERVER['DOCUMENT_ROOT'] . '/../views/'
        ]);
//        $params = ['cache' => $_SERVER['DOCUMENT_ROOT'] . '/../cashe'];
        $twig = new Environment($loader);
        $template .= '.twig';

        return $twig->render($template, $params);
    }

    public function renderTmplTwig()
    {
        $loader = new FilesystemLoader([
            $_SERVER['DOCUMENT_ROOT'] . '/../views/layouts'
        ]);
        $twig = new Environment($loader);
        return $twig->load('mainTwig.twig')->render();
    }
}