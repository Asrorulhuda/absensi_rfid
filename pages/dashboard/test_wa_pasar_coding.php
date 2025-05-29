<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://whatsapp.pasarcoding.com/send-message',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array(
    'api_key' =>  'vYrTe7cux0B3pnVzfM8vDhl2jveqcK',
    'sender' =>  '6289516426939',
    'number' =>  '081391018116',
    'message' =>  'Hello World'
),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;