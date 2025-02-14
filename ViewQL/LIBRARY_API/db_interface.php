<?php
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

  /*#########################################
						dbol_interface //ICOMPONENT	
	#########################################*/

	class db_interface{//extends db_component_query{ 
		var $arr_interface=array("SHOW_COMPONENT_METHODS"=>"SHOW_COMPONENT_METHODS",
			"GET_COMPONENT_NAME"=>"GET_COMPONENT_NAME");
		var $obj_comp;
		var $name_comp;
		//	
		function db_interface($name_component,$list_interface=0){
			$this->set_arr_interface($list_interface);
			$this->name_comp=$name_component;
			$this->obj_comp=new $name_component();
		}
		function SHOW_COMPONENT_METHODS(){//SHOW_INTERFACE
			while(list($control,$n)= each($this->arr_interface)){
				print "&nbsp;&nbsp;&nbsp;&nbsp;Metthod- '$control' <BR>";	
			}//0-id_class(object class,1-name(class),2-object(class or object)
			print "<BR>";
		}
		function is_method($method){
			if(!isset($this->arr_interface[$method]))
				return 0;
			return 1;
		}
		function set_arr_interface($list_interface){
			if($list_interface)
				$this->arr_interface+=array_flip($list_interface); 	
		}
		function dbol_interface_get_object_component(){
				return $this->obj_comp;
		}		
		function GET_COMPONENT_NAME(){		
			return $this->name_comp;
		}
	}//END_CLASS
 
 ?>