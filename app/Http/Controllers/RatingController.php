<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ratings = Rating::all();
        return response()->json($ratings);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rating = Rating::find($id);
        if (!$rating) {
            return response()->json([
                'message' => 'Rating not found'
            ], 404);
        }

        return response()->json($rating);
    }

    public function rate(Request $request, $id_pressing)
    {
        $validatedData = $request->validate([
            'value' => 'required|numeric|min:1|max:5'
        ]);

        // get the authenticated client user
        $client = auth()->user();

        // find the pressing to rate
        $pressing = User::where('id', $id_pressing)->where('role', 'pressing')->firstOrFail();

        // check if the client has already rated the pressing
        $existingRating = Rating::where('client_id', $client->id)->where('pressing_id', $pressing->id)->first();
        if ($existingRating) {
            return response()->json(['message' => 'You have already rated this pressing'], 400);
        }

        // create the new rating
        $rating = new Rating([
            'value' => $validatedData['value']
        ]);
        $rating->client()->associate($client);
        $rating->pressing()->associate($pressing);
        $rating->save();


        
        // $pressing = User::find($id_pressing);
        // if ($pressing) {
        //     $averageRating = Rating::calculateAverageRating($id_pressing);
        //     $pressing->average_rating = $averageRating;
        //     $pressing->save();
        // }

        return response()->json($rating, 201);
    }

    public function updateRating(Request $request, $pressing_id)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Check if the user is a client
        if ($user->role != 'client') {
            return response()->json(['error' => 'Only clients can update their own rating'], 401);
        }

        // Find the pressing with the given ID
        $pressing = User::where('id', $pressing_id)->where('role', 'pressing')->firstOrFail();

        // Check if the client has already rated this pressing
        $existingRating = Rating::where('client_id', $user->id)->where('pressing_id', $pressing->id)->first();

        if (!$existingRating) {
            return response()->json(['error' => 'You have not rated this pressing before'], 400);
        }

        // Update the rating
        $existingRating->update([
            'rating' => $request->input('rating')
        ]);

        // $pressing = User::find($pressing_id);
        // if ($pressing) {
        //     $averageRating = Rating::calculateAverageRating($pressing_id);
        //     $pressing->average_rating = $averageRating;
        //     $pressing->save();
        // }


        return response()->json($existingRating);
    }

    public function deleteRate(Request $request, $id)
    {
        $rate = Rating::findOrFail($id);

        // check if the authenticated user is the owner of the rate
        if ($rate->user_id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rate->delete();

        return response()->json(null, 204);
    }

    public function ratingAverage()
{
    $pressings = User::where('role', 'pressing')->get();

    $ratings = Rating::whereIn('pressing_id', $pressings->pluck('id'))->get();

    $averages = $ratings->groupBy('pressing_id')->map(function ($group) {
        return $group->avg('value');
    });

    return response()->json($averages);
}


}
