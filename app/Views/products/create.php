<!DOCTYPE html>
<html><body>
<h1>New Product</h1>
<form method="POST" action="/products">
<?= csrf_input() ?>
<input name="sku" placeholder="SKU">
<input name="name" placeholder="Name">
<input name="unit" placeholder="Unit">
<input name="price" placeholder="Price">
<input name="tax" placeholder="Tax %">
<button type="submit">Save</button>
</form>
</body></html>
