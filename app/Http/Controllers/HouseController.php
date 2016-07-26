<?php

namespace App\Http\Controllers;

use App\House;
use GuzzleHttp\Client;
// use HttpClient\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests;

class HouseController extends ClientController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return House::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $house = House::findOrFail($id);
    }

    public function addDetails($id)
    {
        $house = House::findOrFail($id);
        $houseDetails = $this->obtainDetails($house);
        $house->zestimate = $houseDetails['zestimate'];
        $house->yearBuilt = $houseDetails['yearBuilt'];
        $house->bathrooms = $houseDetails['bathrooms'];
        $house->bedrooms = $houseDetails['bedrooms'];
        $house->save();
        return $house;
    }

    public function addAllDetails()
    {
      $houses = House::all();
      foreach($houses as $house) {
        if(is_null($house->zestimate)) {
            $this->addDetails($house->id);
        }
      }
      // return 'this is done';
    }
}
