<?php

namespace App\Business\Services;

// Utility Imports
use App\Business\Utilities\TransactionsUtility;
use App\Business\Utilities\NetworkUtility;

// Response Class
use App\Business\Models\ResponseModel;

// Configs
use App\Business\Configs\CodesConfig;


class MessagesService  {

	protected $_ou;
    protected $_tu;
    protected $_nu;

    public function __construct() {
    	/* 
    	Initate Utility Classes. Could migrate to use dependency injection. Not sure if needed
    	*/
        $this->_tu = new TransactionsUtility();
        $this->_nu = new NetworkUtility();
    }

    // Pass through function to send a message.
    public function sendMessage($number, $message) {
    	$this->_nu->sendMessageTwilio($number, $message);
    }

	// Parses the sent message and tries to extract the desired commands
	public function parseMessage($text) {
		$response = new ResponseModel;
		$response->status = CodesConfig::$STATUS_SUCCESS;
		
		return $response;	
	}
}

?>
