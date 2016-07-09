<?php
require "config.php";

if($_POST['photo-description']){
  
  $fb = new Facebook\Facebook([
    'app_id' => FACEBOOK_APP_ID,
    'app_secret' => FACEBOOK_SECRED_KEY,
    'default_graph_version' => 'v2.2',
    ]);

  $accessToken = $_POST['accessToken'];

  $data = [
    'message' => $_POST['photo-description'],
    'source' => $fb->fileToUpload(__DIR__ . '/' .$_POST['photo'])
  ];

  try {
    $response = $fb->post('/me/photos', $data, $accessToken);
    $graphNode = $response->getGraphNode();

    if($response){
      echo json_encode([
        'status' => 'ok',
        'response' => ['photo_id' => $graphNode['id']]
      ]);
    }
    exit;

  } catch(Facebook\Exceptions\FacebookResponseException $e) {

    echo json_encode([
      'status' => 'fail',
      'response' => 'Graph returned an error: ' . $e->getMessage()
    ]);

    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {

    echo json_encode([
      'status' => 'fail',
      'response' => 'Facebook SDK returned an error: ' . $e->getMessage()
    ]);

    exit;
  }
  
}

