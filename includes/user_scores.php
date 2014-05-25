<?php 
/* for member scoring relation tables ************************/
function member_score_install(){
	  global $wpdb;
	  $tables = array(
	  		array(
			'name' 	=> "fACG_score",
			'sql'	=>	" (
			  ID bigint(20) AUTO_INCREMENT PRIMARY KEY,
			  score_date DATE NOT NULL,
			  descrp TEXT
			  );",),
			array(
			'name' 	=>	"fACG_score_detail",
			'sql'=> " (
				  ID bigint(20) AUTO_INCREMENT PRIMARY KEY,
				  score_id bigint(20),
				  member_ID bigint(20),
				  score int(10) NOT NULL DEFAULT 0,
				  descrp TEXT,
				  time timestamp
				  );",),
			array(
			'name' 	=>	"fACG_score_project",
			'sql'=> " (
				  ID bigint(20) AUTO_INCREMENT PRIMARY KEY,
				  score_date DATE NOT NULL,
				  project_id bigint(20),
				  state bool NOT NULL,
				  time timestamp
				  );",),
			array(
			'name' 	=>	"fACG_score_project_detail",
			'sql'=> " (
				  ID bigint(20) AUTO_INCREMENT PRIMARY KEY,
				  score_project_id bigint(20),
				  scoring_member bigint(20),
				  scored_member bigint(20),
				  score int(10) NOT NULL DEFAULT 0
				  );",),
	  );
	  foreach( $tables as $k => $v){
		  $table_name = $wpdb->prefix . $v["name"];
		  if(  strcasecmp($wpdb->get_var("SHOW TABLES LIKE \"". $table_name . "\""),$table_name) != 0 ){
			  $sql = "CREATE TABLE " . $table_name . $v["sql"];
				  require_once(ABSPATH . "wp-admin/includes/upgrade.php");
				  dbDelta($sql);
		  }
	  }
}
add_action( 'after_setup_theme' ,'member_score_install');


?>