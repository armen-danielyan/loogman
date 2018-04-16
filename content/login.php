<main id="login">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div id="login-form-wrap">
					<div class="login-logo-wrap"></div>
					<div class="login-form">
						<form name="loginform" action="<?php echo site_url( '/wp-login.php' ); ?>" method="post">
							<input type="text" size="20" value="" name="log" placeholder="Gebruikersnaam" >
							<input type="password" size="20" value="" name="pwd" placeholder="Wachtwoord">

                            <?php if(isset($_GET['login']) AND $_GET['login'] === 'failed') { ?>
                                <div class="error-msg">Wrong username or password.</div>
                            <?php } ?>

							<input type="submit" class="btn-green" value="Login" name="wp-submit">

							<input type="hidden" value="<?php echo esc_attr( home_url() ); ?>" name="redirect_to">
							<input type="hidden" value="1" name="testcookie">
						</form>
						<a id="forget-password-url" onclick="return false;" href="#">Wachtwoord vergeten?</a>
					</div>
				</div>

				<div id="forget-form-wrap">
					<div class="login-form">
						<div class="form-title">Uw wachtwoord vergeten?</div>
						<div class="form-text">Vul dan hier uw gebruikersnaam of e-mail in en na versturen ontvangt u direct uw wachtwoord in uw mail.</div>

						<div class="forget-password">
							<div id="close-forget"><i class="glyphicon glyphicon-remove"></i></div>
							<form name="loginform" action="<?php echo site_url( '/wp-login.php?action=lostpassword' ); ?>" method="post">
								<input id="user_login" type="text" size="20" value="" name="user_login" placeholder="Gebruikersnaam of e-mail" >
								<button type="submit" class="btn-green" value="Get New Password" name="wp-submit">Verstuur</button>

								<input type="hidden" value="<?php echo esc_attr( get_the_permalink(139) ); ?>" name="redirect_to">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<div id="login-bgr"></div>

<script>
    jQuery(function($) {
        $(document).ready(function() {
            $("#forget-password-url").on("click", function() {
				$("#forget-form-wrap").fadeIn('fast');
            });

	        $("#close-forget").on("click", function() {
                $("#forget-form-wrap").fadeOut('fast');
	        })
        })
    })
</script>
