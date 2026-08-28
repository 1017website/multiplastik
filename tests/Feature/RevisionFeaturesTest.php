<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\PartnershipApplication;
use App\Models\Product;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevisionFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_displays_product_search_and_active_categories(): void
    {
        $brand = Brand::create([
            'slug' => 'hok-cup',
            'name' => 'Hok Cup',
            'is_active' => true,
        ]);
        $category = Category::create([
            'brand_id' => $brand->id,
            'slug' => 'gelas-natural',
            'name' => 'Gelas Natural',
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category->id,
            'slug' => 'gelas-12-oz',
            'name' => 'Gelas Natural 12 Oz',
            'is_active' => true,
        ]);
        Category::create([
            'brand_id' => $brand->id,
            'slug' => 'gelas-printing',
            'name' => 'Gelas Printing',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Cari Produk yang Anda Butuhkan')
            ->assertSee('Gelas Natural')
            ->assertSeeInOrder(['Gelas Natural', 'Gelas Printing'])
            ->assertSee('1 produk')
            ->assertSee('id="homeLiveResults"', false)
            ->assertSee('homeLiveSearch(this.value)', false)
            ->assertSee('action="'.route('site.search').'"', false);
    }

    public function test_home_uses_the_slide_overlay_darkness(): void
    {
        Slide::create([
            'title_top' => 'Slide Uji',
            'overlay_darkness' => 42,
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('rgba(17,17,17,0.42)', false);
    }

    public function test_artisan_console_is_only_accessible_by_developer_role(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $developer = User::create([
            'name' => 'Developer',
            'email' => 'developer@example.com',
            'password' => 'password',
            'role' => 'developer',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.artisan.index'))
            ->assertForbidden();

        $this->actingAs($developer)
            ->get(route('admin.artisan.index'))
            ->assertOk();
    }

    public function test_developer_account_is_hidden_from_admin_user_list(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $developer = User::create([
            'name' => '1017 Website Developer',
            'email' => '1017website@gmail.com',
            'password' => 'password',
            'role' => 'developer',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertDontSee($developer->email);

        $this->actingAs($developer)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee($developer->email);
    }

    public function test_developer_account_is_provisioned_on_first_valid_login(): void
    {
        config()->set('developer.name', '1017 Website Developer');
        config()->set('developer.email', 'developer-bootstrap@example.com');
        config()->set('developer.password', 'StrongDeveloperPassword!');

        $this->assertDatabaseMissing('users', [
            'email' => 'developer-bootstrap@example.com',
        ]);

        $this->post(route('admin.login.post'), [
            'email' => 'developer-bootstrap@example.com',
            'password' => 'password-salah',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', [
            'email' => 'developer-bootstrap@example.com',
        ]);

        $this->post(route('admin.login.post'), [
            'email' => 'developer-bootstrap@example.com',
            'password' => 'StrongDeveloperPassword!',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'developer-bootstrap@example.com',
            'role' => 'developer',
        ]);

        $this->get(route('admin.artisan.index'))->assertOk();
    }

    public function test_visitor_can_submit_a_partnership_application(): void
    {
        $this->get(route('site.partnership'))
            ->assertOk()
            ->assertSee('Mulai Usaha Toko Plastik')
            ->assertSee('Form Calon Mitra');

        $response = $this->post(route('site.partnership.store'), [
            'name' => 'Budi Santoso',
            'whatsapp' => '0812 3456 7890',
            'email' => 'budi@example.com',
            'city' => 'Surabaya',
            'address' => 'Kecamatan Sukolilo',
            'business_stage' => 'planning',
            'capital_range' => '25_50',
            'start_timeline' => '1_3_months',
            'preferred_products' => ['Gelas Plastik', 'Tusuk Bambu'],
            'message' => 'Ingin membuka toko plastik dekat pasar.',
            'consent' => '1',
        ]);

        $response
            ->assertRedirect(route('site.partnership'))
            ->assertSessionHas('partnership_success');

        $this->assertDatabaseHas('partnership_applications', [
            'name' => 'Budi Santoso',
            'whatsapp' => '6281234567890',
            'city' => 'Surabaya',
            'status' => 'new',
        ]);
    }

    public function test_admin_can_review_and_update_a_partnership_application(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin-partnership@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $application = PartnershipApplication::create([
            'name' => 'Siti Aminah',
            'whatsapp' => '628111111111',
            'city' => 'Sidoarjo',
            'business_stage' => 'offline_store',
            'capital_range' => '50_100',
            'start_timeline' => 'immediately',
            'consent_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.partnerships.index'))
            ->assertOk()
            ->assertSee('Siti Aminah')
            ->assertSee('Sidoarjo');

        $this->actingAs($admin)
            ->put(route('admin.partnerships.update', $application), [
                'status' => 'qualified',
                'admin_notes' => 'Prospek siap dihubungi kembali hari Senin.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('partnership_applications', [
            'id' => $application->id,
            'status' => 'qualified',
            'admin_notes' => 'Prospek siap dihubungi kembali hari Senin.',
        ]);
    }

    public function test_partnership_honeypot_rejects_bot_submission(): void
    {
        $this->post(route('site.partnership.store'), [
            'name' => 'Spam Bot',
            'whatsapp' => '081234567890',
            'city' => 'Surabaya',
            'business_stage' => 'planning',
            'capital_range' => 'under_10',
            'start_timeline' => 'over_6_months',
            'consent' => '1',
            'website' => 'https://spam.example',
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseCount('partnership_applications', 0);
    }
}
