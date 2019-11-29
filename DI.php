<?php

include_once "View.php";
include_once "User.php";

/**
 * Class DI
 *
 * create a dependency manager
 */
class DI {
    private static $map;

    public static function getInstanceOf($className, $arguments = null) {

        // checking if the class exists



        // initialized the ReflectionClass
        $reflection = new ReflectionClass($className);

        // creating an instance of the class
        if($arguments === null || count($arguments) == 0) {
            $obj = new $className;
        } else {
            if(!is_array($arguments)) {
                $arguments = array($arguments);
            }

            $obj = $reflection->newInstanceArgs($arguments);
        }

        // injecting
        if($doc = $reflection->getDocComment()) {
            $lines = explode("\\n", $doc);
            foreach($lines as $line) {
                if(count($parts = explode("@Inject", $line)) > 1) {
                    $parts = explode(" ", $parts[1]);
                    if(count($parts) > 1) {
                        $key = $parts[1];
                        $key = trim($key);

                        if(isset(self::$map->$key)) {
                            switch(self::$map->$key->type) {
                                case "value":
                                    $obj->$key = self::$map->$key->value;
                                    break;
                                case "class":
                                    $obj->$key = self::getInstanceOf(self::$map->$key->value, self::$map->$key->arguments);
                                    break;
                                case "classSingleton":
                                    if(self::$map->$key->instance === null) {
                                        $obj->$key = self::$map->$key->instance = self::getInstanceOf(self::$map->$key->value, self::$map->$key->arguments);
                                    } else {
                                        $obj->$key = self::$map->$key->instance;
                                    }
                                    break;
                            }
                        }
                    }
                }
            }
        }
        // return the created instance

        return $obj;
    }

    /**
     * @param $key name that will be injected
     * @param $obj Object parameter
     */
    private static function addToMap($key, $obj) {
        if(self::$map === null) {
            self::$map = (object) array();
        }

        self::$map->$key = $obj;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function mapValue($key, $value) {
        self::addToMap($key, (object) array(
            "value" => $value,
            "type" => "value"
        ));
    }

    /**
     * @param $key name that will be injected
     * @param $value name of the class
     * @param null $arguments constructor arguments
     */
    public static function mapClass($key, $value, $arguments = null) {
        self::addToMap($key, (object) array(
            "value" => $value,
            "type" => "class",
            "arguments" => $arguments
        ));
    }

    /**
     * @param $key name that will be injected
     * @param $value name of the class
     * @param null $arguments constructor arguments
     */
    public static function mapClassAsSingleton($key, $value, $arguments = null) {
        self::addToMap($key, (object) array(
            "value" => $value,
            "type" => "classSingleton",
            "instance" => null,
            "arguments" => $arguments
        ));
    }


    /**
     * Debug purpose
     * @return mixed
     */
    public static function getMap()
    {
        return self::$map;
    }


}