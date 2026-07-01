<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\ProductInvoice;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $client;
    protected $category;
    protected $product;

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

        // Seed basic client, category, and product
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
        ]);
    }

    public function test_can_create_invoice()
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
    }

    public function test_validation_fails_when_products_are_empty()
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
            ]
        ]);

        $response->assertSessionHasErrors(['items']);
        $this->assertCount(0, Invoice::all());
    }

    public function test_can_update_invoice()
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

        // 2. Perform update
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
            ]
        ]);

        $response->assertRedirect(route('invoices.index'));

        // Verify updated product quantity
        $this->assertCount(1, $invoice->fresh()->productInvoices);
        $this->assertEquals(3, $invoice->fresh()->productInvoices->first()->quantity);
    }

    public function test_can_create_client_via_ajax()
    {
        Permission::firstOrCreate(['name' => 'client-create']);
        $this->user->givePermissionTo('client-create');

        $response = $this->actingAs($this->user)->post(route('clients.store.ajax'), [
            'title' => 'Mr.',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'mobile_no' => '987654321',
            'country' => 'Sri Lanka',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);

        $this->assertDatabaseHas('clients', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'mobile_no' => '987654321',
        ]);
        
        // Assert email was automatically generated
        $client = Client::where('first_name', 'Jane')->first();
        $this->assertNotNull($client->email);
        $this->assertStringContainsString('@test.com', $client->email);
    }
}
