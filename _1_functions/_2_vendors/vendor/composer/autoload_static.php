<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita59a2348dfcdea25f192812e1a3d08ef
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Medoo\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Medoo\\' => 
        array (
            0 => __DIR__ . '/..' . '/catfan/medoo/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pug\\' => 
            array (
                0 => __DIR__ . '/..' . '/pug-php/pug/src',
            ),
        ),
        'J' => 
        array (
            'JsPhpize' => 
            array (
                0 => __DIR__ . '/..' . '/js-phpize/js-phpize/src',
            ),
            'Jade\\' => 
            array (
                0 => __DIR__ . '/..' . '/pug-php/pug/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita59a2348dfcdea25f192812e1a3d08ef::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita59a2348dfcdea25f192812e1a3d08ef::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInita59a2348dfcdea25f192812e1a3d08ef::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}