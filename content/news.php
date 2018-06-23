<main id="news">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="main-wrapper">
					<h3><?php the_title(); ?>fff</h3>

					<div class="sep-horizontal"></div>

					<div class="row">
						<?php $args = array(
							'post_type' => 'post',
							'order' => 'ASC',
							'orderby' => 'date'
						);
						wp_reset_query();
						$newsPosts = new WP_Query($args);
						if($newsPosts->have_posts()): while($newsPosts->have_posts()): $newsPosts->the_post(); ?>
							<div class="col-sm-3 item-news">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail('thumb-medium', array('class' => 'img-responsive')); ?>
									<div class="title-news"><?php the_title(); ?></div>
									<?php $limit = 70;
									$excerpt = get_the_excerpt();
									if(strlen($excerpt) > $limit) {
										$excerpt = substr($excerpt, 0, $limit) . '....';
									}; ?>
									<div class="text-secondary"><?php echo $excerpt; ?></div>
								</a>
							</div>
						<?php endwhile; endif; ?>
					</div>

				</div>
			</div>
		</div>
	</div>
</main>