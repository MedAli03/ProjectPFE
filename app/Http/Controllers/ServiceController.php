<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
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
