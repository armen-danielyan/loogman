<main id="blog">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-sm-4 img-product-wrapper">
							<?php if(has_post_thumbnail()) {
								the_post_thumbnail('thumb-large', array('class' => 'img-responsive'));
							} else {
								echo wp_get_attachment_image(135, 'thumb-large', '', array('class' => 'img-responsive'));
							} ?>
                        </div>

                        <div class="col-sm-8">
                            <h3><?php the_title(); ?></h3>
                            <div class="text-secondary"><?php echo get_post_field('post_content', $post->ID); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>