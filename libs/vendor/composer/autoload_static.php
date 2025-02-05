<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2b4cdaf58b910f1313f13378918ba546
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
        'WeDevs_Settings_API' => __DIR__ . '/..' . '/tareq1988/wordpress-settings-api-class/src/class.settings-api.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2b4cdaf58b910f1313f13378918ba546::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2b4cdaf58b910f1313f13378918ba546::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2b4cdaf58b910f1313f13378918ba546::$classMap;

        }, null, ClassLoader::class);
    }
}
