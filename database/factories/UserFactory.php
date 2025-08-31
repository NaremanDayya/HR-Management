<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

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
        $faker = $this->faker;
        $faker->locale('ar_SA'); 

        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // default password
            'role' => $faker->randomElement(['employee', 'manager', 'admin']),
            'privileges' => null,
            'account_status' => $faker->randomElement(['active', 'inactive']),
            'contact_info' => [
                'phone_type' => $faker->randomElement(['mobile', 'home', 'work']),
                'phone_number' => $faker->phoneNumber,
                'residence' => $faker->city,
                'area' => $faker->streetName,
                'residence_neighborhood' => $faker->word,
            ],
            'size_info' => [
                'Tshirt_size' => $faker->randomElement(['S', 'M', 'L', 'XL']),
                'pants_size' => $faker->randomElement(['30', '32', '34', '36']),
            ],
            'birthday' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'age' => null, // will be dynamic
            'id_card' => $faker->numerify('###########'), // random 11 digit number
            'nationality' => $faker->randomElement(['فلسطيني', 'مصري', 'سوري', 'لبناني']),
            'gender' => $faker->randomElement(['male', 'female']),
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

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
