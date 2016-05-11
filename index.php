<?php
	session_start();
	require_once __DIR__ . '/vendor/autoload.php';

	$fb = new Facebook\Facebook([
		'app_id' => '2024199137805681',
	  	'app_secret' => '9ab41fd2363101f8fac6a9dfca1fd98f',
	  	'default_graph_version' => 'v2.5',
	]);

	if(isset($_SESSION['facebook_access_token'])) {
		$oAuth2Client = $fb->getOAuth2Client();
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

		$fb->setDefaultAccessToken($longLivedAccessToken);

		//Get user info
		try {
			$response = $fb->get('/me');
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  	echo 'Graph returned an error: ' . $e->getMessage();
		  	exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  	exit;
		}
		echo 'Logged in as ' . $response->getGraphUser()->getName() . '<br><br>';

		/** Get friends list
		The api function 'friends' should be used. However, it currently returns empty data because no one is logged in the app yet.
		So for purpose of demonstration, I used taggable_friends api function. 
		One caveat though is when sending message, an error occurs(does not resolve to a valid user ID) because taggable_friends api do not return a numeric id used as a parameter for 'Send Dialog' api. 
		However, this should work when 'friends' is used and given there are already users/friends using the app. **/

		try {
			//$friendsResponse = $fb->get('/me/friends?limit=2000');
		  	$friendsResponse = $fb->get('/me/taggable_friends?limit=2000');
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  	echo 'Graph returned an error: ' . $e->getMessage();
		  	exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  	exit;
		}
		echo '<input id="btnSendMsg" type="button" value="Send A Message"><br><br>';

		$friends = $friendsResponse->getGraphEdge()->asArray();
		foreach ($friends as $value) {
			echo '<input type="radio" name="friends" value="' . $value['id'] . '">' . '<img src="' . $value['picture']['url'] . '"> ' . $value['name'] . '<br>';
		}

	} else {
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['user_friends'];
		$loginUrl = $helper->getLoginUrl('http://localhost:8000/login-callback.php', $permissions);

		echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
	}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="script.js"></script>