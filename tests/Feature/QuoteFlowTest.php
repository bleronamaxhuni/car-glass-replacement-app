<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\CarBodyType;
use App\Models\GlassType;
use App\Models\Quote;
use App\Models\Vendor;
use App\Models\VendorGlassPrice;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_vendor_options_returns_success_with_valid_input(): void
    {
        $bodyType = CarBodyType::create(['name' => 'Sedan']);
        $glassType = GlassType::create(['name' => 'Front Windshield']);
        $vendor = Vendor::create(['name' => 'Test Vendor']);
        VendorGlassPrice::create([
            'vendor_id' => $vendor->id,
            'glass_type_id' => $glassType->id,
            'price' => 250.00,
            'warranty_time' => 12,
            'delivery_time' => 3,
        ]);

        $response = $this->post('/quotes/vendor-options', [
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2019,
            'body_type' => 'Sedan',
            'glass_type_id' => $glassType->id,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('quotes.vendor-options');
        $response->assertViewHas('car');
        $response->assertViewHas('glassType');
        $response->assertViewHas('vendorOptions');
        $this->assertCount(1, $response->viewData('vendorOptions'));
    }

    public function test_vendor_options_redirects_back_with_errors_when_body_type_invalid(): void
    {
        $glassType = GlassType::create(['name' => 'Front Windshield']);

        $response = $this->post('/quotes/vendor-options', [
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2019,
            'body_type' => 'InvalidBody',
            'glass_type_id' => $glassType->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('body_type');
    }

    public function test_vendor_options_redirects_back_when_no_vendors_available(): void
    {
        CarBodyType::create(['name' => 'Sedan']);
        $glassType = GlassType::create(['name' => 'Front Windshield']);

        $response = $this->post('/quotes/vendor-options', [
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2019,
            'body_type' => 'Sedan',
            'glass_type_id' => $glassType->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('glass_type_id');
    }

    public function test_vendor_options_fails_validation_with_missing_fields(): void
    {
        $response = $this->post('/quotes/vendor-options', [
            'make' => 'Toyota',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['model', 'year', 'body_type', 'glass_type_id']);
    }

    public function test_store_quote_creates_quote_and_shows_summary(): void
    {
        $bodyType = CarBodyType::create(['name' => 'Sedan']);
        $car = Car::create([
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => '2019',
            'car_body_type_id' => $bodyType->id,
        ]);
        $glassType = GlassType::create(['name' => 'Front Windshield']);
        $vendor = Vendor::create(['name' => 'Test Vendor']);
        $vendorPrice = VendorGlassPrice::create([
            'vendor_id' => $vendor->id,
            'glass_type_id' => $glassType->id,
            'price' => 299.99,
            'warranty_time' => 24,
            'delivery_time' => 5,
        ]);

        $response = $this->post('/quotes', [
            'car_id' => $car->id,
            'glass_type_id' => $glassType->id,
            'vendor_glass_price_id' => $vendorPrice->id,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('quotes.summary');
        $response->assertViewHas('quote');

        $this->assertDatabaseCount('quotes', 1);
        $quote = Quote::first();
        $this->assertSame($car->id, $quote->car_id);
        $this->assertSame($glassType->id, $quote->glass_type_id);
        $this->assertSame($vendorPrice->id, $quote->vendor_glass_price_id);
        $this->assertEquals(299.99, (float) $quote->final_price);
    }

    public function test_store_quote_fails_validation_with_missing_fields(): void
    {
        $response = $this->post('/quotes', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['car_id', 'glass_type_id', 'vendor_glass_price_id']);
    }

    public function test_store_quote_fails_validation_with_invalid_ids(): void
    {
        $response = $this->post('/quotes', [
            'car_id' => 99999,
            'glass_type_id' => 99999,
            'vendor_glass_price_id' => 99999,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
