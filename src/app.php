<?php
    require './controlers.php';

    $API_KEY = 'AIzaSyBSkGdTHKhUGf8lgrhtg-87RPCvrV77nkY';
    $ACCESS_TOKEN = 'e5d882fbfe3eb52992ae10dda8b9cf5f7af27b78';

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    $app = new \Slim\App;

    $app->post('/shortenurl/api', function (Request $request, Response $response) use ($API_KEY) {
        if (!in_array('application/x-www-form-urlencoded', $request->getHeader('Content-Type'))) {
            return $response
                ->withStatus(422) // unproccessable entity
                ->write(json_encode(array(
                    'error' => 'Invalid post data format.'
                )));
        }

        $data = $request->getParsedBody();

        if (!array_key_exists('url', $data)) {
            return $response
                ->withStatus(422)
                ->write(json_encode(array(
                    'error' => 'Invalid post data.'
                )));
        }

        if ($data['provider'] == 'bit.ly') {
            $apiResponse = bitlyApi($data['url']);
            return $apiResponse['url'];
        } elseif ( !$data['provider'] || strlen($data['provider']) == 0 || $data['provider'] == 'goo.gl') {
            $apiResponse = googleApi($data, $API_KEY);
            return $apiResponse->id;
        } else {
             return $response
                ->withStatus(400) 
                ->write(json_encode(array(
                    'error' => 'Invalid provider.'
                )));
        }
        
    });
