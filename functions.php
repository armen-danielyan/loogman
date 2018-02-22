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
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
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
			update_user_meta($userId, $metaKey, json_encode($userMetaValue));
		}

		echo json_encode(array('status' => 1, 'statusMsg' => 'Item Removed'));
	} else {
		echo json_encode(array('status' => 0, 'statusMsg' => 'Data is missing'));
	}

	wp_die();
}

/**
 * Order Now
 */
add_action( 'wp_ajax_order-now', 'orderNow' );
add_action( 'wp_ajax_nopriv_order-now', 'orderNow' );
function orderNow() {
	$userId = (isset($_POST['user_id']) && $_POST['user_id']) ? $_POST['user_id'] : false;

	if($userId) {
		$metaKey = '_shopping_basket';

		$userMetaValue = json_decode(get_user_meta($userId, $metaKey, true));

		if($userMetaValue) {
			update_user_meta($userId, $metaKey, '');
		}

		echo json_encode(array('status' => 1, 'statusMsg' => 'Oreded successfuly'));
	} else {
		echo json_encode(array('status' => 0, 'statusMsg' => 'Data is missing'));
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


class loogmanOptions {
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( 'loogmanOptions', 'addAdminMenu' ) );
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
			'Theme Settings',
			'Theme Settings',
			'manage_options',
			'theme-settings',
			array( 'loogmanOptions', 'createAdminPage' )
		);
	}

	public static function register_settings() {
		register_setting( 'theme_options', 'theme_options', array( 'loogmanOptions', 'sanitize' ) );
	}

	public static function sanitize( $options ) {
		if ( $options ) {
			if ( ! empty( $options['checkbox_example'] ) ) {
				$options['checkbox_example'] = 'on';
			} else {
				unset( $options['checkbox_example'] );
			}

			if ( ! empty( $options['input_example'] ) ) {
				$options['input_example'] = sanitize_text_field( $options['input_example'] );
			} else {
				unset( $options['input_example'] );
			}

			if ( ! empty( $options['select_example'] ) ) {
				$options['select_example'] = sanitize_text_field( $options['select_example'] );
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
                        <th scope="row">Checkbox Example</th>
                        <td>
							<?php $value = self::getThemeOption( 'checkbox_example' ); ?>
                            <input type="checkbox"
                                   name="theme_options[checkbox_example]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Checkbox example description.', 'text-domain' ); ?>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Input Example</th>
                        <td>
							<?php $value = self::getThemeOption( 'input_example' ); ?>
                            <input type="text" name="theme_options[input_example]"
                                   value="<?php echo esc_attr( $value ); ?>">
                        </td>
                    </tr>

                    <tr valign="top" class="wpex-custom-admin-screen-background-section">
                        <th scope="row">Select Example</th>
                        <td>
							<?php $value = self::getThemeOption( 'select_example' ); ?>
                            <select name="theme_options[select_example]">
								<?php
								$options = array(
									'1' => 'Option 1',
									'2' => 'Option 2',
									'3' => 'Option 3',
								);
								foreach ( $options as $id => $label ) { ?>
                                    <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $value, $id, true ); ?>>
										<?php echo strip_tags( $label ); ?>
                                    </option>
								<?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr valign="top" class="wpex-custom-admin-screen-background-section">
                        <th scope="row">Upload File</th>
                        <td>
		                    <?php $value = self::getThemeOption( '_loogman_csv_path' ); ?>
                            <input type="text" name="theme_options[_loogman_csv_path]"
                                   value="<?php echo esc_attr( $value ); ?>">
                            <button class="upload-csv">Set CSV</button>
                        </td>
                    </tr>

                </table>

				<?php submit_button(); ?>

            </form>
        </div>

        <script>

        </script>
	<?php }
}

new loogmanOptions();
