<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendMailJob;


class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        // Define the validation rules
        $rules = [
            'amount' => 'required', // Assuming 'isAdmin' should be a boolean
            'due_date' => 'required|date|date_format:Y-m-d',
        ];
    
        // Create a validator instance
        $validator = Validator::make($request->all(), $rules);
    
        // Check if validation fails
        if ($validator->fails()) {
            // Return a response with validation errors
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity status code for validation errors
        }
    
        // Check if a clientId is provided
        if ($request->has('client_id')) {
            $client = Client::find($request->input('client_id'));
    
            if (!$client) {
                return response()->json(['message' => 'Client not found'], 404);
            }
        } else {
            // Check if a client with the same email and mobile number already exists
            $client = Client::where('email_address', $request->email_address)
                ->where('mobile_number', $request->mobile_number)
                ->first();
    
            if (!$client) {
                // Client does not exist, create a new client
                $client = Client::create([
                    'full_name' => $request->full_name,
                    'mobile_number' => $request->mobile_number,
                    'email_address' => $request->email_address,
                ]);
            }
        }
    
        // Create a new invoice associated with the client
        $invoice = Invoice::create([
            'client_id' => $client->id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            // Add other invoice fields as needed
        ]);
    
        // Dispatch the job to send an email
        $details = [
            'title' => "New Invoice",
            'body' => "Your invoice has been created successfully with Amount: " . $request->amount,
            'mail' => $request->email_address,
            'name' => $request->full_name,
        ];
    
        SendMailJob::dispatch($details);
    
        // Return a response with the created invoice data
        return response()->json(['invoice' => $invoice], 200);
    }
    

    public function getInvoicesByPhoneOrEmail(Request $request)
    {
        $userInput = $request->input('search'); // This should contain either phone or email
        
        $client = Client::where('mobile_number', $userInput)
            ->orWhere('email_address', $userInput)
            ->first();
        
        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }
        
        $invoices = $client->invoices;
        
        return response()->json(['invoices' => $invoices], 200);
    }
    public function getInvoiceById($id)
{
    $invoice = Invoice::find($id);
    
    if (!$invoice) {
        return response()->json(['message' => 'Invoice not found'], 404);
    }
    
    return response()->json(['invoice' => $invoice], 200);
}

public function update(Request $request, $id)
{
    // Find the invoice by ID
    $invoice = Invoice::find($id);

    if (!$invoice) {
        return response()->json(['message' => 'Invoice not found'], 404);
    }

    // Get the associated client
    $client = $invoice->client;

    // Update invoice fields if they are present in the request
    if ($request->has('amount')) {
        $invoice->update(['amount' => $request->input('amount')]);
    }

    if ($request->has('due_date')) {
        $invoice->update(['due_date' => $request->input('due_date')]);
    }

    // Update client fields if they are present in the request
    if ($request->has('full_name')) {
        $client->update(['full_name' => $request->input('full_name')]);
    }

    if ($request->has('mobile_number')) {
        $client->update(['mobile_number' => $request->input('mobile_number')]);
    }

    if ($request->has('email_address')) {
        $client->update(['email_address' => $request->input('email_address')]);
    }

    return response()->json(['message' => 'Data updated successfully'], 200);
}


public function destroy($id)
{
    // Check if the invoice exists
    $invoice = Invoice::find($id);
    
    if (!$invoice) {
        return response()->json(['message' => 'Invoice not found'], 404);
    }
    
    // Delete the invoice
    $invoice->delete();
    
    return response()->json(['message' => 'Invoice deleted successfully'], 200);
}


}
