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

	include_once (strtolower("../ViewQL/LIBRARY_API/T_CLASSES.PHP"));
	include_once (strtolower("./ADMIN_EVENT.PHP"));//
	$mysql_server = new connect_mysql_server();
	$mysql_server->connect();
	$connection = $mysql_server->get_connection();
	$sess_admin_name="ADMIN";		
	
	if(empty($password=$_SESSION["USER"]))
			exit;
	if(empty($_SESSION[$password])){
	    $admin=new admin_event() or die ('I_cannot_build_to_the_ADMIN '); 
		$_SESSION[$password]=serialize($admin);
		$admin->show_form_admin();		
		exit;
	}else{		
		$serialize_admin=$_SESSION[$password];	
		$admin=unserialize($serialize_admin);;	
	}

	if (!$method = $_SERVER['REQUEST_METHOD']){
		exit('REQUEST_METHOD');  
	}
	$arr_request=array();
	
	if ($method == 'POST'){
			$arr_request=$_POST;
			if(array_key_exists( "QUERY",$arr_request)){
				list($keys,$sess_query)= each($arr_request);
				if(!empty($sess_query)){
					$admin->special_query($sess_query);
					$admin->show_form_admin();
				}else
					$admin->show_form_admin();
			}
	}else{
		$arr_request=$_GET;
		
		if(array_key_exists( "QUERY",$arr_request)){
			list($keys,$sess_query)= each($arr_request);
			if(!empty($sess_query)){			
				$admin->special_query($sess_query);
			}
			$admin->show_form_admin();		
		}
		if(array_key_exists( "EVEN_TYPE_CLASS",$arr_request)){
			list($keys,$name)= each($arr_request);
			if(!empty($name)){
				$arr_option=array($name);
				$admin->even_type_class($arr_option);
			}		
		
		}
		if(array_key_exists( "SELECT_CLASS",$arr_request)){
			list($keys,$query)= each($arr_request);
			if(!empty($query)){
				$arr_option=array($query);
				$admin->select_class($arr_option);
			}		
			$admin->show_form_admin();		
		}
		if(array_key_exists( "SET_VIEW",$arr_request)){
			list($keys,$name)= each($arr_request);
			if(!empty($name)){
				$arr_option=array($name);
				$admin->set_view($arr_option);
			}		
			$admin->show_form_admin();		
		}
		if(array_key_exists( "SET_OBJECT",$arr_request)){
			list($keys,$id)= each($arr_request);
			if(!empty($id)){
				$arr_option=array($id);
				$admin->set_object($arr_option);
			}
			$admin->show_form_admin();		
		}
		if(array_key_exists( "OBJ_RELATION",$arr_request)){
			list($keys,$name_interface)= each($arr_request);
			if(!empty($name_interface)){
				$arr_option=array($name_interface);
				$admin->obj_relation($arr_option);
			}
			$admin->show_form_admin();		
		}
		
	}
		
	$_SESSION[$password]=serialize($admin);
	exit; 
">
