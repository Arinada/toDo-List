<?php

namespace App\Autoloader;

class Psr4AutoloaderClass
{
    protected $prefixes = array();

    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
        $this->addMyNamespaces();
    }

    public function addMyNamespaces()
    {
        $this->addNamespace(NAMESPACE_APP_PREFIX, __DIR__);
        $this->addNamespace(NAMESPACE_APP_PREFIX . 'Routes\\', __DIR__ . '/route');
        $this->addNamespace(NAMESPACE_CONTROLLER_PREFIX, CONTROLLER_DIR_PATH);
        $this->addNamespace(NAMESPACE_VIEW_PREFIX, VIEW_DIR_PATH);
        $this->addNamespace(NAMESPACE_MODEL_PREFIX, __DIR__ . '/models');
        $this->addNamespace(NAMESPACE_CONFIG_PREFIX, __DIR__ . '/models/config');
        $this->addNamespace(NAMESPACE_CONFIG_PREFIX, __DIR__ . '/models/config');
        $this->addNamespace("Twig\\", __DIR__ . '/vendor/twig/twig/src');
        $this->addNamespace("Twig\\Loader\\", __DIR__ . '/vendor/twig/twig/src/Loader');
    }

    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        // normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // normalize the base directory with a trailing separator
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        // initialize the namespace prefix array
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        // retain the base directory for the namespace prefix
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }

    public function loadClass($class)
    {
        // the current namespace prefix
        $prefix = $class;

        // work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {

            // retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);

            // the rest is the relative class name
            $relative_class = substr($class, $pos + 1);

            // try to load a mapped file for the prefix and relative class
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            // remove the trailing namespace separator for the next iteration
            // of strrpos()
            $prefix = rtrim($prefix, '\\');
        }

        // never found a mapped file
        return false;
    }

    protected function loadMappedFile($prefix, $relative_class)
    {
        $file_extension = '.php';
        // are there any base directories for this namespace prefix?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        // look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $base_dir) {

            // replace the namespace prefix with the base directory,
            // replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = $base_dir
                . str_replace('\\', '/', $relative_class)
                . $file_extension;

            // if the mapped file exists, require it
            if ($this->requireFile($file)) {
                // yes, we're done
                return $file;
            }
        }

        // never found it
        return false;
    }

    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require($file);
            return true;
        }
        return false;
    }

    protected function includeFile($file)
    {
        if (file_exists($file)) {
            include $file;
            return true;
        }
        return false;
    }

}