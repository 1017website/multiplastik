<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\PartnershipApplication;
use App\Models\Product;
use App\Models\SiteSetting;
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

    public function test_admin_can_toggle_home_product_and_brand_sections(): void
    {
        $admin = User::create([
            'name' => 'Admin Beranda',
            'email' => 'admin-home@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.settings.index', 'homepage'))
            ->assertOk()
            ->assertSee('Bagian Beranda')
            ->assertSee('Cari Produk yang Anda Butuhkan')
            ->assertSee('Temukan Produk dari Brand Terpercaya');

        $this->post(route('admin.settings.update', 'homepage'), [
            'home_category_section_active' => '0',
            'home_brand_section_active' => '0',
        ])->assertRedirect()->assertSessionHas('success');

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('id="kategori-s"', false)
            ->assertDontSee('Cari Produk yang Anda Butuhkan')
            ->assertDontSee('Temukan Produk dari Brand Terpercaya');

        $this->post(route('admin.settings.update', 'homepage'), [
            'home_category_section_active' => '1',
            'home_brand_section_active' => '0',
        ])->assertRedirect();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="kategori-s"', false)
            ->assertSee('Cari Produk yang Anda Butuhkan')
            ->assertDontSee('Temukan Produk dari Brand Terpercaya');

        $this->assertDatabaseHas('site_settings', [
            'key' => 'home_category_section_active',
            'value' => '1',
            'group' => 'homepage',
            'type' => 'boolean',
        ]);
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

    public function test_admin_can_customize_partnership_form_fields_and_options(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin-form@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.settings.index', 'partnership'))
            ->assertOk()
            ->assertSee('Form Kemitraan')
            ->assertSee('Kondisi Usaha Saat Ini')
            ->assertSee('Gelas Plastik');

        $this->actingAs($admin)
            ->post(route('admin.settings.update', 'partnership'), [
                'partnership_business_stage_label' => 'Status Usaha',
                'partnership_business_stages' => "research|Masih riset\nrunning|Sudah berjalan",
                'partnership_capital_range_label' => 'Budget Awal',
                'partnership_capital_ranges' => "starter|Rp5–15 juta\ngrowth|Di atas Rp15 juta",
                'partnership_start_timeline_label' => 'Rencana Mulai',
                'partnership_start_timelines' => "this_month|Bulan ini\nnext_quarter|Kuartal depan",
                'partnership_preferred_products_label' => 'Produk Pilihan',
                'partnership_preferred_products' => "standing_pouch|Standing Pouch\npaper_bowl|Paper Bowl",
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('Status Usaha', SiteSetting::get('partnership_business_stage_label'));

        $this->get(route('site.partnership'))
            ->assertOk()
            ->assertSee('Status Usaha')
            ->assertSee('Masih riset')
            ->assertSee('Budget Awal')
            ->assertSee('Rp5–15 juta')
            ->assertSee('Rencana Mulai')
            ->assertSee('Bulan ini')
            ->assertSee('Produk Pilihan')
            ->assertSee('Standing Pouch');

        $this->post(route('site.partnership.store'), [
            'name' => 'Rina Wijaya',
            'whatsapp' => '081234567891',
            'city' => 'Malang',
            'business_stage' => 'research',
            'capital_range' => 'starter',
            'start_timeline' => 'this_month',
            'preferred_products' => ['standing_pouch'],
            'consent' => '1',
        ])->assertRedirect(route('site.partnership'));

        $application = PartnershipApplication::where('name', 'Rina Wijaya')->firstOrFail();

        $this->assertSame('Masih riset', $application->business_stage_label);
        $this->assertSame(['Standing Pouch'], $application->preferred_product_labels);
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
