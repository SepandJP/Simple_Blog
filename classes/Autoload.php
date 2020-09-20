<?php


class Autoload
{
    public function loadClass($className)
    {
        $fileName = __DIR__.'/'.str_replace('\\', '/', $className).'.php';
        if (file_exists($fileName))
        {
            require $fileName;
        }

        else
        {
            return false;
        }
    }

    public function registerClass()
    {
        spl_autoload_register([$this,'loadClass']);
    }
}

$loader = new Autoload();
$loader->registerClass();