<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.authorurl.com
 * @since      1.0.0
 *
 * @package    Crypto_Goal
 * @subpackage Crypto_Goal/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Crypto_Goal
 * @subpackage Crypto_Goal/admin
 * @author     Author Name <authoremail@gmail.com>
 */
class Crypto_Goal_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Crypto_Goal_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Crypto_Goal_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/crypto-goal-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Crypto_Goal_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Crypto_Goal_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/crypto-goal-admin.js', array( 'jquery' ), $this->version, false );

	}


	public function register_funding_campaign_post_type() {
		$labels = array(
			'name'               => 'Crowd Funding Campaigns',
			'singular_name'      => 'Crowd Funding Campaign',
			'menu_name'          => 'Crowd Funding Campaign',
			'name_admin_bar'     => 'Crowd Funding Campaign',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Funding Campaign',
			'new_item'           => 'New Funding Campaign',
			'edit_item'          => 'Edit Funding Campaign',
			'view_item'          => 'View Funding Campaign',
			'all_items'          => 'All Funding Campaigns',
			'search_items'       => 'Search Funding Campaigns',
			'parent_item_colon'  => 'Parent Funding Campaign:',
			'not_found'          => 'No Funding Campaign found.',
			'not_found_in_trash' => 'No Funding Campaign found in Trash.'
		);

		$args = array( 
			'public'      => true, 
			'labels'      => $labels,
			'rewrite'		=> array( 'slug' => 'funding-campaign' ),
			'description' => 'A Cryptocurrecy Crowd Funding Project Content Type',
			'has_archive'   => true,
			'menu_position' => 3,
			'taxonomies'		=> array( 'post_tag', 'category' ),
			'supports'      => array( 'title', 'editor', 'author', 'thumbnail')			
		);
	   	register_post_type( 'funding_campaign', $args );		
	}

	function funding_campaign_messages( $messages ) {
		$post = get_post();

		$messages['recipe'] = array(
			0  => '',
			1  => 'Funding Campaign updated.',
			2  => 'Custom field updated.',
			3  => 'Custom field deleted.',
			4  => 'Funding Campaign updated.',
			5  => isset( $_GET['revision'] ) ? sprintf( 'Funding Campaign restored to revision from %s',wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => 'Funding Campaign published.',
			7  => 'Funding Campaign saved.',
			8  => 'Funding Campaign submitted.',
			9  => sprintf(
				'Funding Campaign scheduled for: <strong>%1$s</strong>.',
				date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) )
			),
			10 => 'Funding Campaign draft updated.'
		);

		return $messages;
	}

	function funding_campaign_help() {

		$screen = get_current_screen();

		if ( 'recipe' != $screen->post_type ) {
			return;
		}

		$basics = array(
			'id'      => 'funding_campaign_basics',
			'title'   => 'Funding Campaign Basics',
			'content' => 'Content for Funding Campaign help tab here'
		);

		$formatting = array(
			'id'      => 'funding_campaign_formatting',
			'title'   => 'Funding Campaign Formatting',
			'content' => 'Content for Funding Campaign formatting help tab here'
		);

		$screen->add_help_tab( $basics );
		$screen->add_help_tab( $formatting );

	}
	
	public function crowd_funding_project_add_meta_boxes( $post ){
			add_meta_box( 'crowd_funding_project_meta_box', __( 'Project Details', 'crowd_funding_project_plugin' ), 'crowd_funding_project_build_meta_box', 'funding_campaign', 'normal', 'high' );

	}

	/**
	 * Store custom field meta box data
	 *
	 * @param int $post_id The post ID.
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
	 */
	function funding_campaign_save_meta_box_data( $post_id ){
		// verify taxonomies meta box nonce
		if ( !isset( $_POST['crowd_funding_project_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['crowd_funding_project_meta_box_nonce'], basename( __FILE__ ) ) ){
			return;
		}

		// return if autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}

		// store custom fields values
		// cholesterol string
		if ( isset( $_REQUEST['campaign_goal'] ) ) {
			update_post_meta( $post_id, 'campaign_goal', sanitize_text_field( $_POST['campaign_goal'] ) );
		}
		
		// store custom fields values
		// carbohydrates string
		if ( isset( $_REQUEST['wallet_address'] ) ) {
			update_post_meta( $post_id, 'wallet_address', sanitize_text_field( $_POST['wallet_address'] ) );
		}
		

	}
}

function crowd_funding_project_build_meta_box( $post ){
// make sure the form request comes from WordPress
	wp_nonce_field( basename( __FILE__ ), 'crowd_funding_project_meta_box_nonce' );

	// retrieve the _food_cholesterol current value
	$campaign_goal = get_post_meta( $post->ID, 'campaign_goal', true );

	// retrieve the _food_carbohydrates current value
	$wallet_address = get_post_meta( $post->ID, 'wallet_address', true );
	

	?>
	<div class='inside'>


		<h3><?php _e( 'Project Goal', 'crowd_funding_project_plugin' ); ?></h3>
		<p>
			<input type="text" name="campaign_goal" value="<?php echo $campaign_goal; ?>" /> 
		</p>
		<h3><?php _e( 'Wallet Address', 'crowd_funding_project_plugin' ); ?></h3>
		<p>
			<input type="text" name="wallet_address" value="<?php echo $wallet_address; ?>" /> 
		</p>

		
	</div>
	<?php
}