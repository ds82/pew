<?php
namespace pew;

use \pew\exceptions\TemplateKeyNotFoundException;
use \Exception;

use pew\util\Container;

abstract class Template {

    public $out;

    public function __construct() {
        $this->out = new Container();
    }

    public function setOutArr($arr) {

        foreach($arr AS $key => $value) {
            $this->out->$key = $value;
        }
    }

    public function is($key) {
        return property_exists($this->out, $key);
    }

    public function get($key) {
        if ($this->is($key))
            return $this->out->$key;
        //else throw new TemplateKeyNotFoundException('Template tried to access a property that does not exist (' . $key . ')');
        return '';
    }

    public abstract function render();

}
?>