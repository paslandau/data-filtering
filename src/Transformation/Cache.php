<?php

namespace paslandau\DataFiltering\Transformation;


class Cache {
    private $cache;

    public function __construct(){
        $this->cache = array();
    }

    public function addToCache($input, $data = null, $res = null){
        $id = $this->getId($input);
        if($id !== null){
            $this->cache[$id] = ["data" => $data, "res" => $res];
        }
        return $id;
    }

    public function getData($input){
        $id = $this->getId($input);
        return $this->cache[$id]["data"];
    }

    public function getRes($input){
        $id = $this->getId($input);
        return $this->cache[$id]["res"];
    }

    public function getId($input){
        $id = null;
        $res = "";
        if(is_scalar($input)){
            $res = $input;
        }elseif(is_object($input)){
            $res = spl_object_hash($input);
        }elseif(is_array($input)){
            $res = json_encode($input);
            if(!$res){ // json_encode might fail
                // messy, but guaranteed to work
                ob_start();
                @var_dump($input);
                $res=ob_get_contents();
                ob_end_clean();
            }
        }
        $id = gettype($input)."_".md5($res);
        return $id;
    }

    public function clear(){
        $this->cache = array();
    }

    public function contains($input){
        $id = $this->getId($input);
        return array_key_exists($id,$this->cache);
    }

    public function getCache(){
        return $this->cache;
    }

} 