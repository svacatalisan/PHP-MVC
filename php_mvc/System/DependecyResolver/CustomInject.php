<?php

class CustomInject implements ICustomInject {

    //singleton
    private static $instance = null;

    private function __construct() {
        $this :: register('ITest', function() {
                    return new Test();
                });

        $this :: register('IDBConnector', function() {
                    return new DBConnector();
                });
    }

    private function __clone() {}

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    //injection
    protected static $registry = array();

    /* Adds a new resolver to the registry array
     * param  string $name The id
     * @param  object $resolve Closure that creates instance
     * returns nothing
     */

    public static function register($name, Closure $resolve) {
        static::$registry[$name] = $resolve;
    }

    /**
     * Create the instance
     * @param  string $name The id
     * @return mixed
     */
    public static function resolve($name) {
        if (static::registered($name)) {
            $name = static::$registry[$name];
            return $name();
        }

        throw new Exception('Nothing registered with that name, fool.');
    }

    private static function registered($name) {
        return array_key_exists($name, static::$registry);
    }

}

?>