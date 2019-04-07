<?php

$username = 'user1@mail.com';
$password = '12345';
$id = 8;

$ch = curl_init('http://localhost:8000/api/post/' . $id . '/remove');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
$response = curl_exec($ch);
curl_close($ch);

var_dump($response);