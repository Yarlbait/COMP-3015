<?php

require_once __DIR__ . '/../src/Repositories/PostRepository.php';
require_once __DIR__ . '/../src/Models/Post.php';

use PHPUnit\Framework\TestCase;
use src\Repositories\PostRepository;

final class PostRepositoryTest extends TestCase
{
    private PostRepository $postRepository;

    protected function setUp(): void
    {
        parent::setUp();

        //find db by provided sql
        $schemaFile = __DIR__ . '/../database/test_schema.sql';
        $sql = file_get_contents($schemaFile);

        //credentials for mySQL
        $dsn = 'mysql:host=localhost;';
        $user = 'root';
        $pass = '';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        //create posts_webb_app_test and posts tables
        $serverPdo = new PDO($dsn, $user, $pass, $options);
        $serverPdo->exec($sql); 

        //Use repository APP_ENV=testing connects posts_web_app_test
        $this->postRepository = new PostRepository();

        //clean table every test
        $this->postRepository->db()->exec('DELETE FROM posts');
    }

    public function testPostCreation(): void
    {
        $post = $this->postRepository->savePost('Hello', 'World');

        $this->assertNotFalse($post);
        $this->assertSame('Hello', $post->title);
        $this->assertSame('World', $post->body);

        $fetched = $this->postRepository->getPostById((int)$post->id);
        $this->assertNotFalse($fetched);
        $this->assertSame($post->id, $fetched->id);
    }

    public function testPostRetrieval(): void
    {
        $this->postRepository->savePost('A', 'a');
        $this->postRepository->savePost('B', 'b');

        $all = $this->postRepository->getAllPosts();
        $this->assertGreaterThanOrEqual(2, count($all));
        $this->assertIsObject($all[0]);
    }

    public function testPostUpdate(): void
    {
        $post = $this->postRepository->savePost('Old', 'Body');
        $ok   = $this->postRepository->updatePost((int)$post->id, 'New', 'Body2');
        $this->assertTrue($ok);

        $fresh = $this->postRepository->getPostById((int)$post->id);
        $this->assertNotFalse($fresh);
        $this->assertSame('New', $fresh->title);
        $this->assertSame('Body2', $fresh->body);
        $this->assertNotEmpty($fresh->updated_at);
    }

    public function testPostDeletion(): void
    {
        $post = $this->postRepository->savePost('Tmp', 'Delete Me');
        $id   = (int)$post->id;

        $ok = $this->postRepository->deletePostById($id);
        $this->assertTrue($ok);

        $gone = $this->postRepository->getPostById($id);
        $this->assertFalse($gone);
    }
}
