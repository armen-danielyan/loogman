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
						<?php if(have_posts()): $i = 0; ?>
							<div class="col-sm-12 archive-pagination"><?php pagination(); ?></div>
							<div class="col-sm-12"><div class="sep-horizontal"></div></div>
							<div class="col-sm-12">
								<?php while(have_posts()): the_post(); ?>

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
							<div class="col-sm-12 archive-pagination"><?php pagination(); ?></div>
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