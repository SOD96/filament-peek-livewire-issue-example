<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'heading' => $this->faker->sentence,
            'intro' => $this->faker->paragraph,
            'fields' => [],
            'settings' => [],
        ];
    }

    public function basic()
    {
        return $this->state(function (array $attributes) {
            return [
                'fields' => [
                    [
                        'type' => 'Text',
                        'label' => 'Name',
                        'options' => null,
                        'hint_text' => null,
                        'validation' => ['required'],
                        'helper_text' => null,
                        'placeholder' => 'Joe Bloggs',
                    ],
                    [
                        'type' => 'email',
                        'label' => 'Email',
                        'options' => null,
                        'hint_text' => null,
                        'validation' => ['required', 'email:rfc,dns'],
                        'helper_text' => null,
                        'placeholder' => 'joe@bloggs.com',
                    ],
                ],
                'settings' => [
                    'tags' => ['campaign', 'crypto', 'test'],
                    'cta_text' => 'Send',
                    'email_body' => '### You are now part of the team\n\n',
                    'email_title' => 'Welcome to the team',
                    'enable_email' => true,
                    'email_subject' => 'Thank you for submitting the form',
                    'enable_privacy' => true,
                    'privacy_policy_url' => '/privacy-policy',
                    'success_message' => [],
                    'enable_success_url' => false,
                ],
            ];
        });
    }
}
