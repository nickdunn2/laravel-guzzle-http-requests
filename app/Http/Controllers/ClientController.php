<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Http\Requests;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected function performRequest($method, $url, $parameters = [])
    {
      // the 'curl' key info is needed to enable https request in addition to http
      $client = new Client([ 'curl' => [CURLOPT_CAINFO => base_path('resources/certs/cacert.pem')]]);
      $response = $client->request($method, $url, $parameters);
      return $response->getBody()->getContents();
    }

    protected function performGetRequest($url)
    {
      // parameters are only needed for stuff like POST or PUT requests
      return $this->performRequest('GET', $url);
    }

    protected function obtainDetails($house)
    {
      // gather contents for use in URL
      $zwsId = "Your zws-id goes here";
      $street = urlencode($house->street);
      $zip = urlencode($house->zip);
      $propertyDetails = [];

      // example url structure, it needs EITHER a zip or a city/state combo. Here, we just use zip.
      // http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=<ZWSID>&address=2114+Bigelow+Ave&citystatezip=Seattle%2C+WA
      $url = 'http://www.zillow.com/webservice/GetDeepSearchResults.htm?' . 'zws-id=' . $zwsId . '&address=' . $street . '&citystatezip=' . $zip;

      // perform GET request for house and translate XML response to JSON
      $xmlContents = simplexml_load_string($this->performGetRequest($url));
      $jsonContents = json_encode($xmlContents);

      // convert JSON object to array, then parse through for the property's needed details
      $array = json_decode($jsonContents,TRUE);

      // First check to see if the result of the API call returned valid results, then assign those to a new array.
      if(array_key_exists('response', $array)) {
        $resultArray = $array['response']['results']['result'];

        // Check to see if each of the desired details are available, and assign them if so.
        if(array_key_exists('yearBuilt', $resultArray)) {
          $propertyDetails['yearBuilt'] = $resultArray['yearBuilt'];
        } else {
          $propertyDetails['yearBuilt'] = "N/A";
        }

        if(array_key_exists('bathrooms', $resultArray)) {
          $propertyDetails['bathrooms'] = $resultArray['bathrooms'];
        } else {
          $propertyDetails['bathrooms'] = "N/A";
        }

        if(array_key_exists('bedrooms', $resultArray)) {
          $propertyDetails['bedrooms'] = $resultArray['bedrooms'];
        } else {
          $propertyDetails['bedrooms'] = "N/A";
        }

        if(array_key_exists('zestimate', $resultArray)) {
          if(!is_array($resultArray['zestimate']['amount'])) {
            $propertyDetails['zestimate'] = $resultArray['zestimate']['amount'];
          } else {
            $propertyDetails['zestimate'] = "N/A";
          }
        } else {
          $propertyDetails['zestimate'] = "N/A";
        }
      } else {
        // Some type of error occured in the API call, 
        // so just assign them all 'N/A' so the loop in HouseController can keep going.
        $propertyDetails['yearBuilt'] = "N/A";
        $propertyDetails['bathrooms'] = "N/A";
        $propertyDetails['bedrooms'] = "N/A";
        $propertyDetails['zestimate'] = "N/A";
      }
      return $propertyDetails;
    }
}
