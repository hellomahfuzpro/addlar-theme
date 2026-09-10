<?php
/**
 * Admin Submissions Dashboard — stores form submissions in a custom table,
 * provides a WP list table for viewing/managing them, CSV export, and
 * email notification settings.
 *
 * @package Addlar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * 1. DATABASE TABLE
 * ====================================================================== */

/**
 * Create / update the submissions table.
 */
function addlar_submissions_create_table() {
	global $wpdb;
	$table   = $wpdb->prefix . 'addlar_submissions';
	$charset = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table} (
		id           BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		preset       VARCHAR(32) NOT NULL DEFAULT 'contact',
		name         VARCHAR(255) NOT NULL DEFAULT '',
		company      VARCHAR(255) NOT NULL DEFAULT '',
		email        VARCHAR(255) NOT NULL DEFAULT '',
		phone        VARCHAR(100) NOT NULL DEFAULT '',
		country      VARCHAR(255) NOT NULL DEFAULT '',
		product      VARCHAR(255) NOT NULL DEFAULT '',
		message      TEXT NOT NULL,
		ip_address   VARCHAR(100) NOT NULL DEFAULT '',
		status       VARCHAR(20) NOT NULL DEFAULT 'unread',
		PRIMARY KEY  (id),
		KEY idx_status (status),
		KEY idx_preset (preset),
		KEY idx_created (created_at)
	) {$charset};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	// Store installed DB version.
	update_option( 'addlar_submissions_db_version', '1.0' );
}

/**
 * Auto-install table on theme load if not already present.
 */
function addlar_submissions_maybe_install() {
	if ( get_option( 'addlar_submissions_db_version' ) !== '1.0' ) {
		addlar_submissions_create_table();
	}
}
add_action( 'after_setup_theme', 'addlar_submissions_maybe_install' );

/* =========================================================================
 * 2. INSERT SUBMISSION
 * ====================================================================== */

/**
 * Insert a submission row.
 *
 * @param array $data Associative array of field values.
 * @return int|false Inserted ID or false on failure.
 */
function addlar_insert_submission( $data ) {
	global $wpdb;
	$table = $wpdb->prefix . 'addlar_submissions';

	$defaults = array(
		'preset'     => 'contact',
		'name'       => '',
		'company'    => '',
		'email'      => '',
		'phone'      => '',
		'country'    => '',
		'product'    => '',
		'message'    => '',
		'ip_address' => '',
		'status'     => 'unread',
	);

	$row = wp_parse_args( $data, $defaults );

	$inserted = $wpdb->insert(
		$table,
		array(
			'preset'     => sanitize_text_field( $row['preset'] ),
			'name'       => sanitize_text_field( $row['name'] ),
			'company'    => sanitize_text_field( $row['company'] ),
			'email'      => sanitize_email( $row['email'] ),
			'phone'      => sanitize_text_field( $row['phone'] ),
			'country'    => sanitize_text_field( $row['country'] ),
			'product'    => sanitize_text_field( $row['product'] ),
			'message'    => sanitize_textarea_field( $row['message'] ),
			'ip_address' => sanitize_text_field( $row['ip_address'] ),
			'status'     => sanitize_text_field( $row['status'] ),
		),
		array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
	);

	return $inserted ? $wpdb->insert_id : false;
}

/* =========================================================================
 * 3. ADMIN MENU & PAGES
 * ====================================================================== */

function addlar_submissions_admin_menu() {
	$unread = addlar_submissions_unread_count();
	$badge  = $unread ? ' <span class="awaiting-mod count-' . $unread . '"><span class="pending-count">' . $unread . '</span></span>' : '';

	add_menu_page(
		__( 'Inquiries', 'addlar' ),
		__( 'Inquiries', 'addlar' ) . $badge,
		'manage_options',
		'addlar-submissions',
		'addlar_render_submissions_page',
		'dashicons-email-alt',
		26
	);

	add_submenu_page(
		'addlar-submissions',
		__( 'All Submissions', 'addlar' ),
		__( 'All Submissions', 'addlar' ),
		'manage_options',
		'addlar-submissions',
		'addlar_render_submissions_page'
	);

	add_submenu_page(
		'addlar-submissions',
		__( 'Notification Settings', 'addlar' ),
		__( 'Notification Settings', 'addlar' ),
		'manage_options',
		'addlar-form-settings',
		'addlar_render_settings_page'
	);
}
add_action( 'admin_menu', 'addlar_submissions_admin_menu' );

/**
 * Get unread count.
 */
function addlar_submissions_unread_count() {
	global $wpdb;
	$table = $wpdb->prefix . 'addlar_submissions';
	return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE status = 'unread'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
}

/* =========================================================================
 * 4. SUBMISSIONS LIST PAGE
 * ====================================================================== */

function addlar_render_submissions_page() {
	global $wpdb;
	$table = $wpdb->prefix . 'addlar_submissions';

	// Handle actions.
	if ( isset( $_GET['action'] ) && isset( $_GET['id'] ) && check_admin_referer( 'addlar_sub_action' ) ) {
		$id     = absint( $_GET['id'] );
		$action = sanitize_key( $_GET['action'] );

		if ( 'delete' === $action ) {
			$wpdb->delete( $table, array( 'id' => $id ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Submission deleted.', 'addlar' ) . '</p></div>';
		} elseif ( 'mark_read' === $action ) {
			$wpdb->update( $table, array( 'status' => 'read' ), array( 'id' => $id ), array( '%s' ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		} elseif ( 'mark_unread' === $action ) {
			$wpdb->update( $table, array( 'status' => 'unread' ), array( 'id' => $id ), array( '%s' ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}
	}

	// Handle CSV export.
	if ( isset( $_GET['addlar_export_csv'] ) && check_admin_referer( 'addlar_export_csv' ) ) {
		addlar_export_csv();
		return;
	}

	// Filters.
	$status_filter = isset( $_GET['status_filter'] ) ? sanitize_key( $_GET['status_filter'] ) : '';
	$preset_filter = isset( $_GET['preset_filter'] ) ? sanitize_key( $_GET['preset_filter'] ) : '';
	$search        = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

	$where = array( '1=1' );
	$args  = array();

	if ( $status_filter ) {
		$where[] = 'status = %s';
		$args[]  = $status_filter;
	}
	if ( $preset_filter ) {
		$where[] = 'preset = %s';
		$args[]  = $preset_filter;
	}
	if ( $search ) {
		$like    = '%' . $wpdb->esc_like( $search ) . '%';
		$where[] = '(name LIKE %s OR email LIKE %s OR company LIKE %s OR message LIKE %s)';
		$args[]  = $like;
		$args[]  = $like;
		$args[]  = $like;
		$args[]  = $like;
	}

	$where_sql = implode( ' AND ', $where );

	// Pagination.
	$per_page = 20;
	$page_num = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1;
	$offset   = ( $page_num - 1 ) * $per_page;

	$total = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE {$where_sql}", ...$args ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL
	$rows  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} WHERE {$where_sql} ORDER BY created_at DESC LIMIT %d OFFSET %d", array_merge( $args, array( $per_page, $offset ) ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL

	$total_pages = ceil( $total / $per_page );

	$base_url = admin_url( 'admin.php?page=addlar-submissions' );
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Form Submissions', 'addlar' ); ?></h1>
		<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'addlar_export_csv', '1', $base_url ), 'addlar_export_csv' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Export CSV', 'addlar' ); ?></a>
		<hr class="wp-header-end">

		<!-- Filters -->
		<form method="get" action="<?php echo esc_url( $base_url ); ?>">
			<input type="hidden" name="page" value="addlar-submissions">
			<div class="tablenav top" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
				<select name="status_filter">
					<option value=""><?php esc_html_e( 'All statuses', 'addlar' ); ?></option>
					<option value="unread" <?php selected( $status_filter, 'unread' ); ?>><?php esc_html_e( 'Unread', 'addlar' ); ?></option>
					<option value="read" <?php selected( $status_filter, 'read' ); ?>><?php esc_html_e( 'Read', 'addlar' ); ?></option>
				</select>
				<select name="preset_filter">
					<option value=""><?php esc_html_e( 'All forms', 'addlar' ); ?></option>
					<option value="contact" <?php selected( $preset_filter, 'contact' ); ?>><?php esc_html_e( 'Contact Us', 'addlar' ); ?></option>
					<option value="expert" <?php selected( $preset_filter, 'expert' ); ?>><?php esc_html_e( 'Ask the Expert', 'addlar' ); ?></option>
				</select>
				<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search name, email, company…', 'addlar' ); ?>" style="width:220px;">
				<button class="button"><?php esc_html_e( 'Filter', 'addlar' ); ?></button>
			</div>
		</form>

		<!-- Count -->
		<p class="description"><?php printf( esc_html__( '%d submissions found.', 'addlar' ), $total ); ?></p>

		<!-- Table -->
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th style="width:40px;">#</th>
					<th><?php esc_html_e( 'Status', 'addlar' ); ?></th>
					<th><?php esc_html_e( 'Date', 'addlar' ); ?></th>
					<th><?php esc_html_e( 'Form', 'addlar' ); ?></th>
					<th><?php esc_html_e( 'Name', 'addlar' ); ?></th>
					<th><?php esc_html_e( 'Email', 'addlar' ); ?></th>
					<th><?php esc_html_e( 'Company', 'addlar' ); ?></th>
					<th><?php esc_html_e( 'Message', 'addlar' ); ?></th>
					<th style="width:140px;"><?php esc_html_e( 'Actions', 'addlar' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $rows ) ) : ?>
					<tr><td colspan="9"><?php esc_html_e( 'No submissions yet.', 'addlar' ); ?></td></tr>
				<?php else : ?>
					<?php foreach ( $rows as $row ) : ?>
						<tr style="<?php echo 'unread' === $row->status ? 'font-weight:700;' : ''; ?>">
							<td><?php echo esc_html( $row->id ); ?></td>
							<td>
								<?php if ( 'unread' === $row->status ) : ?>
									<span class="dashicons dashicons-email" style="color:#D32F2F;" title="<?php esc_attr_e( 'Unread', 'addlar' ); ?>"></span>
								<?php else : ?>
									<span class="dashicons dashicons-email-alt" style="color:#888;" title="<?php esc_attr_e( 'Read', 'addlar' ); ?>"></span>
								<?php endif; ?>
							</td>
							<td><?php echo esc_html( wp_date( 'M j, Y g:i a', strtotime( $row->created_at ) ) ); ?></td>
							<td><span style="background:#f0f0f0;padding:2px 8px;border-radius:3px;font-size:12px;"><?php echo esc_html( ucfirst( $row->preset ) ); ?></span></td>
							<td><?php echo esc_html( $row->name ); ?></td>
							<td><a href="mailto:<?php echo esc_attr( $row->email ); ?>"><?php echo esc_html( $row->email ); ?></a></td>
							<td><?php echo esc_html( $row->company ); ?></td>
							<td title="<?php echo esc_attr( $row->message ); ?>"><?php echo esc_html( wp_trim_words( $row->message, 12, '…' ) ); ?></td>
							<td>
								<?php
								$view_url   = wp_nonce_url( add_query_arg( array( 'action' => 'view', 'id' => $row->id ), $base_url ), 'addlar_sub_action' );
								$delete_url = wp_nonce_url( add_query_arg( array( 'action' => 'delete', 'id' => $row->id ), $base_url ), 'addlar_sub_action' );
								$toggle     = 'unread' === $row->status ? 'mark_read' : 'mark_unread';
								$toggle_url = wp_nonce_url( add_query_arg( array( 'action' => $toggle, 'id' => $row->id ), $base_url ), 'addlar_sub_action' );
								?>
								<a href="#" class="addlar-view-sub" data-id="<?php echo esc_attr( $row->id ); ?>" data-name="<?php echo esc_attr( $row->name ); ?>" data-email="<?php echo esc_attr( $row->email ); ?>" data-company="<?php echo esc_attr( $row->company ); ?>" data-phone="<?php echo esc_attr( $row->phone ); ?>" data-country="<?php echo esc_attr( $row->country ); ?>" data-product="<?php echo esc_attr( $row->product ); ?>" data-message="<?php echo esc_attr( $row->message ); ?>" data-date="<?php echo esc_attr( wp_date( 'F j, Y g:i a', strtotime( $row->created_at ) ) ); ?>" data-preset="<?php echo esc_attr( $row->preset ); ?>" data-ip="<?php echo esc_attr( $row->ip_address ); ?>" title="<?php esc_attr_e( 'View details', 'addlar' ); ?>"><?php esc_html_e( 'View', 'addlar' ); ?></a> |
								<a href="<?php echo esc_url( $toggle_url ); ?>"><?php echo 'unread' === $row->status ? esc_html__( 'Read', 'addlar' ) : esc_html__( 'Unread', 'addlar' ); ?></a> |
								<a href="<?php echo esc_url( $delete_url ); ?>" onclick="return confirm('<?php esc_attr_e( 'Delete this submission?', 'addlar' ); ?>');" style="color:#a00;"><?php esc_html_e( 'Delete', 'addlar' ); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>

		<!-- Pagination -->
		<?php if ( $total_pages > 1 ) : ?>
			<div class="tablenav bottom">
				<div class="tablenav-pages">
					<?php for ( $i = 1; $i <= $total_pages; $i++ ) : ?>
						<?php if ( $i === $page_num ) : ?>
							<span class="tablenav-pages-navspan button disabled"><?php echo esc_html( $i ); ?></span>
						<?php else : ?>
							<a class="button" href="<?php echo esc_url( add_query_arg( 'paged', $i, $base_url ) ); ?>"><?php echo esc_html( $i ); ?></a>
						<?php endif; ?>
					<?php endfor; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<!-- Detail modal -->
	<div id="addlar-sub-modal" style="display:none;position:fixed;inset:0;z-index:100000;background:rgba(0,0,0,.55);">
		<div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;width:580px;max-width:92vw;max-height:85vh;overflow-y:auto;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.3);padding:32px;">
			<button id="addlar-sub-close" style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:22px;cursor:pointer;color:#666;">&times;</button>
			<h2 style="margin:0 0 6px;font-size:20px;" id="addlar-sub-modal-name"></h2>
			<p style="color:#666;margin:0 0 20px;font-size:13px;" id="addlar-sub-modal-meta"></p>
			<table class="form-table" style="margin:0;">
				<tr><th style="width:110px;"><?php esc_html_e( 'Email', 'addlar' ); ?></th><td id="addlar-sub-modal-email"></td></tr>
				<tr><th><?php esc_html_e( 'Company', 'addlar' ); ?></th><td id="addlar-sub-modal-company"></td></tr>
				<tr><th><?php esc_html_e( 'Phone', 'addlar' ); ?></th><td id="addlar-sub-modal-phone"></td></tr>
				<tr><th><?php esc_html_e( 'Country', 'addlar' ); ?></th><td id="addlar-sub-modal-country"></td></tr>
				<tr><th><?php esc_html_e( 'Product', 'addlar' ); ?></th><td id="addlar-sub-modal-product"></td></tr>
				<tr><th><?php esc_html_e( 'Message', 'addlar' ); ?></th><td id="addlar-sub-modal-message" style="white-space:pre-wrap;"></td></tr>
				<tr><th><?php esc_html_e( 'IP Address', 'addlar' ); ?></th><td id="addlar-sub-modal-ip" style="color:#999;font-size:12px;"></td></tr>
			</table>
			<div style="margin-top:20px;text-align:right;">
				<a id="addlar-sub-reply" href="#" class="button button-primary" target="_blank"><?php esc_html_e( 'Reply via Email', 'addlar' ); ?></a>
			</div>
		</div>
	</div>
	<script>
	(function(){
		var modal = document.getElementById('addlar-sub-modal');
		document.querySelectorAll('.addlar-view-sub').forEach(function(link){
			link.addEventListener('click',function(e){
				e.preventDefault();
				var d = this.dataset;
				document.getElementById('addlar-sub-modal-name').textContent = d.name;
				document.getElementById('addlar-sub-modal-meta').textContent = d.preset.toUpperCase() + ' — ' + d.date;
				document.getElementById('addlar-sub-modal-email').innerHTML = '<a href="mailto:'+d.email+'">'+d.email+'</a>';
				document.getElementById('addlar-sub-modal-company').textContent = d.company || '—';
				document.getElementById('addlar-sub-modal-phone').textContent = d.phone || '—';
				document.getElementById('addlar-sub-modal-country').textContent = d.country || '—';
				document.getElementById('addlar-sub-modal-product').textContent = d.product || '—';
				document.getElementById('addlar-sub-modal-message').textContent = d.message || '—';
				document.getElementById('addlar-sub-modal-ip').textContent = d.ip || '—';
				document.getElementById('addlar-sub-reply').href = 'mailto:'+d.email+'?subject=Re: Your ADDLAR Inquiry';
				modal.style.display = 'block';

				// Auto-mark as read.
				var markUrl = '<?php echo esc_url( $base_url ); ?>&action=mark_read&id=' + d.id + '&_wpnonce=<?php echo wp_create_nonce( 'addlar_sub_action' ); ?>';
				fetch(markUrl,{credentials:'same-origin'});
			});
		});
		document.getElementById('addlar-sub-close').addEventListener('click',function(){ modal.style.display='none'; });
		modal.addEventListener('click',function(e){ if(e.target===modal) modal.style.display='none'; });
		document.addEventListener('keydown',function(e){ if(e.key==='Escape') modal.style.display='none'; });
	})();
	</script>
	<?php
}

/* =========================================================================
 * 5. CSV EXPORT
 * ====================================================================== */

function addlar_export_csv() {
	global $wpdb;
	$table = $wpdb->prefix . 'addlar_submissions';
	$rows  = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY created_at DESC", ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=addlar-submissions-' . gmdate( 'Y-m-d' ) . '.csv' );

	$out = fopen( 'php://output', 'w' );
	if ( ! empty( $rows ) ) {
		fputcsv( $out, array_keys( $rows[0] ) );
		foreach ( $rows as $row ) {
			fputcsv( $out, $row );
		}
	}
	fclose( $out );
	exit;
}

/* =========================================================================
 * 6. NOTIFICATION SETTINGS PAGE
 * ====================================================================== */

function addlar_render_settings_page() {
	// Save settings.
	if ( isset( $_POST['addlar_save_form_settings'] ) && check_admin_referer( 'addlar_form_settings_nonce' ) ) {
		update_option( 'addlar_notify_emails', sanitize_text_field( wp_unslash( $_POST['addlar_notify_emails'] ?? '' ) ) );
		update_option( 'addlar_notify_enabled', isset( $_POST['addlar_notify_enabled'] ) ? '1' : '0' );
		update_option( 'addlar_notify_sender_name', sanitize_text_field( wp_unslash( $_POST['addlar_notify_sender_name'] ?? '' ) ) );
		update_option( 'addlar_notify_sender_email', sanitize_email( wp_unslash( $_POST['addlar_notify_sender_email'] ?? '' ) ) );
		update_option( 'addlar_notify_subject_prefix', sanitize_text_field( wp_unslash( $_POST['addlar_notify_subject_prefix'] ?? '' ) ) );
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Settings saved.', 'addlar' ) . '</p></div>';
	}

	$emails         = get_option( 'addlar_notify_emails', '' );
	$enabled        = get_option( 'addlar_notify_enabled', '1' );
	$sender_name    = get_option( 'addlar_notify_sender_name', 'ADDLAR' );
	$sender_email   = get_option( 'addlar_notify_sender_email', '' );
	$subject_prefix = get_option( 'addlar_notify_subject_prefix', '[ADDLAR]' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Form Notification Settings', 'addlar' ); ?></h1>
		<form method="post">
			<?php wp_nonce_field( 'addlar_form_settings_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="addlar_notify_enabled"><?php esc_html_e( 'Enable Email Notifications', 'addlar' ); ?></label></th>
					<td>
						<label><input type="checkbox" id="addlar_notify_enabled" name="addlar_notify_enabled" value="1" <?php checked( $enabled, '1' ); ?>> <?php esc_html_e( 'Send email notification on each new submission', 'addlar' ); ?></label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="addlar_notify_emails"><?php esc_html_e( 'Recipient Email(s)', 'addlar' ); ?></label></th>
					<td>
						<input type="text" id="addlar_notify_emails" name="addlar_notify_emails" value="<?php echo esc_attr( $emails ); ?>" class="regular-text" placeholder="info@addlar-rc.com, sales@addlar-rc.com">
						<p class="description"><?php esc_html_e( 'Comma-separated list of email addresses. Leave blank to use the ADDLAR email from Customizer.', 'addlar' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="addlar_notify_sender_name"><?php esc_html_e( 'Sender Name', 'addlar' ); ?></label></th>
					<td><input type="text" id="addlar_notify_sender_name" name="addlar_notify_sender_name" value="<?php echo esc_attr( $sender_name ); ?>" class="regular-text" placeholder="ADDLAR"></td>
				</tr>
				<tr>
					<th scope="row"><label for="addlar_notify_sender_email"><?php esc_html_e( 'Sender Email', 'addlar' ); ?></label></th>
					<td>
						<input type="email" id="addlar_notify_sender_email" name="addlar_notify_sender_email" value="<?php echo esc_attr( $sender_email ); ?>" class="regular-text" placeholder="noreply@addlar-rc.com">
						<p class="description"><?php esc_html_e( 'The "From" address for notification emails. Leave blank for the WordPress default.', 'addlar' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="addlar_notify_subject_prefix"><?php esc_html_e( 'Subject Prefix', 'addlar' ); ?></label></th>
					<td><input type="text" id="addlar_notify_subject_prefix" name="addlar_notify_subject_prefix" value="<?php echo esc_attr( $subject_prefix ); ?>" class="regular-text" placeholder="[ADDLAR]"></td>
				</tr>
			</table>
			<p class="submit">
				<button type="submit" name="addlar_save_form_settings" class="button button-primary"><?php esc_html_e( 'Save Settings', 'addlar' ); ?></button>
			</p>
		</form>
	</div>
	<?php
}

/* =========================================================================
 * 7. NOTIFICATION EMAIL SENDER OVERRIDE
 * ====================================================================== */

/**
 * Override wp_mail From name/email when sending ADDLAR notifications.
 */
function addlar_notification_mail_from( $email ) {
	$custom = get_option( 'addlar_notify_sender_email', '' );
	return $custom ? $custom : $email;
}

function addlar_notification_mail_from_name( $name ) {
	$custom = get_option( 'addlar_notify_sender_name', '' );
	return $custom ? $custom : $name;
}
