<!DOCTYPE html>
<html><body>
<h1>New Customer</h1>
<form method="POST" action="/customers">
<?= csrf_input() ?>
<input name="code" placeholder="Code">
<input name="name" placeholder="Name">
<button type="submit">Save</button>
</form>
</body></html>
