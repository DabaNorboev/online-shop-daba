<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit263a03260adc4d5239cb70faed3a5a1d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'D' => 
        array (
            'Dabanorboev\\MyPack\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Dabanorboev\\MyPack\\' => 
        array (
            0 => __DIR__ . '/..' . '/dabanorboev/my-pack/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit263a03260adc4d5239cb70faed3a5a1d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit263a03260adc4d5239cb70faed3a5a1d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit263a03260adc4d5239cb70faed3a5a1d::$classMap;

        }, null, ClassLoader::class);
    }
}
