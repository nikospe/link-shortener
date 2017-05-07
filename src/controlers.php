<?php
/** 
* uses goog.gl api for shortening url 
*
* @param $data array
* @param $API_KEY string
* @return string the shorten url
*/
function googleApi($data, $API_KEY)
{
        
    $postData = array('longUrl' => $data['url'], 'key' => $API_KEY);
    $jsonData = json_encode($postData);
 
    $curlObj = curl_init();
    curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$API_KEY);
    curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlObj, CURLOPT_HEADER, 0);
    curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
    curl_setopt($curlObj, CURLOPT_POST, 1);
    curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
    $response = json_decode(curl_exec($curlObj));
    curl_close($curlObj);
    return $response;
}

/** 
* uses bit.ly api for shortening url
*
* @param $long_url string
* @return json a json response with the shorten url or error message
*/
function bitlyApi($long_url)
{
        
    $domain = 'localhost';
    $access_token= 'e5d882fbfe3eb52992ae10dda8b9cf5f7af27b78';

    $url = 'https://api-ssl.bitly.com/v3/shorten?access_token='.$access_token.'&longUrl='.urlencode($long_url);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = json_decode(curl_exec($ch));

    $response = [
    'url' =>  null,
    'error' => null
    ];
        
    if ($output->status_code == 200) {
        $response['url'] = $output->data->url;
    } else {
        $response['error'] = $output->status_txt;
    }

    return $response;
}
