<header>
	<nav id="main-menu" class="navbar navbar-default car-navbar no-border" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-main-menu">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<? echo home_url(); ?>">
					<img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/logo.png'; ?>" alt="">
				</a>
			</div>
			<div id="header-main-menu" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li id="location">LOCATIE: <strong><?php echo get_user_meta(get_current_user_id(), '_location', true); ?></strong></li>
				</ul>

				<?php wp_nav_menu( array(
						'menu'              => 'main-menu',
						'theme_location'    => 'loogman',
						'depth'             => 2,
						'menu_class'        => 'nav navbar-nav navbar-right',
						'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
						'walker'            => new wp_bootstrap_navwalker())
				); ?>

				<?php global $current_user;
				wp_get_current_user(); ?>
                <ul class="nav navbar-nav navbar-right">
                    <li id="username">Gebruiker: <strong><?php echo $current_user->user_login; ?></strong></li>
                    <li id="logout"><a href="<?php echo wp_logout_url( get_the_permalink(139) ); ?>">Uitloggen</a></li>
                </ul>
			</div>
		</div>
	</nav>
</header>