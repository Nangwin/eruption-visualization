<?php

// vvv Set variables
$er_phs_phs_for_key="ed_for";
$er_phs_phs_for_name="Forecast";

// ^^^ Get code
$code=xml_get_att($er_phs_phs_for_obj, "CODE");

// -- CHECK DATA --

// ^^^ Get owners
if (!v1_get_owners($er_phs_phs_for_obj, $error)) {
	$errors[$l_errors]=$error;
	$l_errors++;
	return FALSE;
}

// vvv Set owners
if (!v1_set_owners($er_phs_phs_for_obj)) {
	// Missing information
	$errors[$l_errors]=array();
	$errors[$l_errors]['code']=1;
	$errors[$l_errors]['message']="&lt;".$er_phs_phs_for_name." code=\"".$code."\"&gt; is missing information: please specify owner";
	$l_errors++;
	return FALSE;
}

// vvv Set volcano
$er_phs_phs_for_obj['results']['vd_id']=$er_phs_phs_obj['results']['vd_id'];

// ^^^ Get times
$issue_time=xml_get_ele($er_phs_phs_for_obj, "ISSUETIME");
$earliest_start_time=xml_get_ele($er_phs_phs_for_obj, "EARLIESTSTARTTIME");
$latest_start_time=xml_get_ele($er_phs_phs_for_obj, "LATESTSTARTTIME");

// ### Check time order
if (!empty($issue_time) && !empty($earliest_start_time)) {
	if (strcmp($issue_time, $earliest_start_time)>0) {
		$errors[$l_errors]=array();
		$errors[$l_errors]['code']=2;
		$errors[$l_errors]['message']="In &lt;".$er_phs_phs_for_name." code=\"".$code."\"&gt;, issue time (".$issue_time.") should be earlier than earliest start time (".$earliest_start_time.")";
		$l_errors++;
		return FALSE;
	}
}

// ### Check time order
if (!empty($issue_time) && !empty($latest_start_time)) {
	if (strcmp($issue_time, $latest_start_time)>0) {
		$errors[$l_errors]=array();
		$errors[$l_errors]['code']=2;
		$errors[$l_errors]['message']="In &lt;".$er_phs_phs_for_name." code=\"".$code."\"&gt;, issue time (".$issue_time.") should be earlier than latest start time (".$latest_start_time.")";
		$l_errors++;
		return FALSE;
	}
}

// ### Check time order
if (!empty($earliest_start_time) && !empty($latest_start_time)) {
	if (strcmp($earliest_start_time, $latest_start_time)>0) {
		$errors[$l_errors]=array();
		$errors[$l_errors]['code']=2;
		$errors[$l_errors]['message']="In &lt;".$er_phs_phs_for_name." code=\"".$code."\"&gt;, earliest start time (".$earliest_start_time.") should be earlier than latest start time (".$latest_start_time.")";
		$l_errors++;
		return FALSE;
	}
}

// ### Check time before
if (!empty($issue_time) && !empty($start_time_phs)) {
	if (strcmp($issue_time, $start_time_phs)>0) {
		$errors[$l_errors]=array();
		$errors[$l_errors]['code']=2;
		$errors[$l_errors]['message']="In &lt;".$er_phs_phs_for_name." code=\"".$code."\"&gt;, issue time (".$issue_time.") should be earlier than Phase start time (".$start_time_phs.")";
		$l_errors++;
		return FALSE;
	}
}

// ^^^ Get publish date
v1_get_pubdate($er_phs_phs_for_obj);

// vvv Set publish date
$data_time=array($issue_time, $earliest_start_time, $latest_start_time);
v1_set_pubdate($data_time, $current_time, $er_phs_phs_for_obj);

// -- CHECK DUPLICATION --

// ### Check duplication
$final_owners=$er_phs_phs_for_obj['results']['owners'];
if (!v1_check_dupli_simple($er_phs_phs_for_name, $er_phs_phs_for_key, $code, $final_owners, $dupli_error)) {
	// Duplication found
	$errors[$l_errors]=array();
	$errors[$l_errors]['code']=7;
	$errors[$l_errors]['message']=$dupli_error;
	$l_errors++;
	return FALSE;
}

// -- RECORD OBJECT --

// vvv Record object
$data=array();
$data['owners']=$final_owners;
v1_record_obj($er_phs_phs_for_key, $code, $data);

// -- CHECK DATABASE --

// ### Check existing data in database
v1_check_db_simple($er_phs_phs_for_name, $er_phs_phs_for_key, $code, $final_owners);

// -- PREPARE DISPLAY --

// Increment data count (for display)
if (!isset($data_list[$er_phs_phs_for_key])) {
	$data_list[$er_phs_phs_for_key]=array();
	$data_list[$er_phs_phs_for_key]['name']="Eruption forecast";
	$data_list[$er_phs_phs_for_key]['number']=0;
	$data_list[$er_phs_phs_for_key]['sets']=array();
}
$data_list[$er_phs_phs_for_key]['number']++;

// -- POP OUT GENERAL INFO --

// Pop general informations
array_shift($gen_owners);
array_shift($gen_pubdates);

?>