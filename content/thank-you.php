<main id="thank-you">
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
						<div class="col-md-12">
							<?php while(have_posts()): the_post(); ?>
								<h3><?php the_title(); ?></h3>
							<?php endwhile; ?>
						</div>
					</div>

                    <div class="sep-horizontal"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Wij nemen spoedig contact met u op voor een afspraak</h4>
                            <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod.</div>
                        </div>
                    </div>
				</div>
			</div>

			<div class="col-md-5">
				<?php get_template_part('component/sc-thank-you'); ?>
			</div>
		</div>
	</div>
</main>
