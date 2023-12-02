<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitdb59d6213707b280f243d01b6ff471bb
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitdb59d6213707b280f243d01b6ff471bb', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitdb59d6213707b280f243d01b6ff471bb', 'loadClassLoader'));

        // require 'vendor/composer/autoload_static.php';
        require '../vendor/composer/autoload_static.php';

        call_user_func(\Composer\Autoload\ComposerStaticInitdb59d6213707b280f243d01b6ff471bb::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
