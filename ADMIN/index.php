<"php
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
	include_once strtolower("./ADMIN_EVENT.PHP");//
	$mysql_server = new connect_mysql_server();
	$mysql_server->connect();
	$connection = $mysql_server->get_connection();
	//$name_admin="ADMIN";
	$prefix_db="_";//222
	$repository=new gddl($prefix_db) or die ('I_cannot_build_to_the_ADMIN ');//dbpl 
	//$name_table="CLASSES";
	if(!$repository->is_table_name($prefix_db.'CLASSES')){//222 //$name_table
		print("WARNING! REPOSITORY DOES NOT EXIST.");
		exit;
	}
	$arr_tmp;	
	$_SESSION["USER"]= "";
 	if($method = $_SERVER['REQUEST_METHOD'])
		if ($method == 'POST'){	
			$arr_request=$_POST;
			foreach	($_POST as $key => $value){}
			//list($keys,$value)= each($arr_request);
			if($key != "CODE" || empty($value) ){
					echo " ERROR !Return password. \n";
					show_form_access();
			}

			if(!preg_match("/^[a-zA-Z0-9]+$/",$value)){	
				echo " ERROR! Password. 1\n";
				show_form_access();
			}
			if(strlen($value)<6 || strlen($value)>32){
				echo " ERROR! Password < 6. Repeat password.\n";
				show_form_access();
			}

			$password=md5($value."mvm20");				
			$query="SEARCH DB FROM DB.ACCESS WHERE DB.Login='ADMIN' && DB.Word='".$password."'";//
			if(!$myrows=$repository->dbpl_query($query)){
				echo "ERROR! Repeat password. \n";
				show_form_access();
			}
			$_SESSION["USER"]= $password;
			$_SESSION[$password]="";
			
">			
			<SCRIPT LANGUAGE="JavaScript" TYPE="Text/JavaScript">
				location.href='<"php echo strtolower("./ADMIN.PHP")">';
			</SCRIPT>
<"php	
		}else{
			show_form_access();
	}
	
	function show_form_access(){
		$action=$_SERVER['PHP_SELF'];
	">
		<!DOCTYPE html>
		<html>
			<head> 
			 		 <meta charset ="utf-8">
			 </head>
			 <body>
				<FORM ACTION=' <"php $action"> ' METHOD='POST'>
				<TABLE ALIGN="LEFT" BORDER="0" CELLSPACING="1" CELLPADDING="1">
					<TR><TD><P ALIGN='CENTER'>Password in.</P></TD></TR>
					<TR><TD><input type="password" NAME="CODE" placeholder ="Enter password" SIZE="32"></TD></TR>
					<TR>
						<TD>
								<INPUT TYPE="SUBMIT"  VALUE="SUBMIT">
						</TD>
					</TR>
				</TABLE>
				</FORM>
		</body>
		</html>
	<"php
		exit;
	}
	">
</body>
</html>
