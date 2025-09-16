<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount('invoices')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $email = $this->generateUniqueEmail();
    
        return view('clients.create', compact('email'));
    }
    
    //Generate a unique email in the format numbers@test.com
    public function generateUniqueEmail(): string
    {
        do {
            $numericPart = mt_rand(100000000, 999999999);
            $email = $numericPart . '@test.com';
    
            $isExist = Client::where('email', $email)->exists();
        } while ($isExist); 
    
        return $email;
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:10',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'country' => 'nullable|string|max:50',
            'passport_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:100',
            'mobile_no' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:255|unique:clients,email',
            'note' => 'nullable|string',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')->with('success', 'Client added successfully.');      
    }

    //Search for client
    public function search(Request $request)
    {
        $search = $request->get('search');
            
        if ($search) {
            $clients = Client::where('first_name', 'like', '%' . $search . '%')->get();
        } else {
            $clients = Client::all();
        }
    
        return view('clients.index', compact('clients'));
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:10',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'country' => 'nullable|string|max:50',
            'passport_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:100',
            'mobile_no' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:255|unique:clients,email,' . $id,
            'note' => 'nullable|string',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
