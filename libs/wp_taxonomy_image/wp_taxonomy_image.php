<?php class TaxonomyImagesSupport {
	private $taxonomy;

	public function __construct($t) {
		$this->taxonomy = $t;
	}

	/**
	 * Initialize the class and start calling our hooks and filters
	 */
	public function init() {
		add_action( 'types_add_form_fields', array( $this, 'addCategoryImage' ), 10, 2 );
		add_action( 'created_types', array( $this, 'saveTaxonomyImage' ), 10, 2 );
		add_action( 'types_edit_form_fields', array( $this, 'updateTaxonomyImage' ), 10, 2 );
		add_action( 'edited_types', array( $this, 'updatedTaxonomyImage' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
		add_action( 'admin_footer', array( $this, 'add_script' ) );
	}

	public function load_media() {
		if ( !isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != $this->taxonomy ) {
			return;
		}
		wp_enqueue_media();
	}

	/**
	 * Add a form field in the new taxonomy page
	 */
	public function addTaxonomyImage( $taxonomy ) { ?>
        <div class="form-field term-group">
            <label for="loogman-taxonomy-image-id">Image</label>
            <input type="hidden" id="loogman-taxonomy-image-id" name="loogman-taxonomy-image-id"
                   class="custom_media_url" value="">
            <div id="category-image-wrapper"></div>
            <p>
                <input type="button" class="button button-secondary loogman_tax_media_button"
                       id="loogman_tax_media_button" name="loogman_tax_media_button"
                       value="Add Image"/>
                <input type="button" class="button button-secondary loogman_tax_media_remove"
                       id="loogman_tax_media_remove" name="loogman_tax_media_remove"
                       value="Remove Image"/>
            </p>
        </div>
	<?php }

	/**
	 * Save the form field
	 */
	public function saveTaxonomyImage( $term_id, $tt_id ) {
		if ( isset( $_POST['loogman-taxonomy-image-id'] ) && '' !== $_POST['loogman-taxonomy-image-id'] ) {
			add_term_meta( $term_id, 'loogman-' . $this->taxonomy . '-image-id', absint( $_POST['loogman-taxonomy-image-id'] ), true );
		}
	}

	/**
	 * Edit the form field
	 */
	public function updateTaxonomyImage( $term, $taxonomy ) { ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="loogman-taxonomy-image-id">Image</label>
            </th>
            <td>
				<?php $image_id = get_term_meta( $term->term_id, 'loogman-' . $this->taxonomy . '-image-id', true ); ?>
                <input type="hidden" id="loogman-taxonomy-image-id" name="loogman-taxonomy-image-id"
                       value="<?php echo esc_attr( $image_id ); ?>">
                <div id="category-image-wrapper">
					<?php if ( $image_id ) { ?>
						<?php echo wp_get_attachment_image( $image_id, 'thumb-small' ); ?>
					<?php } ?>
                </div>
                <p>
                    <input type="button" class="button button-secondary loogman_tax_media_button"
                           id="loogman_tax_media_button" name="loogman_tax_media_button"
                           value="Add Image"/>
                    <input type="button" class="button button-secondary loogman_tax_media_remove"
                           id="loogman_tax_media_remove" name="loogman_tax_media_remove"
                           value="Remove Image"/>
                </p>
            </td>
        </tr>
	<?php }

	/**
	 * Update the form field value
	 */
	public function updatedTaxonomyImage( $term_id, $tt_id ) {
		if ( isset( $_POST['loogman-taxonomy-image-id'] ) && '' !== $_POST['loogman-taxonomy-image-id'] ) {
			update_term_meta( $term_id, 'loogman-' . $this->taxonomy . '-image-id', absint( $_POST['loogman-taxonomy-image-id'] ) );
		} else {
			update_term_meta( $term_id, 'loogman-' . $this->taxonomy . '-image-id', '' );
		}
	}

	/**
	 * Enqueue styles and scripts
	 */
	public function add_script() {
		if ( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != $this->taxonomy ) {
			return;
		} ?>
        <script>
            jQuery(document).ready(function ($) {
                _wpMediaViewsL10n.insertIntoPost = 'Insert';

                function ct_media_upload(button_class) {
                    var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                    $('body').on('click', button_class, function (e) {
                        var button_id = '#' + $(this).attr('id');
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = $(button_id);
                        _custom_media = true;
                        wp.media.editor.send.attachment = function (props, attachment) {
                            console.log(attachment);
                            if (_custom_media) {
                                $('#loogman-taxonomy-image-id').val(attachment.id);
                                $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                                $('#category-image-wrapper .custom_media_image').attr('src', attachment.url).css('display', 'block');
                            } else {
                                return _orig_send_attachment.apply(button_id, [props, attachment]);
                            }
                        };
                        wp.media.editor.open(button);
                        return false;
                    });
                }

                ct_media_upload('.loogman_tax_media_button.button');
                $('body').on('click', '.loogman_tax_media_remove', function () {
                    $('#loogman-taxonomy-image-id').val('');
                    $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                });
                $(document).ajaxComplete(function (event, xhr, settings) {
                    var queryStringArr = settings.data.split('&');
                    if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                        var xml = xhr.responseXML;
                        $response = $(xml).find('term_id').text();
                        if ($response != "") {
                            // Clear the thumb image
                            $('#category-image-wrapper').html('');
                        }
                    }
                });
            });
        </script>
	<?php }
}

