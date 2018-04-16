<main id="confirm">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div id="main-wrapper">
					<div class="row">
						<div class="col-xs-3">
							<a href="<?php echo home_url(); ?>" class="btn-back">BACK</a>
						</div>
						<div class="col-xs-6 col-xs-offset-3">
							<?php get_template_part('component/search'); ?>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<?php while(have_posts()): the_post(); ?>
								<h3><?php the_title(); ?></h3>
							<?php endwhile; ?>
						</div>
					</div>

					<div class="sep-horizontal"></div>

					<div class="row">
						<div class="col-md-12">
							<h4>Heeft u nog opmerkingen bij deze bestelling?</h4>
							<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod.</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea id="additional-notes" class="form-control" rows="15"></textarea>
                                    <div class="row">
                                        <div class="col-md-3 col-md-offset-9">
                                            <a id="add-notes" class="btn-back-dark" href="#"><?php echo strtoupper('toevoegen'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-5">
				<?php get_template_part('component/sc-confirm'); ?>
			</div>
		</div>
	</div>
</main>
