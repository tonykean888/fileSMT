<?php
	error_reporting(E_ERROR);
 	ini_set('display_errors', 1);
	
	session_start();
	ob_start();
	
	if ($_SESSION['ses_status'] == "") {
		$s_redirect_page = "index.php";
		header( 'Location: '.$s_redirect_page );
		}
	
	function BCDate($datetime) {
	if ($datetime == "") {
    return "N/A" ;
	}
		list($d,$m,$Y) = split('/',$datetime); // แยกวันเป็น ปี เดือน วัน
		$Y = $Y+543; // เปลี่ยน ค.ศ. เป็น พ.ศ.
		return $d."/".$m."/".$Y;
	}

	function DCDate($datetime) {
		list($d,$m,$Y) = split('/',$datetime); // แยกวันเป็น ปี เดือน วัน
		$Y = $Y-543; // เปลี่ยน ค.ศ. เป็น พ.ศ.
		return $d."/".$m."/".$Y;
	}

	
	function get_select_month_th($s_name, $s_default){
		$a_month_th = array("- Please Select -","มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection'>";
		//$s_selection .= "<option value=''>-- Please Select --</option>"; 
		foreach ($a_month_th as $key => $value){
			$str_selected = ($key == $s_default ? "SELECTED" : "");
			$s_selection .= "<option value='".$key."'$str_selected>".$value."</option>";	
		}
		$s_selection .= "</select>";
		return $s_selection;
		
	}
	
	

	
	function debug( $msg, $b_stop = false ){
		echo "DEBUG: ===[$msg]===<br>";
		if( $b_stop ) { die(); }
	}
	
	function get_BC( $y ){
		return (int)$y + 543;
	}
	
	function get_DC( $y ){
		return (int)$y - 543;
	}
	
	function oci_escape_string($string) {
	  return str_replace(array('"', "'", '\\'), array('\\"', "''", '\\\\'), $string);
	}

	function get_next_id( $id_field, $table ){
		global $SMTConn;
		
		$strSQL = " SELECT MAX( $id_field ) AS CURR_ID FROM $table ";
		$SMTConn->runQuery( $strSQL );
		$row = $SMTConn->fetch_assoc();
		$curr_id = $row["CURR_ID"];
		if( $curr_id == "" ) { $curr_id = 0; }
		
		$next_id = ( $curr_id + 1 );
		
		return $next_id;
	}
	
	function get_number_list( $s_name, $s_default, $i_start, $i_end ){
		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection' style='width:60px;'>";
		for( $i = $i_start; $i <= $i_end; $i++ ){
			$str_selected = ( $i == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='$i'$str_selected>$i</option>";
		}
		$s_selection .= "</select>";
		return $s_selection;
	}
	
	function get_number_list_ex( $s_name, $s_default, $i_start, $i_end ){
		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection' style='width:50px;'>";
		$s_selection .= "<option value=''>0</option>"; 
		for( $i = $i_start; $i <= $i_end; $i++ ){
			$str_selected = ( $i == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='$i'$str_selected>$i</option>";
		}
		$s_selection .= "</select>";
		return $s_selection;
	}
	
	function get_value_a( $s_type,$s_default){
		return get_config( $s_type, $s_default);
	}
	
	function get_value_bank($s_default){
		return get_config_bank($s_default);
	}
	
	function get_value_bankbranch($s_bank_code,$s_bankbranch_code){
		return get_config_bankbranch($s_bank_code,$s_bankbranch_code);
	}
	
	function check_cont_post($cont_post,$cont_id) {
		global $SMTConn;
		$strSQL = "select * from SMT_TBL_CONTRACT_PERSON WHERE CONT_ID = '".$cont_id."' AND CONT_POST = '".$cont_post."'";
	    $SMTConn->runQuery( $strSQL );
		$num_rows = $SMTConn->get_num_rows();
		
		return $num_rows ;
	}

	/*======================================================================
	 * function: get_site_grade
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string site grage selection
	 =======================================================================*/
	function get_site_grade( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "SITE_GRADE", $s_default, $b_all );
	}


    function get_contract_status( $s_default, $b_all = true ){
		return get_config_selection( "CONT_TYPE", $s_default, $b_all );
	}
	
	/*======================================================================
	 * function: get_resp_tax
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string site type selection
	 =======================================================================*/
	 
	 function get_resp_tax_value( $s_type,$s_default){
		return get_config( $s_type, $s_default);
	}

	/*======================================================================
	 * function: get_rental_type
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string site type selection
	 =======================================================================*/
	function get_rental_type( $s_default, $b_all = true ){
		return get_config_selection( "RENT_TYPE", $s_default, $b_all );
	}
	
	function get_rental_type_value( $s_default){
		return get_config( "RENT_TYPE", $s_default);
	}
	
	function get_ext_cond_value( $s_default){
		return get_config( "CONT_EXT_COND", $s_default);
	}
	
	function get_cont_post( $s_default, $b_all = true){
		return get_config_selection( "CONT_POST", $s_default);
	}
	
	/*======================================================================
	 * function: get_lessor_type 
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string lessor type selection
	 =======================================================================*/
	function get_lessor_type( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "CONT_LESSOR_TYPE", $s_default, $b_all );
	}

	function get_lessor_type_value( $s_default){
		return get_config( "CONT_LESSOR_TYPE", $s_default);
	}

	/*======================================================================
	 * function: get_elec_region 
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string electricity regional selection
	 =======================================================================*/
	function get_elec_region( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "E_REGION", $s_default, $b_all );
	}

	function get_elec_region_value( $s_default){
		return get_config( "E_REGION", $s_default);
	}
	


	/*======================================================================
	 * function: get_elec_domain 
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string electricity domain selection
	 =======================================================================*/
	function get_elec_domain ( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "E_DOMAIN_TYPE", $s_default, $b_all );
	}

	function get_elec_domain_value( $s_default){
		return get_config( "E_DOMAIN_TYPE", $s_default);
	}

	/*======================================================================
	 * function: get_meter_digit  
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string meter diigt selection
	 =======================================================================*/
	function get_meter_digit ( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "METER_DIGIT", $s_default, $b_all );
	}

	function get_meter_digit_value( $s_default){
		return get_config( "METER_DIGIT", $s_default);
	}


	/*======================================================================
	 * function: get_meter_type  
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string meter type selection
	 =======================================================================*/
	function get_meter_type ( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "METER_TYPE", $s_default, $b_all );
	}

	function get_meter_type_value( $s_default){
		return get_config( "METER_TYPE", $s_default);
	}


	/*======================================================================
	 * function: get_meter_owner  
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string meter diigt selection
	 =======================================================================*/
	function get_meter_owner ( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "ELEC_OWNER", $s_default, $b_all );
	}

	function get_meter_owner_value( $s_default){
		return get_config( "ELEC_OWNER", $s_default);
	}
	/*======================================================================
	 * function: get_meter_size 
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string meter size selection
	 =======================================================================*/
	function get_meter_size ( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "METER_SIZE", $s_default, $b_all );
	}

	function get_meter_size_value( $s_default){
		return get_config( "METER_SIZE", $s_default);
	}
	/*======================================================================
	 * function: get_meter_promo 
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string meter promotion selection
	 =======================================================================*/
	function get_meter_prom ( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "PROMOTION", $s_default, $b_all );
	}

	function get_meter_promo_value( $s_default){
		return get_config( "PROMOTION", $s_default);
	}
	/*======================================================================
	 * function: get_meter_insr  
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string meter insurance type
	 =======================================================================*/
	function get_meter_insr_type( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "INSU_ELEC_TYPE", $s_default, $b_all );
	}

	function get_meter_insr_type_value( $s_default){
		return get_config( "INSU_ELEC_TYPE", $s_default);
	}
	/*======================================================================
	 * function: get_meter_insur_vat_type  
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string type of insurance vat  for pay insurance meter type selection
	 =======================================================================*/
	function get_meter_insur_vat_type ( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "INSU_ELEC_VAT_TYPE", $s_default, $b_all );
	}

	function get_meter_insur_vat_type_value( $s_default){
		return get_config( "INSU_ELEC_VAT_TYPE", $s_default);
	}
	/*======================================================================
	 * function: get_meter_insur_flag  
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string status pay for meter insurance  selection
	 =======================================================================*/
	function get_meter_insur_flag ( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "INSU_ELEC_RTRN_FLAG", $s_default, $b_all );
	}

	function get_meter_insur_flag_value( $s_default){
		return get_config( "INSU_ELEC_RTRN_FLAG", $s_default);
	}
	/*======================================================================
	 * function: get_meter_insur  
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string vat insurance meter value selection
	 =======================================================================*/
	function get_meter_insur ( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "INSU_ELEC_VAT", $s_default, $b_all );
	}

	function get_meter_insur_value( $s_default){
		return get_config( "INSU_ELEC_VAT", $s_default);
	}
	/*======================================================================
	 * function: get_meter_insur_paym  
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string payment method for meter insurance selection
	 =======================================================================*/
	function get_meter_insur_paym ( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "INSU_ELEC_PAYM_MTHD", $s_default, $b_all );
	}

	function get_meter_insur_paym_value( $s_default){
		return get_config( "INSU_ELEC_PAYM_MTHD", $s_default);
	}
	


		
	
	/*======================================================================
	 * function: get_station_type
	 * parameter: s_default = detfaul selected value
	 * process: read data from database (configable table)
	 * return: string station type selection
	 =======================================================================*/
	function get_station_type( $s_default, $b_all = true ){
		return get_config_selection( "STATION_TYPE", $s_default, $b_all );
	}

	function get_tower_type( $s_default, $b_all = true, $i_index = "" ){
		return get_config_selection( "TOWER_TYPE", $s_default, $b_all, $i_index );
	}

	function get_company( $s_default, $b_all = true ){
		return get_config_selection( "COMP_CODE", $s_default, $b_all );
	}

	function get_region( $s_default, $b_all = true ){
		return get_config_selection( "REGION", $s_default, $b_all );
	}
	
	function get_land_area_unit( $s_default, $b_all = true ){
		return get_config_selection( "LAND_AREA_UNIT", $s_default, $b_all );
	}
	
	function get_land_area_unit_config( $s_default){
		return get_config( "LAND_AREA_UNIT", $s_default);
	}

	function get_site_sharing( $s_default, $b_all = true ){
		if( $s_default == "" || $s_default == "N" ) { $s_default = "NONE"; }
		return get_config_selection( "SITE_SHARE", $s_default, $b_all );
	}
	
	function get_site_status( $s_default, $b_all = true ){
		return get_config_selection( "SITE_STATUS", $s_default, $b_all );
	}
	
	function get_cont_status( $s_default, $b_all = true ){
		return get_config_selection( "CONT_STATUS", $s_default, $b_all );
	}
	
	function get_cont_type( $s_default, $b_all = true ){
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "CONT_TYPE", $s_default, $b_all );
	}
	
	function get_ext_cond ($s_default, $b_all = true ) {
		if( $s_default == "" ) { $s_default = ""; }
		return get_config_selection( "CONT_EXT_COND", $s_default, $b_all );
	}

	function get_config_value( $config_k ){
		//global $SMTConn;
		$SMTConn = new DBConnection();
		$SMTConn->ConnectSMT();
		$config_value = $_SESSION["TX_CONFIG_$config_k"];
		
		if( $config_value == "" ) {
			$strSQL = " SELECT CONFIG_VALUE FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_KEY = '".trim($config_k)."' ";
			
			//debug($strSQL);
			$SMTConn->runQuery( $strSQL );
			$row = $SMTConn->fetch_assoc();
			$config_value = $row["CONFIG_VALUE"];
			
			$_SESSION["TX_CONFIG_$config_k"] = $config_value;
		}
		
		return $config_value;
	}
	
	function get_user_details( $user_id, $upuser_id, $b_tel = false, $b_email = false ){
		global $SMTConn;
		$s_details = "";
		
		$strSQL = " SELECT * FROM SMT_TBL_USER WHERE USER_ID = '$user_id' ";
		$SMTConn->runQuery( $strSQL );
		$row_num = $SMTConn->get_num_rows();
		
		if( $row_num > 0 ) {
			$row = $SMTConn->fetch_assoc();
			$s_details = $row["F_NAME"]." ".$row["L_NAME"];
		
			if( $b_tel ) { $s_details .= ", เบอร์โทร: ".$row["USER_TEL"]; }
			if( $b_email ) { $s_details .= ", Email: ".$row["E_MAIL"]; }
		} else {
			$strSQL = " SELECT * FROM SMT_TBL_USER WHERE USER_ID = '$upuser_id' ";
			$SMTConn->runQuery( $strSQL );
		$row = $SMTConn->fetch_assoc();
		$s_details = $row["F_NAME"]." ".$row["L_NAME"];
		
		if( $b_tel ) { $s_details .= ", เบอร์โทร: ".$row["USER_TEL"]; }
		if( $b_email ) { $s_details .= ", Email: ".$row["E_MAIL"]; }
		}
		
		return $s_details;
	}
		
	function get_WT_tax_selection( $s_name, $s_default, $b_all = true, $s_style = 1 ){
		global $SMTConn;
		$s_EX = "";
		if( $s_style == 2 ) { $s_EX = " AND CONFIG_KEY != 'WT03' "; }
		if( $s_style == 3 ) { $s_EX = " AND ( CONFIG_KEY != 'WT05' AND CONFIG_KEY != 'WT10' ) "; }
		$strSQL = " SELECT * FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = 'WT_GROUP' $s_EX ORDER BY CONFIG_SEQN ";
		$SMTConn->runQuery( $strSQL );

		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection'>";
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>-- Please Select --</option>"; 
		}

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["CONFIG_KEY"] == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$row["CONFIG_KEY"]."'$str_selected>".$row["CONFIG_VALUE"]."</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}
	
	function get_resp_tax_selection( $s_name, $s_default, $b_all = true ){
		global $SMTConn;
		
		$strSQL = " SELECT * FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = 'RESP_TAX' ORDER BY CONFIG_SEQN ";
		$SMTConn->runQuery( $strSQL );

		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection'>";
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>-- Please Select --</option>"; 
		}

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["CONFIG_KEY"] == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$row["CONFIG_KEY"]."'$str_selected>".$row["CONFIG_VALUE"]."</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}

	function get_config_selection( $c_name, $s_default, $b_all = true, $i_index = "" ){
		global $SMTConn;
		
		$s_name = $c_name.$i_index;
		$strSQL = " SELECT * FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = '$c_name' ORDER BY CONFIG_SEQN ";
		$SMTConn->runQuery( $strSQL );

		$s_selection = "<select name='$s_name' id='$s_name' onkeypress='return check_enter(event)' class='cSelection'>";
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>-- Please Select --</option>"; 
		}

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["CONFIG_KEY"] == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$row["CONFIG_KEY"]."'$str_selected>".$row["CONFIG_VALUE"]."</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}
	
	function get_config($c_name,$s_default){
		global $SMTConn;

		$strSQL = " SELECT * FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = '$c_name' AND CONFIG_KEY = '$s_default' ORDER BY CONFIG_SEQN ";
		$SMTConn->runQuery( $strSQL );

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["CONFIG_KEY"] == $s_default ? " SELECTED" : ""  );
			$s_selection = $row["CONFIG_VALUE"];
		}
		
		return $s_selection;
	}

	function get_district_selection( $s_name, $s_default, $amphoe_id, $b_all = true ){
		global $SMTConn;
		
		if( $amphoe_id != "" ){
			$strSQL = " SELECT * FROM SMT_TBL_MAS_ADDR_DIST WHERE AMPH_ID = '$amphoe_id' ORDER BY DIST_NAME ";
		} else {
		$strSQL = " SELECT * FROM SMT_TBL_MAS_ADDR_DIST ORDER BY DIST_NAME ";
		}
		$SMTConn->runQuery( $strSQL );

		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection'>";
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>- กรุณาเลือกเขต/อำเภอ -</option>"; 
		}

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["DIST_ID"] == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$row["DIST_ID"]."'$str_selected>".$row["DIST_NAME"]."</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}
	
	function get_district_name( $district_id ){
		global $SMTConn;
		
		$district_name = $_SESSION["TX_DIST_$district_id"];
		
		if( $district_name == "" ) {
			$strSQL = " SELECT DIST_NAME FROM SMT_TBL_MAS_ADDR_DIST WHERE DIST_ID = '$district_id' ";
			
			$SMTConn->runQuery( $strSQL );
			$row = $SMTConn->fetch_assoc();
			$district_name = $row["DIST_NAME"];
			
			$_SESSION["TX_DIST_$district_id"] = $district_name;
		}
		
		return $district_name;
	}

	function get_amphoe_selection( $s_name, $s_default, $i_province, $b_all = true ){
		global $SMTConn;
		
		if( $i_province != "" ){
				$strSQL = " SELECT * FROM SMT_TBL_MAS_ADDR_AMPH WHERE PROV_ID = '$i_province' ORDER BY AMPH_NAME ";
		} else {
		$strSQL = " SELECT * FROM SMT_TBL_MAS_ADDR_AMPH ORDER BY AMPH_NAME ";
		}
		$SMTConn->runQuery( $strSQL );

		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection'>";
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>- กรุณาเลือกจังหวัด -</option>"; 
		}

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["AMPH_ID"] == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$row["AMPH_ID"]."'$str_selected>".$row["AMPH_NAME"]."</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}
	
	function get_amphoe_name( $amphoe_id ){
		global $SMTConn;
		//debug( $amphoe_id );
		$amphoe_name = $_SESSION["TX_AMPH_$amphoe_id"];
		
		if( $amphoe_name == "" ) {
			$strSQL = " SELECT AMPH_NAME FROM SMT_TBL_MAS_ADDR_AMPH WHERE AMPH_ID = '$amphoe_id' ";
			
			$SMTConn->runQuery( $strSQL );
			$row = $SMTConn->fetch_assoc();
			$amphoe_name = $row["AMPH_NAME"];
			
			$_SESSION["TX_AMPH_$amphoe_id"] = $amphoe_name;
		}
		
		return $amphoe_name;
	}

	function get_province_selection( $s_name, $s_default, $b_all = true ){
		global $SMTConn;
		
		$strSQL = " SELECT * FROM SMT_TBL_MAS_ADDR_PROV ORDER BY PROV_NAME ";
		$SMTConn->runQuery( $strSQL );

		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection'>";
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>-- Please Select --</option>"; 
		}

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["PROV_ID"] == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$row["PROV_ID"]."'$str_selected>".$row["PROV_NAME"]."</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}
	
	function get_province_name( $province_id ){
		global $SMTConn;
		
		$province_name = $_SESSION["TX_PROV_$province_id"];
		
		if( $province_name == "" ) {
			$strSQL = " SELECT PROV_NAME FROM SMT_TBL_MAS_ADDR_PROV WHERE PROV_ID = '$province_id' ";
			
			$SMTConn->runQuery( $strSQL );
			$row = $SMTConn->fetch_assoc();
			$province_name = $row["PROV_NAME"];
			
			$_SESSION["TX_PROV_$province_id"] = $province_name;
		}
		
		return $province_name;
	}

	function get_extra_payment_selection( $contract_id, $s_name, $s_default ){
		$SMTConn2 = new DBConnection();
		$SMTConn2->connectSMT();
		
		$strSQL = " SELECT * FROM SMT_TBL_CONTRACT_PERSON WHERE CONT_ID = '$contract_id' AND CONT_POST = 'EXTRA' ORDER BY PERSON_ID ";
		$SMTConn2->runQuery( $strSQL );

		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection'>";

		while( $row = $SMTConn2->fetch_assoc() ){
			$PERSON_ID = $row["PERSON_ID"];
			$oPerson = new SMTPerson();
			$oPerson->PERSON_ID = $PERSON_ID;
			$oPerson->load_person();
			$str_selected = ( $PERSON_ID == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$PERSON_ID."'$str_selected>".$oPerson->get_person_name()."</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}

	function get_bank_selection( $s_name, $s_default, $b_all = true ){
		global $SMTConn;
		
		$strSQL = " SELECT * FROM SMT_TBL_MAS_BANK ORDER BY BANK_NAME ";
		$SMTConn->runQuery( $strSQL );

		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection'>";
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>-- Please Select --</option>"; 
		}

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["BANK_CODE"] == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$row["BANK_CODE"]."'$str_selected>".$row["BANK_NAME"]."</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}
	
	function get_config_bank($s_default){
		global $SMTConn;

		$strSQL = " SELECT * FROM SMT_TBL_MAS_BANK WHERE BANK_CODE = '$s_default' ORDER BY BANK_NAME ";
		$SMTConn->runQuery( $strSQL );

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["BANK_CODE"] == $s_default ? " SELECTED" : ""  );
			$s_selection = $row["BANK_NAME"];
		}
		
		return $s_selection;
	}
	
	function get_config_bank_branch($s_default){
		global $SMTConn;

		$strSQL = " SELECT * FROM SMT_TBL_MAS_BANK WHERE BANK_CODE = '$s_default' ORDER BY BANK_NAME ";
		$SMTConn->runQuery( $strSQL );

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["BANK_CODE"] == $s_default ? " SELECTED" : ""  );
			$s_selection = $row["BANK_NAME"];
		}
		
		return $s_selection;
	}
	
	function get_config_bankbranch($s_bank_code,$s_bankbranch_code){
		global $SMTConn;

		$strSQL = " SELECT * FROM SMT_TBL_MAS_BANK_BRANCH WHERE BANK_CODE = '$s_bank_code' and BRANCH_CODE = '$s_bankbranch_code' ORDER BY BRANCH_NAME ";
		$SMTConn->runQuery( $strSQL );

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["BRANCH_CODE"] == $s_default ? " SELECTED" : ""  );
			$s_selection = $row["BRANCH_NAME"];
		}
		
		return $s_selection;
	}

	function get_branch_selection( $s_name, $s_default, $b_all = true,  $s_bank_code = "" ){
		//global $SMTConn;
		//debug( $s_default );
		$SMTConnL = new DBConnection();
		$SMTConnL->connectSMT();
		
		if( $s_bank_code != "" ) { $s_EX = " WHERE BANK_CODE = '$s_bank_code' "; }
		$strSQL = " SELECT * FROM SMT_TBL_MAS_BANK_BRANCH $s_EX ORDER BY BRANCH_NAME ";
		//debug( $strSQL );
		$SMTConnL->runQuery( $strSQL );

		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection'>";
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>-- Please Select --</option>"; 
		}

		while( $row = $SMTConnL->fetch_assoc() ){
			$str_selected = ( $row["BRANCH_CODE"] == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$row["BRANCH_CODE"]."'$str_selected>".$row["BRANCH_NAME"]."</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}

	function get_site_system( $s_default  ){
		return get_config_checkbox( "SITE_SYSTEM", $s_default, $b_all );
	}

	function get_person_serv_type( $s_default  ){
		return get_config_checkbox2( "CONT_POST", $s_default, $b_all );
	}
	
	function get_site_system_list( $s_system ){
		
		$a_system = explode( ",", $s_system );
		
		$s_text = "";
		foreach( $a_system as $v ){
			$s_text  .= ",&nbsp;".get_config_value( $v );
		}
		$s_text = substr( $s_text, 1 );
		
		return $s_text;
	}

	function get_VAT_type( $s_name, $s_default, $b_exclude_no_vat = false ){
		global $SMTConn;

		if( $s_default == "" ) { $s_default = "IVAT"; }
		//return get_config_radio( $s_name, "VAT_TYPE", $s_default );
		
		$strSQL = " SELECT * FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = 'VAT_TYPE' ";
		if( $b_exclude_no_vat ) { $strSQL .= " AND CONFIG_KEY != 'NVAT' "; }
		$strSQL .= " ORDER BY CONFIG_SEQN ";
		$SMTConn->runQuery( $strSQL );
		
		$s_radio = "";
		$i = 1;
		while( $row = $SMTConn->fetch_assoc() ){
			$str_checked = ( $row["CONFIG_KEY"] == $s_default ? " CHECKED" : ""  );
			$s_radio .= "<input type='radio' name='$s_name' id='$s_name$i' value='".$row["CONFIG_KEY"]."'$str_checked><label for='$s_name$i' style='cursor:pointer;'>".$row["CONFIG_VALUE"]."</label>&nbsp;&nbsp;";
			$i++;
		}
		
		return $s_radio;
	}

	function get_payment_type( $s_name, $s_default ){
		if( $s_default == "" ) { $s_default = "PMNT_E"; }
		return get_config_radio( $s_name, "CONT_PMNT_TYPE", $s_default, 2 );
	}

	function get_insu_type( $s_name, $s_default ){
		if( $s_default == "" ) { $s_default = "CASH"; }
		return get_config_radio( $s_name, "INSU_RENT_TYPE", $s_default );
	}

	function get_payment_meth( $s_name, $s_default, $style = 1 ){
		if( $s_default == "" ) { $s_default = "CASH"; }
		//return get_config_radio( $s_name, "PAYM_MTHD", $s_default );

		global $SMTConn;
		
		$s_Ex = ( $style == 1 ? " AND CONFIG_KEY != 'BG' " : "" );
	 	$strSQL = " SELECT * FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = 'PAYM_MTHD' $s_Ex ORDER BY CONFIG_SEQN ";
		$SMTConn->runQuery( $strSQL );
		
		$s_radio = "";
		$i = 1;
		while( $row = $SMTConn->fetch_assoc() ){
			$str_checked = ( $row["CONFIG_KEY"] == $s_default ? " CHECKED" : ""  );
			$s_radio .= "<input type='radio' name='$s_name' id='$s_name$i' value='".$row["CONFIG_KEY"]."'$str_checked><label for='$s_name$i' style='cursor:pointer;'>".$row["CONFIG_VALUE"]."</label>&nbsp;&nbsp;";
			if( $style == 2 ) { $s_radio .= "<br>"; }
			$i++;
		}
		
		return $s_radio;
	}

	function get_config_radio( $s_name, $c_name, $s_default, $style = 1 ){
		global $SMTConn;
		
		$strSQL = " SELECT * FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = '$c_name' ORDER BY CONFIG_SEQN ";
		$SMTConn->runQuery( $strSQL );
		
		$s_radio = "";
		$i = 1;
		while( $row = $SMTConn->fetch_assoc() ){
			$str_checked = ( $row["CONFIG_KEY"] == $s_default ? " CHECKED" : ""  );
			$s_checkbox .= "<input type='radio' name='$s_name' id='$s_name$i' value='".$row["CONFIG_KEY"]."'$str_checked><label for='$s_name$i' style='cursor:pointer;'>".$row["CONFIG_VALUE"]."</label>&nbsp;&nbsp;";
			if( $style == 2 ) { $s_checkbox .= "<br>"; }
			$i++;
		}
		
		return $s_checkbox;
	}
	
	function get_config_checkbox( $s_name, $s_default ){
		global $SMTConn;
		
		$a_checked = explode( ",", $s_default );
		
		$strSQL = " SELECT * FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = '$s_name' ORDER BY CONFIG_SEQN ";
		$SMTConn->runQuery( $strSQL );
		
		$s_checkbox = "";
		$i = 1;
		while( $row = $SMTConn->fetch_assoc() ){
			$s_checkbox .= "<input type='checkbox' name='".$s_name."[]' id='$s_name$i' value='".$row["CONFIG_KEY"]."'><label for='$s_name$i' style='cursor:pointer;'>".$row["CONFIG_VALUE"]."</label>&nbsp;&nbsp;";
			$i++;
		}
		
		foreach( $a_checked as $v ){
			$s_checkbox  = str_replace( "value='$v'", "value='$v' CHECKED", $s_checkbox );
		}
		
		return $s_checkbox;
	}

	function get_config_checkbox2( $s_name, $s_default ){
		global $SMTConn;
		
		$a_checked = explode( ",", $s_default );
		
		$strSQL = " SELECT * FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = '$s_name' ORDER BY CONFIG_SEQN ";
		$SMTConn->runQuery( $strSQL );
		
		$s_checkbox = "";
		$i = 1;
		while( $row = $SMTConn->fetch_assoc() ){
			$c_k = trim($row["CONFIG_KEY"]);
			$s_checkbox .= "<input type='checkbox' name='".$s_name."[]' id='$s_name".$c_k."' value='".$c_k."'><label for='$s_name".$c_k."' style='cursor:pointer;'>".$row["CONFIG_VALUE"]."</label><br>";
			$i++;
		}
		
		foreach( $a_checked as $v ){
			$s_checkbox  = str_replace( "value='$v'", "value='$v' CHECKED", $s_checkbox );
		}
		
		return $s_checkbox;
	}
	
	function get_mortgage_type( $s_default ){
		if( $s_default == "Y" ){
			$no_checked = "";
			$yes_checked = " CHECKED";
		} else {
			$no_checked = " CHECKED";
			$yes_checked = "";
		}
		$s_mortgage = "<input type='radio' name='CONT_MORTGAGE' id='CONT_MORTGAGE1' value='N'$no_checked><label for='CONT_MORTGAGE1' style='cursor:pointer;'>ไม่จำนอง</label>&nbsp;&nbsp;";
		$s_mortgage .= "<input type='radio' name='CONT_MORTGAGE' id='CONT_MORTGAGE2' value='Y'$yes_checked><label for='CONT_MORTGAGE1' style='cursor:pointer;'>จำนอง</label>&nbsp;&nbsp;";
		
		return $s_mortgage;
	}

	function get_tower_height( $s_default, $b_all = true, $i_index = "" ){
		$tower_height = array(3, 4, 5, 6, 9, 10, 12, 13, 15, 16, 18, 19, 20, 21, 22, 23, 24, 25, 26, 28, 29, 30, 34, 35, 39, 40, 42, 43, 45, 46, 47, 48, 50, 52, 55, 60, 61, 70, 72, 77, 80, 84, 85, 95, 100, 120);
		$s_selection = "<select name='TOWER_HEIGHT$i_index' id='TOWER_HEIGHT$i_index' class='cSelection'>";
		
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>-- Please Select --</option>"; 
		}
		foreach( $tower_height as $v ){
			$str_selected = ( $v == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='$v'$str_selected>$v เมตร</option>";
		}
		$s_selection .= "</select>";
		return $s_selection;
	}

	function get_person_type( $s_name, $s_default, $b_all = true ){
		//$person_type = array("นิติบุคคล","บุคคลธรรมดา","ข้าราชการ","รัฐวิสาหกิจ");
		/*$person_type = array("บุคคลธรรมดา", "นิติบุคคล");
		if( $s_default == "" ) { $s_default = 0; }
		
		$s_radio = "";
		
		for( $i = 0; $i < sizeof( $person_type ); $i++ ){
			$s_radio .= "<input type='radio' name='$s_name' id='$s_name$i' value='$i'".( $s_default == $i ? " CHECKED" : "" )."><label for='$s_name$i' style='cursor:pointer;'>".$person_type[$i]."</label>&nbsp;&nbsp;";
		}
		
		return $s_radio;*/
		if( $s_default == "" ) { $s_default = "PERS"; }
		return get_config_radio( $s_name, "PERS_TYPE", $s_default );	}
	
	function get_person_type_tx( $i_type ){
		$person_type = array("บุคคลธรรมดา", "นิติบุคคล");
		return $person_type[ $i_type ];
	}
	
	function print_payment( $CONT_ID, $s_type ){
		$SMTConnL = new DBConnection();
		$SMTConnL->connectSMT();
		
		if( $s_type == "RENT" || $s_type == "SERV" ){
			$strSQL = " SELECT * FROM SMT_TBL_CONTRACT_RENTAL_AMNT WHERE CONT_ID = '$CONT_ID' AND PAYM_TYPE = '$s_type' ORDER BY ROUND ";
				//debug($strSQL);
			$SMTConnL->runQuery( $strSQL );
			$i = 0;
			
			$a_payment = array();
			while( $row = $SMTConnL->fetch_assoc() ) {
				$a_payment[ $i ] = $row["RENT_AMNT_YEAR"];
				echo number_format( $a_payment[ $i ], 2 )."&nbsp;&nbsp;บาท&nbsp;/&nbsp;ปี<br>";
				$i++;
			}
			
			return $a_payment;
		}
	}
	
	function get_elec_type( $s_name, $s_default ){
		//$elec_type = array("รวมมิเตอร์", "แยกมิเตอร์");
		switch( $s_default ) { 
			case "0" :
				$s_default = "E_TYPE_L";
				break; 
			case "1" :
			case "" :
				$s_default = "E_TYPE_T";
				break; 
		}
		return get_config_radio( $s_name, "ELEC_TYPE", $s_default, 2 );
		
		/*$s_radio = "";
		
		for( $i = 0; $i < sizeof( $elec_type ); $i++ ){
			$s_radio .= "<input type='radio' name='$s_name' id='$s_name$i' value='$i'".( $s_default == "$i" ? " CHECKED" : "" )."><label for='$s_name$i' style='cursor:pointer;'>".$elec_type[$i]."</label>&nbsp;&nbsp;";
		}
		
		return $s_radio;*/
	}
	
	function get_electric_institute( $s_name, $s_default ){
		global $SMTConn;
		
		$strSQL = " SELECT * FROM SMT_TBL_MAS_ELEC_DOMAIN ORDER BY ELEC_DOMAIN_NAME, ELEC_DOMAIN_ID ";
		$SMTConn->runQuery( $strSQL );

		$s_selection = "<select name='$s_name' id='$s_name' class='cSelection'>";
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>-- Please Select --</option>"; 
		}

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["ELEC_DOMAIN_ID"] == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$row["ELEC_DOMAIN_ID"]."'$str_selected>".$row["ELEC_DOMAIN_NAME"]." (".$row["ELEC_DOMAIN_ID"].")</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}
	
	function get_electric_owner( $s_default, $b_all, $style = 1 ){
		//return get_config_selection( "ELEC_OWNER", $s_default, $b_all );
		global $SMTConn;
		$s_EX = ( $style == 2 ? " AND CONFIG_KEY != 'LL' " : "" );
		$strSQL = " SELECT * FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = 'ELEC_OWNER' $s_EX ORDER BY CONFIG_SEQN ";
		$SMTConn->runQuery( $strSQL );

		$s_selection = "<select name='ELEC_OWNER' id='ELEC_OWNER' class='cSelection'>";
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>-- Please Select --</option>"; 
		}

		while( $row = $SMTConn->fetch_assoc() ){
			$str_selected = ( $row["CONFIG_KEY"] == $s_default ? " SELECTED" : ""  );
			$s_selection .= "<option value='".$row["CONFIG_KEY"]."'$str_selected>".$row["CONFIG_VALUE"]."</option>";
		}
		
		$s_selection .= "</select>";
		return $s_selection;
	}

	function get_elec_pay_duration( $s_default, $b_all = true){
		$s_selection = "<select name='E_DIVIDE_PAYM' id='E_DIVIDE_PAYM' class='cSelection'>";
		
		if( $b_all ) { 
			$s_selection .= "<option value=''>ทั้งหมด</option>"; 
		} else {
			$s_selection .= "<option value=''>-- Please Select --</option>"; 
		}
		$str_selected = ( "M" == $s_default ? " SELECTED" : ""  );
		$s_selection .= "<option value='M'$str_selected>เดือน</option>";
		$str_selected = ( "Y" == $s_default ? " SELECTED" : ""  );
		$s_selection .= "<option value='Y'$str_selected>ปี</option>";

		$s_selection .= "</select>";
		return $s_selection;
	}
	
	function get_vat_value(){
		global $SMTConn;

		$strSQL = " SELECT CONFIG_VALUE FROM SMT_TBL_MAS_CONFIG WHERE CONFIG_NAME = 'VAT_RATE' ";
		$SMTConn->runQuery( $strSQL );

		while($row = $SMTConn->fetch_assoc()) 
		$vat_rate = $row['CONFIG_VALUE'];
		
		return $vat_rate;		
	}

        function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
		/*
		$interval can be:
		yyyy - Number of full years
		q - Number of full quarters
		m - Number of full months
		y - Difference between day numbers
			(eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
		d - Number of full days
		w - Number of full weekdays
		ww - Number of full weeks
		h - Number of full hours
		n - Number of full minutes
		s - Number of full seconds (default)
		*/
		
		if (!$using_timestamps) {
			$datefrom = strtotime($datefrom, 0);
			$dateto = strtotime($dateto, 0);
		}
		$difference = $dateto - $datefrom; // Difference in seconds
		 
		switch($interval) {
		 
		case 'yyyy': // Number of full years
	
			$years_difference = floor($difference / 31536000);
			if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
				$years_difference--;
			}
			if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
				$years_difference++;
			}
			$datediff = $years_difference;
			break;
	
		case "q": // Number of full quarters
	
			$quarters_difference = floor($difference / 8035200);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
				$months_difference++;
			}
			$quarters_difference--;
			$datediff = $quarters_difference;
			break;
	
		case "m": // Number of full months
	
			$months_difference = floor($difference / 2678400);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
				$months_difference++;
			}
			$months_difference--;
			$datediff = $months_difference;
			break;
	
		case 'y': // Difference between day numbers
	
			$datediff = date("z", $dateto) - date("z", $datefrom);
			break;
	
		case "d": // Number of full days
	
			$datediff = floor($difference / 86400);
			break;
	
		case "w": // Number of full weekdays
	
			$days_difference = floor($difference / 86400);
			$weeks_difference = floor($days_difference / 7); // Complete weeks
			$first_day = date("w", $datefrom);
			$days_remainder = floor($days_difference % 7);
			$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
			if ($odd_days > 7) { // Sunday
				$days_remainder--;
			}
			if ($odd_days > 6) { // Saturday
				$days_remainder--;
			}
			$datediff = ($weeks_difference * 5) + $days_remainder;
			break;
	
		case "ww": // Number of full weeks
	
			$datediff = floor($difference / 604800);
			break;
		case "h": // Number of full hours
	
			$datediff = floor($difference / 3600);
			break;
	
		case "n": // Number of full minutes
	
			$datediff = floor($difference / 60);
			break;
	
		default: // Number of full seconds (default)
	
			$datediff = $difference;
			break;
		}    
	
		return $datediff;
	}
	
	
#By Den
function displayPaging( $total, $limit, $pagenumber, $functionName ){
// how many page numbers to show in list at a time
$showpages = "7"; // 1,3,5,7,9...



// set up icons to be used
$icon_path = 'icons/';
$icon_param = 'align="middle" style="border:0px;" ';
$icon_first= 'หน้าแรก';
$icon_last= 'หน้าสุดท้าย';
$icon_previous= 'ย้อนกลับ';
$icon_next= 'ถัดไป';
///////////////////
///////////////////



// do calculations
$pages = ceil($total / $limit);
$offset = ($pagenumber * $limit) - $limit;
$end = $offset + $limit;
// prepare paging links
$html .= "<div class='pagination' id='page-bottom'  style='margin:10px auto;  text-align:center;'><ul  style='text-align:center;'> ";
// if first link is needed
if($pagenumber > 1) { $previous = $pagenumber -1;
$html .= '<li style="display:inline;"><a href="#" onclick="'.$functionName.'(\'1\')">'.$icon_first.'</a> </li>';
}
// if previous link is needed
if($pagenumber > 2) { $previous = $pagenumber -1;
$html .= '<li style="display:inline;"><a href="#" onclick="'.$functionName.'(\''.$previous.'\')"  >'.$icon_previous.'</a> </li>';
}
// print page numbers
if ($pages>=2) { $p=1;
//$html .= "<li><a href='#' >Page: ";
$pages_before = $pagenumber - 1;
$pages_after = $pages - $pagenumber;
$show_before = floor($showpages / 2);
$show_after = floor($showpages / 2);
if ($pages_before < $show_before){
$dif = $show_before - $pages_before;
$show_after = $show_after + $dif;
}
if ($pages_after < $show_after){
$dif = $show_after - $pages_after;
$show_before = $show_before + $dif;
}
$minpage = $pagenumber - ($show_before+1);
$maxpage = $pagenumber + ($show_after+1);

if ($pagenumber > ($show_before+1) && $showpages > 0) {
//$html .= " ... ";
}
while ($p <= $pages) {
if ($p > $minpage && $p < $maxpage) {
if ($pagenumber == $p) {
$html .= "<li class='active' style='display:inline;'><a href='#' > <b>".$p."</b></a></li>";
} else {
$html .= '<li style="display:inline;"> <a  href="#" onclick="'.$functionName.'(\''.$p.'\')" >'.$p.'</a></li>';
}
}
$p++;
}
if ($maxpage-1 < $pages && $showpages > 0) {
//$html .= " ... ";
}
}
// if next link is needed
if($end < $total) { $next = $pagenumber +1;
if ($next != ($p-1)) {
$html .= '<li style="display:inline;"><a href="#" onclick="'.$functionName.'(\''.$next.'\')">'.$icon_next.'</a></li>';
} else {$html .= '  ';}
}
// if last link is needed
if($end < $total) { $last = $p -1;
$html .= ' <li style="display:inline;"><a href="#" onclick="'.$functionName.'(\''.$last.'\')">'.$icon_last.'</a></li>';
}
$html .= '</ul> </div>';
// return paging links
return $html;
}
?>