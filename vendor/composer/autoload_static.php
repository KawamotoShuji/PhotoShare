<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1d70ecfa384dd23b38813a2f3afc2786
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1d70ecfa384dd23b38813a2f3afc2786::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1d70ecfa384dd23b38813a2f3afc2786::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}