<?php
require_once 'src/ArticleRepository.php';
require_once 'src/Models/Article.php';
require_once 'helpers/helpers.php';

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and trim input values
    $title = trim($_POST['title'] ?? '');
    $url   = trim($_POST['url'] ?? '');

    // Simple validation
    if ($title === '' || strlen($title) > 256) {
        $errors[] = 'Title is required and must be between 1 and 256 characters.';
    }
    if ($url === '' || filter_var($url, FILTER_VALIDATE_URL) === false) {
        $errors[] = 'A valid URL is required.';
    }

    // If no errors, save the article and redirect
    if (count($errors) === 0) {
        $repo = new ArticleRepository('articles.json');
        $article = new Article(0);
        $article->fill(array(
            'title' => $title,
            'url'   => $url
        ));
        $repo->saveArticle($article);

        redirect('index.php?msg=' . urlencode('Article added successfully!'));
    } else {
        // Redirect back with errors encoded in the URL
        redirect('new_article.php?errors=' . urlencode(json_encode($errors)));
    }
}

// Decode any errors passed back via the URL
if (isset($_GET['errors'])) {
    $decoded = json_decode($_GET['errors'], true);
    if (is_array($decoded)) {
        $errors = $decoded;
    }
}
?>

<!doctype html>
<html lang="en">

<?php require_once 'layout/header.php' ?>

<body>

    <?php require_once 'layout/navigation.php' ?>

    <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">

        <h2 class="text-xl text-center font-semibold text-indigo-700 mt-10">Add New Article</h2>

        <?php if ($errors): ?>
            <div class="alert alert-error shadow-lg my-6">
                <div>
                    <span>
                        <?php foreach ($errors as $e): ?>
                            <?php echo e($e); ?><br>
                        <?php endforeach; ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" class="mt-6 space-y-6">

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Title</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    maxlength="256"
                    placeholder="Enter article title"
                    class="input input-bordered w-full" 
                    required>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Link (URL)</span>
                </label>
                <input 
                    type="url" 
                    name="url" 
                    placeholder="https://example.com"
                    class="input input-bordered w-full" 
                    required>
            </div>

            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary w-full">Submit</button>
            </div>

        </form>

    </div>

</body>

</html>
