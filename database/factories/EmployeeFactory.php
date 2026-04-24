<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->numerify('######'),
            'nip' => $this->faker->unique()->numerify('1205#####'),
            'full_name' => strtoupper($this->faker->name()),
            'nick_name' => strtoupper($this->faker->firstName()),
            'mobile' => $this->faker->phoneNumber(),
            'position' => $this->faker->jobTitle(),
            'work_at' => 'PT. JASA SWADAYA UTAMA',
            'location' => $this->faker->city(),
            'join_date' => $this->faker->date(),
            'clothes_size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'pants_size' => $this->faker->numberBetween(28, 40),
            'email' => $this->faker->unique()->safeEmail(),
            'is_active' => true,
            'id_card_print' => false,
        ];
    }
}
