<?php

namespace Elegasoft\Cipher\Tests;

class TextFactory extends \Illuminate\Database\Eloquent\Factories\Factory
{
    protected $model = Text::class;

    /**
     * {@inheritDoc}
     */
    public function definition()
    {
        return [
            'string' => $this->faker->randomElement([
                $this->faker->password,
                $this->faker->uuid,
                nl2br($this->faker->address),
                $this->faker->title . $this->faker->isbn10(),
            ]),
        ];
    }
}
