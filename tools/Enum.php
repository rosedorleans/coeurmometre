<?php
namespace Tools;

use ReflectionClass;

abstract class Enum {
    private static $cache_array = NULL;

    public static function get_constants() {
        if (self::$cache_array == NULL) {
            self::$cache_array = [];
        }
        $called_class = get_called_class();
        if (!array_key_exists($called_class, self::$cache_array)) {
            $reflection_object = new ReflectionClass($called_class);
            self::$cache_array[$called_class] = $reflection_object->getConstants();
        }
        return self::$cache_array[$called_class];
    }

    public static function check_valid_name($name, $is_strict = false) {
        $constants = self::get_constants();

        if ($is_strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function check_valid_value($value, $is_strict = true) {
        $values = array_values(self::get_constants());
        return in_array($value, $values, $is_strict);
    }

    public static function getByValue($value){
        $response = null;
        if (self::check_valid_value($value)){
            $response = array_search($value,self::get_constants());
            $response = str_replace("µn"," & ",$response);
        }
        return $response;
    }

    /**
     * @param $name string
     * @return int|null
     */
    public static function getByName($name){
        $response = null;
        if (self::check_valid_name($name)){
            $name = str_replace(" & ","µn",$name);
            $response = self::get_constants()[$name];
        }
        return $response;
    }
}