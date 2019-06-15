<?php

namespace App\Business\Services;

// Utility Imports
use App\Business\Utilities\TransactionsUtility;
use App\Business\Utilities\NetworkUtility;

// Response Class
use App\Business\Models\ResponseModel;

// Configs
use App\Business\Configs\CodesConfig;
use App\Business\Configs\MessagesConfig;


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

    public function getBoredMessage() {
        $variables = [];        
        $result = $this->_nu->queryGraphQL($variables);
        return $result->getdata()["activity"]["name"];
    }

	// Parses the sent message and tries to extract the desired commands
	public function parseMessage($body) {
		$response = new ResponseModel;
		$response->status = CodesConfig::$STATUS_SUCCESS;

        $text = strtolower($body);
        $tokens = explode(' ', $text);

        if((in_array('i', $tokens) || in_array('i\'m', $tokens)) && in_array('bored', $tokens)) {
            $response->message = MessagesConfig::$LOOKUP_MESSAGE;
            return $response;
        }
        else {
            $response->status = CodesConfig::$STATUS_FAILURE;
            $response->message = MessagesConfig::$SYNTAX_ERROR;
    		return $response;
        }	
	}
}

?>
