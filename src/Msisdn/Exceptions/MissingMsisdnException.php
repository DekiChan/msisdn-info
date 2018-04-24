<?php

namespace App\Msisdn\Exceptions;

use App\Exceptions\ResponsableException;

class MissingMsisdnException extends ResponsableException { 
    public function __construct() {
        parent::__construct('Missing required parameter: msisdn', 400);
    }
}
