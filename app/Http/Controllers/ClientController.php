<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;
class ClientController extends Controller
{
    public function GetAllClientsWithPagination(Request $request)
    {
        // Query builder for clients
        $query = Client::query();

        // Apply filters if provided
        if ($request->has('full_name')) {
                $query->where('full_name', 'like', '%' . $request->input('full_name') . '%');

        }
    
        if ($request->has('mobile_number')) {

            $query->where('mobile_number', 'like', '%' . $request->input('mobile_number') . '%');
        }
    
        if ($request->has('email_address')) {

            $query->where('email_address', 'like', '%' . $request->input('email_address') . '%');
        }
    
        // Paginate the results
        $perPage = $request->input('per_page', 10); // Number of results per page, default is 10
        $clients = $query->paginate($perPage);
    
        return response()->json($clients);
    }
}
