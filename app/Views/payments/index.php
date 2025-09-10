<!DOCTYPE html>
<html><body>
<h1>Payments</h1>
<ul>
<?php foreach ($list as $p): ?>
<li><?= htmlspecialchars($p->amount) ?></li>
<?php endforeach; ?>
</ul>
<a href="/payments/new">New</a>
</body></html>
