<?php
namespace pew\common;

class ContentType {

    private $types = array(
        'json' => 'application/json',
        'default' => 'text/html',
    );

    public function __construct() {

    }

    public function setContentTypeHeader($format) {
        $header = in_array($format, $this->types) ? $this->types[$format] : $this->types['default'];
        header('Content-Type: ' . $header);
    }

}

?>