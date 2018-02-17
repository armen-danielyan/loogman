<div id="sc-wrapper">
	<?php global $woocommerce; ?>
	<div class="row">
		<div class="col-xs-9">
			<div class="sc-title">Geselecteerde items</div>
		</div>
		<div class="col-xs-3">
			<span class="sc-count"><?php echo  $woocommerce->cart->cart_contents_count; ?></span>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="sc-items">
				<table class="table">
					<thead>
					<tr>
						<th>Sign</th>
						<th>Aantal</th>
						<th>Prijs</th>
					</tr>
					</thead>
					<tbody>
					<?php $items = $woocommerce->cart->get_cart();
					foreach($items as $item => $values): ?>
						<tr>
							<?php $product = wc_get_product($values['data']->get_id());
							$getProductDetail = wc_get_product($values['product_id']);
							$price = get_post_meta($values['product_id'] , '_price', true); ?>
							<td>
								<?php echo $getProductDetail->get_image(); ?>
								<?php echo $product->get_title(); ?>
							</td>
							<td>
								<?php echo $values['quantity']; ?>
							</td>
							<td>
								<?php echo $price; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>









	<?php
	$items = $woocommerce->cart->get_cart();

	foreach($items as $item => $values) {
		$_product =  wc_get_product( $values['data']->get_id() );
		//product image
		$getProductDetail = wc_get_product( $values['product_id'] );
		echo $getProductDetail->get_image(); // accepts 2 arguments ( size, attr )

		echo "<b>".$_product->get_title() .'</b>  <br> Quantity: '.$values['quantity'].'<br>';
		$price = get_post_meta($values['product_id'] , '_price', true);
		echo "  Price: ".$price."<br>";
		/*Regular Price and Sale Price*/
		echo "Regular Price: ".get_post_meta($values['product_id'] , '_regular_price', true)."<br>";
		echo "Sale Price: ".get_post_meta($values['product_id'] , '_sale_price', true)."<br>";
	}
	?>
</div>