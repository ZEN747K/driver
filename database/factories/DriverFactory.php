<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'password_for_profile' => $this->faker->password(),
            'birthdate' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'id_card_path' => 'docs/id_card.png',
            'driver_license_path' => 'docs/license.png',
            'face_photo_path' => 'docs/face.png',
            'vehicle_registration_path' => 'docs/registration.png',
            'compulsory_insurance_path' => 'docs/compulsory.png',
            'vehicle_insurance_path' => 'docs/insurance.png',
            'service_type' => $this->faker->randomElement(['car', 'motorcycle', 'delivery']),
            'status' => $this->faker->randomElement(['No_approve', 'Pending', 'Approved']),
        ];
    }
}
