<?php
/**
 * Admin edit-screen metabox for the addlar_product CPT.
 *
 * One field per meta key from addlar_product_meta_fields(). The structured
 * textareas (performance rows, properties, applications, approvals,
 * formulation) follow the same "plain text, parsed on save" convention as
 * the Finder widget's category textareas — see addlar_parse_finder_rows()
 * and products-render.php's addlar_product_table_rows()/addlar_product_line_list().
 *
 * On save, the raw textarea values are parsed into the pre-rendered
 * `_addlar_performance_table_html` / `_addlar_properties_table_html`
 * fragments that the Theme Builder template's HTML widgets read via Dynamic
 * Tag — see products-render.php for why (Elementor's native Dynamic Tag
 * can't iterate a repeater without ACF Pro).
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function addlar_add_product_metaboxes() {
	add_meta_box(
		'addlar_product_details',
		__( 'Product Data Sheet', 'addlar' ),
		'addlar_render_product_metabox',
		'addlar_product',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'addlar_add_product_metaboxes' );

function addlar_render_product_metabox( $post ) {
	wp_nonce_field( 'addlar_save_product', 'addlar_product_nonce' );

	$get = function ( $key ) use ( $post ) {
		return get_post_meta( $post->ID, $key, true );
	};
	?>
	<style>
		.addlar-pm-field { margin-bottom: 16px; }
		.addlar-pm-field label { display: block; font-weight: 600; margin-bottom: 4px; }
		.addlar-pm-field input[type="text"], .addlar-pm-field textarea { width: 100%; font-family: Consolas, Menlo, monospace; }
		.addlar-pm-field .description { font-family: -apple-system, sans-serif; margin-top: 4px; }
		.addlar-pm-row { display: flex; gap: 16px; }
		.addlar-pm-row .addlar-pm-field { flex: 1; }
	</style>

	<div class="addlar-pm-row">
		<div class="addlar-pm-field">
			<label for="addlar_code"><?php esc_html_e( 'Code', 'addlar' ); ?></label>
			<input type="text" id="addlar_code" name="addlar_code" value="<?php echo esc_attr( $get( '_addlar_code' ) ); ?>" placeholder="7375">
			<p class="description"><?php esc_html_e( 'Bare code, no “ADDLAR” prefix — must match the Finder catalogue exactly (e.g. 7375, KC420, Z 2612).', 'addlar' ); ?></p>
		</div>
		<div class="addlar-pm-field">
			<label for="addlar_subcategory"><?php esc_html_e( 'Sub-category', 'addlar' ); ?></label>
			<input type="text" id="addlar_subcategory" name="addlar_subcategory" value="<?php echo esc_attr( $get( '_addlar_subcategory' ) ); ?>" placeholder="Passenger Car">
		</div>
		<div class="addlar-pm-field">
			<label for="addlar_doc_code"><?php esc_html_e( 'PDS document code', 'addlar' ); ?></label>
			<input type="text" id="addlar_doc_code" name="addlar_doc_code" value="<?php echo esc_attr( $get( '_addlar_doc_code' ) ); ?>" placeholder="RCH/V1.1/7375">
		</div>
	</div>

	<div class="addlar-pm-field">
		<label for="addlar_spec_string"><?php esc_html_e( 'Specification string', 'addlar' ); ?></label>
		<input type="text" id="addlar_spec_string" name="addlar_spec_string" value="<?php echo esc_attr( $get( '_addlar_spec_string' ) ); ?>" placeholder="API SN/CF to API SJ | ILSAC GF-5 | ACEA C3/C4">
	</div>

	<div class="addlar-pm-field">
		<label for="addlar_description"><?php esc_html_e( 'Description', 'addlar' ); ?></label>
		<?php
		wp_editor( $get( '_addlar_description' ), 'addlar_description', array(
			'textarea_name' => 'addlar_description',
			'textarea_rows' => 4,
			'media_buttons' => false,
			'quicktags'     => false,
			'teeny'         => true,
		) );
		?>
	</div>

	<div class="addlar-pm-field">
		<label for="addlar_applications_text"><?php esc_html_e( 'Applications (one per line)', 'addlar' ); ?></label>
		<textarea id="addlar_applications_text" name="addlar_applications_text" rows="4"><?php echo esc_textarea( $get( '_addlar_applications_text' ) ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Optional. Used by component-type products (e.g. cutting-oil additives, ZDDP) that are dosed for a use-case rather than graded to an API/ACEA level.', 'addlar' ); ?></p>
	</div>

	<div class="addlar-pm-row">
		<div class="addlar-pm-field">
			<label for="addlar_performance_note"><?php esc_html_e( 'Performance table note', 'addlar' ); ?></label>
			<input type="text" id="addlar_performance_note" name="addlar_performance_note" value="<?php echo esc_attr( $get( '_addlar_performance_note' ) ); ?>" placeholder="Multigrade">
		</div>
		<div class="addlar-pm-field">
			<label for="addlar_performance_headers"><?php esc_html_e( 'Performance table headers', 'addlar' ); ?></label>
			<input type="text" id="addlar_performance_headers" name="addlar_performance_headers" value="<?php echo esc_attr( $get( '_addlar_performance_headers' ) ); ?>" placeholder="Level | Treat Rate % | TBN">
		</div>
	</div>

	<div class="addlar-pm-field">
		<label for="addlar_performance_rows_text"><?php esc_html_e( 'Performance table rows (one per line, | separated)', 'addlar' ); ?></label>
		<textarea id="addlar_performance_rows_text" name="addlar_performance_rows_text" rows="6"><?php echo esc_textarea( $get( '_addlar_performance_rows_text' ) ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Optional — leave blank for raw components with no graded performance level. Column count must match the headers above.', 'addlar' ); ?></p>
	</div>

	<div class="addlar-pm-field">
		<label for="addlar_approvals_text"><?php esc_html_e( 'OEM / industry approvals (one per line)', 'addlar' ); ?></label>
		<textarea id="addlar_approvals_text" name="addlar_approvals_text" rows="4"><?php echo esc_textarea( $get( '_addlar_approvals_text' ) ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Optional flat list, kept separate from the performance table so a row with 8 OEM approvals doesn’t distort the table.', 'addlar' ); ?></p>
	</div>

	<div class="addlar-pm-row">
		<div class="addlar-pm-field">
			<label for="addlar_formulation_label"><?php esc_html_e( 'Formulation example label', 'addlar' ); ?></label>
			<input type="text" id="addlar_formulation_label" name="addlar_formulation_label" value="<?php echo esc_attr( $get( '_addlar_formulation_label' ) ); ?>" placeholder="SAE 30 (TBN 5 mg KOH/g) Formulation">
		</div>
	</div>
	<div class="addlar-pm-field">
		<label for="addlar_formulation_text"><?php esc_html_e( 'Formulation example (one "Component: value" per line)', 'addlar' ); ?></label>
		<textarea id="addlar_formulation_text" name="addlar_formulation_text" rows="4"><?php echo esc_textarea( $get( '_addlar_formulation_text' ) ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Optional — used only by products documented as a worked recipe (e.g. base oil + additive % for one finished-oil example) instead of a treat-rate table.', 'addlar' ); ?></p>
	</div>

	<div class="addlar-pm-field">
		<label for="addlar_properties_text"><?php esc_html_e( 'Typical properties (one row per line: Test | Method | Value | Unit)', 'addlar' ); ?></label>
		<textarea id="addlar_properties_text" name="addlar_properties_text" rows="8"><?php echo esc_textarea( $get( '_addlar_properties_text' ) ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Leave the Value cell blank rather than guessing if the source PDS doesn’t report it — it renders as “—”.', 'addlar' ); ?></p>
	</div>

	<div class="addlar-pm-field">
		<label for="addlar_viscosity_note"><?php esc_html_e( 'Viscosity grades', 'addlar' ); ?></label>
		<textarea id="addlar_viscosity_note" name="addlar_viscosity_note" rows="3"><?php echo esc_textarea( $get( '_addlar_viscosity_note' ) ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Free text — SAE, ISO VG, or a segmented Automotive/Industrial list, whichever the source PDS actually uses.', 'addlar' ); ?></p>
	</div>
	<?php
}

/**
 * Parse every raw textarea into its pre-rendered HTML fragment and save
 * both the raw and rendered values. Raw values are kept so the metabox can
 * repopulate on the next edit; the rendered HTML is what the Theme Builder
 * template's Dynamic Tag actually reads.
 *
 * @param int $post_id Post ID.
 */
function addlar_save_product_metabox( $post_id ) {
	if ( ! isset( $_POST['addlar_product_nonce'] ) || ! wp_verify_nonce( $_POST['addlar_product_nonce'], 'addlar_save_product' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( 'addlar_product' !== get_post_type( $post_id ) ) {
		return;
	}

	$text_fields = array(
		'addlar_code'                => '_addlar_code',
		'addlar_subcategory'         => '_addlar_subcategory',
		'addlar_doc_code'            => '_addlar_doc_code',
		'addlar_spec_string'         => '_addlar_spec_string',
		'addlar_applications_text'   => '_addlar_applications_text',
		'addlar_performance_note'    => '_addlar_performance_note',
		'addlar_performance_headers' => '_addlar_performance_headers',
		'addlar_performance_rows_text' => '_addlar_performance_rows_text',
		'addlar_approvals_text'      => '_addlar_approvals_text',
		'addlar_formulation_label'   => '_addlar_formulation_label',
		'addlar_formulation_text'    => '_addlar_formulation_text',
		'addlar_properties_text'     => '_addlar_properties_text',
	);

	foreach ( $text_fields as $post_key => $meta_key ) {
		$value = isset( $_POST[ $post_key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $post_key ] ) ) : '';
		update_post_meta( $post_id, $meta_key, $value );
	}

	if ( isset( $_POST['addlar_description'] ) ) {
		update_post_meta( $post_id, '_addlar_description', wp_kses_post( wp_unslash( $_POST['addlar_description'] ) ) );
	}
	if ( isset( $_POST['addlar_viscosity_note'] ) ) {
		update_post_meta( $post_id, '_addlar_viscosity_note', wp_kses_post( wp_unslash( $_POST['addlar_viscosity_note'] ) ) );
	}

	addlar_render_all_product_fragments( $post_id );
}
add_action( 'save_post', 'addlar_save_product_metabox' );
