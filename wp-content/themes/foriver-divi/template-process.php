<?php
// Template Name: PayPal Process

if (class_exists('Caldera_Forms')) {
  /**
   * Get saved data for this entry
   */

  global $wpdb;

  $data = $wpdb->get_results( $wpdb->prepare( "SELECT slug, value FROM `" . $wpdb->prefix . "cf_form_entry_values` WHERE `entry_id` = %d", $_GET['cf_id'] ), OBJECT_K );

  // print_r($data);


  /**
   * The following is all taken from the previous Joomla site
   * We found this documentation from PayPal that is related:
   * https://developer.paypal.com/docs/classic/payflow/integration-guide/#payflow-connection-parameters
   */

  // Generate random secure key
  function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
  }

  // Generate random token
  function getToken($length){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
  }

  // Set up variables
  $error = 0;
  $mode = "LIVE";
  $user = "TRWCcart";
  $vendor = "TRWCmgr";
  $partner = "PayPal";
  $pwd = "3p1cWinter";
	$secureTokenId = getToken(36);

  if (array_key_exists('other_amount', $data)) {
    $amt = $data['other_amount']->value;  // Custom amount
  } else {
    if (array_key_exists('frequency', $data) && $data['frequency']->value == 'monthly') {
      $amt = $data['amount_recurring']->value;  // Recurring amount
    } else {
      $amt = $data['amount_once']->value; // One-time amount
    }
  }

  // Prep params for API request
	$postData = "USER=" . $user
			.   "&VENDOR=" . $vendor
			.   "&PARTNER=" . $partner
			.   "&PWD=" . $pwd
			.   "&CREATESECURETOKEN=Y"
			.   "&SECURETOKENID=" . $secureTokenId
			.   "&TRXTYPE=S"
			.   "&AMT=" . $amt;

  // Hit up Payflow SDK to get authorized secure token
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://payflowpro.paypal.com");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	$resp = curl_exec($ch);
	$pairs = explode("&", $resp);
	foreach ($pairs as $pair) {
		$sets = explode("=", $pair);
		$arr[$sets[0]] = $sets[1];

	}
	if (!$resp) $error = 1;
	if ($arr['RESULT'] != 0) {
		$error = 1;
	}

  // If the SDK returns an okay response, let's set up the URL redirect to the hosted checkout page
	if (!$error) {
		$url = 'https://payflowlink.paypal.com?SECURETOKEN='.$arr['SECURETOKEN'].'&SECURETOKENID='.$secureTokenId.'&MODE='.$mode;
		$url .= "&FIRST_NAME=" . $data['first_name']->value;
		$url .= "&LAST_NAME=" . $data['last_name']->value;
		$url .= "&ADDRESS=" . $data['address_line_1']->value;
		$url .= (array_key_exists('address_line_2', $data)) ? "&BILLINGADDRESS2=" . $data['address_line_2']->value : '';
    $url .= "&CITY=" . $data['city']->value;
		$url .= "&STATE=" . $data['state']->value;
		$url .= "&ZIP=" . $data['zippostal_code']->value;
		$url .= "&EMAIL=" . $data['email_address']->value;
		$url .= "&TYPE=S";
		if (array_key_exists('frequency', $data) && $data['frequency']->value == 'monthly') {
			$recurring = "Y";
			$note = " Monthly pledge of $amt.";
		}
		else {
			$recurring = "N";
		}
		$url .= "&DESCRIPTION=TRWC Online Donation.".$note;
		$url .= "&RECURRING=" . $recurring;

    // Redirect to the PayPal hosted checkout page
		header("location: $url");

    // Debug
    // print_r($url);
	}
	else {
		echo $resp;
		echo "<P>The donation form could not be processed.</P>";
	}

}
?>
