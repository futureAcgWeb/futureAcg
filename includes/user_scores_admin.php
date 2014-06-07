<?php 
add_action('admin_menu', 'add_scoring_system');
function add_scoring_system(){
	add_menu_page( "scoring_system", "积分系统", "scoring_system", "scoring_system", "fACG_score_socring_system" , "" , 10.3 );
	add_submenu_page( "scoring_system", "new_scoring", "新增积分" , "scoring_system", "new_scoring", "fACG_score_new_socring" );
	add_submenu_page( "scoring_system", "project_scoring", "组内打分" , "scoring_system", "project_scoring", "fACG_score_project_scoring" );
}

/*** Scoring admin
***********************************************/
// page for scoring admin
function fACG_score_socring_system(){
	?>
    <div class="wrap">
    <h2>积分系统</h2>
    <p><div>（注：双击修改）</div></p>
    <p><div id = "processing">&nbsp;</div></p>
  
	<ul style="height: 1.5em;"><li style="float:left">备注:</li><li style=" float:left; width:66px; background:lightGrey;
padding: 0px 2px;"><div id = "detail_descrp_edit" class = "score_detail editable" info = "descrp"></div></li> </ul>
	<?php
	//global $wpdb;
	//$wpdb->query( $wpdb->prepare(" UPDATE wp_facg_score SET score_date = %s WHERE ID = 6",'2014-06-19'));
		$members = fACG_get_current_members(false);
		fACG_score_member_query( $members , true );
	?>
    </div>
    <?php
}
// print the table for scores
function fACG_score_member_query( $members , $edit = false){
	if($edit){
		 if (!function_exists('add_action'))
		 {
			 require_once("../../../../wp-config.php");
		 }
		 if (isset($dl_pluginSeries)) {
			 $dl_pluginSeries->showComments();
		 }
	}
	$m = $members[0];
	$arr_m = array();
	foreach($members as $m){
		array_push($arr_m,$m->ID); 
	}
	$sql = "SELECT member_ID, score_id, score, wp_facg_score_detail.descrp
			FROM  `wp_facg_score_detail` 
			RIGHT JOIN wp_facg_score ON wp_facg_score_detail.score_id = wp_facg_score.id
			WHERE member_ID
			IN ( " . implode(',',$arr_m) . " ) 
			ORDER BY member_ID
			";
	global $wpdb;
	
	$score_details_raw =$wpdb->get_results($sql);
	$score_raw = $wpdb->get_results("SELECT id, score_date, descrp FROM wp_facg_score");
	
	$scores = array();
	foreach($score_raw as $s){
		$scores[$s->id] = array( "score_date"	=>	$s->score_date,	"descrp" => $s->descrp );
	}

	$member_ids = array();
	foreach($members as $m){
		$member_ids[$m->ID]= $m->first_name. " " . $m->last_name; 
	}
	
	$score_sum = $wpdb->get_results("
			SELECT member_ID, SUM( score ) as sss
			FROM  `wp_facg_score_detail` 
			WHERE member_ID
			IN ( " . implode(',',$arr_m) . " ) 
			GROUP BY member_ID
			");
	$score_details = array();

	$tmpt_arr = array();
	foreach($score_details_raw as $sd){
		
		$score_details[$sd->member_ID][$sd->score_id]["score"] = $sd->score;
		$score_details[$sd->member_ID][$sd->score_id]["descrp"] = $sd->descrp;
	}
	foreach($score_sum as $ss){
		$score_details[$ss->member_ID]["sum"] = $ss->sss;
		unset($member_ids[$ss->member_ID]);
		
	}
	foreach($member_ids as $k=>$v){
		$score_details[$k]["sum"] = 0;
	}
	foreach($members as $m){
		$score_details[$m->ID]["name"] = $m->first_name. " " . $m->last_name; 
		$score_details[$m->ID]["ID"] = $m->ID;
	}
	
	usort($score_details,'sortBySum');
	
	?>
	<div  id = "scores_table_wrap">
        <table border="0" id = "scores_table">
          <tr>
            <th><div style="width:56px">姓名</div></th>
            <th class = "score_sum"><div style="width:46px">合计</div></th>
            <?php
                foreach($scores as $k => $v){
                    echo '<th width="66" scoreName = "' . $k . '">';
                    echo '<li class="score_date';
                    if($edit){	echo " editable"; }
                    echo '" info = "score_date" dbvalue = "'.$v["score_date"].'">'.$v["score_date"].'</li>';
					?>
                    
                    <?php
					echo '<li class = "descrp' ;
                    if($edit){	echo " editable score"; }
                    echo '" info = "descrp" table = "score">'.$v["descrp"];				
					echo '</li>';
					if($edit){
                    	echo '<div class="score_delete" scoreId="' . $k . '" style = "cursor:pointer">[x]</div>';
					}
                    echo '</th>';
                }
            ?>
            
            <th class = "score_sum"><div style="width:46px">合计</div></th>
            <th ><div style="width:56px">姓名</div></th>
          </tr>
          
            <?php
            foreach($score_details as $sk => $sd){
                $name = $sd["name"];
            
                ?>
                  <tr>
                    <th scoreMember="<?php echo $sd["ID"];?>"  ><?php echo $name; ?></th>
                    <th scoreMember="<?php echo $sd["ID"];?>" ><div class="score_sum" scoreMember="<?php echo $sd["ID"];?>" ><?php echo $sd["sum"]; ?></div></th>
                    <?php
                    $score_detail = $sd;
                    foreach($scores as $k => $v){
                        echo '<td width="46" 
                                    scoreName = "'. $k .'" 
                                    scoreMember="'.  $sd["ID"] .'"                     
                                    title = "'.$score_detail[$k]["descrp"].'" class="';
						
						echo '" >';
                        if( null != $score_detail[$k]["descrp"] ){
                            echo	"<div class='bottomdirection'></div>";
                            }
                        echo		"<div info = 'score' class = 'score_block";
                        if($edit){	echo " editable score_detail"; }
                        echo 		"' dbvalue = '". $score_detail[$k]["score"] ."'>";
						if( null != $score_detail[$k]["score"] && "" != $score_detail[$k]["score"]){
							echo $score_detail[$k]["score"];
							}
						else{
							echo 0;		
						}
						echo "</div>";
                        echo		'</th>';
                        
                        }
                    ?>
                    <th scoreMember="<?php echo $sd["ID"];?>"><div class="score_sum" scoreMember="<?php echo $sd["ID"];?>" ><?php echo $sd["sum"]; ?></div></th>
                    <th scoreMember="<?php echo  $sd["ID"];?>"><?php echo $name; ?></th>
                  </tr>
                <?php 
            }
            ?>
        </table>
	</div>
	<?php 
	
}
//sorting def
function sortBySum($a, $b) {

	if ($a["sum"] == $b["sum"]) {
	
	return 0;
	
	} else {
	
	return ($a["sum"] < $b["sum"]) ? 1 : -1;
	
	}
}
/***********
*
* ajax methods
*/
//callback functions for ajax method
add_action( 'wp_ajax_delete_score', 'delete_score_callback' );
function delete_score_callback() {
	global $wpdb; // this is how you get access to the database

	$scoreid = intval( $_POST['scoreid'] );
	$rslt = $wpdb->query("DELETE FROM wp_facg_score_detail where score_id = ". $scoreid);
    $rslt = $wpdb->query("DELETE FROM wp_facg_score where ID = ". $scoreid);
	echo $rslt;

	die(); // this is required to return a proper result
}

add_action( 'wp_ajax_update_score_details', 'update_score_details_callback' );
function update_score_details_callback() {
	global $wpdb; // this is how you get access to the database

	$scoreid = intval( $_POST['scoreid'] );
	$memberid = intval( $_POST['memberid'] );
	$tablename = 'wp_facg_' . $_POST['table'];
	$colname = $_POST['type'];
	$datatype = $_POST['datatype'];
	$new = $_POST['new'];
	$sql = "";
	if( $memberid < 0 ){
		$sql =  "	UPDATE " . $tablename . " SET " . $colname . " = " . $datatype . " 
					WHERE ID = ". $scoreid;
		
		}
	else{
		if ( $wpdb->get_var("SELECT ID FROM wp_facg_score_detail where score_id = ". $scoreid . " AND member_ID = " . $memberid ) > 0 ){
			$sql = "UPDATE " . $tablename . " SET " . $colname . " = " . $datatype . "
					WHERE score_id = ". $scoreid ." AND member_ID = " . $memberid;
		}else{
			$sql = "INSERT INTO " . $tablename . "(score_id,member_ID," . $colname . ") VALUES (".$scoreid.",".$memberid."," . $datatype . " )";
			}
		}
		
	$rslt = $wpdb->query( $wpdb->prepare( $sql, $new ) );
	echo $rslt;
	//echo  $wpdb->prepare( $sql, $new );
	die(); // this is required to return a proper result
}


/*** New scoring
**********************************************/
// page for new scoring
function fACG_score_new_socring(){
	?>
    <div class="wrap">
    <h2>新建积分</h2>
	<?php
	fACG_score_new_member_query();
	?>
     </div>
    <?php
}
// page for print table for new scoring 
function fACG_score_new_member_query(){
	fACG_score_new_process();
	echo '<pre>';
	$members = fACG_get_current_members(false);
	//print_r($members);
	echo '</pre>';
	date_default_timezone_set('Etc/GMT-8');     //set time zone
            
	?>
    <div id = "poststuff">
    	<div id="scoretitile">
          <form name = "score_detail_input" method = "post" target="_self" action="">    
              <table width="336" border="0">
                  <tr>
                      <th scope="row"><strong>积分时间:</strong></th>
                      <td><input name="score_time" type="text" dbvalue="<?php echo date("Y-m-d"); ?>" style="border:1px solid #999;" onclick="fPopCalendar(event,this,this)" onfocus="this.select()" readonly="readonly"></td>
                      <td rowspan="3"><input type="submit" name="submit" id="submit" class="button button-primary button-large" value="提交"  ></td>
                  </tr>
                  <tr>
                      <th rowspan="2" scope="row"><strong>积分描述:</strong></th>
                      <td>
                       
                            
                          <select id = "score_descrp_select" name = "score_descrp_select">
							  <?php 
                                    global $wpdb;
                                    $arr = $wpdb->get_results("	SELECT descrp, count(*) AS cnt 
                                                                FROM wp_facg_score 
                                                                GROUP BY descrp 
                                                                HAVING cnt > 1 
                                                                ORDER BY cnt DESC 
																LIMIT 10");
                                                    
                                    foreach( $arr as $k => $v){
                                        echo "<option value = \"". $v->descrp ."\">". $v->descrp ."</option>";	
                                    }
									echo sizeof( $arr);
                              ?>
                              <option value="0">其他</option>
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td><input type="text" class = "<?php if (sizeof( $arr) > 0){ echo 'hidden';} ?>" id="score_descrp" name = "score_descrp" /></td>
                  </tr>
              </table>
          </form>
        </div>
        <div id="scoredetaildiv" class="postbox">
            <div class="handlediv" title="Click to toggle"><br></div>
            		<h3 class=" hndle"><span>分数</span>
                    	<span style="font-weight:normal; font-size:12px">(非数字的输入都会当做0)</span>
            			<span style="float:right"><a class = "button" id = "clear_all" >清空全部</a>&nbsp;<a class ="button" id = "copy_fist_row" >复制第一行</a></span>
                    </h3>
            <div class="inside">
    
            <table border="0" id="score_detail_table">
          <tr>
            <th >姓名</th>
            <th >分数</th>
            <th width= "100" > 备注 </th>
          </tr>
            <?php
            foreach($members as $m){
                $name = $m->first_name. " " . $m->last_name;
            
                ?>
                  <tr>
                    <td><?php echo $name; ?></td>
                    <?php
                    echo '<td  scoreMember="'. $m->ID .'"><input type="text" class ="score_detail" name="score_"'.$m->ID.' member_id="'.$m->ID.'"></td>';
					echo '<td  scoreMember="'. $m->ID .'"><input type="text" class ="score_detail_descrp" name="score_descrp_"'.$m->ID.' member_id="'.$m->ID.'"></td>';
                    ?>
                  </tr>
                <?php 
            }
            ?>
        </table>
        	<textarea name = "score_details" id = "score_details" class="hidden" ></textarea>
            
          </div>
        </div>
    </div>
	  
	<?php 	
}
//adding new scores
function fACG_score_new_process(){
	$arr = stripslashes($_POST["score_details"]);
	$date = $_POST["score_time"];
	$descrp = $_POST["score_descrp_select"];
	if( $descrp == "0" ){
		$descrp = $_POST["score_descrp"];
	}

	if( null == $date ){
		return;	
	}
	if( null == $arr || null == $descrp){
			echo "输入不完整,缺少描述或者成绩";
			return;
	}

	
	global $wpdb;
	$wpdb->query( $wpdb->prepare( 
	"
		INSERT INTO wp_facg_score(score_date,descrp)
		VALUES ( %s, %s )
	", 
	$date, 
	$descrp 
	) );
	$score_id = $wpdb->get_var("SELECT ID FROM wp_facg_score ORDER BY ID DESC LIMIT 1;");
	$details = json_decode($arr,true);
	$sql = "INSERT INTO wp_facg_score_detail(score_id,member_ID,score,descrp) VALUES ";
	echo "<pre>";
	foreach( $details as $k => $v ){
		if( $k >0 ){
			$sql = $sql . ",";
		}
		$sql = $sql."(" . $score_id ."," . $v["id"] . "," . $v["score"] . ",\"" . mysql_real_escape_string($v["descrp"]). "\")";
	}
	$wpdb->query($sql);
	echo("");
	echo "<script>setTimeout(function(){ location.href='admin.php?page=scoring_system'} , 1000);</script>";  
	wp_die("成功更新 正在跳转...");
}

/*** Group scoring
**********************************************/
// page for all group scoring
function fACG_score_project_scoring(){
	?>
    <div class="wrap">
    <h2>组内打分<a class="add-new-h2" id = "click_new_ps" >点击新增</a></form></span></h2>
    <?php
    	fACG_score_project_new_query();
	?>
    <h3>第 次打分情况</h3>
	<?php
	fACG_score_project_query();
	?>
     </div>
     <input type="button" class="button" value="删除全部组内打分记录"/>
    <?php
}
function print_project_score(){
	global $wpdb;
	//$rslt = $wpdb->get_result
}
// function for printing and editing all the scorings
function fACG_score_project_query($project_scoring_id = -1 , $member = -1){// the current scoring member id, default is all the members
	$member;
}
function fACG_score_project_new_query(){
	$arr = $_POST["projectlist"];
	$date =  $_POST["score_date"];
	if( null != $arr ){
	$projectlist = json_decode($arr,true);
	?>
    <?php
		if( sizeof($projectlist) == 0){
			echo "至少选择一个项目进行评分";	
		}
		else{
			global $wpdb;
			$seq = $wpdb->get_results("SELECT project_score_title
									FROM wp_facg_score_project
									ORDER BY ID DESC 
									LIMIT 1");
			$s = 1;
			if( $seq[0]>0 ){
				$s = $seq[0];
			}
			
			$sql = "INSERT INTO wp_facg_score_project(project_score_title,project_id,score_date,state) VALUES ";
			$flag = false;
			foreach( $projectlist as $k => $p ){
				if($flag){
					$sql = $sql . ",";
				}// end of if
				$flag = true;
				$sql = $sql . "(" .  $s ."," . $p. ",'". $date ."',0)";
			}// end of foreach
			$rslt = $wpdb->query($sql);
			if( $rslt > 0 ){ echo "成功新增项目";	}
		}// end of else
	}// end of $arr != null;
	
	
	$args = array(
				'orderby'          => 'post_date',
				'order'            => 'DESC',
				'post_type'        => 'project',
				'post_status'      => 'publish',
				); 
	$posts_array = get_posts( $args );
	?>
    <div id = "new_project_scoring" class="hidden">
    	<form id="form1" name="form1" method="post" action="" target="_self">
         <strong>日期：</strong>
        <input name="score_date" type="text" style="border:1px solid #999;" dbvalue = "<?php echo date('Y-m-d',time()) ?>" onclick="fPopCalendar(event,this,this)" onfocus="this.select()" readonly="readonly" >
        <br />
        <div id = "checkboxes">
    	<?php 
			foreach( $posts_array as $k => $v ){
				echo '<label> <input type="checkbox" name="CheckboxGroup" value="' .$v->ID.'" />';
				echo $v->post_title;
				echo '</label> <br />';
			}
		?>
        </div>
       
        <textarea id = "projectlist" name = "projectlist" class="hidden"></textarea><br />
        <input type="submit" id ="click_submit_ps" class="add-new-h2" value="点击提交" />
        <a class="add-new-h2" id = "click_cancel_ps">点击取消</a>
    </form>
    </div>
    
<?php
}
?>