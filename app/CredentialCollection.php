<?php

namespace App;

use Illuminate\Support\Collection;

class CredentialCollection extends Collection{

    public function __get($name){
        return $this->getCredential($name)->value;
    }

    public function __set($name, $value){
        $credential = $this->getCredential($name);
        $credential->value = $value;
    }

    public function save(){
        $this->each(function(Credential $credential){
            $credential->save();
        });
    }

    public function toArray()
    {
//        return $this->groupBy('name');
        $ret = [];
        $this->each(function($credential) use(&$ret){
            $ret[$credential->name] = $credential;
        });
        return $ret;
    }

    private function getCredential($name)
    {
        return $this->filter(function($item) use($name){
            return $name === $item->name;
        })->first();
    }
}