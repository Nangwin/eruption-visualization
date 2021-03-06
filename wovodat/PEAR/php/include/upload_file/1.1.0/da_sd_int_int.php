<?php

// Database functions
require_once("php/funcs/db_funcs.php");

// XML functions
require_once("php/funcs/xml_funcs.php");

// WOVOML 1.* functions
require_once("php/funcs/v1_funcs.php");

// Get code
$code=xml_get_att($da_sd_int_int_obj, "CODE");

// Get owners
$owners=$da_sd_int_int_obj['results']['owners'];

// Prepare link to sd_evn_id
if (substr($da_sd_int_int_obj['results']['sd_evn_id'], 0, 1)=="@") {
	$sd_evn_id=$db_ids[substr($da_sd_int_int_obj['results']['sd_evn_id'], 1)];
}
else {
	$sd_evn_id=$da_sd_int_int_obj['results']['sd_evn_id'];
}

// Prepare link to sd_evs_id
if (substr($da_sd_int_int_obj['results']['sd_evs_id'], 0, 1)=="@") {
	$sd_evs_id=$db_ids[substr($da_sd_int_int_obj['results']['sd_evs_id'], 1)];
}
else {
	$sd_evs_id=$da_sd_int_int_obj['results']['sd_evs_id'];
}

// INSERT or UPDATE?
$id=v1_get_id("sd_int", $code, $owners);

// If ID is NULL, INSERT
if ($id==NULL) {
	
	// Prepare variables
	$insert_table="sd_int";
	$field_name=array();
	$field_name[0]="sd_int_code";
	$field_name[1]="sd_int_time";
	$field_name[2]="sd_int_time_unc";
	$field_name[3]="sd_int_city";
	$field_name[4]="sd_int_maxdist";
	$field_name[5]="sd_int_maxrint";
	$field_name[6]="sd_int_maxrint_dist";
	$field_name[7]="sd_int_ori";           
	$field_name[8]="sd_int_com";         
	$field_name[9]="sd_evn_id";
	$field_name[10]="sd_evs_id";
	$field_name[11]="vd_id";
	$field_name[12]="cc_id";
	$field_name[13]="cc_id2";
	$field_name[14]="cc_id3";
	$field_name[15]="sd_int_pubdate";
	$field_name[16]="cc_id_load";
	$field_name[17]="sd_int_loaddate";
	$field_name[18]="cb_ids";
	$field_value=array();
	$field_value[0]=$code;
	$field_value[1]=xml_get_ele($da_sd_int_int_obj, "TIME");
	$field_value[2]=xml_get_ele($da_sd_int_int_obj, "TIMEUNC");
	$field_value[3]=xml_get_ele($da_sd_int_int_obj, "CITY");
	$field_value[4]=xml_get_ele($da_sd_int_int_obj, "MAXDISTANCE");
	$field_value[5]=xml_get_ele($da_sd_int_int_obj, "MAXREPORTED");
	$field_value[6]=xml_get_ele($da_sd_int_int_obj, "DISTMAXREPORTED");
	$field_value[7]=xml_get_ele($da_sd_int_int_obj, "ORGDIGITIZE");           
	$field_value[8]=xml_get_ele($da_sd_int_int_obj, "COMMENTS");	        
	$field_value[9]=$sd_evn_id;
	$field_value[10]=$sd_evs_id;
	$field_value[11]=$da_sd_int_int_obj['results']['vd_id'];
	$field_value[12]=$da_sd_int_int_obj['results']['owners'][0]['id'];
	$field_value[13]=$da_sd_int_int_obj['results']['owners'][1]['id'];
	$field_value[14]=$da_sd_int_int_obj['results']['owners'][2]['id'];
	$field_value[15]=$da_sd_int_int_obj['results']['pubdate'];
	$field_value[16]=$cc_id_load;
	$field_value[17]=$current_time;
	$field_value[18]=$cb_ids;
	
	// INSERT values into database and write UNDO file
	if (!v1_insert($undo_file_pointer, $insert_table, $field_name, $field_value, $upload_to_db, $last_insert_id, $error)) {
		$errors[$l_errors]=$error;
		$l_errors++;
		return FALSE;
	}
	
	// Store ID
	$da_sd_int_int_obj['id']=$last_insert_id;
	array_push($db_ids, $last_insert_id);
}
// Else, UPDATE
else {
	
	// Prepare variables
	$update_table="sd_int";
	$field_name=array();
	$field_name[0]="sd_int_pubdate";
	$field_name[1]="sd_int_time";
	$field_name[2]="sd_int_time_unc";
	$field_name[3]="sd_int_city";
	$field_name[4]="sd_int_maxdist";
	$field_name[5]="sd_int_maxrint";
	$field_name[6]="sd_int_maxrint_dist";
	$field_name[7]="sd_int_ori";          
	$field_name[8]="sd_int_com";           	
	$field_name[9]="sd_evn_id";
	$field_name[10]="sd_evs_id";
	$field_name[11]="vd_id";
	$field_name[12]="cc_id";
	$field_name[13]="cc_id2";
	$field_name[14]="cc_id3";
	$field_name[15]="cb_ids";
	$field_value=array();
	$field_value[0]=$da_sd_int_int_obj['results']['pubdate'];
	$field_value[1]=xml_get_ele($da_sd_int_int_obj, "TIME");
	$field_value[2]=xml_get_ele($da_sd_int_int_obj, "TIMEUNC");
	$field_value[3]=xml_get_ele($da_sd_int_int_obj, "CITY");
	$field_value[4]=xml_get_ele($da_sd_int_int_obj, "MAXDISTANCE");
	$field_value[5]=xml_get_ele($da_sd_int_int_obj, "MAXREPORTED");
	$field_value[6]=xml_get_ele($da_sd_int_int_obj, "DISTMAXREPORTED");
	$field_value[7]=xml_get_ele($da_sd_int_int_obj, "ORGDIGITIZE");           	
	$field_value[8]=xml_get_ele($da_sd_int_int_obj, "COMMENTS");	        		
	$field_value[9]=$sd_evn_id;
	$field_value[10]=$sd_evs_id;
	$field_value[11]=$da_sd_int_int_obj['results']['vd_id'];
	$field_value[12]=$da_sd_int_int_obj['results']['owners'][0]['id'];
	$field_value[13]=$da_sd_int_int_obj['results']['owners'][1]['id'];
	$field_value[14]=$da_sd_int_int_obj['results']['owners'][2]['id'];
	$field_value[15]=$cb_ids;
	$where_field_name=array();
	$where_field_name[0]="sd_int_id";
	$where_field_value=array();
	$where_field_value[0]=$id;
	
	// UPDATE values in database and write UNDO file
	if (!v1_update($undo_file_pointer, $update_table, $field_name, $field_value, $where_field_name, $where_field_value, $upload_to_db, $error)) {
		$errors[$l_errors]=$error;
		$l_errors++;
		return FALSE;
	}
	
	// Store ID
	$da_sd_int_int_obj['id']=$id;
	array_push($db_ids, $id);
}

?>