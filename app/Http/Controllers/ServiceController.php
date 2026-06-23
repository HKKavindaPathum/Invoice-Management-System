<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    // Display all services
    public function index()
    {
        $services = Service::with('category')->get();
        return view('services.index', compact('services'));
    }

    public function show($id)
    {
        $service = Service::with('category')->findOrFail($id);
        return view('services.show', compact('service'));
    }

    // Show the form for creating a new service
    public function create()
    {
        $categories = Category::all();
        return view('services.create', compact('categories'));
    }

    // Store a new service
    public function store(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'name' => 'required|string|max:255|unique:services,name,NULL,id,category_id,' . $request->category_id,
                'category_id' => 'required|exists:categories,id',
                'unit_price' => 'required|numeric',
                'description' => 'nullable|string',
            ], [
                'name.unique' => 'The service with this name already exists in this category.',
            ]);

            Service::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'unit_price' => $request->unit_price,
                'description' => $request->description,
            ]);

            return redirect()->route('services.index')->with('success', 'Service added successfully!');

        } catch (\Exception $e) {
            return redirect()->route('services.create')->with('error', 'The service already exists in this category!');
        }
    }

    // Search for service
    public function search(Request $request)
    {
        $search = $request->get('search');
            
        if ($search) {
            $services = Service::where('name', 'like', '%' . $search . '%')->with('category')->get();
        } else {
            $services = Service::with('category')->get();
        }

        return view('services.index', compact('services'));
    }

    // Show the form for editing a service
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $categories = Category::all();
        return view('services.edit', compact('service', 'categories'));
    }

    // Update an existing service
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $service = Service::findOrFail($id);

        $service->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_price' => $request->unit_price,
            'description' => $request->description,
        ]);

        return redirect()->route('services.index')->with('success', 'Service updated successfully!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully!');
    }
}
