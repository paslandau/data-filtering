<?php
namespace paslandau\DataFiltering\Util;

class ReflectionUtil
{
    /**
     * Returns the object as an associative array by using get_object_vars
     * Won't work on circular references!
     * @param mixed $object
     * @param boolean $recursive [optional]. Default: false. If true, all properties will be also converted to arrays.
     * @param int $reflectionPropertyVisibility [optional]. Default: null. [All]
     * @return mixed[]
     */
    public static function ObjectToArray($object = null, $recursive = false, $reflectionPropertyVisibility = null)
    {

        if (is_null($object) || (!is_object($object) && !is_array($object))) {
            return $object;
        }

        if (is_array($object)) {
            $arr = $object;
        } else {
            if($object instanceof \stdClass){
                $arr = get_object_vars($object);
            }else{
                $refClass = new \ReflectionClass($object);
                $props = $refClass->getProperties();
                $arr = [];
                foreach($props as $prop){
                    $prop->setAccessible(true);
                    $arr[$prop->name] = $prop->getValue($object);
                }
            }
        }
        if ($recursive) {
            $newArr = array();
            foreach ($arr as $key => $el) {
                $new = self::ObjectToArray($el, $recursive, $reflectionPropertyVisibility);
                $newArr[$key] = $new;
            }
        } else {
            $newArr = $arr;
        }
        return $newArr;
    }

    /**
     * Fills $obj by the values of the matching keys of $arr.
     * If strict mode is active, all keys in the arr must match.
     *
     * e.g.:
     * class SomeClass{
     *    public $name;
     *  public $email;
     * }
     *
     * $arr = array("name" => "Max", "email" => "test@me.de");
     * $obj = new SomeClass();
     * FillObjectFromArray($obj, $arr);
     * // $obj->name : "Max"; $obj->email => "test@me.de"
     *
     * @param $obj . The object to fill.
     * @param mixed[] $arr . The array used to fill the object.
     * @param boolean $strict [optional]. Default: true.
     * @throws \Exception
     */
    public static function FillObjectFromArray(&$obj, $arr, $strict = true)
    {
        $objClass = get_class($obj);
        $refClass = new \ReflectionClass($objClass);
        $props = $refClass->getProperties();
        foreach ($props as $prop) {
            if (array_key_exists($prop->name, $arr)) {
                $prop->setAccessible(true);
                $prop->setValue($obj, $arr[$prop->name]);
                unset($arr[$prop->name]);
            }
        }
        if ($strict && count($arr) > 0) {
            $keys = implode(", ", array_keys($arr, "\n"));
            throw new \Exception("There are still values left (keys: $keys) in the given array and strict mode is active!");
        }
    }

    /**
     * Get all properties of $className
     * @param string $className
     * @param string $filter [optional]. Default: null. Use \ReflectionMethod:: Constants
     * @return \ReflectionMethod[]. E.g. [string name => \ReflectionMethod]
     */
    public static function GetMethods($className, $filter = null)
    {
        $reflectionClass = new \ReflectionClass ($className);
        $_methods = $reflectionClass->getMethods($filter);
        $methods = array();
        foreach ($_methods as $method) {
            $methods[$method->name] = $method;
        }
        return $methods;
    }

    /**
     * Get all properties of $className
     * @param string $className
     * @return \ReflectionProperty[] E.g. [0 => \ReflectionProperty()]
     */
    public static function GetProperties($className)
    {
        $reflectionClass = new \ReflectionClass ($className);
        $props = $reflectionClass->getProperties();
        return $props;
    }

}