<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class StockTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $productA;
    protected $productB;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard categories and products
        $this->category = Category::create(['name' => 'Test Category']);
        
        $this->productA = Product::create([
            'name' => 'Product A',
            'category_id' => $this->category->id,
            'unit_price' => 100,
            'quantity' => 10,
        ]);

        $this->productB = Product::create([
            'name' => 'Product B',
            'category_id' => $this->category->id,
            'unit_price' => 200,
            'quantity' => 5,
        ]);
    }

    /**
     * Set up user with specific permissions.
     */
    protected function authenticateWithPermissions(array $permissions)
    {
        $this->user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'TestRole']);
        
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }
        
        $role->syncPermissions($permissions);
        $this->user->assignRole($role);
        
        return $this->actingAs($this->user);
    }

    public function test_unauthorized_user_cannot_access_stock_handle_page()
    {
        $this->authenticateWithPermissions(['product-list']); // missing product-edit

        $response = $this->get(route('stock.index'));
        $response->assertStatus(403);
    }

    public function test_authorized_user_can_access_stock_handle_page()
    {
        $this->authenticateWithPermissions(['product-edit']);

        $response = $this->get(route('stock.index'));
        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_authorized_user_can_perform_bulk_stock_adjustments()
    {
        $this->authenticateWithPermissions(['product-edit']);

        $response = $this->post(route('stock.update'), [
            'adjustments' => [
                [
                    'product_id' => $this->productA->id,
                    'type' => 'add',
                    'quantity' => 5, // 10 + 5 = 15
                ],
                [
                    'product_id' => $this->productB->id,
                    'type' => 'set',
                    'quantity' => 20, // set to 20
                ]
            ]
        ]);

        $response->assertRedirect(route('stock.index'));
        $response->assertSessionHas('success');

        $this->productA->refresh();
        $this->productB->refresh();

        $this->assertEquals(15, $this->productA->quantity);
        $this->assertEquals(20, $this->productB->quantity);
    }

    public function test_negative_stock_adjustment_fails_and_rolls_back_changes()
    {
        $this->authenticateWithPermissions(['product-edit']);

        // Attempting to deduct 12 from productA (current stock: 10) which should fail.
        // It also tries to add 10 to productB. Since one fails, BOTH should rollback due to transaction.
        $response = $this->post(route('stock.update'), [
            'adjustments' => [
                [
                    'product_id' => $this->productB->id,
                    'type' => 'add',
                    'quantity' => 10, // 5 + 10 = 15
                ],
                [
                    'product_id' => $this->productA->id,
                    'type' => 'remove',
                    'quantity' => 12, // 10 - 12 = -2 (Fails!)
                ]
            ]
        ]);

        $response->assertRedirect(route('stock.index'));
        $response->assertSessionHasErrors(['stock']);

        $this->productA->refresh();
        $this->productB->refresh();

        // Quantities must remain unchanged because of database transaction rollback
        $this->assertEquals(10, $this->productA->quantity);
        $this->assertEquals(5, $this->productB->quantity);
    }
}
