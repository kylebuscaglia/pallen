<?php

namespace App\Business\Utilities;

class NetworkUtility  {

	// Send a message using the curl command
	public function sendMessageTwilio($number, $message) {
		system("curl 'https://api.twilio.com/2010-04-01/Accounts/AC6236758eb7752b014afdf2f249d4121e/Messages.json' -X POST \
			--data-urlencode 'To=" . $number . "' \
			--data-urlencode 'From=+17162394598' \
			--data-urlencode 'Body=" . $message . "' \
			-u " . env("AUTH_TOKEN"));
	}
}

?>
