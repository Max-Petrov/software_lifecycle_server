<?php

namespace Kenguru\Logistic\API\DAL\Exceptions;

/**
 * Description of DbConfigException
 *
 * @author maxim
 */
class DbConfigException extends \Exception {
    
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
}
