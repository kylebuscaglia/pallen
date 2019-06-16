<?php

namespace App\Business\Utilities;

use App\Business\Models\TransactionModel;

class TransactionsUtility  {

	// Creates a new transaction record in the database
	public function createTransaction($number, $message, $fromzip, $code) {
		$model = new TransactionModel;
		$model->number = $number;
		$model->message = $message;
		if($fromzip != null) {
			$model->fromzip = $fromzip;
		}
		$model->cat = $code;
		$model->save();
	}
}

?>