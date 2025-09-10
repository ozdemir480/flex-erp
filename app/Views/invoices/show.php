<!DOCTYPE html>
<html><body>
<h1>Invoice <?= htmlspecialchars($invoice->number) ?></h1>
<p>Total: <?= htmlspecialchars((string)$invoice->total) ?></p>
<form method="POST" action="/invoices/<?= $invoice->id ?>/post">
<?= csrf_input() ?>
<button type="submit">Post</button>
</form>
</body></html>
