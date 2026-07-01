<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\ProductInvoice;
use Carbon\Carbon;

class ExampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create 10 Hotel & Restaurant Categories
        $categoriesData = [
            ['name' => 'Breakfast'],
            ['name' => 'Main Courses'],
            ['name' => 'Beverages'],
            ['name' => 'Desserts'],
            ['name' => 'Short Eats & Snacks'],
            ['name' => 'Soups & Salads'],
            ['name' => 'Side Dishes'],
            ['name' => 'Traditional Sweets'],
            ['name' => 'Specials & Combos'],
            ['name' => 'Room Services'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[] = Category::firstOrCreate(['name' => $c['name']], $c);
        }

        // 2. Create Hotel Food & Beverage Products for all 10 categories
        $productsData = [
            // Breakfast (Category Index 0)
            ['name' => 'Kiribath with Lunu Miris', 'category_idx' => 0, 'price' => 350.00, 'desc' => 'Traditional Sri Lankan milk rice served with spicy onion sambol.'],
            ['name' => 'Pol Roti with Dhal & Lunu Miris', 'category_idx' => 0, 'price' => 300.00, 'desc' => 'Three coconut flatbreads served with dhal curry and lunu miris.'],
            ['name' => 'String Hoppers Set (Chicken)', 'category_idx' => 0, 'price' => 450.00, 'desc' => '15 string hoppers served with chicken curry, pol sambol, and dhal.'],
            ['name' => 'Egg Hoppers (Set of 2)', 'category_idx' => 0, 'price' => 250.00, 'desc' => 'Crispy hoppers with soft cooked egg in center, served with katta sambol.'],
            ['name' => 'Sri Lankan Omelette with Toast', 'category_idx' => 0, 'price' => 280.00, 'desc' => 'Spiced omelette with green chillies and onions, served with toasted bread.'],

            // Main Courses (Category Index 1)
            ['name' => 'Chicken Fried Rice (Large)', 'category_idx' => 1, 'price' => 1200.00, 'desc' => 'Wok-tossed basmati rice with chicken, eggs, and fresh veggies.'],
            ['name' => 'Cheese & Egg Koththu', 'category_idx' => 1, 'price' => 950.00, 'desc' => 'Shredded parotta chopped with egg, spices, veggies, and creamy melted cheese.'],
            ['name' => 'Sri Lankan Rice & Curry (Fish)', 'category_idx' => 1, 'price' => 650.00, 'desc' => 'Steamed red/white rice served with fish curry and 4 seasonal vegetable curries.'],
            ['name' => 'Seafood Fried Rice (Special)', 'category_idx' => 1, 'price' => 1500.00, 'desc' => 'Premium basmati rice tossed with prawns, cuttlefish, fish, and egg.'],
            ['name' => 'Chicken Biryani (Basmati)', 'category_idx' => 1, 'price' => 1400.00, 'desc' => 'Fragrant basmati rice cooked with biryani spices, served with chicken leg, egg, and raita.'],

            // Beverages (Category Index 2)
            ['name' => 'Fresh Mango Juice', 'category_idx' => 2, 'price' => 450.00, 'desc' => 'Chilled fresh ripe mango blend.'],
            ['name' => 'Ceylon Milk Tea (Hot)', 'category_idx' => 2, 'price' => 150.00, 'desc' => 'Premium brewed Ceylon black tea with fresh milk.'],
            ['name' => 'Woodapple Juice', 'category_idx' => 2, 'price' => 380.00, 'desc' => 'Traditional local woodapple pulp blend with coconut milk.'],
            ['name' => 'King Coconut (Thambili)', 'category_idx' => 2, 'price' => 200.00, 'desc' => 'Freshly cut organic local king coconut water.'],
            ['name' => 'Lime Juice (With Ice)', 'category_idx' => 2, 'price' => 300.00, 'desc' => 'Freshly squeezed lime juice with syrup.'],

            // Desserts (Category Index 3)
            ['name' => 'Traditional Watalappan', 'category_idx' => 3, 'price' => 450.00, 'desc' => 'Spiced steamed egg custard made with kithul jaggery and coconut milk.'],
            ['name' => 'Creamy Caramel Pudding', 'category_idx' => 3, 'price' => 380.00, 'desc' => 'Smooth melt-in-mouth pudding with caramelized sugar syrup.'],
            ['name' => 'Fruit Salad with Ice Cream', 'category_idx' => 3, 'price' => 550.00, 'desc' => 'Fresh cut seasonal fruits topped with vanilla ice cream.'],
            ['name' => 'Buffalo Curd & Kithul Treacle', 'category_idx' => 3, 'price' => 400.00, 'desc' => 'Creamy local clay pot curd served with pure kithul syrup.'],

            // Short Eats & Snacks (Category Index 4)
            ['name' => 'Fish Cutlets (5 Pieces)', 'category_idx' => 4, 'price' => 400.00, 'desc' => 'Deep fried spiced fish and potato breaded balls.'],
            ['name' => 'Vegetable Samosas (4 Pieces)', 'category_idx' => 4, 'price' => 300.00, 'desc' => 'Crispy pastry triangles stuffed with spiced potatoes and green peas.'],
            ['name' => 'Egg Roti with Gravy', 'category_idx' => 4, 'price' => 280.00, 'desc' => 'Flatbread folded with egg and cooked on a griddle, served with dhal.'],
            ['name' => 'Spicy Chicken Roll', 'category_idx' => 4, 'price' => 180.00, 'desc' => 'Savory crêpe rolled with spiced chicken filling and breaded.'],

            // Soups & Salads (Category Index 5)
            ['name' => 'Cream of Tomato Soup', 'category_idx' => 5, 'price' => 420.00, 'desc' => 'Rich and smooth tomato soup served with crispy bread croutons.'],
            ['name' => 'Spicy Seafood Soup', 'category_idx' => 5, 'price' => 650.00, 'desc' => 'Local style hot and sour soup loaded with prawns and fish.'],
            ['name' => 'Fresh Caesar Salad', 'category_idx' => 5, 'price' => 800.00, 'desc' => 'Crisp romaine lettuce tossed with Caesar dressing, parmesan, and croutons.'],

            // Side Dishes (Category Index 6)
            ['name' => 'Garlic Bread (4 Pieces)', 'category_idx' => 6, 'price' => 250.00, 'desc' => 'Toasted baguette slices brushed with garlic herb butter.'],
            ['name' => 'French Fries (Portion)', 'category_idx' => 6, 'price' => 400.00, 'desc' => 'Crispy golden potato fries served with tomato ketchup.'],
            ['name' => 'Steamed Basmati Rice', 'category_idx' => 6, 'price' => 300.00, 'desc' => 'Fluffy steamed basmati rice.'],

            // Traditional Sweets (Category Index 7)
            ['name' => 'Kavum & Kokis Platter', 'category_idx' => 7, 'price' => 500.00, 'desc' => 'Assorted platter of traditional Sri Lankan sweetmeats (Kavum, Kokis, Aluwa).'],
            ['name' => 'Sweet Coconut Pancakes', 'category_idx' => 7, 'price' => 300.00, 'desc' => 'Three local style pancakes rolled with sweet coconut panipol filling.'],
            ['name' => 'Sri Lankan Halapa (2 Pieces)', 'category_idx' => 7, 'price' => 150.00, 'desc' => 'Traditional kurakkan and coconut sweet wrapped in kanda leaf.'],

            // Specials & Combos (Category Index 8)
            ['name' => 'Weekend Rice & Curry Combo', 'category_idx' => 8, 'price' => 950.00, 'desc' => 'Steamed rice served with chicken curry, 5 side curries, papadam, and a soft drink.'],
            ['name' => 'Family Fried Rice Feast Pack', 'category_idx' => 8, 'price' => 3500.00, 'desc' => 'Large fried rice served with chili chicken, hot butter cuttlefish, chopsuey, and 1.5L soft drink.'],
            ['name' => 'Executive Lunch Box', 'category_idx' => 8, 'price' => 750.00, 'desc' => 'Convenient packed lunch with fish curry, 3 veg curries, rice, papadam, and watalappan slice.'],

            // Room Services (Category Index 9)
            ['name' => 'Deluxe Room Breakfast Service', 'category_idx' => 9, 'price' => 2500.00, 'desc' => 'Breakfast platter delivered directly to your room with hot tea/coffee.'],
            ['name' => 'Special Candlelight Dinner Setup', 'category_idx' => 9, 'price' => 8500.00, 'desc' => 'Romantic dining table set up on the private balcony or garden.'],
            ['name' => 'Mini Bar Stock Recharge', 'category_idx' => 9, 'price' => 3500.00, 'desc' => 'Recharging room mini-fridge with juices, soft drinks, and snacks.'],
        ];

        $products = [];
        foreach ($productsData as $p) {
            $cat = $categories[$p['category_idx']];
            $products[] = Product::firstOrCreate(
                ['name' => $p['name'], 'category_id' => $cat->id],
                [
                    'name' => $p['name'],
                    'category_id' => $cat->id,
                    'unit_price' => $p['price'],
                    'description' => $p['desc'],
                    'image' => null,
                ]
            );
        }

        // 3. Create 30 Hotel Guests (Clients)
        $firstNames = [
            'Amal', 'Nimal', 'Sunil', 'Kavin', 'John', 'Robert', 'David', 'James', 'Michael', 'William',
            'Thomas', 'Charles', 'Daniel', 'Richard', 'Emma', 'Olivia', 'Sophia', 'Jane', 'Mary', 'Patricia',
            'Linda', 'Elizabeth', 'Sarah', 'Jessica', 'Ali', 'Hiroshi', 'Sven', 'Carlos', 'Hans', 'Jean'
        ];
        $lastNames = [
            'Silva', 'Perera', 'Fernando', 'Pathum', 'Doe', 'Smith', 'Johnson', 'Williams', 'Brown', 'Jones',
            'Miller', 'Davis', 'Wilson', 'Anderson', 'Martinez', 'Taylor', 'Thomas', 'Hernandez', 'Moore', 'Martin',
            'Jackson', 'Thompson', 'White', 'Lopez', 'Al-Fayed', 'Tanaka', 'Lindstrom', 'Gomez', 'Muller', 'Dupont'
        ];
        $countries = [
            'Sri Lanka', 'United Kingdom', 'Germany', 'Australia', 'Japan', 'France', 'United States', 'Sweden',
            'India', 'Russia', 'Italy', 'Singapore', 'Canada', 'Switzerland', 'Netherlands'
        ];
        $titles = ['Mr.', 'Mrs.', 'Ms.', 'Dr.'];

        $clients = [];
        for ($i = 0; $i < 30; $i++) {
            $firstName = $firstNames[$i];
            $lastName = $lastNames[$i];
            $email = strtolower($firstName . '.' . $lastName . '@example.com');
            $country = $countries[$i % count($countries)];

            $clients[] = Client::firstOrCreate(
                ['email' => $email],
                [
                    'title' => $titles[$i % count($titles)],
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'country' => $country,
                    'passport_no' => 'P' . rand(1000000, 9999999),
                    'address' => rand(10, 999) . ' Beach Road, Mount Lavinia, ' . $country,
                    'company_name' => rand(0, 1) ? $lastName . ' Travels' : null,
                    'mobile_no' => '+' . rand(94, 99) . rand(1000000, 99999999),
                    'email' => $email,
                    'note' => 'Hotel guest / diner.',
                ]
            );
        }

        // 4. Create 50 Food/Dining Invoices
        $invoiceStatuses = ['paid', 'unpaid', 'partially_paid', 'overdue', 'processing'];
        $today = Carbon::now();

        for ($i = 0; $i < 50; $i++) {
            $client = $clients[$i % count($clients)];
            
            // Distribute invoice date over the past 12 months
            if ($i < 35) {
                $status = 'paid';
                $monthsAgo = rand(1, 11);
            } else {
                $status = collect(['unpaid', 'partially_paid', 'overdue', 'processing'])->random();
                $monthsAgo = 0; 
            }

            $invoiceDate = (clone $today)->subMonths($monthsAgo)->subDays(rand(1, 28));
            $dueDate = (clone $invoiceDate)->addDays($status === 'overdue' ? -3 : 7); 

            $discountType = rand(0, 1) ? 'percentage' : 'fixed';
            $discount = ($discountType === 'percentage') ? rand(0, 1) * 10 : rand(0, 4) * 100;

            $invoice = Invoice::create([
                'client_id' => $client->id,
                'invoice_date' => $invoiceDate->format('Y-m-d'),
                'due_date' => $dueDate->format('Y-m-d'),
                'status' => $status,
                'total_amount' => 0,
                'final_amount' => 0,
                'discount_type' => $discountType,
                'discount' => $discount,
                'note' => 'Restaurant Dine-in / Room Service Bill. Table/Room: #' . rand(101, 505),
            ]);

            $totalAmount = 0;

            // Select 1 to 4 random food items
            $selectedProducts = collect($products)->random(rand(1, 4));
            foreach ($selectedProducts as $prod) {
                $qty = rand(1, 4); // Order quantity
                $days = 1;
                $amount = $prod->unit_price * $qty * $days;

                ProductInvoice::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $prod->id,
                    'quantity' => $qty,
                    'days' => $days,
                    'amount' => $amount,
                ]);

                $totalAmount += $amount;
            }

            // Apply discount
            if ($discountType === 'percentage') {
                $finalAmount = $totalAmount * (1 - ($discount / 100));
            } else {
                $finalAmount = max(0, $totalAmount - $discount);
            }

            $invoice->update([
                'total_amount' => $totalAmount,
                'final_amount' => $finalAmount,
            ]);
        }
    }
}
