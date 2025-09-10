<!DOCTYPE html>
<html><body>
<h1>New Payment</h1>
<form method="POST" action="/payments">
<?= csrf_input() ?>
<input name="method" placeholder="Method">
<input name="amount" placeholder="Amount">
<button type="submit">Save</button>
</form>
</body></html>
