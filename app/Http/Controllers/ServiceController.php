<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{


    public function getAvailableServices()
    {
        $services = Service::where('is_available', true)->orderBy('name')->get();
        return response()->json($services);
    }

    public function getServicesNotAvailable()
    {
        $services = Service::where('is_available', false)->orderBy('name')->get();
        return response()->json($services);
    }
    

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'is_available' => 'sometimes|boolean'
        ]);
    
        $service = new Service;
        $service->name = $validatedData['name'];
        $service->is_available = $validatedData['is_available'] ?? true;
        $service->save();
    
        return response()->json($service, 201);
    }

    public function storeFromPressing(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'is_available' => 'sometimes|boolean'
        ]);
    
        $service = new Service;
        $service->name = $validatedData['name'];
        $service->is_available = $validatedData['is_available'] ?? false;
        $service->save();
    
        return response()->json($service, 201);
    }
    

    public function makeAvailable($id) {
        $service = Service::find($id);
    
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
    
        $service->is_available = true;
        $service->save();
    
        return response()->json(['message' => 'Service updated successfully'], 200);
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


    public function destroy($id){
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        $service->delete();
        return response()->json(['message' => 'Service deleted successfully'], 200);
    }
}
