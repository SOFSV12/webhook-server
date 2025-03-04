<?php

namespace App\Http\Controllers;

use App\Models\Joke;
use App\Models\Business;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreBusinessRequest;

class BusinessController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBusinessRequest $request)
    {
        try {
            //create a business 
            $apiKey = Str::random(32);
            
            $business = Business::create([
                'id'       => Str::uuid(),
                'name'     => $request->name,
                'hook_url' => $request->hook_url,
                'api_key'  => Hash::make($apiKey),
            ]);

            //create an array assign to variable $data
            $data             = [];
            $data['id']       =  $business->id;
            $data['business'] =  $business->name;
            $data['hook_url'] =  $business->hook_url;
            $data['api_key']  =  $apiKey;
            
            return $this->successResponse($data, 'Business Created', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while creating the business', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function showJokes($apiKey)
    {
        try {
            $apiKey = request()->header('X-API-KEY');
            //check if the api key is not set
            if (!$apiKey) {
                return $this->errorResponse('please provide an api key', 401);
            }

            $business = Business::retreiveBusiness($apiKey);

            if (!$business) {
                return $this->errorResponse('Invalid API key', 403);
            }

            $jokes = Joke::where('business_id', $business->id)->get();

            return $this->successResponse($jokes, 200);

        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching the jokes', 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
