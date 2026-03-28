<?php

namespace Tests\Feature;

use Tests\TestCase;

class CarApiTest extends TestCase
{
    public function test_get_makes_returns_successful_response_with_data(): void
    {
        $response = $this->getJson('/api/car/makes');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
        $this->assertContains('Toyota', $response->json('data'));
        $this->assertContains('Volkswagen', $response->json('data'));
    }

    public function test_get_models_returns_models_for_valid_make(): void
    {
        $response = $this->getJson('/api/car/models?make=Toyota');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
        $data = $response->json('data');
        $this->assertContains('Corolla', $data);
        $this->assertContains('RAV4', $data);
    }

    public function test_get_models_returns_empty_data_when_no_models_found(): void
    {
        $response = $this->getJson('/api/car/models?make=UnknownMake');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_get_models_requires_make_parameter(): void
    {
        $response = $this->getJson('/api/car/models');

        $response->assertStatus(422);
    }

    public function test_get_years_returns_years_for_valid_make_and_model(): void
    {
        $response = $this->getJson('/api/car/years?make=Toyota&model=Corolla');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
        $data = $response->json('data');
        $this->assertContains(2018, $data);
        $this->assertContains(2019, $data);
        $this->assertContains(2020, $data);
    }

    public function test_get_years_returns_empty_data_when_no_years_found(): void
    {
        $response = $this->getJson('/api/car/years?make=Unknown&model=Unknown');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_get_years_requires_make_and_model(): void
    {
        $response = $this->getJson('/api/car/years');

        $response->assertStatus(422);
    }

    public function test_get_body_types_returns_body_types_for_valid_selection(): void
    {
        $response = $this->getJson('/api/car/body-types?make=Toyota&model=Corolla&year=2019');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
        $data = $response->json('data');
        $this->assertContains('Sedan', $data);
    }

    public function test_get_body_types_returns_empty_data_when_no_body_types_found(): void
    {
        $response = $this->getJson('/api/car/body-types?make=Toyota&model=Corolla&year=1990');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_get_body_types_requires_make_model_and_year(): void
    {
        $response = $this->getJson('/api/car/body-types');

        $response->assertStatus(422);
    }
}
