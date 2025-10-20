<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/redirect.php';
require_once __DIR__ . '/../db.php';

/**
 * POST section — handles create and/or delete using PRG (303 redirects)
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $action = (string)($_POST['action'] ?? '');

    if ($action === 'create')
    {
        $name = trim((string)($_POST['item_name'] ?? ''));
        $qty  = (int)($_POST['quantity'] ?? -1);

        if ($name === '' || $qty < 0)
        {
            flash_set('error', 'Item name is required and quantity must be 0 or greater.');
            redirect('index.php');
        }

        $stmt = db()->prepare("INSERT INTO inventory (item_name, quantity) VALUES (:name, :qty)");
        $stmt->execute([':name' => $name, ':qty' => $qty]);

        flash_set('success', 'Item added.');
        redirect('index.php');
    }
    elseif ($action === 'delete')
    {
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0)
        {
            flash_set('error', 'Invalid id.');
            redirect('index.php');
        }

        $stmt = db()->prepare("DELETE FROM inventory WHERE id = :id");
        $stmt->execute([':id' => $id]);

        flash_set('success', 'Item deleted.');
        redirect('index.php');
    }
    else
    {
        flash_set('error', 'Unknown action.');
        redirect('index.php');
    }
}

/**
 * GET section — handles search and reset (link back to index.php)
 */
$q = trim((string)($_GET['q'] ?? ''));
if ($q !== '')
{
    $stmt = db()->prepare("SELECT * FROM inventory WHERE item_name LIKE :term ORDER BY id DESC");
    $stmt->execute([':term' => "%{$q}%"]);
    $rows = $stmt->fetchAll();
}
else
{
    $stmt = db()->query("SELECT * FROM inventory ORDER BY id DESC");
    $rows = $stmt->fetchAll();
}

$success = flash_get('success');
$error   = flash_get('error');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Grocery Inventory</title>
  <style>
    :root { color-scheme: light; }
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
    h1 { margin-bottom: .25rem; }
    p.muted { color: #666; margin-top: 0; }
    .flash-success { background:#e6ffed; border:1px solid #b7f5c1; padding:.5rem .75rem; border-radius:8px; margin:.75rem 0; }
    .flash-error { background:#ffecec; border:1px solid #ffc2c2; padding:.5rem .75rem; border-radius:8px; margin:.75rem 0; }
    .row { display:flex; gap:.5rem; align-items:center; flex-wrap: wrap; }
    form { margin:.25rem 0; }
    input[type="text"], input[type="search"], input[type="number"] { padding:.45rem .55rem; border:1px solid #bbb; border-radius:8px; }
    button, .btn { padding:.45rem .65rem; border:1px solid #aaa; background:#f8f8f8; border-radius:8px; cursor:pointer; text-decoration:none; }
    table { width:100%; border-collapse:collapse; margin-top:.75rem; }
    th, td { border:1px solid #ddd; padding:.55rem .6rem; text-align:left; }
    th { background:#fafafa; }
    td form { margin:0; }
  </style>
</head>
<body>
  <h1>Grocery Inventory</h1>
  <p class="muted">Add items, search by name, delete items. Use Reset to clear the search.</p>

  <?php if ($success): ?><div class="flash-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="flash-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <!-- Create -->
  <h2>Add Item</h2>
  <form method="post" action="index.php" class="row" autocomplete="off">
    <input type="hidden" name="action" value="create">
    <label>Item</label>
    <input type="text" name="item_name" required placeholder="e.g., Bananas">
    <label>Qty</label>
    <input type="number" name="quantity" min="0" value="0" required>
    <button type="submit">Add</button>
  </form>

  <!-- Search / Reset -->
  <h2>Inventory</h2>
  <form method="get" action="index.php" class="row">
    <input type="search" name="q" placeholder="Search items..." value="<?= htmlspecialchars($q) ?>">
    <button type="submit">Search</button>
    <a class="btn" href="index.php">Reset</a>
  </form>

  <?php if (empty($rows)): ?>
    <p>No results<?= $q !== '' ? ' for "<strong>' . htmlspecialchars($q) . '</strong>"' : '' ?>.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th style="width:70px;">ID</th>
          <th>Item</th>
          <th style="width:140px;">Quantity</th>
          <th style="width:140px;">Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['item_name']) ?></td>
            <td><?= (int)$r['quantity'] ?></td>
            <td>
              <form method="post" action="index.php" onsubmit="return confirm('Delete this item?');">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                <button type="submit">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>
