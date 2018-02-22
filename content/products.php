<main id="products">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div id="main-wrapper">
					<?php $postId = get_the_ID();
					$qObject = get_queried_object();
					$taxes = get_post_taxonomies($postId);
					$tax = $taxes[0];
					$terms = get_the_terms($postId, $tax);
					$termId = $terms[0]->term_id; ?>

					<div class="row">
						<div class="col-xs-3">
							<a href="<?php echo get_term_link($terms[0]->term_id); ?>" class="btn-back">BACK</a>
						</div>
						<div class="col-xs-6 col-xs-offset-3">
							<?php get_template_part('component/search'); ?>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="category-title-wrap">
								<?php $image_id = get_term_meta( $termId, 'loogman-' . $tax . '-image-id', true ); ?>
								<?php if( $image_id ) { ?>
									<?php echo wp_get_attachment_image( $image_id, 'thumb-small', '', array('class' => 'category-img') ); ?>
								<?php } ?>
								<h3><?php the_title(); ?></h3>
								<div class="text-secondary"><?php the_content(); ?></div>
							</div>
						</div>
					</div>

					<div class="sep-horizontal"></div>

					<div class="row">
						<div class="col-sm-8 img-product-wrapper">
							<?php if(has_post_thumbnail()) {
								the_post_thumbnail('thumb-large', array('class' => 'img-responsive'));
							} else {
								echo wp_get_attachment_image(135, 'thumb-large', '', array('class' => 'img-responsive'));
							} ?>
						</div>

						<div class="col-sm-4">
							<?php $termName = $terms[0]->name;
							$metaFormat = get_post_meta($postId, '_loogman_formaat', true);
							$metaExtraB = get_post_meta($postId, '_loogman_extra_b', true); ?>

							<?php if($termName) { ?>
								<h5><?php echo strtoupper('Soort sign'); ?></h5>
								<div class="text-third"><?php echo $termName; ?></div>
							<?php } ?>

							<?php if($metaFormat) { ?>
								<h5><?php echo strtoupper('Formaat in cm'); ?></h5>
								<div class="text-third"><?php echo $metaFormat; ?></div>
							<?php } ?>

							<?php if($metaExtraB) { ?>
								<h5><?php echo strtoupper('Algemene info'); ?></h5>
								<div class="text-third"><?php echo $metaExtraB; ?></div>
							<?php } ?>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<?php $metaExtraA = get_post_meta($postId, '_loogman_extra_a', true); ?>

							<?php if($metaExtraA) { ?>
								<h5><?php echo strtoupper('Aanvullende informatie'); ?></h5>
								<div class="text-third"><?php echo $metaExtraA; ?></div>
							<?php } ?>

							<h5><i>Let op:</i></h5>
							<div class="text-third" style="margin-bottom: 0;">
								<i>Het oude wordt verwijderd. Nieuw artikel wordt gemonteerd, inclusief benodigde materialen</i>
							</div>
						</div>
					</div>

					<div class="sep-horizontal"></div>

					<div class="row">
						<div class="col-xs-4 col-md-12 col-lg-4">
                            <div class="location-icon-wrap">
                                <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/pdf.jpg'; ?>" alt="">
                                <a href="#"><?php echo strtoupper('Bekijk locatie'); ?></a>
                            </div>
						</div>

						<div id="add-to-cart" class="col-xs-8 col-md-12 col-lg-8">
							<div id="select-item" class="btn-green space-left" data-postid="<?php echo $postId; ?>">Selecteer item(s)</div>

							<div class="btn-gray space-left">
								<span id="btn-arrow-down" class="btn-arrow"><i class="glyphicon glyphicon-triangle-bottom"></i></span>
								<span id="select-item-amount"></span>
								<span id="btn-arrow-up" class="btn-arrow"><i class="glyphicon glyphicon-triangle-top"></i></span>
							</div>

							<div class="aantal"><?php echo strtoupper('Aantal'); ?></div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-5">
				<?php get_template_part('component/sc'); ?>
			</div>
		</div>
	</div>
</main>

<script>
    jQuery(function($) {
        $(document).ready(function() {
            var selectedAmount = 1;
            $("#select-item-amount").html(selectedAmount);
            $("#btn-arrow-down").on("click", function() {
                if(selectedAmount > 1) {
                    $("#select-item-amount").html(--selectedAmount);
                }
            });
            $("#btn-arrow-up").on("click", function() {
                if(selectedAmount < 10000) {
                    $("#select-item-amount").html(++selectedAmount);
                }
            });

            $("#select-item").on("click", function() {
                var postId = $(this).data("postid"),
                    productAmount = $("#select-item-amount").text();

                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    type: "POST",
                    data: {
                        action: "add_shopping_basket",
                        post_id: postId,
                        user_id: <?php echo get_current_user_id(); ?>,
                        product_amount: productAmount
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