<?php

$username = 'user1@mail.com';
$password = '12345';
$postId = 8;

$data = [
    'rating'  => 3,
];

$ch = curl_init('http://localhost:8000/api/post/' . $postId . '/rate');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
curl_close($ch);

var_dump($response);