<!DOCTYPE html>
<html><body>
<h1>Products</h1>
<ul>
<?php foreach ($list as $p): ?>
<li><?= htmlspecialchars($p->name) ?></li>
<?php endforeach; ?>
</ul>
<a href="/products/create">New</a>
</body></html>
