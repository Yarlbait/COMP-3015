<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/Models/Article.php';

class ArticleRepository
{
    private string $filename;

    public function __construct(string $theFilename)
    {
        $this->filename = $theFilename;

        // file_exists + ensure storage file is present
        if (!file_exists($this->filename)) {
            write_json($this->filename, array());
        }
    }

    /**
     * @return Article[]
     */
    public function getAllArticles(): array
    {
        // Use the simple helper if you have it; otherwise keep your original read logic
        $decodedArticles = read_json($this->filename);
        if (!is_array($decodedArticles)) 
            {
            return array();
        }

        $articles = array();
        foreach ($decodedArticles as $decodedArticle) 
            {
            // Use the saved id if present
            $articleId = isset($decodedArticle['id']) ? (int)$decodedArticle['id'] : 0;

            // Construct the Article with its id and fill other fields
            $articles[] = (new Article($articleId))->fill($decodedArticle);
        }
        return $articles;
    }

    public function getArticleById(int $id): Article|null
    {
        $articles = $this->getAllArticles();
        foreach ($articles as $article) 
            {
            if ($article->getId() === $id) {
                return $article;
            }
        }
        return null;
    }

    /**
     * Delete an article by id (no error if not found).
     */
    public function deleteArticleById(int $id): void
    {
        $all = read_json($this->filename);
        if (!is_array($all)) 
            {
            $all = array();
        }

        $new = array();
        foreach ($all as $row) 
            {
            $rowId = isset($row['id']) ? (int)$row['id'] : 0;
            if ($rowId !== $id) 
                {
                $new[] = $row;
            }
        }

        write_json($this->filename, $new);
    }

    /**
     * Save a new article. If it has no id (<=0), assign the next integer id.
     */
    public function saveArticle(Article $article): void
    {
        $all = read_json($this->filename);
        if (!is_array($all)) 
            {
            $all = array();
        }

        $id = (int)$article->getId();
        if ($id <= 0) 
            {
            $id = $this->nextId($all);

            // If your Article has setId(), keep the object consistent:
            if (method_exists($article, 'setId')) 
                {
                $article->setId($id);
            }
        }

        $all[] = array(
            'id'    => $id,
            'title' => $article->getTitle(),
            'url'   => $article->getUrl(),
        );

        write_json($this->filename, $all);
    }

    /**
     * Update an existing article by id (no error if not found).
     */
    public function updateArticle(int $id, Article $updatedArticle): void
    {
        $all = read_json($this->filename);
        if (!is_array($all)) 
            {
            $all = array();
        }

        for ($i = 0; $i < count($all); $i++) 
            {
            $rowId = isset($all[$i]['id']) ? (int)$all[$i]['id'] : 0;
            if ($rowId === $id) 
                {
                // Keep the same id; update title and url from the given Article
                $all[$i]['title'] = $updatedArticle->getTitle();
                $all[$i]['url']   = $updatedArticle->getUrl();
                break;
            }
        }

        write_json($this->filename, $all);
    }

    /**
     * Compute next integer id = max existing id + 1
     * @param array $rows
     * @return int
     */
    private function nextId(array $rows): int
    {
        $max = 0;
        foreach ($rows as $row) 
            {
            if (isset($row['id'])) 
                {
                $max = max($max, (int)$row['id']);
            }
        }
        return $max + 1;
    }
}