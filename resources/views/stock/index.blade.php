@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="max-w-7xl mx-auto p-4 space-y-6" 
     x-data="{
        search: '',
        openDropdown: false,
        products: {{ $products->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'image' => $p->image ? asset($p->image) : null,
                'quantity' => $p->quantity,
                'unit_price' => $p->unit_price,
                'category_name' => $p->category->name ?? 'General'
            ];
        })->toJson() }},
        adjustments: [],
        
        get filteredProducts() {
            let list = this.products;
            if (this.search.trim() !== '') {
                list = list.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
            }
            // Exclude already added products
            return list.filter(p => !this.adjustments.some(a => a.id === p.id));
        },
        
        addProduct(product) {
            this.adjustments.push({
                id: product.id,
                name: product.name,
                image: product.image,
                category_name: product.category_name,
                current_stock: product.quantity,
                quantity: 1,
                type: 'add' // 'add', 'remove', 'set'
            });
            this.search = '';
            this.openDropdown = false;
        },
        
        removeAdjustment(index) {
            this.adjustments.splice(index, 1);
        },
        
        calculateNewStock(current, qty, type) {
            qty = parseInt(qty);
            if (isNaN(qty) || qty < 0) qty = 0;
            current = parseInt(current) || 0;
            
            if (type === 'add') return current + qty;
            if (type === 'remove') return current - qty;
            if (type === 'set') return qty;
            return current;
        },
        
        hasError(adj) {
            return this.calculateNewStock(adj.current_stock, adj.quantity, adj.type) < 0;
        },
        
        get canSubmit() {
            if (this.adjustments.length === 0) return false;
            // Ensure no negative stock calculations
            return !this.adjustments.some(adj => this.hasError(adj));
        }
     }">

    <div class="bg-white border border-slate-100/80 shadow-[0_2px_12px_rgba(0,0,0,0.015)] rounded-2xl p-6">
        
        <!-- Header -->
        <div class="border-b border-slate-100 pb-4 mb-6">
            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wider">Stock Handle (Bulk Update)</h2>
            <p class="text-xs text-slate-500 mt-1">Select multiple products to adjust or set their stock quantities simultaneously.</p>
        </div>

        <!-- Success Toast -->
        @if(session('success'))
        <div id="successToast" class="fixed top-20 right-6 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-2xl z-50 opacity-0 transform translate-y-4 transition-all duration-500 flex items-center gap-2 text-sm border border-slate-800">
            <span class="text-emerald-400 font-bold">✓</span>
            {{ session('success') }}
        </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="mb-6">
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-xl text-sm" role="alert">
                <h3 class="font-bold mb-1">Please fix the following issues:</h3>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Product Search & Selection Section -->
        <div class="mb-8 max-w-2xl relative">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Search or Select Product</label>
            <div class="relative">
                <input 
                    type="text" 
                    x-model="search" 
                    @focus="openDropdown = true"
                    @click.away="setTimeout(() => openDropdown = false, 200)"
                    placeholder="Click to browse or type to search products..." 
                    class="w-full px-4 py-2.5 pl-10 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition duration-200 text-sm">
                
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                
                <!-- Clear Search -->
                <button 
                    type="button" 
                    x-show="search.length > 0"
                    @click="search = ''"
                    class="absolute right-3 top-3 text-slate-450 hover:text-slate-650 transition duration-200"
                    x-cloak>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Search Results Dropdown -->
            <div x-show="openDropdown && filteredProducts.length > 0" 
                 class="absolute z-10 w-full bg-white border border-slate-200/80 mt-1.5 rounded-xl shadow-xl max-h-64 overflow-y-auto divide-y divide-slate-50 transition duration-200"
                 x-cloak>
                <template x-for="product in filteredProducts" :key="product.id">
                    <button type="button" 
                            @click="addProduct(product)" 
                            class="w-full flex items-center justify-between px-4 py-3 hover:bg-slate-50/80 text-left transition">
                        <div class="flex items-center gap-3">
                            <img :src="product.image" class="w-9 h-9 object-cover rounded-lg border border-slate-100" x-show="product.image">
                            <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 text-xs font-semibold" x-show="!product.image">
                                N/A
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm" x-text="product.name"></div>
                                <div class="text-xs text-slate-500" x-text="product.category_name"></div>
                            </div>
                        </div>
                        <div class="text-xs font-semibold px-2.5 py-1.5 bg-slate-100 text-slate-600 rounded-full" x-text="'Stock: ' + product.quantity"></div>
                    </button>
                </template>
            </div>
            
            <div x-show="openDropdown && filteredProducts.length === 0" 
                 class="absolute z-10 w-full bg-white border border-slate-200 mt-1.5 rounded-xl shadow-lg p-4 text-center text-sm text-slate-400"
                 x-cloak>
                No available products found.
            </div>
        </div>

        <!-- Adjustments Form -->
        <form action="{{ route('stock.update') }}" method="POST">
            @csrf
            
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-hidden border border-slate-100 rounded-xl">
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-slate-50/75 border-b border-slate-100 text-slate-500 uppercase text-xs font-bold tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 font-semibold">Product Name</th>
                            <th class="py-3.5 px-4 font-semibold text-center w-36">Current Stock</th>
                            <th class="py-3.5 px-4 font-semibold text-center w-40">Adjustment Operation</th>
                            <th class="py-3.5 px-4 font-semibold text-center w-36">Quantity</th>
                            <th class="py-3.5 px-4 font-semibold text-center w-36">New Stock</th>
                            <th class="py-3.5 px-4 font-semibold text-center w-16">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <!-- Empty State -->
                        <tr x-show="adjustments.length === 0">
                            <td colspan="6" class="py-12 text-center text-slate-400 font-medium">
                                No products selected. Use the search input above to select products and adjust their stock.
                            </td>
                        </tr>
                        
                        <!-- Row template -->
                        <template x-for="(adj, index) in adjustments" :key="adj.id">
                            <tr class="hover:bg-slate-50/30 transition duration-150" :class="hasError(adj) ? 'bg-rose-50/20 hover:bg-rose-50/30' : ''">
                                <td class="py-4 px-4 font-semibold text-slate-900">
                                    <input type="hidden" :name="'adjustments[' + index + '][product_id]'" :value="adj.id">
                                    <div class="flex items-center gap-3">
                                        <img :src="adj.image" class="w-10 h-10 object-cover rounded-xl border border-slate-100" x-show="adj.image">
                                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 text-xs font-semibold" x-show="!adj.image">
                                            N/A
                                        </div>
                                        <div>
                                            <span x-text="adj.name"></span>
                                            <div class="text-xs text-slate-450 font-normal mt-0.5" x-text="adj.category_name"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center text-slate-600 font-semibold" x-text="adj.current_stock"></td>
                                <td class="py-4 px-4 text-center">
                                    <select :name="'adjustments[' + index + '][type]'" 
                                            x-model="adj.type"
                                            class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white font-medium">
                                        <option value="add">Add (+)</option>
                                        <option value="remove">Remove (-)</option>
                                        <option value="set">Set (=)</option>
                                    </select>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <input type="number" 
                                           :name="'adjustments[' + index + '][quantity]'" 
                                           x-model.number="adj.quantity" 
                                           min="0" 
                                           required
                                           class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs text-center focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white font-bold">
                                </td>
                                <td class="py-4 px-4 text-center font-bold">
                                    <div class="flex flex-col items-center">
                                        <span :class="hasError(adj) ? 'text-rose-600' : 'text-emerald-600'" 
                                              x-text="calculateNewStock(adj.current_stock, adj.quantity, adj.type)"></span>
                                        <span class="text-[10px] text-rose-500 font-semibold mt-0.5" x-show="hasError(adj)" x-cloak>
                                            Stock cannot be negative
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button type="button" 
                                            @click="removeAdjustment(index)" 
                                            class="text-slate-400 hover:text-rose-600 transition duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4">
                <div x-show="adjustments.length === 0" class="py-8 text-center text-slate-400 text-sm">
                    No products selected. Use the search input above to select products.
                </div>
                
                <template x-for="(adj, index) in adjustments" :key="adj.id">
                    <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-4 space-y-3" :class="hasError(adj) ? 'border-rose-200 bg-rose-50/10' : ''">
                        <input type="hidden" :name="'adjustments[' + index + '][product_id]'" :value="adj.id">
                        
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <img :src="adj.image" class="w-10 h-10 object-cover rounded-xl border border-slate-100" x-show="adj.image">
                                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 text-xs font-semibold" x-show="!adj.image">
                                    N/A
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm" x-text="adj.name"></div>
                                    <div class="text-xs text-slate-500" x-text="adj.category_name"></div>
                                </div>
                            </div>
                            <button type="button" 
                                    @click="removeAdjustment(index)" 
                                    class="text-slate-400 hover:text-rose-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="block text-slate-500 font-semibold mb-1">Current Stock</span>
                                <span class="text-sm font-bold text-slate-800" x-text="adj.current_stock"></span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-semibold mb-1">New Stock</span>
                                <span class="text-sm font-bold" 
                                      :class="hasError(adj) ? 'text-rose-600' : 'text-emerald-600'" 
                                      x-text="calculateNewStock(adj.current_stock, adj.quantity, adj.type)"></span>
                                <span class="block text-[10px] text-rose-500 font-semibold mt-0.5" x-show="hasError(adj)" x-cloak>
                                    Stock cannot be negative
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 mb-1 uppercase tracking-wider">Operation</label>
                                <select :name="'adjustments[' + index + '][type]'" 
                                        x-model="adj.type"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white font-medium">
                                    <option value="add">Add (+)</option>
                                    <option value="remove">Remove (-)</option>
                                    <option value="set">Set (=)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 mb-1 uppercase tracking-wider">Quantity</label>
                                <input type="number" 
                                       :name="'adjustments[' + index + '][quantity]'" 
                                       x-model.number="adj.quantity" 
                                       min="0" 
                                       required
                                       class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition bg-white font-bold">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Form Submissions -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 border-t border-slate-100 pt-6" x-show="adjustments.length > 0">
                <button type="button" 
                        @click="adjustments = []" 
                        class="px-6 py-2.5 border border-slate-200 rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition text-sm font-semibold w-full sm:w-auto text-center">
                    Clear All
                </button>
                <button type="submit" 
                        :disabled="!canSubmit"
                        class="px-6 py-2.5 border border-transparent rounded-xl shadow-md text-white bg-blue-600 hover:bg-blue-700 shadow-blue-500/20 transition text-sm font-bold w-full sm:w-auto text-center disabled:opacity-50 disabled:cursor-not-allowed">
                    Save Stock Updates
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toast = document.getElementById('successToast');
        if (toast) {
            toast.classList.remove('opacity-0', 'translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-4');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 5000);
        }
    });
</script>
@endpush
