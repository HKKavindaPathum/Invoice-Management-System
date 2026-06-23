<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display the stock handling interface.
     */
    public function index()
    {
        // Load products with their categories
        $products = Product::with('category')->get();
        return view('stock.index', compact('products'));
    }

    /**
     * Update product stocks in bulk.
     */
    public function update(Request $request)
    {
        $request->validate([
            'adjustments' => 'required|array|min:1',
            'adjustments.*.product_id' => 'required|exists:products,id',
            'adjustments.*.type' => 'required|in:add,remove,set',
            'adjustments.*.quantity' => 'required|integer|min:0',
        ], [
            'adjustments.required' => 'Please add at least one product to update.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->adjustments as $adjustment) {
                    $product = Product::findOrFail($adjustment['product_id']);
                    $qty = intval($adjustment['quantity']);
                    $type = $adjustment['type'];

                    if ($type === 'add') {
                        $product->increment('quantity', $qty);
                    } elseif ($type === 'remove') {
                        if ($product->quantity < $qty) {
                            throw new \Exception("Cannot remove {$qty} units from '{$product->name}' because only {$product->quantity} are in stock.");
                        }
                        $product->decrement('quantity', $qty);
                    } elseif ($type === 'set') {
                        $product->quantity = $qty;
                        $product->save();
                    }
                }
            });

            return redirect()->route('stock.index')->with('success', 'Stock updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('stock.index')
                ->withInput()
                ->withErrors(['stock' => $e->getMessage()]);
        }
    }
}
