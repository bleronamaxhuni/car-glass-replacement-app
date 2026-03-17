<?php

namespace Tests\Unit;

use App\Services\CarApiClient;
use PHPUnit\Framework\TestCase;

class CarApiClientTest extends TestCase
{
    private CarApiClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new CarApiClient;
    }

    public function test_get_makes_returns_list_of_makes(): void
    {
        $makes = $this->client->getMakes();

        $this->assertIsArray($makes);
        $this->assertContains('Toyota', $makes);
        $this->assertContains('Volkswagen', $makes);
        $this->assertCount(2, $makes);
    }

    public function test_get_models_returns_models_for_valid_make(): void
    {
        $models = $this->client->getModels('Toyota');

        $this->assertIsArray($models);
        $this->assertContains('Corolla', $models);
        $this->assertContains('RAV4', $models);
        $this->assertCount(2, $models);
    }

    public function test_get_models_returns_empty_array_for_unknown_make(): void
    {
        $models = $this->client->getModels('UnknownMake');

        $this->assertIsArray($models);
        $this->assertEmpty($models);
    }

    public function test_get_years_returns_years_for_valid_make_and_model(): void
    {
        $years = $this->client->getYears('Toyota', 'Corolla');

        $this->assertIsArray($years);
        $this->assertContains(2018, $years);
        $this->assertContains(2019, $years);
        $this->assertContains(2020, $years);
        $this->assertCount(3, $years);
    }

    public function test_get_years_returns_empty_array_for_unknown_make_or_model(): void
    {
        $this->assertEmpty($this->client->getYears('Toyota', 'UnknownModel'));
        $this->assertEmpty($this->client->getYears('UnknownMake', 'Corolla'));
    }

    public function test_get_body_types_returns_body_types_for_valid_selection(): void
    {
        $bodyTypes = $this->client->getBodyTypes('Toyota', 'Corolla', 2019);

        $this->assertIsArray($bodyTypes);
        $this->assertContains('Sedan', $bodyTypes);
        $this->assertCount(1, $bodyTypes);
    }

    public function test_get_body_types_returns_multiple_for_supported_years(): void
    {
        $bodyTypes = $this->client->getBodyTypes('Toyota', 'Corolla', 2018);

        $this->assertIsArray($bodyTypes);
        $this->assertContains('Sedan', $bodyTypes);
        $this->assertContains('Hatchback', $bodyTypes);
        $this->assertCount(2, $bodyTypes);
    }

    public function test_get_body_types_returns_empty_array_for_unknown_selection(): void
    {
        $this->assertEmpty($this->client->getBodyTypes('Toyota', 'Corolla', 2015));
        $this->assertEmpty($this->client->getBodyTypes('Unknown', 'Corolla', 2019));
    }
}
