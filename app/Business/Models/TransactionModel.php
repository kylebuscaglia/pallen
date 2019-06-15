<?php

namespace App\Business\Models;

use Illuminate\Database\Eloquent\Model;

// Transaction database model
class TransactionModel extends Model {

  	protected $table = "transactions";
  	protected $primaryKey = "id";
}


?>