<?php
namespace ItForFree\SimpleMVC\exceptions;

class SmvcException extends \Exception
{
    // Переопределим исключение так, что параметр message станет обязательным
    public function __construct($message, $code = 0, \Exception $previous = null) {
        // некоторый код 
    
        // убедитесь, что все передаваемые параметры верны
        parent::__construct($message, $code, $previous);
    }

    // Переопределим строковое представление объекта.
    public function __toString() {
        return __CLASS__ . ": [!] SimpleMVC [{$this->code}]: {$this->message}\n";
    }

}