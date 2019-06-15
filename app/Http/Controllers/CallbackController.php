<?php

namespace App\Http\Controllers;
// Larvel Imports
use Illuminate\Http\Request;

// Import our services
use App\Business\Services\MessagesService;
use App\Business\Services\TransactionsService;

// Import our Configs
use App\Business\Configs\CodesConfig;


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
		$from = $request->input('from');
		$body = $request->input('body');

		// Log our message
		$result = $this->_ts->createTransaction($from, $body);

		// Parse our text input to get our command
		$result = $this->_ms->parseMessage($body);

		// Send the response back
		$result = $this->_ms->sendMessage($from, $body);

		return response()->json(['status' => 'success'], 200);
	}
}
