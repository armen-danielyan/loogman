<div id="sc-wrapper">
	<div class="row">
		<div class="col-xs-9">
			<div class="sc-title">Geselecteerde items</div>
		</div>
        <?php $count = 0;
        $userId = get_current_user_id();
        $userMetaValue = json_decode(get_user_meta($userId, '_shopping_basket', true));
        if($userMetaValue) {
	        $shoppingBasketItems = get_object_vars($userMetaValue);
	        $count = count($shoppingBasketItems);
        } ?>
		<div class="col-xs-3">
			<span class="sc-count"><?php echo $count; ?></span>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="sc-items">
                <?php if($userMetaValue && $userMetaValue != '') { ?>
                    <table class="table">
                        <thead>
                        <tr>
                            <th colspan="3">Sign</th>
                            <th class="text-center">Aantal</th>
                            <th class="text-center">Prijs</th>
                        </tr>
                        </thead>
                        <tbody>
	                        <?php $totalAmoout = 0;
	                        $totalPrice = 0;
	                        $userBudget = get_user_meta($userId, '_budget', true);
	                        $notes = get_user_meta($userId, '_order_notes', true);
                            foreach ($shoppingBasketItems as $key => $item) {
	                            $postId = $item->_productId;
	                            $itemPrice = get_post_meta($postId, '_loogman_price', true); ?>
                                <tr>
                                    <td>
                                        <span class="remove-item" data-postid="<?php echo $postId; ?>">
                                            <i class="glyphicon glyphicon-remove"></i>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if(has_post_thumbnail($postId)) {
		                                    echo get_the_post_thumbnail($postId,'thumb-basket', array('class' => 'img-responsive'));
	                                    } else {
		                                    echo wp_get_attachment_image(135, 'thumb-basket', '', array('class' => 'img-responsive'));
	                                    } ?>
                                    </td>
                                    <td>
                                        <?php $taxes = get_post_taxonomies($postId);
	                                    $tax = $taxes[0];
	                                    $terms = get_the_terms($postId, $tax); ?>
                                        <a href="<?php echo get_the_permalink($postId); ?>"><?php echo get_the_title($postId); ?></a>
                                        <?php echo $terms[0]->name; ?>
                                    </td>
                                    <td class="text-forth"><?php echo $item->_productAmount; ?></td>
                                    <td class="text-forth"><?php echo number_format($itemPrice, 2, ',', ''); ?></td>
                                </tr>
                                <?php $totalAmoout += $item->_productAmount;
	                            $totalPrice += $item->_productAmount * $itemPrice; ?>
                            <?php } ?>
                        <tr>
                            <td></td>
                            <td colspan="2"><?php echo strtoupper('Totaal'); ?></td>
                            <td class="text-forth"><?php echo $totalAmoout; ?></td>
                            <td class="text-forth"><?php echo number_format($totalPrice, 2, ',', ''); ?></td>
                        </tr>
                        <?php if($notes) { ?>
                            <tr>
                                <td colspan="4" class="text-forth" style="text-align: left;"><?php echo strtoupper('Uw Opmerking Bij deze bestelling'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="4"><?php echo $notes; ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td></td>
                            <td>
                                <div class="text-fifth">Budget (€):</div>
                                 Na bestelling:
                            </td>
                            <td>
                                <div class="text-fifth"><?php echo number_format($userBudget, 2, ',', ''); ?></div>
                                <?php echo number_format($userBudget - $totalPrice, 2, ',', ''); ?>
                            </td>
                            <td colspan="2">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                <?php } ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div id="confirm-order" class="btn-green">Meer bestellen</div>
    </div>
</div>

<script>
    jQuery(function($) {
        $(document).ready(function() {
            $(".remove-item").on("click", function() {
                var postId = $(this).data("postid");

                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    type: "POST",
                    data: {
                        action: "remove_shopping_basket",
                        post_id: postId,
                        user_id: <?php echo get_current_user_id(); ?>
                    },
                    success: function(data) {
                        var parsedData = JSON.parse(data);
                        if(parsedData.status) location.reload();
                    }
                });
            });

            $("#confirm-order").on("click", function() {
                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    type: "POST",
                    data: {
                        action: "confirm-order",
                        user_id: <?php echo get_current_user_id(); ?>
                    },
                    success: function(data) {
                        var parsedData = JSON.parse(data);
                        if(parsedData.status) location.replace("<?php echo get_the_permalink(141); ?>");
                    }
                });
            });

            $("#add-notes").on("click", function() {
                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    type: "POST",
                    data: {
                        action: "add-notes",
                        user_id: <?php echo get_current_user_id(); ?>,
                        notes: $("#additional-notes").val()
                    },
                    success: function(data) {
                        var parsedData = JSON.parse(data);
                        if(parsedData.status) location.reload();
                    }
                });
            });
        })
    });
</script>