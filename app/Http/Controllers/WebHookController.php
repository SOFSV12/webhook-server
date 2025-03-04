<?php

namespace App\Http\Controllers;

use App\Models\Joke;
use App\Models\Business;
use App\Jobs\SendJokeWebhookJob;
use App\Http\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\SendJokeRequest;

class WebHookController extends Controller
{
    use ApiResponseTrait;

    public function sendJoke(SendJokeRequest $request)
    {
        try {
            $apiKey = $request->header('X-API-KEY');
            //check if the api key is not set
            if (!$apiKey) {
                return $this->errorResponse('please provide an api key', 401);
            }

            $business = Business::retreiveBusiness($apiKey);

            if (!$business) {
                return $this->errorResponse('Invalid API key', 403);
            }

            $joke = $this->fetchJoke();

            dispatch(new SendJokeWebhookJob($business, $joke, $request->name))->delay(now()->addSeconds(10));

            return $this->successResponse("Joke delivery scheduled in 10 seconds", 200);

        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while sending the joke', 500, $e->getMessage());
        }
    }

    /**
     * GET a joke from Chuck Norris API.
     */
    private function fetchJoke(): string
    {
        $response = Http::get('https://api.chucknorris.io/jokes/random');

        if ($response->successful()) {
            return $response->json()['value'];
        }

        return "Joke service is currently unavailable.";
    }

    public function retreiveMyJokes()
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
}
