<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pakistaniNames = [
            // Male names
            'Muhammad Ali', 'Ahmed Hassan', 'Hassan Khan', 'Ali Raza', 'Fatima Ahmed',
            'Bilal Ahmed', 'Omar Khan', 'Usman Malik', 'Tariq Ahmed', 'Adnan Khan',
            'Rizwan Hassan', 'Samir Khan', 'Faisal Ahmed', 'Ibrahim Khan', 'Karim Hassan',
            'Nasir Ahmed', 'Qasim Khan', 'Rahim Hassan', 'Sohail Ahmed', 'Tahir Khan',
            'Shafiq Ahmed', 'Wasim Khan', 'Altaf Hassan', 'Aslam Ahmed', 'Bashir Khan',
            'Chand Ahmed', 'Dawood Khan', 'Ehsan Hassan', 'Farooq Ahmed', 'Ghani Khan',
            'Hamid Ahmed', 'Imran Khan', 'Jalil Hassan', 'Kamran Ahmed', 'Liaquat Khan',
            // Female names
            'Aisha Khan', 'Zainab Ahmed', 'Hina Hassan', 'Saira Khan', 'Nida Ahmed',
            'Shabana Khan', 'Raufa Hassan', 'Noor Ahmed', 'Eman Khan', 'Farida Hassan',
            'Gulnar Ahmed', 'Houria Khan', 'Issra Hassan', 'Jasmine Ahmed', 'Khalida Khan',
            'Laila Hassan', 'Mariam Ahmed', 'Nadia Khan', 'Omara Hassan', 'Parveen Ahmed',
            'Qamar Khan', 'Rukhsana Hassan', 'Samina Ahmed', 'Tania Khan', 'Ulfat Hassan',
            'Vina Ahmed', 'Wafa Khan', 'Yasmin Hassan', 'Zahra Ahmed', 'Amina Khan',
        ];
        
        return [
            'name' => $this->faker->randomElement($pakistaniNames),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
