<?php

namespace App\Business\Services;

// Utility Imports
use App\Business\Utilities\NetworkUtility;

// Response Class
use App\Business\Models\ResponseModel;

// Configs
use App\Business\Configs\CodesConfig;
use App\Business\Configs\MessagesConfig;


class MessagesService  {

    protected $_nu;

    public function __construct() {
    	/* 
    	Initate Utility Classes. Could migrate to use dependency injection. Not sure if needed
    	*/
        $this->_nu = new NetworkUtility();
    }

    // Pass through function to send a message.
    public function sendMessage($number, $message) {
        // Sends a message using Twilio api
    	$this->_nu->sendMessageTwilio($number, $message);
    }

    // A function that call ours graphql backend looking for a bored message
    public function getBoredMessage() {
        $variables = [];
        $query = 'query GetActivity { activity { name type } }';

        $result = $this->_nu->queryGraphQL($query, $variables);
        $activityName = $result["activity"]["name"];
        return $activityName;
    }
    
    // A function that call ours graphql backend looking for a food suggestion
    public function getHungryMessage($zip) {
        $variables = [];
        $query = 'query GetFood { food(zip: "' . $zip . '") { url name } }';

        $result = $this->_nu->queryGraphQL($query, $variables);
        $activityName = $result["food"][0]["url"];
        return $activityName;
    }
    
    // A function that call ours graphql backend looking for a random fact
    public function getRandomFact() {
        $variables = [];
        $query = 'query GetFact { random { fact } }';
        $result = $this->_nu->queryGraphQL($query, $variables);
        $activityName = $result["random"]["fact"];
        return $activityName;
    }

	// Parses the raw body of the message into a command
	public function parseMessage($body) {
		$response = new ResponseModel;
		$response->status = CodesConfig::$STATUS_SUCCESS;

        // Explode our message into space delimited syntax tokens
        $text = strtolower($body);
        $tokens = explode(' ', $text);

        // Syntax parser looking for a greeting.
        // Key tokens: hi, hello
        if(in_array('hi', $tokens) || in_array('hello', $tokens)) {
            $response->code = CodesConfig::$CODE_GREETING;
            $response->message = MessagesConfig::$GREETING;
            return $response;
        }
        // Syntax parser looking for help
        // Key tokens: help. 
        // Size of message is no greater than 3 words
        else if(sizeof($tokens) <= 3 && in_array('help', $tokens)) {
            $response->code = CodesConfig::$CODE_HELP;
            $response->message = MessagesConfig::$HELP;
            return $response;
        }
        // Syntax parser looking for bored
        // Key tokens: i, i'm, bored 
        else if((in_array('i', $tokens) || in_array('i\'m', $tokens)) && in_array('bored', $tokens)) {
            $response->code = CodesConfig::$CODE_BORED;
            $response->message = MessagesConfig::$LOOKUP_MESSAGE_BORED;
            return $response;
        }
        // Syntax parser looking for Hungry
        // Key tokens: i, i'm, hungry 
        else if((in_array('i', $tokens) || in_array('i\'m', $tokens)) && in_array('hungry', $tokens)) {
            $response->code = CodesConfig::$CODE_HUNGRY;
            $response->message = MessagesConfig::$LOOKUP_MESSAGE_HUNGRY;
            return $response;
        }
        // Syntax parser looking for random fact
        // Key tokens: tell, fact, random 
        else if((in_array('tell', $tokens) || in_array('fact', $tokens)) && in_array('random', $tokens)) {
            $response->code = CodesConfig::$CODE_RANDOM;
            return $response;
        }
        // Syntax parser looking for weather
        // Key tokens: how, how's, what, what's, weather 
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
