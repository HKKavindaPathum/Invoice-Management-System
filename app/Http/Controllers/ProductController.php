<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    //Display all products
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return view('products.show', compact('product'));
    }


    //Show the form for creating a new product
    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact( 'categories'));
    }


    //Store a new product
    public function store(Request $request)
    {
        try {
            //Validate input
            $request->validate([
                'name' => 'required|string|max:255|unique:products,name,NULL,id,category_id,' . $request->category_id,
                'category_id' => 'required|exists:categories,id',
                'unit_price' => 'required|numeric',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
            ], [
                'name.unique' => 'The product with this name already exists in this category.',
            ]);
    
            //Initialize image variable to NULL
            $imagePath = null;
    
            //Check if an image is uploaded
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
    
                $filename = time().'.'.$extension;
                $path = 'uploads/product/';
                $file->move($path, $filename);
    
                //Set the image path
                $imagePath = $path . $filename;
            }
    
            //Create Product
            Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'unit_price' => $request->unit_price,
                'description' => $request->description,
                'image' => $imagePath,  //Set to NULL if no image uploaded
            ]);
    
            return redirect()->route('products.index')->with('success', 'Product added successfully!');
    
        } catch (\Exception $e) {
            //Handle any exception (including QueryException)
            return redirect()->route('products.create')->with('error', 'The product already exists in this category!');
        }
    }
    
    
    
    // Search for product
    public function search(Request $request)
    {
        $search = $request->get('search');
            
        // If a search term is provided, filter categories by name
        if ($search) {
            $products = Product::where('name', 'like', '%' . $search . '%')->get();
        } else {
            $products = Product::all();
        }
    
        return view('products.index', compact('products'));
    }

    // Show the form for editing a product
    public function edit($product_id)
    {
        $product = Product::findOrFail($product_id); // Fetch the product by ID
        $categories = Category::all(); // Fetch all categories
        return view('products.edit', compact('product', 'categories')); // Pass product and categories to the view
    }

    // Update an existing product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
        ]);

        $product = Product::findOrFail($id); // Fetch the product by ID

        // Default to the existing image path
        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $path = 'uploads/product/';
            $file->move($path, $filename);

            // Delete the old image if it exists
            if (File::exists($product->image)) {
                File::delete($product->image);
            }

            // Update image path
            $imagePath = $path . $filename;
        }

        // Update Product
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_price' => $request->unit_price,
            'description' => $request->description,
            'image' => $imagePath, // Use the existing image if no new image is uploaded
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
    
        // Check if the product has an image and delete it from the storage
        if (File::exists($product->image)) {
            File::delete($product->image);
        }
    
        // Delete the product from the database
        $product->delete();
    
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
    


}
