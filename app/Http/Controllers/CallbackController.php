<?php

namespace App\Http\Controllers;
// Larvel Imports
use Illuminate\Http\Request;

// Import our services
use App\Business\Services\MessagesService;
use App\Business\Services\TransactionsService;

// Import our Configs
use App\Business\Configs\CodesConfig;

use Log;

class CallbackController extends Controller {

	protected $_ms;
	protected $_ts;

	public function __construct() {
		$this->_ms = new MessagesService();
		$this->_ts = new TransactionsService();
	}

	/*
		Receives the message sent, process the message and execute the desired functionality
	*/
	protected function processMessage(Request $request) {

		// Get our target iputs 
		$from = $request->input('From');
		$body = $request->input('Body');
		$fromZip = $request->input('FromZip');

		// Verify that our inputs are valid.
		if($from == null && $body == null) {
			return response()->json(['status' => CodesConfig::$STATUS_FAILURE], 400);
		}

		// Parse our text input to get our command
		$result = $this->_ms->parseMessage($body);

		// If we could not parse our message, lets send back a message
		if($result->status == CodesConfig::$STATUS_FAILURE) {
			$this->_ms->sendMessage($from, $result->message);
		}
		// We have a valid command, lets do it
		else {

			// Log the command in the form of a transaction.
			$this->_ts->createTransaction($from, $body, $fromZip, $result->code);

			// Process the command
			if($result->code == CodesConfig::$CODE_GREETING) {
				$this->_ms->sendMessage($from, $result->message);			
			}
			else if($result->code == CodesConfig::$CODE_BORED) {
				$this->_ms->sendMessage($from, $result->message);
				// Lets lets get a response from our graphql server and send it back to our user
				$answer = $this->_ms->getBoredMessage();
				$this->_ms->sendMessage($from, $answer);
			}
			else if($result->code == CodesConfig::$CODE_HUNGRY) {

			}
			else if($result->code == CodesConfig::$CODE_WEATHER) {

			}
			else if($result->code == CodesConfig::$CODE_RANDOM) {
				$randomFact = $this->_ms->getRandomFact();
				$this->_ms->sendMessage($from, $randomFact);
			}
		}
		return response()->json(['status' => 'success'], 200);
	}
}
