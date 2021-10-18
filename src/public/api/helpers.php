<?php
// Helper method to determine if a shop domain is valid
function validateShopDomain($shop) {
  $substring = explode('.', $shop);

  // 'blah.myshopify.com'
  if (count($substring) != 3) {
    return FALSE;
  }

  // allow dashes and alphanumberic characters
  $substring[0] = str_replace('-', '', $substring[0]);
  return (ctype_alnum($substring[0]) && $substring[1] . '.' . $substring[2] == 'myshopify.com');
}

// Helper method to determine if a request is valid
function validateHmac($params, $secret) {
  $hmac = $params['hmac'];
  unset($params['hmac']);
  ksort($params);

  $computedHmac = hash_hmac('sha256', http_build_query($params), $secret);

  return hash_equals($hmac, $computedHmac);
}

// Helper method for exchanging credentials
function getAccessToken($shop, $apiKey, $secret, $code) {
  $query = array(
  	'client_id' => $apiKey,
  	'client_secret' => $secret,
  	'code' => $code
  );

  // Build access token URL
  $access_token_url = "https://{$shop}/admin/oauth/access_token";

  // Configure curl client and execute request
  $curl = curl_init();
  $curlOptions = array(
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_URL => $access_token_url,
    CURLOPT_POSTFIELDS => http_build_query($query)
  );
  curl_setopt_array($curl, $curlOptions);
  $jsonResponse = json_decode(curl_exec($curl), TRUE);
  curl_close($curl);

  return $jsonResponse['access_token'];
}

// Helper method for making Shopify API requests
function performShopifyRequest($shop, $token, $resource, $params = array(), $method = 'GET') {
  $url = "https://{$shop}/admin/{$resource}.json";

  $curlOptions = array(
    CURLOPT_RETURNTRANSFER => TRUE
  );

  if ($method == 'GET') {
    if (!is_null($params)) {
      $url = $url . "?" . http_build_query($params);
    }
  } else {
    $curlOptions[CURLOPT_CUSTOMREQUEST] = $method;
  }

  $curlOptions[CURLOPT_URL] = $url;

  $requestHeaders = array(
    "X-Shopify-Access-Token: ${token}",
    "Accept: application/json"
  );

  if ($method == 'POST' || $method == 'PUT') {
    $requestHeaders[] = "Content-Type: application/json";

    if (!is_null($params)) {
      $curlOptions[CURLOPT_POSTFIELDS] = json_encode($params);
    }
  }

  $curlOptions[CURLOPT_HTTPHEADER] = $requestHeaders;

  $curl = curl_init();
  curl_setopt_array($curl, $curlOptions);
  $response = curl_exec($curl);
  curl_close($curl);

  return json_decode($response, TRUE);
}

// Helper method for call Shopify API requests
function shopify_call($api_endpoint, $method = 'GET', $query = null, $request_headers = null) {
  $user = $_ENV['PR_API_KEY'];
  $password=$_ENV['PR_PASSWORD'];
  $shop = $_ENV['SHOP'];
  $api_url = $_ENV['API_URL'];
	// Build URL
	$url = "https://". $user .':' .$password .'@'. $shop .$api_url. $api_endpoint;
	if (!is_null($query) && in_array($method, array('GET', 	'DELETE'))) $url = $url . "?" . http_build_query($query);
	// Configure cURL
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, TRUE);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
	// curl_setopt($curl, CURLOPT_SSLVERSION, 3);
	curl_setopt($curl, CURLOPT_USERAGENT, 'My PHP Shopify App v.1');
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

	// Setup headers
	// $request_headers[] = "";
	// if (!is_null($access_token)) $request_headers[] = "X-Shopify-Access-Token: " . $access_token;
	// curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);

	if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
		if (is_array($query)) $query = http_build_query($query);
		curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
	}
    
	// Send request to Shopify and capture any errors
	$response = curl_exec($curl);
	
	$error_number = curl_errno($curl);
	$error_message = curl_error($curl);

	// Close cURL to be nice
	curl_close($curl);

	// Return an error is cURL has a problem
	if ($error_number) {
		return $error_message;
	} else {

		// No error, return Shopify's response by parsing out the body and the headers
		$response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
		
		// Convert headers into an array
		$headers = array();
		$header_data = explode("\n",$response[0]);
		$headers['status'] = $header_data[0]; // Does not contain a key, have to explicitly set
		array_shift($header_data); // Remove status, we've already set it above
		foreach($header_data as $part) {
			$h = explode(":", $part);
			$headers[trim($h[0])] = trim($h[1]);
		}
		// Return headers and Shopify's response
		return array('headers' => $headers, 'response' => json_decode($response[1]));

	}
    
}