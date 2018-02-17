<main id="home">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div id="main-wrapper">
					<?php while(have_posts()): the_post(); ?>
						<?php the_title(); ?>
						<?php the_content(); ?>
					<?php endwhile; ?>
				</div>
			</div>

			<div class="col-md-5">
				<?php get_template_part('component/sc'); ?>
			</div>
		</div>
	</div>
</main>