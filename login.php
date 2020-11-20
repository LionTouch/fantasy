<?php

// set the relative path to your txt file to store the csrf token
$cookie_file = realpath('./cookie.txt');

// login url
$url = 'https://users.premierleague.com/accounts/login/';

// make a get request to the official fantasy league login page first, before we log in, to grab the csrf token from the hidden input that has the name of csrfmiddlewaretoken
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "POST");
$response = curl_exec($ch);

$dom = new DOMDocument;
@$dom->loadHTML($response);

// set the csrf here
$tags = $dom->getElementsByTagName('input');
for($i = 0; $i < $tags->length; $i++) {
    $grab = $tags->item($i);
    if($grab->getAttribute('name') === 'csrfmiddlewaretoken') {
        $token = $grab->getAttribute('value');
    }
}

// now that we have the token, use our login details to make a POST request to log in along with the essential data form header fields
//echo $token;
if(!empty($token)) {
    echo 'in';
    $params = array(
        "login"                 => '7amzaes@gmail.com',
        "password"              => '1935866@linkin',
        "app"                   => "plfpl-web",
        "redirect_uri"          => "https://fantasy.premierleague.com/",
    );

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    /**
     * using CURLOPT_SSL_VERIFYPEER below is only for testing on a local server, make sure to remove this before uploading to a live server as it can be a security risk.
     * If you're having trouble with the code after removing this, look at the link that @Dharman provided in the comment section.
     */
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //***********************************************^

    $response = curl_exec($ch);

    // set the header field for the token for our final request
//    $headers = array(
//        'csrftoken ' . $token,
//    );
    print_r(json_decode($response,true));

}
print_r(json_decode($response,true));

?>
