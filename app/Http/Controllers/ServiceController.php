<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * @OA\Get(
     *     path="api/pressings/service",
     *     summary="List all services",
     *     description="Returns a list of all services.",
     *     operationId="getServices",
     *     tags={"Pressing"},
     *     @OA\Response(
     *         response=200,
     *         description="List of services",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="services"

     *             )
     *         )
     *     )
     * )
     */

    public function index(){
        $services = Service::all();
        return response()->json(['services' => $services]);
    }

    public function getServicesForPressing($pressing_id){
        // Retrieve the services available at the given pressing
        $services = Service::where('pressing_id', $pressing_id)->get();

        // Check if the services are empty
        if ($services->isEmpty()) {
            return response()->json([
                'message' => 'No services available at the given pressing'
            ], 404);
        }

        // Return the services to the client user
        return response()->json([
            'services' => $services
        ]);
    }

    /**
     * @OA\Get(
     *     path="api/pressings/service/all",
     *     summary="List services available at current user's pressing",
     *     description="Returns a list of services available at the current user's pressing.",
     *     operationId="getServicesForCurrentUserPressing",
     *     tags={"Pressing"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of services",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="services"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No services available",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="No services available"
     *             )
     *         )
     *     )
     * )
     */

    public function getServicesForCurrentUserPressing(Request $request) {
        // Get the current user's pressing_id
        $pressing_id = $request->user()->id;

        // Retrieve the services available at the current user's pressing
        $services = Service::where('pressing_id', $pressing_id)->get();

        // Check if the services are empty
        if ($services->isEmpty()) {
            return response()->json([
                'message' => 'No services available'
            ], 404);
        }

        // Return the services to the pressing user
        return response()->json([
            'services' => $services
        ]);
    }

    /**
     * @OA\Post(
     *     path="api/pressings/service",
     *     summary="Create a new service",
     *     description="Creates a new service with the given name.",
     *     operationId="createService",
     *     tags={"Pressing"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Service object that needs to be created"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service created successfully",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input data",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'is_available' => 'in:true,false'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $service = new Service;
        $service->name = $request->name;
        // $service->is_available = $request->has('is_available') ? $request->is_available : false;
        $service->save();
    
        return response()->json(['message' => 'Service created successfully'], 201);
    }


    /**
     * @OA\Put(
     *     path="api/pressings/service/{id}",
     *     summary="Update a service",
     *     description="Updates a service with the given ID.",
     *     operationId="updateService",
     *     tags={"Pressing"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the service to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="New service data",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Service updated successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Service not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 additionalProperties=@OA\Property(type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'is_available' => 'in:true,false,null'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $service = Service::find($id);
    
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }
    
        $service->name = $request->name;
        // $service->is_available = $request->has('is_available') ? $request->is_available : false;
        $service->save();
    
        return response()->json(['message' => 'Service updated successfully'], 200);
    }


    /**
     * @OA\Delete(
     *     path="api/pressings/service/{id}",
     *     summary="Delete a service by ID",
     *     description="Deletes a service from the database by its ID",
     *     operationId="destroyService",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the service to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Service deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Service not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */


    public function destroy($id){
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        $service->delete();
        return response()->json(['message' => 'Service deleted successfully'], 200);
    }
}
