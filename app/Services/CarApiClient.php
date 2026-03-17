<?php

namespace App\Services;

/**
 * Mocked external API for car data
 *
 */
class CarApiClient
{
    /**
     * Get list ofcar makes
     *
     * @return array<int, string>
     */
    public function getMakes(): array
    {
        return array_keys($this->data());
    }

    /**
     * Get list of models for a specific make
     *
     * @param string $make
     * @return array<int, string>
     */
    public function getModels(string $make): array
    {
        $data = $this->data();

        return isset($data[$make]) ? array_keys($data[$make]) : [];
    }

    /**
     * Get list of years for a specific make and model
     *
     * @param string $make
     * @param string $model
     * @return array<int, int>
     */
    public function getYears(string $make, string $model): array
    {
        $data = $this->data();

        if (! isset($data[$make][$model])) {
            return [];
        }

        return array_keys($data[$make][$model]);
    }

    /**
     * Get list of body types for a specific make / model / year
     *
     * @param string $make
     * @param string $model
     * @param int    $year
     * @return array<int, string>
     */
    public function getBodyTypes(string $make, string $model, int $year): array
    {
        $data = $this->data();

        if (! isset($data[$make][$model][$year])) {
            return [];
        }

        return $data[$make][$model][$year];
    }

    /**
     * Mocked external API for car data
     *
     * @return array<string, array<string, array<int, array<int, string>>>>
     */
    private function data(): array
    {
        return [
            'Toyota' => [
                'Corolla' => [
                    2018 => ['Sedan', 'Hatchback'],
                    2019 => ['Sedan'],
                    2020 => ['Sedan', 'Hatchback'],
                ],
                'RAV4' => [
                    2018 => ['SUV'],
                    2019 => ['SUV'],
                    2020 => ['SUV'],
                ],
            ],
            'Volkswagen' => [
                'Golf' => [
                    2017 => ['Hatchback'],
                    2018 => ['Hatchback'],
                ],
                'Passat' => [
                    2018 => ['Sedan'],
                    2019 => ['Sedan'],
                ],
            ],
        ];
    }
}

