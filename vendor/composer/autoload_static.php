<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitddb9f07a097d563abfe39f26e6516463
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitddb9f07a097d563abfe39f26e6516463::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitddb9f07a097d563abfe39f26e6516463::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitddb9f07a097d563abfe39f26e6516463::$classMap;

        }, null, ClassLoader::class);
    }
}
