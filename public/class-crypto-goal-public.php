<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.authorurl.com
 * @since      1.0.0
 *
 * @package    Crypto_Goal
 * @subpackage Crypto_Goal/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Crypto_Goal
 * @subpackage Crypto_Goal/public
 * @author     Author Name <authoremail@gmail.com>
 */
class Crypto_Goal_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/crypto-goal-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/crypto-goal-public.js', array( 'jquery' ), $this->version, false );

	}

	public function funding_campaign_template($template){
	  	global $post;

	    // Is this a "my-custom-post-type" post?
	    if ($post->post_type == "funding_campaign"){

	        //Your plugin path 
	        $plugin_path = plugin_dir_path( __FILE__ );

	        // The name of custom post type single template
	        $template_name = 'funding_campaign_template.php';

	        // A specific single template for my custom post type exists in theme folder? Or it also doesn't exist in my plugin?
	        if($template === get_stylesheet_directory() . '/' . $template_name
	            || !file_exists($plugin_path . $template_name)) {

	            //Then return "single.php" or "single-my-custom-post-type.php" from theme directory.
	            return $template;
	        }

	        // If not, return my plugin custom post type template.
	        return $plugin_path . $template_name;
	    }

	    //This is not my custom post type, do nothing with $template
	    return $template;
	}

}
add_shortcode( 'crypto_campaign_table' , 'crypto_campaign_table' );
function crypto_campaign_table(){
	?>
	<style>
	.percentfunded {
    position: relative;
    top: -28px;
    width: 100%;
    text-align: center;
}
</style>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<?php
	$posts = new WP_Query([
	  'post_type' => 'funding_campaign',
	  'post_status' => 'publish',
	  'numberposts' => -1
	  // 'order'    => 'ASC'
	]);
	$query = new WP_Query(array(
	  'post_type' => 'funding_campaign',
	  'post_status' => 'publish',
	  'numberposts' => -1
	));
	echo "<table>";
	echo "<tr>
    <th>Campaign</th>
    <th>Total Raised / Goal</th> 
    
	<th>Wallet Address</th>    
  	</tr>";
	while ($query->have_posts()) {
		echo "<tr>";
	    $query->the_post();
	    $post_id = get_the_ID();
	    	$to_post = get_permalink($post_id);
	    	$wallet_addr = get_post_meta($post_id, 'wallet_address', true);
					$url="http://tzc.explorerz.top:3004/ext/getbalance/".$wallet_addr;
					$ch = curl_init();

					// define options
					$optArray = array(
					    CURLOPT_URL => $url,
					    CURLOPT_RETURNTRANSFER => true
					);

					// apply those options
					curl_setopt_array($ch, $optArray);

					// execute request and get response
					$result = curl_exec($ch);
					$campaign_total = $result;	
					$percent_funded = ((int)$campaign_total/(int)get_post_meta($post_id, 'campaign_goal', true))*100;   
					if ($percent_funded>100){
						$real = $percent_funded;
						$percent_funded=100;
						$ccolor="green";
						$perc_color="white";
						$wallet_addr = "Fully Funded - Thank you!";

					} elseif ($percent_funded==100){
						$percent_funded=100;						
						$real = $percent_funded;
						$ccolor="green";
						$perc_color="white";
						$wallet_addr = "Fully Funded - Thank you!";

					} elseif ($percent_funded>50 && $percent_funded < 99){
						$real = $percent_funded;
						$ccolor="orange";
						$perc_color="black";
					} else {
						$real = $percent_funded;
						$ccolor="red";
						$perc_color="black";						
					}
	    echo "<td><a href='".$to_post."'>".get_the_title($post_id)."</a></td>";
	    echo "<td style='text-align:center'><strong>".round($campaign_total,0)."/".get_post_meta($post_id, 'campaign_goal', true)." TZC</strong><div class='w3-light-grey' style='border:1px solid #ccc'>
  <div class='w3-".$ccolor."' style='height:24px;width:".$percent_funded."%'></div></div><div style='color:".$perc_color."' class='percentfunded'>".round($real, 0)."%</div></td>";

	    echo "<td>".$wallet_addr."</td>";
		echo "</tr>";
	}
	echo "</table>";

	wp_reset_query();	
}
			