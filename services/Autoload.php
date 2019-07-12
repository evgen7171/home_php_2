<?php

class Autoload
{
    private $dir = [
        'models', 'services'
    ];

    public function getEndString($str)
    {
        preg_match_all('/\\\/', $str, $match, PREG_OFFSET_CAPTURE);
        $index = $match[0][count($match[0]) - 1][1];
        if ($index) {
            $end_slash_index = mb_strlen($str);
            $end_of_str = mb_substr($str, $index + 1, $end_slash_index - $index);
            return $end_of_str;
        } else {
            return $str;
        }
    }

    public function loadClass($className)
    {
        $classNameLast = $this->getEndString($className);
        foreach ($this->dir as $dir) {
            $file = $_SERVER['DOCUMENT_ROOT'] .
                "/../{$dir}/{$classNameLast}.php";
            if (file_exists($file)) {
                include $file;
                break;
            }
        }
    }
}