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

	class ARRAY_RELATION extends db_interface {
		var $obj_component;
		
		function ARRAY_RELATION() {
			$list_interface=array("SET","GET_RELATIONS",
								"GET_RELATIONS_DIRECT","GET_RELATIONS_FEEDBACK","SHOW_INTERFACE");
			$this->arr_interface=array_flip($list_interface);  
			$this->obj_component=new db_array_relations_content();
		}
		function SET($arr_option){
			$obj_class=$arr_option[0];//$obj_class ::=object of class container inheritanse this components.
			$method=$arr_option[1];// $method::=name of method(control) this class($obj_class);
			if(!$this->obj_component->set($obj_class,$method))
				return 0;
			return 1;
		}

		function GET_RELATIONS($arr_options){return $this->obj_component->get_relation($arr_options);}
		function GET_RELATIONS_DIRECT($arr_options){return $this->obj_component->get_relation_direct($arr_options);}
		function GET_RELATIONS_FEEDBACK($arr_options){return $this->obj_component->get_relation_feedback($arr_options);}
	}//end_class

			   /************************************************************
                            		ARRAY POINTER
 				************************************************************/	
	class ARRAY_POINTER extends db_interface {
		var $obj_component;
		
		function ARRAY_POINTER() {
			$list_interface=array("SET","GET_POINTERS",
								"GET_POINTERS_PARENT","GET_POINTERS_CHILDREN","GET_PACH_PARENT","SHOW_INTERFACE");
			$this->arr_interface=array_flip($list_interface); 
			$this->obj_component=new db_array_pointers_content();
		}
		function SET($arr_option){
			$obj_class=$arr_option[0];//$obj_class ::=object of class container inheritanse this components.
			$method=$arr_option[1];// $method::=name of method(control) this class($obj_class);
			if(!$this->obj_component->set($obj_class,$method))return 0;return 1;
		}

		function GET_POINTERS($arr_options){return $this->obj_component->get_pointers($arr_options);}
		function GET_POINTERS_PARENT($arr_options){return $this->obj_component->get_pointers_parent($arr_options);}
		function GET_POINTERS_CHILDREN($arr_options){return $this->obj_component->get_pointers_children($arr_options);}
		function GET_PACH_PARENT($arr_options){return $this->obj_component->get_pointers_pach($arr_options);}
		
	}//end_class
	
?>