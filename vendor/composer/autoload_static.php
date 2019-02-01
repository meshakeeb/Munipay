<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit20a90e5a47a8472fda3994caedd0bc48
{
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
        'Munipay\\Ajax' => __DIR__ . '/../..' . '/includes/class-ajax.php',
        'Munipay\\Bootstrap_Walker' => __DIR__ . '/../..' . '/includes/class-bootstrap-walker.php',
        'Munipay\\Check' => __DIR__ . '/../..' . '/includes/class-check.php',
        'Munipay\\Check_Form' => __DIR__ . '/../..' . '/includes/class-check-form.php',
        'Munipay\\Checkout' => __DIR__ . '/../..' . '/includes/class-checkout.php',
        'Munipay\\Data' => __DIR__ . '/../..' . '/includes/class-data.php',
        'Munipay\\Disable_Emojis' => __DIR__ . '/../..' . '/includes/class-disable-emojis.php',
        'Munipay\\Form' => __DIR__ . '/../..' . '/includes/class-form.php',
        'Munipay\\Order' => __DIR__ . '/../..' . '/includes/class-order.php',
        'Munipay\\Post_Types' => __DIR__ . '/../..' . '/includes/class-post-types.php',
        'Munipay\\Profile' => __DIR__ . '/../..' . '/includes/class-profile.php',
        'Munipay\\Registration' => __DIR__ . '/../..' . '/includes/class-registration.php',
        'Munipay\\Review_Order' => __DIR__ . '/../..' . '/includes/class-review-order.php',
        'Munipay\\Theme_Setup' => __DIR__ . '/../..' . '/includes/class-theme-setup.php',
        'Munipay\\Traits\\Ajax' => __DIR__ . '/../..' . '/includes/traits/class-ajax.php',
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
