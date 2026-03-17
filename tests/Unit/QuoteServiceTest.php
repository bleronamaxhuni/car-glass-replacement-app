<?php

namespace Tests\Unit;

use App\Models\Car;
use App\Models\CarBodyType;
use App\Models\GlassType;
use App\Models\Quote;
use App\Models\Vendor;
use App\Models\VendorGlassPrice;
use App\Services\QuoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteServiceTest extends TestCase
{
    use RefreshDatabase;

    private QuoteService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new QuoteService;
    }

    public function test_get_vendor_options_returns_car_glass_and_vendor_options_when_valid(): void
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

        $result = $this->service->getVendorOptions([
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2019,
            'body_type' => 'Sedan',
            'glass_type_id' => $glassType->id,
        ]);

        $this->assertArrayNotHasKey('error', $result);
        $this->assertArrayHasKey('car', $result);
        $this->assertArrayHasKey('glassType', $result);
        $this->assertArrayHasKey('vendorOptions', $result);
        $this->assertInstanceOf(Car::class, $result['car']);
        $this->assertSame('Toyota', $result['car']->make);
        $this->assertSame('Corolla', $result['car']->model);
        $this->assertSame('2019', $result['car']->year);
        $this->assertInstanceOf(GlassType::class, $result['glassType']);
        $this->assertCount(1, $result['vendorOptions']);
        $this->assertSame(250.00, (float) $result['vendorOptions'][0]->price);
        $this->assertSame(12, $result['vendorOptions'][0]->warranty_time);
        $this->assertSame(3, $result['vendorOptions'][0]->delivery_time);
    }

    public function test_get_vendor_options_returns_error_when_body_type_not_found(): void
    {
        $glassType = GlassType::create(['name' => 'Front Windshield']);

        $result = $this->service->getVendorOptions([
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2019,
            'body_type' => 'InvalidBodyType',
            'glass_type_id' => $glassType->id,
        ]);

        $this->assertSame('body_type', $result['error']);
    }

    public function test_get_vendor_options_returns_error_when_no_vendors_for_glass_type(): void
    {
        $bodyType = CarBodyType::create(['name' => 'Sedan']);
        $glassType = GlassType::create(['name' => 'Front Windshield']);

        $result = $this->service->getVendorOptions([
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2019,
            'body_type' => 'Sedan',
            'glass_type_id' => $glassType->id,
        ]);

        $this->assertSame('no_vendors', $result['error']);
    }

    public function test_get_vendor_options_creates_car_if_not_exists(): void
    {
        $this->assertDatabaseCount('cars', 0);

        $bodyType = CarBodyType::create(['name' => 'Sedan']);
        $glassType = GlassType::create(['name' => 'Front Windshield']);
        $vendor = Vendor::create(['name' => 'Vendor']);
        VendorGlassPrice::create([
            'vendor_id' => $vendor->id,
            'glass_type_id' => $glassType->id,
            'price' => 100,
            'warranty_time' => 6,
            'delivery_time' => 1,
        ]);

        $this->service->getVendorOptions([
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2019,
            'body_type' => 'Sedan',
            'glass_type_id' => $glassType->id,
        ]);

        $this->assertDatabaseCount('cars', 1);
        $this->assertDatabaseHas('cars', ['make' => 'Toyota', 'model' => 'Corolla', 'year' => '2019']);
    }

    public function test_create_quote_stores_quote_with_correct_data(): void
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

        $quote = $this->service->createQuote([
            'car_id' => $car->id,
            'glass_type_id' => $glassType->id,
            'vendor_glass_price_id' => $vendorPrice->id,
        ]);

        $this->assertInstanceOf(Quote::class, $quote);
        $this->assertSame($car->id, $quote->car_id);
        $this->assertSame($glassType->id, $quote->glass_type_id);
        $this->assertSame($vendorPrice->id, $quote->vendor_glass_price_id);
        $this->assertEquals(299.99, (float) $quote->final_price);
        $this->assertNotNull($quote->requested_at ?? $quote->created_at);

        $this->assertDatabaseCount('quotes', 1);
        $this->assertDatabaseHas('quotes', [
            'car_id' => $car->id,
            'glass_type_id' => $glassType->id,
            'vendor_glass_price_id' => $vendorPrice->id,
        ]);
    }
}
