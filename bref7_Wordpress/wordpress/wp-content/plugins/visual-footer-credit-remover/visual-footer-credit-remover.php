<?php
/*
Plugin Name: Visual Footer Credit Remover
Version: 1.2
Plugin URI: https://upwerd.com/visual-footer-credit-remover
Description: Visually remove or replace footer credits
Author: Upwerd LLC
Author URI: https://upwerd.com
Text Domain: visual-footer-credit-remover
*/


//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
* Add a submenu under Tools
*/
function jabvfcr_admin_menu() {
	$page = add_submenu_page( 'tools.php', 'Visual Footer Credit Remover', 'Visual Footer Credit Remover', 'activate_plugins', 'visual-footer-credit-remover', 'jabvfcr_options_page' );
}

function jabvfcr_options_page() {
	$data = get_option( 'jabvfcr_data' );
	$content = $data['content'];
	$selector = $data['selector'];
	$manipulation = is_null($data['manipulation']) ? 'html' : $data['manipulation'];

?>

<div class="jabvfcr">
	<button class="button js-open-inspector mt2">Open Visual Editor</button>
	<div class="js-inspector inspector">
		<div class="site-preview">
			<iframe class="js-site-preview-iframe" src="<?php echo get_site_url() ?>?jabvfcr_inspector=1"></iframe>
		</div>
		<div class="right-panel">
			<div class="top-bar">
				<div class="fr">
					<span class="dashicons dashicons-no js-close-inspector close pointer" style="margin-right: 10px"></span>
				</div>
				<h3 class="ma0 mb1">Visual Footer Credit Remover</h3>
			</div>
			<div class="options">
				<span class="dashicons dashicons-undo js-clear clear dn pointer" title="Reset"></span>
				<a href="https://youtube.com/watch?v=NKL9uZ_XRO8" target="_blank" class="fr">Help</a>
			</div>
			<form method="post">
				<section>
					<?php wp_editor($content, 'content', $settings = array('wpautop' => false,'editor_height' => '150', 'tinymce' => array('content_css' => plugins_url( 'editor.css', __FILE__ )))); ?>

					<div class="mt1">
						<a href="#" class="js-toggle-advanced-options toggle-advanced-options">Advanced Options</a>
						<div class="js-advanced-options advanced-options mt1">
							<strong>Selector: </strong>
							<input type="text" class="js-selector selector regular-text" name="selector" value="<?php echo $selector ?>"/>
							<div class="invalid dn">Please enter a valid selector.</div><br/>
							<div class="mt1 mb1">
								<strong>Manipulation: </strong><br/>
								<label><input class="js-manipulation" type="radio" name="manipulation" value="html" <?php echo ($manipulation == 'html' ? 'checked="checked"' : '')  ?>> Insert</label> &nbsp;&nbsp;
								<label><input class="js-manipulation" type="radio" name="manipulation" value="replaceWith" <?php echo ($manipulation == 'replaceWith' ? 'checked="checked"' : '')  ?>> Replace</label>&nbsp;&nbsp;
								<label><input class="js-manipulation" type="radio" name="manipulation" value="before" <?php echo ($manipulation == 'before' ? 'checked="checked"' : '')  ?>> Before</label>&nbsp;&nbsp;
								<label><input class="js-manipulation" type="radio" name="manipulation" value="after" <?php echo ($manipulation == 'after' ? 'checked="checked"' : '')  ?>> After</label>
							</div>
							<strong>Current Selector:</strong>
							<div class="js-current-selector current-selector"></div>
						</div>
					</div>
				</section>
				<div class="mt1">
					<input type="submit" class="button button-primary js-submit" value="Save" />
				</div>
			</form>
		</div>
	</div>
	<img alt="loading" class="loading js-loading-screen" src="<?php echo plugins_url( 'loader.gif', __FILE__ ) ?>"/>
	<div class="overlay js-loading-screen"></div>
</div>
		
<?php }

//Add left menu item in admin
add_action( 'admin_menu', 'jabvfcr_admin_menu' );

function jabvfcr_script() {
	$data = get_option( 'jabvfcr_data' );
	$html = $data['content'];
	$selector = $data['selector'];
	$manipulation = $data['manipulation'];
	if (strlen($selector) > 0) {
		?>
		<script>
			var jabvfcr = {
				selector: "<?php echo $selector ?>",
				manipulation: "<?php echo $manipulation; ?>",
				html: "<?php echo preg_replace("/[\n\r]/","",str_replace('"', '\"', $html)) ?>"
			};
		</script>
		<?php
	}
}
add_action('wp_head', 'jabvfcr_script');

function jabvfcr_enqueue_scripts() {
	wp_register_script( 'jabvfcr_script', plugins_url( 'script.js', __FILE__ ), array('jquery'), false, true );
	wp_enqueue_script('jabvfcr_script');
}

function jabvfcr_enqueue_inspector_scripts($hook) {
	wp_enqueue_script( 'jabvfcr_inspector_script', plugins_url( 'inspector.js', __FILE__ ), array('jquery', 'jabvfcr_simmerjs'), false, true );
	wp_enqueue_script( 'jabvfcr_simmerjs', plugins_url( 'simmer.js', __FILE__ ), array(), false, true );
	wp_enqueue_style( 'jabvfcr_inspector_style', plugins_url( 'inspector.css', __FILE__ ));

}

if( isset( $_GET['jabvfcr_inspector'] )  ) {
	add_action( 'wp_enqueue_scripts', 'jabvfcr_enqueue_inspector_scripts', 50, 1);
	add_filter( 'show_admin_bar', '__return_false' );
} else {
	add_action( 'wp_enqueue_scripts', 'jabvfcr_enqueue_scripts', 50, 1);
}

function jabvfcr_enqueue_admin_files($hook) {
 
	if( $hook != 'tools_page_visual-footer-credit-remover' ) 
		return;

	wp_enqueue_script( 'jabvfcr_admin_script', plugins_url( 'admin.js' , __FILE__), array('jquery'), false, true);
	wp_enqueue_style( 'jabvfcr_admin_style', plugins_url( 'admin.css', __FILE__ ));
	
	wp_localize_script('jabvfcr_admin_script', 'jabvfcr_ajax', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('jabvfcr_ajax-nonce')
    ));
}
add_action('admin_enqueue_scripts', 'jabvfcr_enqueue_admin_files');

add_action( 'wp_ajax_jabvfcr_save_selector', 'jabvfcr_save_selector' );

function jabvfcr_save_selector() {
	check_ajax_referer('jabvfcr_ajax-nonce');

	$selector = stripslashes_deep($_POST['selector']);
	$manipulation = stripslashes_deep($_POST['manipulation']);
	$content = stripslashes_deep($_POST['content']);

	$data = array(
		'selector' => $selector,
		'content' => $content,
		'manipulation' => $manipulation
	);

	update_option( 'jabvfcr_data', $data );

    wp_die();
}

function jabvfcr_action_links( $links ) {

	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( 'tools.php?page=visual-footer-credit-remover' ) ) . '">' . __( 'Visual Editor', 'textdomain' ) . '</a>'
	), $links );

	return $links;

}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'jabvfcr_action_links' );


function jabvfcr_activate(){
    register_uninstall_hook( __FILE__, 'jabvfcr_uninstall' );
}
register_activation_hook( __FILE__, 'jabvfcr_activate' );

function jabvfcr_uninstall(){
    delete_option( 'jabvfcr_data');
}