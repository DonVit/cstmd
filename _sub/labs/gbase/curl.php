<?php

// create a new cURL resource
$ch = curl_init();
print curl_error($ch);
// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://www.example.com");
print curl_error($ch);
curl_setopt($ch, CURLOPT_HEADER, 0);
print curl_error($ch);

// grab URL and pass it to the browser
curl_exec($ch);

print "<br>Error number: ".curl_errno($ch);
print "<br>Error description: ".curl_error($ch);

// close cURL resource, and free up system resources
curl_close($ch);
?> 