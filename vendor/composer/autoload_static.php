<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit20a90e5a47a8472fda3994caedd0bc48
{
    public static $files = array (
        '553e0353b13a09eb3f62406313fa5292' => __DIR__ . '/..' . '/cmb2/cmb2/init.php',
    );

    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Munipay\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Munipay\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Munipay' => __DIR__ . '/../..' . '/includes/class-munipay.php',
        'Munipay\\Bootstrap_Walker' => __DIR__ . '/../..' . '/includes/class-bootstrap-walker.php',
        'Munipay\\Disable_Emojis' => __DIR__ . '/../..' . '/includes/class-disable-emojis.php',
        'Munipay\\Profile' => __DIR__ . '/../..' . '/includes/class-profile.php',
        'Munipay\\Registration' => __DIR__ . '/../..' . '/includes/class-registration.php',
        'Munipay\\Theme_Setup' => __DIR__ . '/../..' . '/includes/class-theme-setup.php',
        'Munipay\\Traits\\Hooker' => __DIR__ . '/../..' . '/includes/traits/class-hooker.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit20a90e5a47a8472fda3994caedd0bc48::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit20a90e5a47a8472fda3994caedd0bc48::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit20a90e5a47a8472fda3994caedd0bc48::$classMap;

        }, null, ClassLoader::class);
    }
}