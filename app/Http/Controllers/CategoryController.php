<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    //Display all categories
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function show($id)
    {
        $categories = Category::all();

        $category = Category::findOrFail($id);

        return view('categories.show', compact('category'));
    }


    //Store a new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    //Search for categories
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $categories = Category::where('name', 'like', '%' . $search . '%')->get();
        } else {
            $categories = Category::all();
        }

        return view('categories.index', compact('categories'));
    }

    //Update a category in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id . '|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    //Delete a category from the database
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
