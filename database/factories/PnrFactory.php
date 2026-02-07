<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Branch;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pnr>
 */
class PnrFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'UserID' => User::factory(),
            'pnr' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'FlightNoDeparture' => $this->faker->regexify('[A-Z]{2}[0-9]{3}'),
            'SectorDeparture' => $this->faker->randomElement(['KHI-LHR', 'LHR-KHI', 'ISB-DXB', 'DXB-ISB']),
            'FlightDateDeparture' => $this->faker->dateTimeBetween('now', '+1 year'),
            'FlightTimeDeparture' => $this->faker->time('H:i'),
            'FlightDateArrivalDeparture' => $this->faker->dateTimeBetween('now', '+1 year'),
            'FlightTimeArrivalDeparture' => $this->faker->time('H:i'),
            'FlightNoReturn' => $this->faker->regexify('[A-Z]{2}[0-9]{3}'),
            'SectorReturn' => $this->faker->randomElement(['KHI-LHR', 'LHR-KHI', 'ISB-DXB', 'DXB-ISB']),
            'FlightDateReturn' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            'FlightDepartureTimeReturn' => $this->faker->time('H:i'),
            'FlightArrivalDateReturn' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            'FlightArrivalTimeReturn' => $this->faker->time('H:i'),
        ];
    }
}

