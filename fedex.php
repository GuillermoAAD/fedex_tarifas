<?php

// use GuzzleHttp\Client;
require 'vendor/autoload.php';


function callAPIAuthorization(){
    $headers = '{
        "content-type":"application/x-www-form-urlencoded"
    }';
    $headers= json_decode($headers, TRUE);

    $body = '{
        "grant_type":"client_credentials",
        "client_id":"API_ID",
        "client_secret":"CLIENT_SECRET"
    }';

    $body = json_decode($body, TRUE);

    $body = http_build_query($body);

    $url = "https://apis-sandbox.fedex.com/oauth/token";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_ENCODING ,"");
    $head = curl_exec($ch); 
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    curl_close($ch);
    
    $head = json_decode($head);
    return $head;
}

function callAPITarifas($resAPIAuth){
  echo "<br>CON CURL<br>";

  /*
    // $resAPIAuth = callAPIAuthorization();
    // echo "<br><br>";
    // print_r($resAPIAuth);
    // echo "<br><br>";
    // var_dump($resAPIAuth->access_token);
    // echo "<br><br>";
    // var_dump($resAPIAuth->token_type);
    // echo "<br><br>";
    // print_r($resAPIAuth->expires_in);
    // echo "<br><br>";
    // print_r($resAPIAuth->scope);
    // echo "<br><br>";
  */

    $headers = '{
        "Authorization":"Authorization: Bearer '.$resAPIAuth->access_token.'",
        "content-type":"content-type: application/json",
        "x-locale":"x-locale:en_US"
    }';
    
    $headers= json_decode($headers, TRUE);

    $body = '{
        "accountNumber": {
          "value": "740561073"
        },
        "requestedShipment": {
          "shipper": {
            "address": {
              "postalCode": 11800,
              "countryCode": "MX"
            }
          },
          "recipient": {
            "address": {
              "postalCode": 66000,
              "countryCode": "MX"
            }
          },
          "pickupType": "DROPOFF_AT_FEDEX_LOCATION",
          "serviceType": "FEDEX_EXPRESS_SAVER",
          "packagingType": "YOUR_PACKAGING",
          "rateRequestType": [
            "LIST",
            "ACCOUNT"
          ],
          "requestedPackageLineItems": [
            {
              "weight": {
                "units": "KG",
                "value": 1
              }
            }
          ]
        }
    }';
    // $body= json_decode($body, TRUE);

    // print_r($body);
    // echo "<br><br>";
    
    // $body = http_build_query($body);
    $url = "https://apis-sandbox.fedex.com/rate/v1/rates/quotes";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_ENCODING ,"");
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    curl_close($ch);

    echo "<br><br>httpCode<br>";
    print_r($httpCode);
    echo "<br><br>";
    print_r($head);
    echo "<br><br><br>";
}

function callAPITarifasConGuzzle($resAPIAuth){
    echo "<br>CON GUZZLE<br>";

    $headers = '{
      "Authorization":"Bearer '.$resAPIAuth->access_token.'",
      "content-type":"application/json",
      "x-locale":"en_US"
    }';
    $headers= json_decode($headers, TRUE);

    $body = '{
      "accountNumber": {
        "value": "740561073"
      },
      "requestedShipment": {
        "shipper": {
          "address": {
            "postalCode": 11800,
            "countryCode": "MX"
          }
        },
        "recipient": {
          "address": {
            "postalCode": 66000,
            "countryCode": "MX"
          }
        },
        "pickupType": "DROPOFF_AT_FEDEX_LOCATION",
        "serviceType": "FEDEX_EXPRESS_SAVER",
        "packagingType": "YOUR_PACKAGING",
        "rateRequestType": [
          "LIST",
          "ACCOUNT"
        ],
        "requestedPackageLineItems": [
          {
            "weight": {
              "units": "KG",
              "value": 1
            }
          }
        ]
      }
    }';

    $url = "https://apis-sandbox.fedex.com/rate/v1/";

    $client = new GuzzleHttp\Client(['base_uri'=>$url,'headers'=>$headers]);
    $res = $client->request('POST','rates/quotes',['body'=>$body]);
    $body = json_decode($res->getBody(),true);

    $statusCode = $res->getStatusCode();

    echo "<br><br>httpCode<br>";
    print_r($statusCode);
    echo "<br><br>";
    $body = json_encode($body);
    print_r($body);
    echo "<br><br><br>";

    // print_r($body["transactionId"]);
    

}




// $resAPIAuth = callAPIAuthorization();
// var_dump($resAPIAuth);
$resAPIAuth = callAPIAuthorization();

echo "token:<br>";
print_r($resAPIAuth);
echo "<br>";

callAPITarifas($resAPIAuth);
callAPITarifasConGuzzle($resAPIAuth);





// print_r($resAPIAuth);
// echo "<br><br>";
// $resAPIAuth=
// json_encode($resAPIAuth);
// print_r($resAPIAuth->scope);


?>