<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\MasterCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HomeCategoryImageTest extends TestCase
{
    use RefreshDatabase;

    public static function coverImages(): array
    {
        return [
            'null image' => [null, null],
            'empty image' => ['', null],
            'uploaded image' => ['master-cover.png', '/uploads/master-categories/master-cover.png'],
            'remote image' => ['https://example.com/master-cover.png', 'https://example.com/master-cover.png'],
        ];
    }

    #[DataProvider('coverImages')]
    public function test_home_category_only_uses_its_own_image(?string $image, ?string $expectedUrl): void
    {
        $masterCategory = MasterCategory::create([
            'slug' => 'kantong-plastik',
            'name' => 'Kantong Plastik',
            'image' => $image,
            'icon' => 'fas fa-bag-shopping',
            'is_active' => true,
        ]);
        $brand = Brand::create([
            'slug' => 'test-brand',
            'name' => 'Test Brand',
            'is_active' => true,
        ]);
        Category::create([
            'master_category_id' => $masterCategory->id,
            'brand_id' => $brand->id,
            'slug' => 'kantong-plastik-anak',
            'name' => 'Kantong Plastik Anak',
            'image' => 'child-cover.png',
            'is_active' => true,
        ]);

        $response = $this->get('/')->assertOk();
        $document = new \DOMDocument;
        @$document->loadHTML('<?xml encoding="UTF-8">'.$response->getContent());
        $xpath = new \DOMXPath($document);
        $cards = $xpath->query('//a[@class="home-category-card"]');

        $this->assertCount(1, $cards);
        $card = $cards->item(0);
        $this->assertSame(route('site.master-category', $masterCategory->slug), $card->getAttribute('href'));
        $this->assertStringContainsString('Kantong Plastik', $card->textContent);
        $this->assertStringContainsString('1 kategori · 1 brand · 0 produk', $card->textContent);
        $this->assertCount(0, $xpath->query('.//i[contains(@class, "fa-bag-shopping")]', $card));

        $images = $xpath->query('.//img', $card);
        $imageAreas = $xpath->query('.//div[@class="home-category-image"]', $card);
        if ($expectedUrl === null) {
            $this->assertCount(0, $images);
            $this->assertCount(0, $imageAreas);
        } else {
            $this->assertCount(1, $images);
            $this->assertCount(1, $imageAreas);
            $this->assertSame(url($expectedUrl), $images->item(0)->getAttribute('src'));
        }
    }
}
