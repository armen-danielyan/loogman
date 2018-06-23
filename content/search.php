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

					<div class="row">
						<?php global $query_string;
						$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;


						$searchParams = array(
							'orderby'           => 'title',
							'order'             => 'ASC',
							'posts_per_page'    => 12,
							'paged'             => $paged,
                        );
						wp_parse_str( $query_string, $searchQuery );
						$args = array_merge($searchQuery, $searchParams);
						$search = new WP_Query( $args );

						if($search->have_posts()): $i = 0; ?>
							<div class="col-sm-12 archive-pagination"><?php pagination($search->max_num_pages); ?></div>
							<div class="col-sm-12"><div class="sep-horizontal"></div></div>
							<div class="col-sm-12">
								<?php while($search->have_posts()): $search->the_post(); ?>

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

								<?php if(($i - 1) % 3 !== 2) echo '</div>'; ?>

							</div>
							<div class="col-sm-12"><div class="sep-horizontal"></div></div>
							<div class="col-sm-12 archive-pagination"><?php pagination($search->max_num_pages); ?></div>
						<?php else: ?>
							<h4>No Result</h4>
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