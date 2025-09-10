<!DOCTYPE html>
<html><body>
<h1>Invoices</h1>
<ul>
<?php foreach ($list as $inv): ?>
<li><a href="/invoices/<?= $inv->id ?>">Invoice <?= htmlspecialchars($inv->number) ?></a></li>
<?php endforeach; ?>
</ul>
<a href="/invoices/new">New</a>
</body></html>
