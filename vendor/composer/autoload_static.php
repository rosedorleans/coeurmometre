<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9c44cefcdc04081dce53a5f8e6c00e95
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Value\\' => 6,
        ),
        'T' => 
        array (
            'Tools\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Value\\' => 
        array (
            0 => __DIR__ . '/../..' . '/object/value',
        ),
        'Tools\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tools',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Value\\Value' => __DIR__ . '/../..' . '/object/Bet/Value.php',
        'Value\\ValueController' => __DIR__ . '/../..' . '/object/Bet/ValueController.php',
        'Value\\ValueDatabase' => __DIR__ . '/../..' . '/object/Bet/ValueDatabase.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9c44cefcdc04081dce53a5f8e6c00e95::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9c44cefcdc04081dce53a5f8e6c00e95::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9c44cefcdc04081dce53a5f8e6c00e95::$classMap;

        }, null, ClassLoader::class);
    }
}
