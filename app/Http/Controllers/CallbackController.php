<?php

namespace App\Http\Controllers;
// Larvel Imports
use Illuminate\Http\Request;

// Import our services
use App\Business\Services\MessagesService;
use App\Business\Services\TransactionsService;

// Import our Configs
use App\Business\Configs\CodesConfig;
use App\Business\Configs\MessagesConfig;


class CallbackController extends Controller {

	protected $_ms;
	protected $_ts;

	public function __construct() {
		// Initalize our services
		$this->_ms = new MessagesService();
		$this->_ts = new TransactionsService();
	}

	/*
		Receives the message sent and processes it
		Required: From, Body, FromZip in HTTP body
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

		// Parse our body text to process it into a format that can be interpreted and executed
		$result = $this->_ms->parseMessage($body);

		// If we could not parse our message, lets send back a message
		// saying we could not understand what they were saying
		if($result->status == CodesConfig::$STATUS_FAILURE) {
			$this->_ms->sendMessage($from, $result->message);
		}
		// If we can process it, lets do it
		else {

			// Log the request in the form of a transaction object.
			$this->_ts->createTransaction($from, $body, $fromZip, $result->code);

			// Process the command
			// Check for a greeting request
			if($result->code == CodesConfig::$CODE_GREETING) {
				$this->_ms->sendMessage($from, $result->message);			
			}
			// Check for a help request
			else if($result->code == CodesConfig::$CODE_HELP) {
				$this->_ms->sendMessage($from, $result->message);			
			}
			// Check for a bored request
			else if($result->code == CodesConfig::$CODE_BORED) {
				// Lets send them back a temporary message while we query for better data
				$this->_ms->sendMessage($from, $result->message);

				// Issue a reques to our graphql server
				$answer = $this->_ms->getBoredMessage();
				// Respond back with the answer
				$this->_ms->sendMessage($from, $answer);
			}
			// Check if a hunger request
			else if($result->code == CodesConfig::$CODE_HUNGRY) {
				// Check to see if there is a valid zip code
				if($fromZip == null) {
					$this->_ms->sendMessage($from, MessagesConfig::$NO_ZIP);
				}
				else {
					// Lets send them back a temporary message while we query for better data
					$this->_ms->sendMessage($from, $result->message);

					// Issue a reques to our graphql server
					$answer = $this->_ms->getHungryMessage($fromZip);
					// Respond back with the answer
					$this->_ms->sendMessage($from, $answer);
				}
			}
			// Check for weather request. NOT IMPLEMENTED YET
			else if($result->code == CodesConfig::$CODE_WEATHER) {

			}
			// Check for a random request
			else if($result->code == CodesConfig::$CODE_RANDOM) {
				// Get an answer from graphql
				$randomFact = $this->_ms->getRandomFact();
				// Send the fact back
				$this->_ms->sendMessage($from, $randomFact);
			}
		}
		// Respond back to the API endpoint
		return response()->json(['status' => 'success'], 200);
	}
}
