<?php
define( 'VERSION', '0.0.1' );

/**
 * Load Scripts and Styles
 */
add_action( 'wp_enqueue_scripts', 'loadFrontScriptsStyles' );
function loadFrontScriptsStyles() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('loogmanBootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), VERSION, true);
	wp_enqueue_script('loogmanMain', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery', 'loogmanBootstrap'), VERSION, true);

	wp_enqueue_style('loogmanBootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css', '', VERSION, '');
	wp_enqueue_style('loogmanBootstrapTheme', get_stylesheet_directory_uri() . '/assets/css/bootstrap-theme.min.css', '', VERSION, '');
	wp_enqueue_style('loogmanMain', get_stylesheet_directory_uri() . '/assets/css/main.css', array('loogmanBootstrap'), VERSION, '');
}

/**
 * Load Admin Scripts and Styles
 */
add_action( 'admin_enqueue_scripts', 'loadBackScriptsStyles' );
function loadBackScriptsStyles() {
	wp_enqueue_media();
	wp_enqueue_script('loogmanAdminPapaparse', get_stylesheet_directory_uri() . '/libs/wp_papaparse/papaparse.min.js', '', VERSION, true);
	wp_enqueue_script('loogmanAdminMain', get_stylesheet_directory_uri() . '/assets/js/admin-main.js', array('jquery', 'loogmanAdminPapaparse'), VERSION, true);
	wp_localize_script( 'loogmanAdminMain', 'wp_vars', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	) );
}

/**
 * Register Thumbnails
 */
if (function_exists( 'add_theme_support' )) {
	add_theme_support( 'post-thumbnails' );
	add_image_size('thumb-small', 100, 100, true);
	add_image_size('thumb-basket', 110, 60, true);
	add_image_size('thumb-archive', 180, 135, true);
	add_image_size('thumb-medium', 280, 160, true);
	add_image_size('thumb-large', 380, 285, true);
}

/**
 * Register Menus
 */
add_action( 'init', 'registerMenus' );
function registerMenus() {
	register_nav_menus( array(
		'main-menu' => 'Main Menu'
	));
}

/**
 * Include Bootstrap NavWalker
 */
require_once('libs/wp_bootstrap_navwalker/wp_bootstrap_navwalker.php');

/**
 * Register Product Custom Post Type
 */
add_action( 'init', 'registerProductPostType', 0 );
function registerProductPostType() {
	$labels = array(
		'name'                  => 'Products',
		'singular_name'         => 'Product',
		'menu_name'             => 'Products',
		'parent_item_colon'     => 'Parent Product',
		'all_items'             => 'All Products',
		'view_item'             => 'View Product',
		'add_new_item'          => 'Add New Product',
		'add_new'               => 'Add New',
		'edit_item'             => 'Edit Product',
		'update_item'           => 'Update Product',
		'search_items'          => 'Search Product',
		'not_found'             => 'Not Found',
		'not_found_in_trash'    => 'Not found in Trash'
	);
	$args = array(
		'label'                 => 'products',
		'description'           => 'Products list',
		'menu_icon'             => 'dashicons-cart',
		'menu_position'         => 28,
		'labels'                => $labels,
		'supports'              => array( 'author', 'title', 'editor', 'thumbnail', 'revisions' ),
		'taxonomies'            => array( 'types' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'show_in_admin_bar'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => array('post', 'products'),
		'map_meta_cap'          => true,
		'register_meta_box_cb'  => 'addProductsMetaboxes'
	);
	register_post_type( 'products', $args );
}

/**
 * Register Type Taxonomy For Products Custom Post Type
 */
add_action('init', 'registerTypesPostType', 0);
function registerTypesPostType() {
	$labels = array(
		'name'              => 'Types',
		'singular_name'     => 'Type',
		'search_items'      => 'Search Types',
		'all_items'         => 'All Types',
		'parent_item'       => 'Parent Type',
		'parent_item_colon' => 'Parent Type:',
		'edit_item'         => 'Edit Type',
		'update_item'       => 'Update Type',
		'add_new_item'      => 'Add New Type',
		'new_item_name'     => 'New Genre Name',
		'menu_name'         => 'Types'
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'types' ),
		'capabilities'      => array(
			'manage_terms'  => 'manage_categories',
			'edit_terms'    => 'edit_types',
			'delete_terms'  => 'delete_types',
			'assign_terms'  => 'assign_types'
		)
	);
	register_taxonomy( 'types', 'products', $args );
}

/**
 * Register Products Metaboxes
 */
function addProductsMetaboxes() {
	add_meta_box( 'loogman_extra_a', 'Aanvullende Informatie', 'loogmanExtraA', 'products', 'normal', 'high' );
	add_meta_box( 'loogman_extra_b', 'Algemene Info', 'loogmanExtraB', 'products', 'normal', 'high' );
	add_meta_box( 'loogman_price', 'Price', 'loogmanPrice', 'products', 'side', 'high' );
	add_meta_box( 'loogman_formaat', 'Formaat In cm', 'loogmanFormaat', 'products', 'side', 'high' );
}

/**
 * Create Aanvullende Informatie Metabox
 */
function loogmanExtraA() {
	global $post;
	$ident = '_loogman_extra_a';
	echo '<input type="hidden" name="nonce' . $ident . '" id="nonce' . $ident . '" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$value = get_post_meta($post->ID, $ident, true);
	echo '<textarea id="' . $ident . '" name="' . $ident . '" class="widefat">' . $value  . '</textarea>';
}

/**
 * Create Algemene Info Metabox
 */
function loogmanExtraB() {
	global $post;
	$ident = '_loogman_extra_b';
	echo '<input type="hidden" name="nonce' . $ident . '" id="nonce' . $ident . '" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$value = get_post_meta($post->ID, $ident, true);
	echo '<textarea id="' . $ident . '" name="' . $ident . '" class="widefat">' . $value  . '</textarea>';
}

/**
 * Create Price Metabox
 */
function loogmanPrice() {
	global $post;
	$ident = '_loogman_price';
	echo '<input type="hidden" name="nonce' . $ident . '" id="nonce' . $ident . '" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$value = get_post_meta($post->ID, $ident, true);
	echo '<input id="' . $ident . '" type="number" step="any" min="0" max="100000" name="' . $ident . '" value="' . $value  . '" class="widefat" />';
}

/**
 * Create Formaat In cm Metabox
 */
function loogmanFormaat() {
	global $post;
	$ident = '_loogman_formaat';
	echo '<input type="hidden" name="nonce' . $ident . '" id="nonce' . $ident . '" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$value = get_post_meta($post->ID, $ident, true);
	echo '<input id="' . $ident . '" type="text" name="' . $ident . '" value="' . $value  . '" class="widefat" />';
}

/**
 * Save Products Metaboxes
 */
add_action( 'save_post', 'saveProductsMetaboxes', 1, 2 );
function saveProductsMetaboxes($post_id, $post) {
	if ( !wp_verify_nonce($_POST['nonce_loogman_extra_a'], plugin_basename(__FILE__)) &&
	     !wp_verify_nonce($_POST['nonce_loogman_extra_b'], plugin_basename(__FILE__)) &&
	     !wp_verify_nonce($_POST['nonce_loogman_price'], plugin_basename(__FILE__)) &&
	     !wp_verify_nonce($_POST['nonce_loogman_soort_sign'], plugin_basename(__FILE__)) &&
	     !wp_verify_nonce($_POST['nonce_loogman_formaat'], plugin_basename(__FILE__))
	) {
		return $post->ID;
	}
	if ( !current_user_can( 'edit_post', $post->ID )) {
		return $post->ID;
	}
	$productsMeta['_loogman_extra_a'] = $_POST['_loogman_extra_a'];
	$productsMeta['_loogman_extra_b'] = $_POST['_loogman_extra_b'];
	$productsMeta['_loogman_price'] = $_POST['_loogman_price'];
	$productsMeta['_loogman_formaat'] = $_POST['_loogman_formaat'];

	foreach ($productsMeta as $key => $value) {
		if( $post->post_type == 'revision' ) return;
		$value = implode(',', (array)$value);
		if(get_post_meta($post->ID, $key, FALSE)) {
			update_post_meta($post->ID, $key, $value);
		} else {
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key);
	}
}

/**
 * Include Taxonomy Image
 */
require_once( 'libs/wp_taxonomy_image/wp_taxonomy_image.php' );

$taxonomyImagesSupport = new TaxonomyImagesSupport('types');
$taxonomyImagesSupport->init();

/**
 * Pagination
 */
function pagination($pages = '', $range = 1) {
	$showitems = 1;
	global $paged, $wp_query;
	if (empty($paged)) $paged = 1;
	if ($pages == '') {
		$pages = $wp_query->max_num_pages;
		if (!$pages) {
			$pages = 1;
		}
	}
	if (1 != $pages) { ?>
		<div class="paged btn-group btn-group-xs" role="group">
            <?php if ($paged > 1 && $showitems < $pages) { ?>
                <a class="btn btn-default" type="button" href="<?php echo get_pagenum_link($paged - 1); ?>">&laquo;</a>
            <?php }
            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range || $i <= $paged - $range) || $pages <= $showitems)) {
                    if ($paged == $i) { ?>
                        <span class="btn btn-default" type="button"><?php echo $i; ?></span>
                    <?php } else { ?>
                        <a class="btn btn-default" href="<?php echo get_pagenum_link($i); ?>" ><?php echo $i; ?></a>
                    <?php }
                }
            }
            if ($paged < $pages && $showitems < $pages) { ?>
                <a class="btn btn-default" href="<?php echo get_pagenum_link($paged + 1); ?>">&raquo;</a>
            <?php } ?>
		</div>
		<span class="page-count"><?php echo 'van ' . $pages; ?></span>
	<?php }
}

/**
 * Correct Posts Per Page
 */
add_action( 'pre_get_posts', 'correctPostsPerPage' );
function correctPostsPerPage( $query ) {
	if ( $query->is_tax('types') && $query->is_main_query() ) {
		$query->set( 'posts_per_page', 12 );
	}
}

/**
 * Create Loogman user role
 */
add_action( 'after_setup_theme', 'createLoogmanRole' );
function createLoogmanRole() {
	add_role( 'loogman', 'Loogman', array(
		// 'read'                      => true,
		'edit_products'             => true,
		'read_products'             => true,
		'delete_products'           => true,
		'publish_products'          => true,
		'edit_published_products'   => true,
		'upload_files'              => true,
		'delete_published_products' => true,
		'assign_types'              => true
	) );

	$role = get_role('administrator');
	$role->add_cap( 'edit_types' );
	$role->add_cap( 'delete_types' );
	$role->add_cap( 'assign_types' );
}

/**
 * Set Theme Options on Switch Theme
 */
add_action('switch_theme', 'setThemeOptions');
function setThemeOptions () {
	global $wp_roles;

	$role = 'loogman';
	if (isset($wp_roles->roles[$role])) {
		$wp_roles->remove_role($role);
	}
}

/**
 * Show Only Current User Products
 */
function filterTabs($views) {
	unset($views['all']);
	unset($views['publish']);

	return $views;
}

function filterQuery( $wp_query ) {
	if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/edit.php' ) !== false ) {
		global $current_user;
		$wp_query->set( 'author', $current_user->id );
	}
}

if (current_user_can('loogman')) {
	add_filter('views_edit-products', 'filterTabs');
	add_filter('parse_query', 'filterQuery' );
	add_filter('show_admin_bar', '__return_false');
}

/**
 * Add Location field for user
 */
add_action( 'show_user_profile', 'addExtraUserProfileFields' );
add_action( 'edit_user_profile', 'addExtraUserProfileFields' );

function addExtraUserProfileFields( $user ) { ?>
	<h3>Extra profile information</h3>

	<table class="form-table">
		<tr>
			<th><label for="location">Location</label></th>
			<td>
				<input type="text" name="_location" id="location" value="<?php echo esc_attr( get_the_author_meta( '_location', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Location.</span>
			</td>
		</tr>
        <tr>
            <th><label for="budget">Budget</label></th>
            <td>
                <input type="number" step="any" min="0" name="_budget" id="budget" value="<?php echo esc_attr( get_the_author_meta( '_budget', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Please enter your Budget.</span>
            </td>
        </tr>
        <tr>
            <th><label for="budget">Document</label></th>
            <td>
                <input type="text" name="_document" id="_document" value="<?php echo esc_attr( get_the_author_meta( '_document', $user->ID ) ); ?>" class="regular-text" /><br />
                <button type="button" id="attach-document" class="button">Attach Document</button>
            </td>
        </tr>
	</table>
<?php }

add_action( 'personal_options_update', 'saveExtraUserProfileFields' );
add_action( 'edit_user_profile_update', 'saveExtraUserProfileFields' );

function saveExtraUserProfileFields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	update_user_meta( $user_id, '_location', $_POST['_location'] );
	update_user_meta( $user_id, '_budget', $_POST['_budget'] );
	update_user_meta( $user_id, '_document', $_POST['_document'] );
}


/**
 * Add Shopping Basket
 */
add_action( 'wp_ajax_add_shopping_basket', 'addShoppingBasket' );
add_action( 'wp_ajax_nopriv_add_shopping_basket', 'addShoppingBasket' );
function addShoppingBasket() {
	$postId = (isset($_POST['post_id']) && $_POST['post_id']) ? $_POST['post_id'] : false;
	$userId = (isset($_POST['user_id']) && $_POST['user_id']) ? $_POST['user_id'] : false;
	$productAmount = (isset($_POST['product_amount']) && $_POST['product_amount']) ? $_POST['product_amount'] : false;

	$_productPrice = get_post_meta($postId, '_loogman_price', true);

	if($postId && $productAmount && $userId && $_productPrice) {
		$metaKey = '_shopping_basket';
		$productKey = 'product_' . $postId;
		$data = (object)[];

		$userMetaValue = json_decode(get_user_meta($userId, $metaKey, true));

		if($userMetaValue && $userMetaValue->$productKey) {
			$userMetaValue->$productKey->_productAmount = $userMetaValue->$productKey->_productAmount + $productAmount;
			update_user_meta($userId, $metaKey, json_encode($userMetaValue));
		} else {
			$_productAmount = $productAmount;

			$data->_productId = $postId;
			$data->_productAmount = $_productAmount;

			$userMetaValue->$productKey = $data;
			update_user_meta($userId, $metaKey, json_encode($userMetaValue));
		}

		echo json_encode(array('status' => 1, 'statusMsg' => 'New item added to shopping basket'));
    } else {
		echo json_encode(array('status' => 0, 'statusMsg' => 'Data is missing'));
    }

	wp_die();
}

/**
 * Add Shopping Basket
 */
add_action( 'wp_ajax_remove_shopping_basket', 'removeShoppingBasket' );
add_action( 'wp_ajax_nopriv_remove_shopping_basket', 'removeShoppingBasket' );
function removeShoppingBasket() {
	$postId = (isset($_POST['post_id']) && $_POST['post_id']) ? $_POST['post_id'] : false;
	$userId = (isset($_POST['user_id']) && $_POST['user_id']) ? $_POST['user_id'] : false;


	if($postId && $userId) {
		$metaKey = '_shopping_basket';
		$productKey = 'product_' . $postId;

		$userMetaValue = json_decode(get_user_meta($userId, $metaKey, true));

		if($userMetaValue && $userMetaValue->$productKey) {
			unset($userMetaValue->$productKey);
			if(empty((array)$userMetaValue)) {
				delete_user_meta($userId, $metaKey);
			} else {
				update_user_meta($userId, $metaKey, json_encode($userMetaValue));
            }

		}

		echo json_encode(array('status' => 1, 'statusMsg' => 'Item Removed'));
	} else {
		echo json_encode(array('status' => 0, 'statusMsg' => 'Data is missing'));
	}

	wp_die();
}
/**
 * Confirm Order
 */
add_action( 'wp_ajax_confirm-order', 'confirmOrder' );
add_action( 'wp_ajax_nopriv_confirm-order', 'confirmOrder' );
function confirmOrder() {
	$userId = (isset($_POST['user_id']) && $_POST['user_id']) ? $_POST['user_id'] : false;

	if($userId) {
		$metaKey = '_shopping_basket';
		$metaNotes = '_order_notes';
		$notes = get_user_meta($userId, '_order_notes', true);
		$userData = get_userdata($userId);

		$userMetaValue = json_decode(get_user_meta($userId, $metaKey, true));
		if($userMetaValue) {
			$shoppingBasketItems = get_object_vars($userMetaValue);
		};
		$themeOptions = get_option('theme_options');

		ob_start(); ?>
        <html>
            <head>
                <style>
                    body {
                        background-color: #eee;
                    }
                    table {
                        width: 75%;
                        background-color: #fff;
                    }
                    table, th, td {
                        border: 1px solid #ccc;
                    }
                    th, td {
                        padding: 5px 10px;
                    }
                </style>
            </head>
        <body>
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Sign</th>
                        <th>Aantal</th>
                        <th>Prijs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $totalAmoout = 0;
                    $totalPrice = 0;
                    $userBudget = get_user_meta($userId, '_budget', true);
                    foreach ($shoppingBasketItems as $key => $item) {
                        $postId = $item->_productId;
                        $itemPrice = get_post_meta($postId, '_loogman_price', true); ?>
                        <tr>
                            <td>
                                <?php if(has_post_thumbnail($postId)) {
                                    echo get_the_post_thumbnail($postId,'thumb-basket');
                                } else {
                                    echo wp_get_attachment_image(135, 'thumb-basket', '');
                                } ?>
                            </td>
                            <td>
                                <?php $taxes = get_post_taxonomies($postId);
                                $tax = $taxes[0];
                                $terms = get_the_terms($postId, $tax); ?>
                                <a href="<?php echo get_the_permalink($postId); ?>"><?php echo get_the_title($postId); ?></a><br>
                                <?php echo $terms[0]->name; ?>
                            </td>
                            <td><?php echo $item->_productAmount; ?></td>
                            <td><?php echo number_format($itemPrice, 2, ',', ''); ?></td>
                        </tr>
                        <?php $totalAmoout += $item->_productAmount;
                        $totalPrice += $item->_productAmount * $itemPrice; ?>
                    <?php } ?>
                    <tr>
                        <td colspan="2"><strong><?php echo strtoupper('Totaal'); ?></strong></td>
                        <td><?php echo $totalAmoout; ?></td>
                        <td><?php echo number_format($totalPrice, 2, ',', ''); ?></td>
                    </tr>
                    <?php if($notes) { ?>
                        <tr>
                            <td colspan="4"><strong><?php echo strtoupper('Uw Opmerking Bij deze bestelling'); ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="4"><?php echo $notes; ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <div><strong>Budget (€):</strong></div><br>
                            <strong>Na bestelling:</strong>
                        </td>
                        <td colspan="3">
                            <div><?php echo number_format($userBudget, 2, ',', ''); ?></div><br>
                            <?php echo number_format($userBudget - $totalPrice, 2, ',', ''); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>

        <?php $mailContent = ob_get_contents();
		ob_end_clean();

		wp_mail(
            array(
	            $themeOptions['loogman_email'],
	            $userData->user_email
            ),
			$themeOptions['loogman_email_subject'],
            $mailContent,
            array(
		        'MIME-Version: 1.0\r\n',
                'Content-Type: text/html; charset=utf-8\r\n'
        ));

		if($userMetaValue) {
			update_user_meta($userId, $metaKey, '');
			update_user_meta($userId, $metaNotes, '');
		}

		echo json_encode(array('status' => 1, 'statusMsg' => 'Oreded successfuly'));
	} else {
		echo json_encode(array('status' => 0, 'statusMsg' => 'Data is missing'));
	}

	wp_die();
}

/**
 * Add Notes
 */
add_action( 'wp_ajax_add-notes', 'addNotes' );
add_action( 'wp_ajax_nopriv_add-notes', 'addNotes' );
function addNotes() {
	$userId = (isset($_POST['user_id']) && $_POST['user_id']) ? $_POST['user_id'] : false;
	$notes = (isset($_POST['notes']) && $_POST['notes']) ? $_POST['notes'] : false;

	if($userId && $notes) {
		$metaKey = '_order_notes';

		if (strlen($notes) > 1000) $notes = substr( $notes, 0, 1000 );

		update_user_meta($userId, $metaKey, sanitize_text_field($notes));

		echo json_encode(array('status' => 1, 'statusMsg' => 'Added notes'));
	} else {
		echo json_encode(array('status' => 0, 'statusMsg' => 'Data is empty'));
	}

	wp_die();
}

/**
 * Redirect non logged users to login page
 */
add_action('wp', 'redirectLoginPage');
function redirectLoginPage(){
    wp_reset_query();
    if(!is_user_logged_in() && !is_page(139) && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php'){
        wp_redirect(get_the_permalink(139), 301);
        exit;
    } elseif (is_user_logged_in() && is_page(139)) {
	    wp_redirect(home_url(), 301);
	    exit;
    }
}

add_action('wp_login_failed', 'loginFailed');
function loginFailed($username) {
	$referrer = $_SERVER['HTTP_REFERER'];
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
		wp_redirect( $referrer . '?login=failed' );
		exit;
	}
}


class loogmanOptions {
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( 'loogmanOptions', 'addAdminMenu' ) );
			add_action( 'admin_menu', array( 'loogmanOptions', 'addAdminMenuImportProducts' ) );
			add_action( 'admin_init', array( 'loogmanOptions', 'register_settings' ) );
		}
	}

	public static function getThemeOptions() {
		return get_option( 'theme_options' );
	}

	public static function getThemeOption($id) {
		$options = self::getThemeOptions();
		if ( isset($options[$id]) ) {
			return $options[$id];
		}
	}

	public static function addAdminMenu() {
		add_menu_page(
			'Loogman Settings',
			'Loogman Setting',
			'manage_options',
			'loogman-settings',
			array( 'loogmanOptions', 'createAdminPage' )
		);
	}

	public static function register_settings() {
		register_setting( 'theme_options', 'theme_options', array( 'loogmanOptions', 'sanitize' ) );
	}

	public static function sanitize( $options ) {
		if ( $options ) {
			if ( ! empty( $options['loogman_email'] ) ) {
				$options['loogman_email'] = sanitize_text_field( $options['loogman_email'] );
			} else {
				unset( $options['loogman_email'] );
			}

			if ( ! empty( $options['loogman_email_subject'] ) ) {
				$options['loogman_email_subject'] = sanitize_text_field( $options['loogman_email_subject'] );
			} else {
				unset( $options['loogman_email_subject'] );
			}
		}
		return $options;
	}

	public static function createAdminPage() { ?>
        <div class="wrap">
            <h1>Theme Options</h1>

            <form method="post" action="options.php">
				<?php settings_fields( 'theme_options' ); ?>
                <table class="form-table wpex-custom-admin-login-table">
                    <tr valign="top">
                        <th scope="row">Email</th>
                        <td>
							<?php $value = self::getThemeOption( 'loogman_email' ); ?>
                            <input type="email" name="theme_options[loogman_email]"
                                   value="<?php echo esc_attr( $value ); ?>">
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Email Subject</th>
                        <td>
			                <?php $value = self::getThemeOption( 'loogman_email_subject' ); ?>
                            <input type="text" name="theme_options[loogman_email_subject]"
                                   value="<?php echo esc_attr( $value ); ?>">
                        </td>
                    </tr>
                </table>

				<?php submit_button(); ?>

            </form>
        </div>
	<?php }

	public static function addAdminMenuImportProducts() {
		add_menu_page(
			'Import Products',
			'Import Products',
			'manage_options',
			'import-products',
			array( 'loogmanOptions', 'createImportProductsPage' )
		);
	}

	public static function createImportProductsPage() { ?>
        <div class="wrap">
            <h1>Import Products</h1>

            <table class="form-table wpex-custom-admin-login-table">
                <tr valign="top" class="wpex-custom-admin-screen-background-section">
                    <th scope="row">Import CSV</th>
                    <td>
                        <input type="file" id="select-csv">
                        <button id="import-csv" type="button">Import <span id="import-progress"></span></button>
                    </td>
                </tr>

                <tr valign="top" class="wpex-custom-admin-screen-background-section">
                    <td colspan="2">
                        <div style="height: 400px; overflow-y: scroll">
                            <table id="csv-table">

                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
	<?php }
}

new loogmanOptions();


/**
 * Add Product
 */
add_action( 'wp_ajax_add-product', 'addProduct' );
add_action( 'wp_ajax_nopriv_add-product', 'addProduct' );
function addProduct() {
    $data = $_POST['data'] ? $_POST['data'] : '';
    if($data === '') {
        echo json_encode(array('status' => 'ERR', 'errorMsg' => 'Data is empty!'));
	    wp_die();
	    return;
    }

    foreach ($data as $product) {
        $pName = $product[0] ? trim($product[0]) : '';
        $pCategory = $product[1] ? trim($product[1]) : '';
        $pDim = $product[2] && $product[4] ? trim($product[2]) . ' x ' . trim($product[4]) : '';
        $pExtraInfoA = $product[5] ? trim($product[5]) : '';
        $pExtraInfoB = $product[6] ? trim($product[6]) : '';
	    $pImageName = $product[8] ? trim($product[8]) : '';
	    if($product[7]) {
		    preg_match('/[0-9\,\.]+/', trim($product[7]), $matchesPrice);
		    $pPrice = $matchesPrice[0];
        } else {
		    $pPrice = 0;
        }

	    $userName = getUserFromProductName($pName);
        $userId = checkUser($userName);
        $imgUrl = "http://{$_SERVER['SERVER_NAME']}/archive/{$userName}/web/{$pImageName}.jpg";
        $termId = checkTerm($pCategory);

	    $postArg = array(
		    'post_title' => $pName,
		    'post_status' => 'publish',
		    'post_author' => $userId,
		    'post_type' => 'products',
		    'tax_input' => array(
		            'types' => array($termId)
            ),
            'meta_input' => array(
                    '_loogman_extra_a' => $pExtraInfoA,
                    '_loogman_extra_b' => $pExtraInfoB,
                    '_loogman_formaat' => $pDim,
                    '_loogman_price' => $pPrice
            )
	    );
	    $insertedProductId = wp_insert_post($postArg);
	    if ($insertedProductId) {
		    setProductFeaturedImage($insertedProductId, $imgUrl, $userId);
	    }
    }

	wp_die();
}

/**
 * Get Username(location) from Product Name
 */
function getUserFromProductName($productName) {
    $usersShortNames = array(
	    'Alm' => 'Almere',
        'Adijk' => 'Amsteldijk',
        'Adam' => 'Amsterdam',
        'Hfd' => 'Hoofddorp',
        'Lely' => 'Lelystad',
        'Rdam' => 'Rotterdam',
        'Utr' => 'Utrecht',
        'Aals' => 'Aalsmeer'
    );

    $output = '';

    foreach ($usersShortNames as $key => $value) {
        if(strpos( $productName, $key ) === 0) {
	        $output = $value;
	        break;
        }
    }

    return $output;
}

/**
 * Create User if user doesn't exist
 */
function checkUser($userName) {
	$userId = username_exists( $userName );
	if ( !$userId ) {
		$userEmail = strtolower($userName) . '@easyliquids.nl';
		$randomPassword = wp_generate_password( $length = 12, $include_standard_special_chars = false );
		$userId = wp_create_user( $userName, $randomPassword, $userEmail );

		$user = new WP_User($userId);
		$user->set_role('loogman');

		update_user_meta( $userId, '_location', $userName );
	}
	return (int)$userId;
}

/**
 * Create Term if term doesn't exist
 */
function checkTerm($term) {
	$termId = term_exists( $term, 'types' );
	if(!$termId){
		wp_insert_term($term, 'types');
	}

	return (int)$termId['term_id'];
}

/**
 * Set Product Feature Image
 */
function setProductFeaturedImage($productId, $imgUrl, $userId){
	preg_match('/^.+\/(.+)/', $imgUrl, $matchesExt);
	$imgExt = $matchesExt[1];

	preg_match('/^.+\/(.+)\./', $imgUrl, $matchesName);
	$imgName = $matchesName[1];

	$httpWP = new WP_Http();
	$photo = $httpWP->request($imgUrl);

	if( is_wp_error( $photo ) ) {
		return;
	}

	$attachment = wp_upload_bits($imgExt, null, $photo['body'], date("Y-m", strtotime($photo['headers']['last-modified'])));
	$fileType = wp_check_filetype(basename($attachment['file']), null);

	$postInfo = array(
		'post_mime_type' => $fileType['type'],
		'post_title' => $imgName,
		'post_content' => '',
		'post_status' => 'inherit',
		'post_author' => $userId,
	);

	$fileName = $attachment['file'];
	$attachId = wp_insert_attachment($postInfo, $fileName, $productId);
	$attachData = wp_generate_attachment_metadata($attachId, $fileName);
	wp_update_attachment_metadata($attachId, $attachData);
	set_post_thumbnail($productId, $attachId);

	return $attachId;
}