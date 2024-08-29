<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit161063ac4ca51fc49e6bcb5a58487262
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Phpml\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Phpml\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-ai/php-ml/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit161063ac4ca51fc49e6bcb5a58487262::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit161063ac4ca51fc49e6bcb5a58487262::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit161063ac4ca51fc49e6bcb5a58487262::$classMap;

        }, null, ClassLoader::class);
    }
}
