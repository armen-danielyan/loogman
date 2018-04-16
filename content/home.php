<main id="home">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div id="main-wrapper">
					<h3><?php the_title(); ?></h3>
					<div class="text-secondary"><?php echo get_post_field('post_content', $post->ID); ?></div>

					<div class="sep-horizontal"></div>

					<h4>Kies een productgroep</h4>
					<div class="row">
						<?php $categories = array(31, 24, 25, 26);
						foreach($categories as $catId):
							$term = get_term($catId, 'types');
							$thumbId = get_term_meta($catId, 'loogman-types-image-id', true);

							$catName = $term->name;
							$catUrl = get_category_link($catId);
							$imageUrl = wp_get_attachment_url($thumbId); ?>

							<div class="col-xs-3 item-cat">
								<a href="<?php echo $catUrl; ?>">
									<img src="<?php echo $imageUrl; ?>" alt="<?php echo $catName; ?>">
									<div class="title-cat"><?php echo $catName; ?></div>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
                    <div class="row">
						<?php $categories = array(27, 28);
						foreach($categories as $catId):
							$term = get_term($catId, 'types');
							$thumbId = get_term_meta($catId, 'loogman-types-image-id', true);

							$catName = $term->name;
							$catUrl = get_category_link($catId);
							$imageUrl = wp_get_attachment_url($thumbId); ?>

                            <div class="col-xs-3 item-cat">
                                <a href="<?php echo $catUrl; ?>">
                                    <img src="<?php echo $imageUrl; ?>" alt="<?php echo $catName; ?>">
                                    <div class="title-cat"><?php echo $catName; ?></div>
                                </a>
                            </div>
						<?php endforeach; ?>
                    </div>

					<div class="sep-horizontal"></div>

					<h4>Direct zoeken op naam</h4>
					<?php get_template_part('component/search'); ?>

					<div class="sep-horizontal"></div>
					<div class="row">
						<?php $args = array(
							'post_type' => 'post',
							'order' => 'ASC',
							'orderby' => 'date',
							'posts_per_page' => '2'
						);
						$newsPosts = new WP_Query($args);
						if($newsPosts->have_posts()): while($newsPosts->have_posts()): $newsPosts->the_post(); ?>
							<div class="col-sm-6 item-news">
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

			<div class="col-md-5">
				<?php get_template_part('component/sc'); ?>
			</div>
		</div>
	</div>
</main>