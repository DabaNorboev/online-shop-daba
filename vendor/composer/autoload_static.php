<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit95ad5ad690c2233780d5f93ab1ba8c63
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
            0 => __DIR__ . '/..' . '/dabanorboev/my-pack/Core',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit95ad5ad690c2233780d5f93ab1ba8c63::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit95ad5ad690c2233780d5f93ab1ba8c63::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit95ad5ad690c2233780d5f93ab1ba8c63::$classMap;

        }, null, ClassLoader::class);
    }
}
