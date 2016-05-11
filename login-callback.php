<?php
    session_start();
    require_once __DIR__ . '/vendor/autoload.php';

    $fb = new Facebook\Facebook([
        'app_id' => '2024199137805681',
        'app_secret' => '9ab41fd2363101f8fac6a9dfca1fd98f',
        'default_graph_version' => 'v2.5',
    ]);

    $helper = $fb->getRedirectLoginHelper();

    try {
        $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    if (isset($accessToken)) {
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        header('Location: index.php');
    }
?>