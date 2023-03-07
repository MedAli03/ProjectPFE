<?php

namespace App\Http\Controllers;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
     public function store(Request $request)
     {
         $rating = Rating::create($request->all());
         return response()->json(['rating' => $rating], 201);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'value' => 'numeric',
            'client_id' => 'exists:clients,id',
            'pressing_id' => 'exists:pressings,id',
        ]);

        $rating = Rating::find($id);
        if (!$rating) {
            return response()->json([
                'message' => 'Rating not found'
            ], 404);
        }

        $rating->update($validatedData);

        return response()->json([
            'message' => 'Rating updated successfully',
            'data' => $rating
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rating = Rating::find($id);
        if (!$rating) {
            return response()->json([
                'message' => 'Rating not found'
            ], 404);
        }

        $rating->delete();

        return response()->json([
            'message' => 'Rating deleted successfully'
        ]);
    }
}
