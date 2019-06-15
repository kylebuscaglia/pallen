<?php

namespace App\Business\Utilities;

use App\Business\Models\TransactionModel;

class TransactionsUtility  {

	// Creates a new transaction record in the database
	public function createTransaction($number, $message) {
		$model = new TransactionModel;
		$model->number = $number;
		$model->message = $message;
		$model->save();
	}
}

?>