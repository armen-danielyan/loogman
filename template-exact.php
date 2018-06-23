<?php
/**
 * Template Name: Exact
 */
?>

<?php
require_once 'libs/exact/ExactApi.php';

$themeOptions = get_option('theme_options');

// Configuration, change these:
$clientId 		= $themeOptions['loogman_exact_clientid'];
$clientSecret 	= $themeOptions['loogman_exact_clientsecret'];
$redirectUri 	= $themeOptions['loogman_exact_redirecturl'];
$division		= $themeOptions['loogman_exact_division'];

$exactApi = new ExactApi('nl', $clientId, $clientSecret, $division);

try {
	// Initialize ExactAPI
	$exactApi = new ExactApi('nl', $clientId, $clientSecret, $division);
	var_dump($exactApi);

	$exactApi->getOAuthClient()->setRedirectUri($redirectUri);

	if (!isset($_GET['code'])) {

		// Redirect to Auth-endpoint
		$authUrl = $exactApi->getOAuthClient()->getAuthenticationUrl();
		header('Location: ' . $authUrl, TRUE, 302);
		die('Redirect');

	} else {

//		// Receive data from Token-endpoint
//		$tokenResult = $exactApi->getOAuthClient()->getAccessToken($_GET['code']);
//		$exactApi->setRefreshToken($tokenResult['refresh_token']);
//
//		// List accounts
//		$response = $exactApi->sendRequest('crm/Accounts', 'get');
//		var_dump($response);
//
//		// Create account
//		$response = $exactApi->sendRequest('crm/Accounts', 'post', array(
//			'Status'			=>	'C',
//			'IsSupplier'		=>	True,
//			'Name'				=>	'iWebDevelopment B.V.',
//			'AddressLine1'		=>	'Ceresstraat 1',
//			'Postcode'			=>	'4811CA',
//			'City'				=>	'Breda',
//			'Country'			=>	'NL',
//			'Email'				=>	'info@iwebdevelopment.nl',
//			'Phone'				=>	'+31(0)76-7002008',
//			'Website'			=>	'www.iwebdevelopment.nl'
//
//		));
//		var_dump($response);

	}

}catch(ErrorException $e){

	var_dump($e);

}
