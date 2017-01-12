<?php
$access_token = 'z2TVe07oCkUP2/cDQ8/Y8w+zT6HHfHDF6rRKuewNXRK5qA25Fbkgkl2xQTqSd+tnZ4z1Bacb4YVSi99KuuC5TKbUOJdDNPR2MPWMH+iesB4LI9mcNAsz9HyWcMFWsQYCaIczAdBRSg+W04nC399yOwdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;