<!DOCTYPE html>
<html><body>
<h1>New Invoice</h1>
<form method="POST" action="/invoices">
<?= csrf_input() ?>
<input name="desc" placeholder="Description">
<input name="qty" placeholder="Qty">
<input name="price" placeholder="Unit price">
<input name="tax" placeholder="Tax %">
<button type="submit">Save</button>
</form>
</body></html>
