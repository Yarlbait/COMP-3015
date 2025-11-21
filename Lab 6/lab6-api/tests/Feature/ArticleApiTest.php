<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1:
     * index() should return ONLY the latest 15 articles.
     */
    public function test_index_returns_latest_15_articles()
    {
        Article::factory()->count(20)->create();

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
                 ->assertJsonCount(15);
    }

    /**
     * Test 2:
     * index() returns an empty array when there are no articles.
     */
    public function test_index_returns_empty_array_when_no_articles()
    {
        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
                 ->assertExactJson([]);
    }

    /**
     * Test 3:
     * show() should return the article AND increment its views by 1.
     */
    public function test_show_returns_article_and_increments_views()
    {
        $article = Article::factory()->create(['views' => 0]);

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $article->id,
                     'views' => 1, // after increment
                 ]);
    }

    /**
     * Test 4:
     * show() should return 404 if the ID does not exist.
     */
    public function test_show_returns_404_if_article_not_found()
    {
        $response = $this->getJson('/api/articles/999');

        $response->assertStatus(404);
    }

    /**
     * Test 5:
     * store() should create a new article when valid data is provided.
     */
    public function test_store_creates_new_article()
    {
        $payload = [
            'title' => 'New Article',
            'url' => 'https://example.com',
        ];

        $response = $this->postJson('/api/articles', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('articles', $payload);
    }

    /**
     * Test 6:
     * store() should reject invalid input and return validation errors.
     */
    public function test_store_validates_input()
    {
        $response = $this->postJson('/api/articles', [
            'title' => '',
            'url' => 'not-a-url',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'url']);
    }

    /**
     * Test 7:
     * update() should successfully modify an existing article.
     */
    public function test_update_edits_article()
    {
        $article = Article::factory()->create();

        $payload = [
            'title' => 'Updated Title',
            'url' => 'https://updated.com',
        ];

        $response = $this->putJson("/api/articles/{$article->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('articles', $payload);
    }

    /**
     * Test 8:
     * update() should fail validation when invalid data is provided.
     */
    public function test_update_validates_input()
    {
        $article = Article::factory()->create();

        $response = $this->putJson("/api/articles/{$article->id}", [
            'title' => '',
            'url' => 'invalid',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'url']);
    }

    /**
     * Test 9:
     * destroy() should delete an article and return 204 No Content.
     */
    public function test_destroy_deletes_article()
    {
        $article = Article::factory()->create();

        $response = $this->deleteJson("/api/articles/{$article->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }

    /**
     * Test 10:
     * destroy() returns 404 when deleting a non-existent article.
     */
    public function test_destroy_returns_404_if_article_not_found()
    {
        $response = $this->deleteJson('/api/articles/999');

        $response->assertStatus(404);
    }
}
