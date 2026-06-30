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
        // 1. Create 10 Mobile Shop Categories
        $categoriesData = [
            ['name' => 'Smartphones'],
            ['name' => 'Tablets & iPads'],
            ['name' => 'Audio Accessories'],
            ['name' => 'Wearables & Smartwatches'],
            ['name' => 'Power & Chargers'],
            ['name' => 'Cases & Protection'],
            ['name' => 'Repair Services'],
            ['name' => 'Setup & Support Services'],
            ['name' => 'Warranty & Care Plans'],
            ['name' => 'Pre-Owned Devices'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[] = Category::firstOrCreate(['name' => $c['name']], $c);
        }

        // 2. Create 30 Mobile Shop Products (smartphones, accessories, protective gears)
        $productsData = [
            // Smartphones (Category Index 0)
            ['name' => 'iPhone 15 Pro Max 256GB', 'category_idx' => 0, 'price' => 450000.00, 'desc' => 'Titanium design, A17 Pro chip.'],
            ['name' => 'iPhone 15 128GB', 'category_idx' => 0, 'price' => 310000.00, 'desc' => 'Dynamic Island, 48MP Main camera.'],
            ['name' => 'Galaxy S24 Ultra 512GB', 'category_idx' => 0, 'price' => 480000.00, 'desc' => 'With S Pen, Snapdragon 8 Gen 3 for Galaxy.'],
            ['name' => 'Galaxy A55 5G 128GB', 'category_idx' => 0, 'price' => 145000.00, 'desc' => 'Awesome Iceblue, 8GB RAM.'],
            ['name' => 'Xiaomi 14 512GB', 'category_idx' => 0, 'price' => 280000.00, 'desc' => 'Leica professional optics, Snapdragon 8 Gen 3.'],
            ['name' => 'Pixel 8 Pro 128GB', 'category_idx' => 0, 'price' => 330000.00, 'desc' => 'Google AI, best-in-class camera.'],

            // Tablets & iPads (Category Index 1)
            ['name' => 'iPad Air 10.9" M1 64GB', 'category_idx' => 1, 'price' => 220000.00, 'desc' => 'Liquid Retina display, M1 chip.'],
            ['name' => 'iPad Pro 11" M2 128GB', 'category_idx' => 1, 'price' => 360000.00, 'desc' => 'ProMotion technology, LiDAR Scanner.'],
            ['name' => 'Galaxy Tab S9 FE 128GB', 'category_idx' => 1, 'price' => 180000.00, 'desc' => 'IP68 water resistant, S Pen included.'],

            // Audio Accessories (Category Index 2)
            ['name' => 'AirPods Pro (2nd Gen) USB-C', 'category_idx' => 2, 'price' => 85000.00, 'desc' => 'Active Noise Cancellation, Adaptive Audio.'],
            ['name' => 'AirPods (3rd Gen) Lightning', 'category_idx' => 2, 'price' => 62000.00, 'desc' => 'Spatial audio with dynamic head tracking.'],
            ['name' => 'Galaxy Buds 2 Pro', 'category_idx' => 2, 'price' => 58000.00, 'desc' => '24-bit Hi-Fi audio, ANC.'],
            ['name' => 'Sony WH-1000XM5 ANC Headphones', 'category_idx' => 2, 'price' => 135000.00, 'desc' => 'Industry-leading noise canceling.'],
            ['name' => 'JBL GO 4 Portable Speaker', 'category_idx' => 2, 'price' => 18000.00, 'desc' => 'Ultra-portable waterproof speaker.'],

            // Wearables & Smartwatches (Category Index 3)
            ['name' => 'Apple Watch Series 9 GPS 45mm', 'category_idx' => 3, 'price' => 155000.00, 'desc' => 'S9 SiP chip, double tap gesture.'],
            ['name' => 'Apple Watch SE GPS 44mm', 'category_idx' => 3, 'price' => 110000.00, 'desc' => 'Crash Detection, heart rate tracking.'],
            ['name' => 'Galaxy Watch 6 Bluetooth 44mm', 'category_idx' => 3, 'price' => 95000.00, 'desc' => 'Personalized heart zone workouts.'],

            // Power & Chargers (Category Index 4)
            ['name' => 'Apple 20W USB-C Power Adapter', 'category_idx' => 4, 'price' => 9500.00, 'desc' => 'Fast charging for iPhones.'],
            ['name' => 'Anker Nano II 45W Charger', 'category_idx' => 4, 'price' => 14000.00, 'desc' => 'Compact GaN USB-C wall charger.'],
            ['name' => 'Anker 3-in-1 Charging Cable 3ft', 'category_idx' => 4, 'price' => 4800.00, 'desc' => 'Lightning, USB-C, and Micro USB connector.'],
            ['name' => 'Anker PowerCore 20000mAh Power Bank', 'category_idx' => 4, 'price' => 24000.00, 'desc' => 'PowerIQ technology, ultra-high capacity.'],
            ['name' => 'Samsung 25W Fast Charging Adapter', 'category_idx' => 4, 'price' => 8500.00, 'desc' => 'Super fast charging for Galaxy devices.'],

            // Cases & Protection (Category Index 5)
            ['name' => 'iPhone 15 Pro MagSafe Silicone Case', 'category_idx' => 5, 'price' => 19500.00, 'desc' => 'Apple official MagSafe case.'],
            ['name' => 'Spigen Ultra Hybrid Case (iPhone 15)', 'category_idx' => 5, 'price' => 8500.00, 'desc' => 'Crystal clear protection.'],
            ['name' => '9H Tempered Glass Screen Protector', 'category_idx' => 5, 'price' => 2500.00, 'desc' => 'Premium scratch protection.'],
            ['name' => 'iPad Air Smart Fold Case', 'category_idx' => 5, 'price' => 12500.00, 'desc' => 'Tri-fold magnetic stand cover.'],

            // Pre-Owned Devices (Category Index 9)
            ['name' => 'Pre-Owned iPhone 13 128GB (Good)', 'category_idx' => 9, 'price' => 195000.00, 'desc' => 'Fully tested, 85%+ battery health.'],
            ['name' => 'Pre-Owned iPhone 12 128GB (Fair)', 'category_idx' => 9, 'price' => 140000.00, 'desc' => 'Cosmetic wear, fully functional.'],
            ['name' => 'Pre-Owned Galaxy S22 5G 128GB', 'category_idx' => 9, 'price' => 165000.00, 'desc' => 'Excellent condition, unlocked.'],
            ['name' => 'Pre-Owned iPad 9th Gen 64GB WiFi', 'category_idx' => 9, 'price' => 110000.00, 'desc' => 'Pre-owned budget tablet, very good.'],
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

        // 3. Create 30 Mobile Shop Customers (Clients)
        $firstNames = [
            'John', 'Robert', 'David', 'James', 'Michael', 'William', 'Thomas', 'Charles', 'Daniel', 'Richard',
            'Emma', 'Olivia', 'Sophia', 'Jane', 'Mary', 'Patricia', 'Linda', 'Elizabeth', 'Sarah', 'Jessica',
            'Ali', 'Hiroshi', 'Sven', 'Carlos', 'Hans', 'Jean', 'Maria', 'Yuki', 'Elena', 'Amara'
        ];
        $lastNames = [
            'Doe', 'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Wilson', 'Anderson',
            'Martinez', 'Taylor', 'Thomas', 'Hernandez', 'Moore', 'Martin', 'Jackson', 'Thompson', 'White', 'Lopez',
            'Al-Fayed', 'Tanaka', 'Lindstrom', 'Gomez', 'Muller', 'Dupont', 'Rossi', 'Sato', 'Ivanova', 'Adedoja'
        ];
        $countries = [
            'United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'Japan', 'Sweden',
            'Italy', 'Spain', 'Egypt', 'Brazil', 'South Africa', 'New Zealand', 'Singapore'
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
                    'passport_no' => 'ID' . rand(1000000, 9999999),
                    'address' => rand(10, 999) . ' Main Street, City Center, ' . $country,
                    'company_name' => rand(0, 1) ? $lastName . ' Tech Solutions' : null,
                    'mobile_no' => '+' . rand(1, 99) . rand(1000000, 99999999),
                    'email' => $email,
                    'note' => 'Walk-in retail customer.',
                ]
            );
        }

        // 4. Create 50 Invoices
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
            $dueDate = (clone $invoiceDate)->addDays($status === 'overdue' ? -5 : 14); 

            $discountType = rand(0, 1) ? 'percentage' : 'fixed';
            $discount = ($discountType === 'percentage') ? rand(0, 1) * 10 : rand(0, 4) * 1000;

            $invoice = Invoice::create([
                'client_id' => $client->id,
                'invoice_date' => $invoiceDate->format('Y-m-d'),
                'due_date' => $dueDate->format('Y-m-d'),
                'status' => $status,
                'total_amount' => 0,
                'final_amount' => 0,
                'discount_type' => $discountType,
                'discount' => $discount,
                'note' => 'Mobile shop sales receipt. Warranty Ref: #' . rand(10000, 99999),
            ]);

            $totalAmount = 0;

            

            // Select 1 to 3 random products (phones, cases, power adapters)
            $selectedProducts = collect($products)->random(rand(1, 3));
            foreach ($selectedProducts as $prod) {
                $qty = rand(1, 2);
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
