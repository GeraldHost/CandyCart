<p style="font-family: Consolas,Monaco,monospace;">
	You can define your own order summary by creating a file named 
	"order-summary" in the following path: <br/>wp-content/theme/yourtheme/candy/templates/. 
	You can download the existing template at github.com</p>
<hr>

<p>Order ID: 000</p>
<p><b>Customer</b></p>
<p>Customer Name: <?= get_post_meta(get_the_ID(), 'customer_name', true); ?>
<br/>Customer Email: <?= get_post_meta(get_the_ID(), 'customer_email', true); ?></p>

<p><b>Shipping Address</b></p>
<p><?= get_post_meta(get_the_ID(), 'shipping_address', true); ?> <br/>
<?= get_post_meta(get_the_ID(), 'shipping_address_1', true); ?><br/>
<?= get_post_meta(get_the_ID(), 'shipping_address_2', true); ?><br/>
<?= get_post_meta(get_the_ID(), 'shipping_postcode', true); ?></p>

<p><b>Cart Items</b></p>
<ul>
	<?php
	$items = unserialize(get_post_meta(get_the_ID(), 'items', true));
	foreach($items as $item) :
	?>
	<li><?= $item['product']->post_title; ?> (x<?= $item['qty']; ?>)</li>
	<?php endforeach; ?>
</ul>

<p><b>Order</b></p>
<?php $totals = unserialize(get_post_meta(get_the_ID(), 'totals', true)); ?>
<p>Sub Total: <?= Candy_Store::getCurrencySymbol() . number_format($totals['sub_total']/100, 2); ?></p>
<p>Shipping Total: <?= Candy_Store::getCurrencySymbol() . number_format($totals['shipping']/100, 2); ?></p>
<p>Tax Total: <?= Candy_Store::getCurrencySymbol() . number_format($totals['tax']/100, 2); ?></p>
<p>Total: <?= Candy_Store::getCurrencySymbol() . number_format(($totals['sub_total'] + $totals['shipping'] + $totals['tax'])/100, 2); ?></p>
