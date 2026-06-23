<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\Service;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\ProductInvoice;
use App\Models\ServiceInvoice;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $client;
    protected $category;
    protected $product;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and assign Admin role with all permissions
        $this->user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Admin']);
        
        $permissions = [
            'invoice-list',
            'invoice-create',
            'invoice-edit',
            'invoice-delete',
        ];
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }
        $role->syncPermissions($permissions);
        $this->user->assignRole($role);

        // Seed basic guest, category, product, service
        $this->client = Client::create([
            'title' => 'Mr.',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'mobile_no' => '12345678',
        ]);

        $this->category = Category::create([
            'name' => 'General Stays'
        ]);

        $this->product = Product::create([
            'name' => 'Mineral Water',
            'category_id' => $this->category->id,
            'unit_price' => 500,
            'quantity' => 10,
        ]);

        $this->service = Service::create([
            'name' => 'Deluxe Room Stay',
            'category_id' => $this->category->id,
            'unit_price' => 15000,
        ]);
    }

    public function test_can_create_invoice_with_only_services()
    {
        $response = $this->actingAs($this->user)->post(route('invoices.store'), [
            'client_id' => $this->client->id,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'unpaid',
            'discount_type' => 'percentage',
            'discount' => 10,
            'total_amount' => 45000, // 15000 * 3 days * 1 qty
            'final_amount' => 40500,  // 45000 - 10%
            'products' => [
                // Empty product row simulating empty form input
                [
                    'product_id' => '',
                    'unit_price' => '',
                    'quantity' => '1',
                    'days' => '1',
                    'amount' => '',
                ]
            ],
            'services' => [
                [
                    'service_id' => $this->service->id,
                    'unit_price' => 15000,
                    'quantity' => 1,
                    'days' => 3,
                    'amount' => 45000,
                ]
            ]
        ]);

        $response->assertRedirect(route('invoices.index'));
        $this->assertDatabaseHas('invoices', [
            'client_id' => $this->client->id,
            'total_amount' => 45000,
            'final_amount' => 40500,
        ]);

        $invoice = Invoice::first();
        $this->assertCount(0, $invoice->productInvoices);
        $this->assertCount(1, $invoice->serviceInvoices);
    }

    public function test_can_create_invoice_with_only_products()
    {
        $response = $this->actingAs($this->user)->post(route('invoices.store'), [
            'client_id' => $this->client->id,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'unpaid',
            'discount_type' => 'fixed',
            'discount' => 500,
            'total_amount' => 1000, // 500 * 2 qty * 1 day
            'final_amount' => 500,  // 1000 - 500
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'unit_price' => 500,
                    'quantity' => 2,
                    'days' => 1,
                    'amount' => 1000,
                ]
            ],
            'services' => [
                [
                    'service_id' => '',
                    'unit_price' => '',
                    'quantity' => '1',
                    'days' => '1',
                    'amount' => '',
                ]
            ]
        ]);

        $response->assertRedirect(route('invoices.index'));
        
        $this->assertDatabaseHas('invoices', [
            'client_id' => $this->client->id,
            'total_amount' => 1000,
            'final_amount' => 500,
        ]);

        $invoice = Invoice::first();
        $this->assertCount(1, $invoice->productInvoices);
        $this->assertCount(0, $invoice->serviceInvoices);

        // Verify stock decremented (10 - 2 = 8)
        $this->assertEquals(8, $this->product->fresh()->quantity);
    }

    public function test_validation_fails_when_both_products_and_services_are_empty()
    {
        $response = $this->actingAs($this->user)->post(route('invoices.store'), [
            'client_id' => $this->client->id,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'unpaid',
            'discount_type' => 'percentage',
            'discount' => 0,
            'total_amount' => 0,
            'final_amount' => 0,
            'products' => [
                [
                    'product_id' => '',
                    'unit_price' => '',
                    'quantity' => '1',
                    'days' => '1',
                    'amount' => '',
                ]
            ],
            'services' => [
                [
                    'service_id' => '',
                    'unit_price' => '',
                    'quantity' => '1',
                    'days' => '1',
                    'amount' => '',
                ]
            ]
        ]);

        $response->assertSessionHasErrors(['items']);
        $this->assertCount(0, Invoice::all());
    }

    public function test_can_update_invoice_with_only_services()
    {
        // 1. Create an invoice
        $invoice = Invoice::create([
            'client_id' => $this->client->id,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'unpaid',
            'discount_type' => 'percentage',
            'discount' => 0,
            'total_amount' => 1000,
            'final_amount' => 1000,
        ]);
        ProductInvoice::create([
            'invoice_id' => $invoice->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'days' => 1,
            'amount' => 1000,
        ]);
        $this->product->decrement('quantity', 2);

        // 2. Perform update with only services (removing product, adding service)
        $response = $this->actingAs($this->user)->put(route('invoices.update', $invoice), [
            'client_id' => $this->client->id,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'paid',
            'discount_type' => 'percentage',
            'discount' => 10,
            'total_amount' => 15000,
            'final_amount' => 13500,
            'products' => [
                [
                    'product_id' => '',
                    'unit_price' => '',
                    'quantity' => '1',
                    'days' => '1',
                    'amount' => '',
                ]
            ],
            'services' => [
                [
                    'service_id' => $this->service->id,
                    'unit_price' => 15000,
                    'quantity' => 1,
                    'days' => 1,
                    'amount' => 15000,
                ]
            ]
        ]);

        $response->assertRedirect(route('invoices.index'));

        // Verify old product was deleted from invoice
        $this->assertCount(0, $invoice->fresh()->productInvoices);
        // Verify new service was added to invoice
        $this->assertCount(1, $invoice->fresh()->serviceInvoices);

        // Verify old product stock was restored (8 + 2 = 10)
        $this->assertEquals(10, $this->product->fresh()->quantity);
    }

    public function test_can_update_invoice_with_only_products()
    {
        // 1. Create an invoice with a service
        $invoice = Invoice::create([
            'client_id' => $this->client->id,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'unpaid',
            'discount_type' => 'percentage',
            'discount' => 0,
            'total_amount' => 15000,
            'final_amount' => 15000,
        ]);
        ServiceInvoice::create([
            'invoice_id' => $invoice->id,
            'service_id' => $this->service->id,
            'quantity' => 1,
            'days' => 1,
            'amount' => 15000,
        ]);

        // 2. Update with only products (removing service, adding product)
        $response = $this->actingAs($this->user)->put(route('invoices.update', $invoice), [
            'client_id' => $this->client->id,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'paid',
            'discount_type' => 'fixed',
            'discount' => 100,
            'total_amount' => 1500, // 500 * 3 qty * 1 day
            'final_amount' => 1400,
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'unit_price' => 500,
                    'quantity' => 3,
                    'days' => 1,
                    'amount' => 1500,
                ]
            ],
            'services' => [
                [
                    'service_id' => '',
                    'unit_price' => '',
                    'quantity' => '1',
                    'days' => '1',
                    'amount' => '',
                ]
            ]
        ]);

        $response->assertRedirect(route('invoices.index'));

        // Verify old service was deleted
        $this->assertCount(0, $invoice->fresh()->serviceInvoices);
        // Verify new product was added
        $this->assertCount(1, $invoice->fresh()->productInvoices);

        // Verify stock decremented (10 - 3 = 7)
        $this->assertEquals(7, $this->product->fresh()->quantity);
    }
}
