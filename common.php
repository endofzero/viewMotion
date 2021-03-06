<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/**
 * Load a config file
 */
function config_load($name) {

        $configuration = array();

        if (!file_exists(dirname(__FILE__) . '/config/' . $name . '.php'))
                die('The file ' . dirname(__FILE__) . '/config/' . $name . '.php does not exist.');

        require(dirname(__FILE__) . '/config/' . $name . '.php');

        if (!isset($config) OR !is_array($config))
                die('The file ' . dirname(__FILE__) . '/config/' . $name . '.php file does not appear to be formatted correctly.');

        if (isset($config) AND is_array($config))
                $configuration = array_merge($configuration, $config);

        return $configuration;

}

/**
 * Load a config item
 */
function config_item($name, $item) {

        static $config_item = array();

        if (!isset($config_item[$item])) {

                $config = config_load($name);

                if (!isset($config[$item]))
                        return FALSE;

                $config_item[$item] = $config[$item];

        }

        return $config_item[$item];

}

/**
 * Autoloading classes
 */
function __autoload($class_name) {

        require_once('libraries/' . $class_name . '.php');

}
