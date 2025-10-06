<?php
require_once __DIR__ . '/helpers/helpers.php';
require_once __DIR__ . '/src/ArticleRepository.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id > 0) {
    $repo = new ArticleRepository(__DIR__ . '/articles.json');
    $repo->deleteArticleById($id);
}

redirect('index.php?msg=' . urlencode('Article deleted.'));

