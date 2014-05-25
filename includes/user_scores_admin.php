<?php 
add_action('admin_menu', 'add_scoring_system');
function add_scoring_system(){
	add_menu_page( "scoring_system", "积分系统", "scoring_system", "scoring_system", "fACG_score_socring_system" , "" , 10.3 );
	add_submenu_page( "scoring_system", "new_scoring", "新增积分" , "scoring_system", "new_scoring", "fACG_score_new_socring" );
}
/*** Scoring admin
***********************************************/
// page for scoring admin
function fACG_score_socring_system(){
	?>
    <div class="wrap">
    <h2>积分系统</h2>
	<?php
	$members = fACG_get_current_members(false);
	fACG_score_member_query( $members , true );
	?>
    </div>
    <?php
}
// print the table for scores
function fACG_score_member_query( $members , $edit = false){
	
	$m = $members[0];
	$arr_m = array();
	foreach($members as $m){
		array_push($arr_m,$m->ID); 
	}
	echo '<pre>';
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

	$score_sum = $wpdb->get_results("
			SELECT member_ID, SUM( score ) as sss
			FROM  `wp_facg_score_detail` 
			WHERE member_ID
			IN ( " . implode(',',$arr_m) . " ) 
			GROUP BY member_ID
			");
	$score_details = array();
	$member_id;
	$tmpt_arr = array();
	foreach($score_details_raw as $sd){
		
		$score_details[$sd->member_ID][$sd->score_id]["score"] = $sd->score;
		$score_details[$sd->member_ID][$sd->score_id]["descrp"] = $sd->descrp;
	}
	foreach($score_sum as $ss){
		$score_details[$ss->member_ID]["sum"] = $ss->sss;
	}
	foreach($members as $m){
		$score_details[$m->ID]["name"] = $m->first_name. " " . $m->last_name; 
	}
	usort($score_details,'sortBySum');
	echo '</pre>';
	?>

    <table border="0">
      <tr>
        <th>姓名</th>
		<?php
            foreach($scores as $k => $v){
                echo '<th width="66" scoreName = "'. $k .'"><div class="score_date">'.$v["score_date"].'</div><div class = "descrp">'.$v["descrp"].'</div></th>';
            }
        ?>
        
        <th class = "score_sum" >合计</th>
        <th >姓名</th>
      </tr>
        <?php
        foreach($score_details as $sk => $sd){
            $name = $sd["name"];
        
            ?>
              <tr>
                <td><?php echo $name; ?></td>
                <?php
				$score_detail = $sd;
                foreach( $score_detail as $k => $v){
					if( $k == "sum" || $k == "name"){
						continue;	
					}
                    echo '<td width="46" 
								scoreName = "'. $k .'" 
								scoreMember="'.  $sk .'" 
								style ="cursor:pointer" 
								title = "'.$v["descrp"].'" >';
					if( null !=$v["descrp"] ){
						echo	"<div class='bottomdirection'></div>";
						}
					echo		"<div class = 'score_block'>" . $v["score"] . "</div>";
					echo		'</th>';
                } 
                ?>
                <th class = "score_sum"><div class="score_sum"><?php echo $sd["sum"]; ?></div></th>
                <td><?php echo $name; ?></td>
              </tr>
            <?php 
        }
        ?>
    </table>

	<?php 
	
}
function sortBySum($a, $b) {

if ($a["sum"] == $b["sum"]) {

return 0;

} else {

return ($a["sum"] < $b["sum"]) ? 1 : -1;

}

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
        	<textarea name = "score_details" id = "score_details" class="" ></textarea>
            
          </div>
        </div>
    </div>
	</form>
	<?php 	
}

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
?>