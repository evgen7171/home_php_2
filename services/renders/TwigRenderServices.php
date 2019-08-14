<?php
namespace App\services\renders;

use Twig\Loader\FilesystemLoader;

class TwigRenderServices implements IRenderService
{
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
            $_SERVER['DOCUMENT_ROOT'] . '/../views/',
        ]);
        $twig = new \Twig\Environment($loader);
        $template .= '.twig';
        return $twig->render($template, $params);
    }
}