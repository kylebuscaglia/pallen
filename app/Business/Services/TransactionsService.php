<?php

namespace App\Business\Services;

// Utility Imports
use App\Business\Utilities\TransactionsUtility;

// Service Response Class
use App\Business\Models\ServiceResponseModel;

class TransactionsService  {

    protected $_tu;

    public function __construct() {
    	/* 
    	Initate Utility Classes. Could migrate to use dependency injection. Not sure if needed
    	*/
        $this->_tu = new TransactionsUtility();
    }

	// Creates and logs a transaction
	public function createTransaction($number, $message, $fromzip, $code) {
        // Call the transaction utility function that will update the database
		$this->_tu->createTransaction($number, $message, $fromzip, $code);
	}
}

?>
