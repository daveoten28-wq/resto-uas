<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\RestaurantSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class RestaurantOrderingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RestaurantSeeder::class);
    }

    public function test_customer_can_checkout_pay_and_view_receipt(): void
    {
        $menu = Menu::firstOrFail();

        $this->post(route('cart.add', $menu), ['quantity' => 2])
            ->assertRedirect(route('cart.index'));

        $this->post(route('checkout.store'), [
            'customer_name' => 'Naya Putri',
            'customer_email' => 'naya@example.com',
            'table_number' => 7,
        ])->assertRedirect();

        $order = Order::with('items')->firstOrFail();

        $this->assertSame('naya@example.com', $order->customer_email);
        $this->assertSame(7, $order->table_number);
        $this->assertSame('pending', $order->payment_status);
        $this->assertSame($menu->price * 2, $order->total_price);
        $this->assertCount(1, $order->items);
        $this->assertSame(2, $order->items->first()->quantity);

        $this->post(route('payment.succeed', $order))
            ->assertRedirect(route('receipt.show', $order));

        $this->get(route('receipt.show', $order))
            ->assertOk()
            ->assertSee('Pesanan Diterima')
            ->assertSee('Struk digital');
    }

    public function test_checkout_rejects_a_table_outside_the_restaurant_range(): void
    {
        $menu = Menu::firstOrFail();

        $this->withSession(['cart' => [$menu->id => 1]])
            ->post(route('checkout.store'), [
                'customer_name' => 'Naya Putri',
                'customer_email' => 'naya@example.com',
                'table_number' => 31,
            ])
            ->assertSessionHasErrors('table_number');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_kitchen_panel_requires_authentication(): void
    {
        $this->get(route('kitchen.index'))
            ->assertRedirect(route('login'));
    }

    public function test_cancelled_payment_shows_the_failure_screen(): void
    {
        $order = $this->createPendingOrder(now()->addMinutes(15));

        $this->post(route('payment.cancel', $order))
            ->assertRedirect(route('payment.failed', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'cancelled',
        ]);
        $this->get(route('payment.failed', $order))
            ->assertOk()
            ->assertSee('Kamu membatalkan pembayaran.');
    }

    public function test_expired_payment_is_marked_when_the_payment_page_is_opened(): void
    {
        $order = $this->createPendingOrder(now()->subMinute());

        $this->get(route('payment.show', $order))
            ->assertOk()
            ->assertSee('Waktu pembayaran habis.');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'expired',
        ]);
    }

    public function test_catalog_language_can_be_switched_and_persists_in_session(): void
    {
        $this->get(route('menu.index'))
            ->assertOk()
            ->assertSee('Makanan Utama');

        $this->from(route('menu.index'))
            ->post(route('locale.update'), ['locale' => 'en'])
            ->assertRedirect(route('menu.index'));

        $this->get(route('menu.index', ['kategori' => 'main-course']))
            ->assertOk()
            ->assertSee('Main Course')
            ->assertSee('Grilled chicken glazed with honey');
    }

    public function test_admin_can_create_update_and_delete_categories_and_menus(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $this->post(route('admin.categories.store'), [
            'name_id' => 'Menu Musiman',
            'name_en' => 'Seasonal Menu',
        ])->assertRedirect(route('admin.categories.index'));

        $category = \App\Models\Category::where('slug', 'seasonal-menu')->firstOrFail();

        $this->put(route('admin.categories.update', $category), [
            'name_id' => 'Menu Pilihan',
            'name_en' => 'Featured Menu',
        ])->assertRedirect(route('admin.categories.index'));

        $category->refresh();
        $this->assertSame('Featured Menu', $category->name_en);

        $this->post(route('admin.menus.store'), [
            'category_id' => $category->id,
            'name' => 'Sup Musiman',
            'description_id' => 'Sup hangat musiman.',
            'description_en' => 'A warm seasonal soup.',
            'price' => 40000,
            'image' => UploadedFile::fake()->image('sup.png'),
            'is_available' => 1,
        ])->assertRedirect(route('admin.menus.index'));

        $menu = Menu::where('slug', 'sup-musiman')->firstOrFail();
        $this->assertSame('A warm seasonal soup.', $menu->description_en);
        $originalImage = $menu->image;
        Storage::disk('public')->assertExists($originalImage);
        $this->get(route('menu.index'))
            ->assertOk()
            ->assertSee(asset('storage/'.$originalImage));

        $this->put(route('admin.menus.update', $menu), [
            'category_id' => $category->id,
            'name' => 'Sup Musiman Spesial',
            'description_id' => 'Sup pilihan musiman.',
            'description_en' => 'A special seasonal soup.',
            'price' => 45000,
            'image' => UploadedFile::fake()->image('sup-baru.png'),
            'is_available' => 1,
        ])->assertRedirect(route('admin.menus.index'));

        $menu->refresh();
        Storage::disk('public')->assertMissing($originalImage);
        Storage::disk('public')->assertExists($menu->image);

        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'name' => 'Sup Musiman Spesial',
            'price' => 45000,
        ]);

        $uploadedImage = $menu->image;
        $this->delete(route('admin.menus.destroy', $menu))
            ->assertRedirect(route('admin.menus.index'));
        Storage::disk('public')->assertMissing($uploadedImage);
        $this->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    private function createPendingOrder($expiresAt): Order
    {
        return Order::create([
            'public_id' => (string) Str::uuid(),
            'customer_name' => 'Naya Putri',
            'customer_phone' => '',
            'customer_email' => 'naya@example.com',
            'table_number' => 7,
            'status' => 'received',
            'payment_status' => 'pending',
            'payment_expires_at' => $expiresAt,
            'total_amount' => 50000,
        ]);
    }
}
