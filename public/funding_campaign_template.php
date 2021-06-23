<?php
/**
* Template Name: Funding Campaign
*
* @package Total
*/

get_header();
the_post();

?>
<div id="content" class="site-content">
	<div class="container pad-top-40">
		<div class="row">
			<div class="col-sm-12" style="margin-top:50px">
				<h1><?php echo get_the_title() ?></h1>
				<p><?php echo get_the_content() ?></p>				
				<P>Funding Goal: <?php echo get_post_meta(get_the_ID(), 'campaign_goal', true); ?> TZR</P>
				<P>Wallet Address: <?php echo get_post_meta(get_the_ID(), 'wallet_address', true); ?></P>
				<?php 
					
					$url="http://tzc.explorerz.top:3004/ext/getbalance/".get_post_meta(get_the_ID(), 'wallet_address', true);
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
					echo "Total Raised: ".round($result,0);
					?>
			</div>
		</div>
	</div>
</div>

<?php get_footer();
