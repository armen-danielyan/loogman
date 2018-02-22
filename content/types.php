<main id="types">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div id="main-wrapper">
                    <div class="row">
                        <div class="col-xs-3">
                            <a href="<? echo home_url(); ?>" class="btn-back">BACK</a>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3">
	                        <?php get_template_part('component/search'); ?>
                        </div>
                    </div>

                    <?php $qObject = get_queried_object();
                    $termId = $qObject->term_id;
                    $tax = $qObject->taxonomy; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="category-title-wrap">
	                            <?php $image_id = get_term_meta( $termId, 'loogman-' . $tax . '-image-id', true ); ?>
	                            <?php if( $image_id ) { ?>
		                            <?php echo wp_get_attachment_image( $image_id, 'thumb-small', '', array('class' => 'category-img') ); ?>
	                            <?php } ?>
                                <h3><?php echo get_queried_object()->name; ?></h3>
                                <div class="text-secondary"><?php echo get_queried_object()->description; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
	                    <?php $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	                    $productsArchiveArgs = array(
		                    'post_type'         => 'products',
		                    'author__in'        => array(get_current_user_id()),
		                    'posts_per_page'    => 12,
		                    'paged'             => $paged,
		                    'tax_query'         => array(
			                    array(
				                    'taxonomy'  => 'types',
				                    'field'     => 'id',
				                    'terms'     => (int)$termId
			                    )
		                    )
	                    );

	                    $productsQuery = new WP_Query( $productsArchiveArgs ); ?>
	                    <?php if($productsQuery->have_posts()): $i = 0; ?>
                            <div class="col-sm-12 archive-pagination"><?php pagination($productsQuery->max_num_pages); ?></div>
                            <div class="col-sm-12"><div class="sep-horizontal"></div></div>
                            <div class="col-sm-12">
                                <?php while($productsQuery->have_posts()): $productsQuery->the_post(); ?>

                                    <?php if($i % 3 === 0) echo '<div class="row">'; ?>

                                    <div class="col-sm-4 img-archive-item">
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="img-archive-wrapper">
                                                <div class="img-archive-cover">
                                                </div>
                                                <?php if(has_post_thumbnail()) {
                                                    the_post_thumbnail('thumb-archive', array('class' => 'img-responsive'));
                                                } else {
                                                    echo wp_get_attachment_image(135, 'thumb-archive', '', array('class' => 'img-responsive'));
                                                } ?>
                                            </div>
                                            <div class="img-archive-title"><?php the_title(); ?></div>
                                        </a>
                                    </div>

                                    <?php if($i % 3 === 2) echo '</div>'; $i++; ?>

                                <?php endwhile; ?>

                                <?php if($i < 12 && $i % 3 !== 2) echo '</div>'; ?>

                            </div>
                            <div class="col-sm-12"><div class="sep-horizontal"></div></div>
                            <div class="col-sm-12 archive-pagination"><?php pagination($productsQuery->max_num_pages); ?></div>
	                    <?php endif; ?>
                    </div>

				</div>
			</div>

			<div class="col-md-5">
				<?php get_template_part('component/sc'); ?>
			</div>
		</div>
	</div>
</main>