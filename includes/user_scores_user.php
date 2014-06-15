<?php 
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );
// printing current member's scoring 
function extra_user_profile_fields( $user ) { 
?>
<?php if( !in_array("member",$user->roles) ){
		echo "not member";
		return;
	} ?>
<div id = "scoring">
	<h2><strong>个人积分</strong><span id = "score_alarm" class="hidden" >您的积分过低！</span></h2>
	<?php
		$members = array($user,);
		fACG_score_member_query( $members , false );
	?></h3>
</div>
<p>&nbsp;</p>
<script type="text/javascript">
	jQuery(document).ready(function (){
		jQuery("#scoring").insertBefore(jQuery(".wrap").find("h2:first"));
		if(parseInt(jQuery("div.score_sum:first").html()) < 100 ){
			jQuery("#score_alarm").show();	
		}
	});
</script>
<?php } 
add_action('admin_menu', 'add_group_scoring');
function add_group_scoring(){
	$user_ID = get_current_user_id();	
	 global $wpdb;
	 $rslt = $wpdb->get_results("SELECT post_id FROM wp_project_member WHERE user_id = " . $user_ID . " AND participate = 1");
	 ?>
<?php
	if( sizeof($rslt) != 0 ){
		add_menu_page( "group_scoring", "组内评分", "read", "group_scoring", "fACG_score_project_scoring_user" , "" , 70.1 );	
	}
}
function fACG_score_project_scoring_user(){
	 //this page is for the group scoring
}

?>