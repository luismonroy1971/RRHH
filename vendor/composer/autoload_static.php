<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc0cb2b6fa1e6d04e355fec91e2fda292
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'thiagoalessio\\TesseractOCR\\' => 27,
        ),
        'M' => 
        array (
            'Models\\' => 7,
        ),
        'L' => 
        array (
            'Libs\\' => 5,
        ),
        'C' => 
        array (
            'Controllers\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'thiagoalessio\\TesseractOCR\\' => 
        array (
            0 => __DIR__ . '/..' . '/thiagoalessio/tesseract_ocr/src',
        ),
        'Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Models',
        ),
        'Libs\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Libs',
        ),
        'Controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Controllers',
        ),
    );

    public static $classMap = array (
        'AltoRouter' => __DIR__ . '/..' . '/altorouter/altorouter/AltoRouter.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc0cb2b6fa1e6d04e355fec91e2fda292::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc0cb2b6fa1e6d04e355fec91e2fda292::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc0cb2b6fa1e6d04e355fec91e2fda292::$classMap;

        }, null, ClassLoader::class);
    }
}
