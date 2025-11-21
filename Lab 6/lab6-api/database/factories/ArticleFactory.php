<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extend Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * define models default state
     */
    public function definition() : array
    {
        return [
            'title' => $this->faker->sentence(4),
            'url'   => $this->faker->url(),
            'views' => 0,
        ];
    }
}
