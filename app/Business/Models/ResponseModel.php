<?php

namespace App\Business\Models;


// Generic response model to use throughout the application
class ResponseModel {

    public $status = "";
    public $code = 0;
    public $message = "";
    public $count = 0;
    public $data = [];
}

?>