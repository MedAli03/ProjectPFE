<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * 
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        $ratings = Rating::all();
        return response()->json($ratings);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $rating = Rating::find($id);
        if (!$rating) {
            return response()->json([
                'message' => 'Rating not found'
            ], 404);
        }

        return response()->json($rating);
    }
    
    public function deleteRate($id){
        $rate = Rating::findOrFail($id);

        // check if the authenticated user is the owner of the rate
        if ($rate->user_id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rate->delete();

        return response()->json(null, 204);
    }


    public function pressingGetRatings(Request $request){
        $pressing = $request->user();

        $ratings = Rating::with('client')->where('pressing_id', $pressing->id)->orderBy('created_at', 'desc')->get();

        return response()->json($ratings);
    }
    

    public function pressingAverageRating(Request $request)
{
    $pressing = $request->user();

    $averageRating = Rating::where('pressing_id', $pressing->id)->avg('value');
    $numRatings = Rating::where('pressing_id', $pressing->id)->count();

    return response()->json([
        'average_rating' => $averageRating,
        'num_ratings' => $numRatings
    ]);
}

    

}
