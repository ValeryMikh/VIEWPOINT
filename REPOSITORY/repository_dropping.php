<?php
session_start();
########################################################
#    Copyright 2024 Valery Mikhailovski.
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
# 	 See the GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
 #   along with this program.  If not, see <https://www.gnu.org/licenses/>.
########################################################	   
 
	ini_set(strtolower('DISPLAY_ERRORS'),'On');	
	ini_set(strtolower('LOG_ERRORS'),'On');	
	include_once  strtolower("../ViewQL/LIBRARY_API/T_CLASSES.PHP");

	$mysql_server = new connect_mysql_server();
	$mysql_server->connect();
	$connection = $mysql_server->get_connection();
	$repository = new repository_builder();
	$arr_tmp;
	$prefix_db="_";//222
	$name_table="CLASSES";
	if(!$repository->is_table_name($prefix_db.$name_table)){//222
		print("WARNING! REPOSITORY DOES NOT EXIST.");
		exit;
	}	
  	if($method = $_SERVER['REQUEST_METHOD'])
		if ($method == 'POST'){	
			foreach	($_POST as $key => $value){}
			if($key != "CODE" || empty($value) ){
				echo " ERROR !Return password. \n";
				show_form_access();
			}
			if(!preg_match("/^[a-zA-Z0-9]+$/",$value)){	
				echo " ERROR! Password. 1\n";
				show_form_access();
			}		
			//$sanitized_pass = filter_var(trim($arr_tmp["CODE"]), FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH);		
			if(strlen($value)<6 || strlen($value)>32){
				echo " ERROR! Password. 2\n";
				show_form_access();
			}
			$password=md5($value."mvm20");
		
			$query="SEARCH DB FROM DB.ACCESS WHERE DB.Word='".$password."'";
			if(!$myrows=$repository->dbpl_query($query)){
				echo "ERROR! Password  replay 2\n";
				show_form_access();
			}
			$name_DB=$mysql_server->get_name_Database();
			$repository ->drop_repository($name_DB);
		}else{
			show_form_access();
		}
	exit;	

	function show_form_access(){
		$action=$_SERVER['PHP_SELF'];
	?>
		<!DOCTYPE html>
		<html>
			<head> 
			 		 <meta charset ="utf-8">
			 </head>
			 <body>
				<FORM ACTION=' <?php $action?> ' METHOD='POST'>
				<TABLE ALIGN="LEFT" BORDER="0" CELLSPACING="1" CELLPADDING="1">
					<TR><TD><P ALIGN='CENTER'>DROP THE DATABASE  <BR> If you are sure, enter the password.</P></TD></TR>
					<TR><TD><input type="password" NAME="CODE" placeholder ="Enter password" SIZE="32"></TD></TR>
					<TR>
						<TD>
								<INPUT TYPE="SUBMIT"  VALUE="DROP DATABASE ">
						</TD>
					</TR>
				</TABLE>
				</FORM>
		</body>
		</html>
		<?php
		exit;
	}
	?>
</body>
</html>
