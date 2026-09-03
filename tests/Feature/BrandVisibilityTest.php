<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\MasterCategory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BrandVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_listings_and_footer_only_show_active_visible_brands(): void
    {
        $visible = Brand::create(['name' => 'Brand Terlihat', 'slug' => 'terlihat', 'sort_order' => 2]);
        $first = Brand::create(['name' => 'Brand Pertama', 'slug' => 'pertama', 'sort_order' => 1]);
        $hidden = Brand::create(['name' => 'Brand Tersembunyi', 'slug' => 'tersembunyi', 'show_on_frontend' => false]);
        $inactive = Brand::create(['name' => 'Brand Nonaktif', 'slug' => 'nonaktif', 'is_active' => false]);

        foreach ([route('home'), route('site.brands')] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertViewHas('brands', fn ($brands) => $brands->modelKeys() === [$first->id, $visible->id])
                ->assertSee($visible->name)
                ->assertDontSee($hidden->name)
                ->assertDontSee($inactive->name);
        }

        // A page without brand cards still shares the filtered footer.
        $this->get(route('site.news'))
            ->assertOk()
            ->assertSee($visible->name)
            ->assertDontSee($hidden->name)
            ->assertDontSee($inactive->name);
    }

    public function test_admin_can_hide_and_restore_brand_without_disabling_its_catalog(): void
    {
        [$brand, $masterCategory, $category, $product] = $this->createCatalog();
        $draft = Product::create([
            'category_id' => $category->id,
            'name' => 'Gelas Draf',
            'slug' => 'gelas-draf',
            'is_active' => false,
        ]);
        $brandUrl = route('site.brand', $brand->slug);
        $categoryUrl = route('site.category', [$brand->slug, $category->slug]);
        $productUrl = route('site.product', [$brand->slug, $category->slug, $product->slug]);
        $indexUrl = route('admin.brands.index', ['page' => 2]);

        $this->actingAs($this->createAdmin());
        foreach ([0, 0] as $visibility) {
            $this->from($indexUrl)->patch(route('admin.brands.visibility', $brand), [
                'show_on_frontend' => $visibility,
                'is_active' => 0,
                'name' => 'Should not change',
            ])->assertRedirect($indexUrl)->assertSessionHas('success');
        }

        $this->assertFalse($brand->fresh()->show_on_frontend);
        $this->assertTrue($brand->fresh()->is_active);
        $this->assertSame($brand->name, $brand->fresh()->name);
        $this->assertTrue($category->fresh()->is_active);
        $this->assertTrue($product->fresh()->is_active);
        $this->assertFalse($draft->fresh()->is_active);

        $this->get(route('home'))->assertOk()
            ->assertDontSee($brandUrl, false)
            ->assertSee('1 kategori · 1 brand · 1 produk');
        $this->get(route('site.brands'))->assertOk()->assertDontSee($brandUrl, false);
        $this->get($brandUrl)->assertOk()->assertSee($category->name);
        $this->get(route('site.master-category', $masterCategory->slug))
            ->assertOk()->assertSee($categoryUrl, false);
        $this->get($categoryUrl)->assertOk()->assertSee($productUrl, false)->assertDontSee($draft->name);
        $this->get($productUrl)->assertOk()->assertSee($product->name);
        $this->get(route('site.product', [$brand->slug, $category->slug, $draft->slug]))->assertNotFound();
        $this->get(route('site.search', ['q' => 'Gelas']))
            ->assertOk()->assertSee($productUrl, false)->assertDontSee($draft->name);
        $this->getJson(route('site.search.live', ['q' => 'Gelas']))
            ->assertOk()->assertJsonPath('count', 1)->assertJsonPath('items.0.url', $productUrl);

        $this->patch(route('admin.brands.visibility', $brand), ['show_on_frontend' => 1])
            ->assertRedirect()->assertSessionHas('success');

        $this->assertTrue($brand->fresh()->show_on_frontend);
        $this->assertTrue($product->fresh()->is_active);
        $this->get(route('home'))->assertOk()->assertSee($brandUrl, false);
        $this->get(route('site.brands'))->assertOk()->assertSee($brandUrl, false);
    }

    public function test_visibility_does_not_reactivate_an_inactive_brand(): void
    {
        [$brand, , $category, $product] = $this->createCatalog();
        $brand->update(['is_active' => false, 'show_on_frontend' => false]);

        $this->actingAs($this->createAdmin())
            ->patch(route('admin.brands.visibility', $brand), ['show_on_frontend' => 1])
            ->assertRedirect();

        $this->assertFalse($brand->fresh()->is_active);
        $this->assertTrue($brand->fresh()->show_on_frontend);
        $this->get(route('site.brands'))->assertOk()->assertDontSee($brand->name);
        $this->get(route('site.brand', $brand->slug))->assertNotFound();
        $this->get(route('site.category', [$brand->slug, $category->slug]))->assertNotFound();
        $this->get(route('site.product', [$brand->slug, $category->slug, $product->slug]))->assertNotFound();
        $this->assertTrue($product->fresh()->is_active);
    }

    public function test_admin_forms_can_save_visibility_separately_from_active_status(): void
    {
        $this->actingAs($this->createAdmin());
        $this->get(route('admin.brands.create'))->assertOk()->assertSee('Tampilkan di frontend');
        $this->post(route('admin.brands.store'), [
            'name' => 'Brand Baru',
            'slug' => 'brand-baru',
            'is_active' => 1,
            'show_on_frontend' => 0,
        ])->assertRedirect(route('admin.brands.index'))->assertSessionHasNoErrors();

        $brand = Brand::where('slug', 'brand-baru')->firstOrFail();
        $this->assertFalse($brand->show_on_frontend);
        $this->assertTrue($brand->is_active);

        $this->get(route('admin.brands.index'))->assertOk()
            ->assertSee('Tampil di frontend')
            ->assertSee('aria-checked="false"', false)
            ->assertSee(route('admin.brands.visibility', $brand), false);

        $response = $this->get(route('admin.brands.edit', $brand))->assertOk();
        $document = new \DOMDocument;
        @$document->loadHTML($response->getContent());
        $this->assertFalse($document->getElementById('show_on_frontend')->hasAttribute('checked'));
        $this->assertTrue($document->getElementById('is_active')->hasAttribute('checked'));

        $this->put(route('admin.brands.update', $brand), [
            'name' => $brand->name,
            'slug' => $brand->slug,
            'is_active' => 0,
            'show_on_frontend' => 1,
        ])->assertRedirect(route('admin.brands.index'))->assertSessionHasNoErrors();

        $this->assertTrue($brand->fresh()->show_on_frontend);
        $this->assertFalse($brand->fresh()->is_active);
    }

    public function test_forms_without_visibility_keep_existing_preferences_and_default_new_brands_to_visible(): void
    {
        $this->actingAs($this->createAdmin());
        $this->post(route('admin.brands.store'), [
            'name' => 'Brand Lama',
            'slug' => 'brand-lama',
            'is_active' => 1,
        ])->assertRedirect(route('admin.brands.index'))->assertSessionHasNoErrors();

        $brand = Brand::where('slug', 'brand-lama')->firstOrFail();
        $this->assertTrue($brand->show_on_frontend);
        $brand->update(['show_on_frontend' => false]);

        $this->put(route('admin.brands.update', $brand), [
            'name' => 'Nama Baru',
            'slug' => $brand->slug,
            'is_active' => 1,
        ])->assertRedirect(route('admin.brands.index'))->assertSessionHasNoErrors();

        $this->assertFalse($brand->fresh()->show_on_frontend);
        $this->assertTrue($brand->fresh()->is_active);
    }

    public function test_visibility_update_requires_login_and_a_valid_boolean(): void
    {
        $brand = Brand::create(['name' => 'Brand Uji', 'slug' => 'brand-uji']);
        $url = route('admin.brands.visibility', $brand);

        $this->patch($url, ['show_on_frontend' => 0])->assertRedirect(route('admin.login'));
        $this->assertTrue($brand->fresh()->show_on_frontend);

        $this->actingAs($this->createAdmin());
        $this->patch($url, [])->assertSessionHasErrors('show_on_frontend');
        $this->patch($url, ['show_on_frontend' => 'invalid'])->assertSessionHasErrors('show_on_frontend');
        $this->assertTrue($brand->fresh()->show_on_frontend);

        $this->put(route('admin.brands.update', $brand), [
            'name' => $brand->name,
            'slug' => $brand->slug,
            'is_active' => 1,
            'show_on_frontend' => 'invalid',
        ])->assertSessionHasErrors('show_on_frontend');
        $this->assertTrue($brand->fresh()->show_on_frontend);
    }

    public function test_migration_preserves_existing_catalog_statuses_and_can_be_rolled_back(): void
    {
        [$brand, , $category, $product] = $this->createCatalog();
        $migration = require database_path('migrations/2026_09_03_000001_add_show_on_frontend_to_brands_table.php');
        $migration->down();
        $this->assertFalse(Schema::hasColumn('brands', 'show_on_frontend'));

        $inactiveId = DB::table('brands')->insertGetId([
            'name' => 'Brand Nonaktif Lama',
            'slug' => 'nonaktif-lama',
            'is_active' => false,
        ]);
        $migration->up();

        $this->assertTrue($brand->fresh()->show_on_frontend);
        $this->assertTrue($brand->fresh()->is_active);
        $this->assertTrue(Brand::findOrFail($inactiveId)->show_on_frontend);
        $this->assertFalse(Brand::findOrFail($inactiveId)->is_active);
        $this->assertTrue($category->fresh()->is_active);
        $this->assertTrue($product->fresh()->is_active);
    }

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Admin Brand',
            'email' => 'admin-brand@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }

    private function createCatalog(): array
    {
        $brand = Brand::create([
            'name' => 'Brand Uji',
            'slug' => 'brand-uji',
            'tagline' => 'Perlengkapan kemasan makanan',
            'is_active' => true,
        ]);
        $masterCategory = MasterCategory::create([
            'name' => 'Gelas',
            'slug' => 'gelas',
            'description' => 'Pilihan gelas plastik',
            'is_active' => true,
        ]);
        $category = Category::create([
            'brand_id' => $brand->id,
            'master_category_id' => $masterCategory->id,
            'name' => 'Gelas Natural',
            'slug' => 'gelas-natural',
            'description' => 'Gelas plastik untuk minuman',
            'is_active' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Gelas Natural 12 Oz',
            'slug' => 'gelas-natural-12-oz',
            'description' => 'Gelas plastik ukuran 12 oz',
            'is_active' => true,
        ]);

        return [$brand, $masterCategory, $category, $product];
    }
}
