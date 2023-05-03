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

    public static function calculateAverageRating($pressing_id)
    {
        $totalRating = Rating::where('pressing_id', $pressing_id)->sum('value');
        $numberOfRatings = User::where('id', $pressing_id)->value('number_of_raters');
    
        if ($numberOfRatings > 0) {
            return $totalRating / $numberOfRatings;
        } else {
            return 0;
        }
    }

    /**
     * @OA\Post(
     *     path="/api/client/rating/rate/{id}",
     *     tags={"Client"},
     *     summary="Rate a pressing",
     *     description="Creates a new rating for the specified pressing by the authenticated client user",
     *     operationId="ratePressing",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the pressing to rate",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Rating data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="value",
     *                 type="number",
     *                 format="float",
     *                 description="Rating value (must be between 1 and 5)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rating created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You have already rated this pressing")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are not authorized to access this resource.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pressing not found")
     *         )
     *     )
     * )
     */

    
    public function rate(Request $request, $id_pressing){
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

        // update the pressing's average rating and number of raters
        $pressing->number_of_raters += 1;
        $pressing->average_rating = Rating::calculateAverageRating($id_pressing);
        $pressing->save();

        return response()->json($rating, 201);
    }

    /**
     * Update the rating of a pressing for the authenticated client.
     *
     * @OA\Put(
     *      path="/api/client/rating/update/{id}",
     *      tags={"Client"},
     *      summary="Update the rating of a pressing for the authenticated client.",
     *      operationId="updateRating",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the pressing to rate.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="rating",
     *                      type="integer",
     *                      example="4",
     *                      description="The new rating value."
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="value", type="integer"),
     *              @OA\Property(property="client_id", type="integer"),
     *              @OA\Property(property="pressing_id", type="integer"),
     *              @OA\Property(property="created_at", type="string"),
     *              @OA\Property(property="updated_at", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string")
     *          )
     *      ),
     * )
     */

    public function updateRating(Request $request, $pressing_id){
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

        return response()->json($existingRating);
    }

    /**
     * Delete a rating by ID.
     *
     * @OA\Delete(
     *     path="/api/client/rating/{id}",
     *     tags={"Client"},
     *     summary="Delete a rating",
     *     operationId="deleteRate",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the rating",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Rating deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rating not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Rating not found")
     *         )
     *     )
     * )
     */
    public function deleteRate($id){
        $rate = Rating::findOrFail($id);

        // check if the authenticated user is the owner of the rate
        if ($rate->user_id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rate->delete();

        return response()->json(null, 204);
    }


    // public function getRating($id_pressing) {
    //     // find the authenticated pressing
    //     $pressing = auth()->user();
    
    //     // check if the pressing has any ratings
    //     $ratings = $pressing->pressing()->where('client_id', '!=', null)->get();
    //     if ($ratings->isEmpty()) {
    //         return response()->json(['message' => 'No ratings found for this pressing'], 404);
    //     }
    
    //     // filter the ratings to get only those for the current pressing
    //     $pressingRatings = $ratings->filter(function ($rating) use ($id_pressing) {
    //         return $rating->client->role == 'client' && $rating->pressing_id == $id_pressing;
    //     });
    
    //     // check if there are any ratings for the pressing
    //     if ($pressingRatings->isEmpty()) {
    //         return response()->json(['message' => 'No ratings found for this pressing'], 404);
    //     }
    
    //     // calculate the pressing's average rating
    //     $averageRating = $pressingRatings->avg('value');
    
    //     return response()->json(['average_rating' => $averageRating, 'number_of_raters' => $pressing->number_of_raters], 200);
    // }
    

}
