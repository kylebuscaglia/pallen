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
        $query = '
        query GetActivity { activity { name type } }';

        $result = $this->_nu->queryGraphQL($query, $variables);
        $activityName = $result["activity"]["name"];
        return $activityName;
    }

	// Parses the sent message and tries to extract the desired commands
	public function parseMessage($body) {
		$response = new ResponseModel;
		$response->status = CodesConfig::$STATUS_SUCCESS;

        $text = strtolower($body);
        $tokens = explode(' ', $text);

        // Syntax parser for bored
        if(in_array('hi', $tokens) || in_array('hello', $tokens)) {
            $response->code = CodesConfig::$CODE_GREETING;
            $response->message = MessagesConfig::$GREETING;
            return $response;
        }
        // Syntax parser for bored
        if((in_array('i', $tokens) || in_array('i\'m', $tokens)) && in_array('bored', $tokens)) {
            $response->code = CodesConfig::$CODE_BORED;
            $response->message = MessagesConfig::$LOOKUP_MESSAGE_BORED;
            return $response;
        }
        // Syntax parser for Hungry
        else if((in_array('i', $tokens) || in_array('i\'m', $tokens)) && in_array('hungry', $tokens)) {
            $response->code = CodesConfig::$CODE_HUNGRY;
            $response->message = MessagesConfig::$LOOKUP_MESSAGE_HUNGRY;
            return $response;
        }
        // Syntax parser for random fact
        else if((in_array('tell', $tokens) || in_array('i\'m', $tokens)) && in_array('random', $tokens)) {
            $response->code = CodesConfig::$CODE_RANDOM;
            $response->message = MessagesConfig::$LOOKUP_MESSAG_RANDOM;
            return $response;
        }
        // Syntax parser for weather
        else if((in_array('how', $tokens)
            || in_array('how\'s', $tokens) 
            || in_array('what', $tokens) 
            || in_array('what\'s', $tokens)) 
            && in_array('weather', $tokens)) {
            $response->code = CodesConfig::$CODE_WEATHER;
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
