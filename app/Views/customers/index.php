<!DOCTYPE html>
<html><body>
<h1>Customers</h1>
<ul>
<?php foreach ($list as $c): ?>
<li><?= htmlspecialchars($c->name) ?></li>
<?php endforeach; ?>
</ul>
<a href="/customers/create">New</a>
</body></html>
