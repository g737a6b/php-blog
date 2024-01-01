<?php

spl_autoload_register(function ($class) {
    $prefix = "BlogLite\\";
    $baseDir = __DIR__."/src/";
    $length = strlen($prefix);
    if(strncmp($prefix, $class, $length) !== 0) {
        return;
    }
    $file = $baseDir.str_replace("\\", "/", substr($class, $length)).".php";
    require($file);
});
