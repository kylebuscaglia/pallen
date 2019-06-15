<?php

namespace App\Business\Utilities;

class NetworkUtility  {

	// Send a SMS message using the Twilio api to a desired number
	public function sendMessageTwilio($number, $message) {
		system("curl 'https://api.twilio.com/2010-04-01/Accounts/AC6236758eb7752b014afdf2f249d4121e/Messages.json' -X POST \
			--data-urlencode 'To=" . $number . "' \
			--data-urlencode 'From=+17162394598' \
			--data-urlencode 'Body=" . $message . "' \
			-u " . env("AUTH_TOKEN"));
	}

	// Issues a graphQL query to our target backend
	public function queryGraphQL($query, $variables) {
		$client = \Softonic\GraphQL\ClientBuilder::build('http://ec2-34-210-75-74.us-west-2.compute.amazonaws.com/graphql');
		$response = $client->query($query, $variables)->getdata();
		return $response;
	}
}

?>