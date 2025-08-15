<?php
spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'App\\')) {
        $path = __DIR__ . '/app/' . str_replace('App\\', '', $class) . '.php';
        $path = str_replace('\\', '/', $path);
        if (file_exists($path)) {
            require $path;
        }
    }
});

spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'Prembly\\')) {
        // $path = __DIR__ . '/core/' . str_replace('Prembly\\', '', $class) . '.php';
        $path = __DIR__ . '/core/' . str_replace('\\', '/', $class) . '.php';
        $path = str_replace('\\', '/', $path);
        if (file_exists($path)) {
            require $path;
        }
    }
});


