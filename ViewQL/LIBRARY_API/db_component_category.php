<"php
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
    			/************************************************************
                            			POINTER
 				************************************************************/  
	class db_array_pointers_content extends db_category{
		var $obj;
		var $id_object;
	
 		function set($obj_class,$control){//$id_object,$control - id_record into table "text"
			$this->obj=$obj_class;
	   		$this->prefix_table=$this->obj->prefix_table;
			$this->str_table=$this->prefix_table.$this->name_table;

			$this->id_object=$this->obj->get_id_object();
			$this->control=$control;		
			return 1;
		}
		function get_current_node(){return $this->id_object;}		
		
		function get_pointers($arr_option=0){
			if(empty($arr_option) && !empty($this->obj->array_pointers_class))
				return $this->obj->array_pointers_class;				
			if(empty($this->obj->array_pointers_class))
				return 0;
			$name_class_pointer=trim($arr_option[0]);
			if(!$arr_pointer=$this->obj->get_inheritance_class_info($this->obj->array_pointers_class,$name_class_pointer))
				return 0;
				
			return $arr_pointer;
		}
		function get_pointers_parent($arr_option){
			if(!$arr_pointer=$this->get_pointers($arr_option))
				return 0;		
				
			$arr_objects=array();				
			for($i=0;$i<count($arr_pointer);$i++){
				if($arr_pointer[$i][1] == _PARENT){
					$arr_objects[]=$this->_get_pointer_parent($this->id_object,$arr_pointer[$i][0]);	
				}
			}
			return $arr_objects;			
		}				
		function get_pointers_children($arr_option=0){
			if(!$arr_pointer=$this->get_pointers($arr_option))
				return 0;		
			$arr_objects=array();				
			for($i=0;$i<count($arr_pointer);$i++){
				if($arr_pointer[$i][1] == _CHILDREN){
					$arr_objects[]=$this->_get_pointer_children($this->id_object,$arr_pointer[$i][0]);	
				}
			}
			return $arr_objects;
		}
		function get_pointers_pach($arr_option){
			if(!$arr_pointer=$this->get_pointers($arr_option))
				return 0;		
			$arr_objects=array();				
			for($i=0;$i<count($arr_pointer);$i++){
				if($arr_pointer[$i][1] == _PARENT){
					$arr_objects[]=$this->_get_pointer_pach($this->id_object,$arr_pointer[$i][0]);	
				}
			}
			return $arr_objects;			
		}				

	}//end class	
    			/************************************************************
                            			RELATION
 				************************************************************/  

	class db_array_relations_content extends db_relation{
		var $obj;
		var $id_object;
 		function set($obj_class,$control){//$id_object,$control - id_record into table "text"
			$this->obj=$obj_class;
	   		$this->prefix_table=$this->obj->prefix_table;
			$this->str_table=$this->prefix_table.$this->name_table;

			$this->id_object=$this->obj->get_id_object();
			$this->control=$control;		
			return 1;
		}		
		function get_relation($arr_option=0){		
			if(empty($arr_option) && !empty($this->obj->array_relation_class))
				return $this->obj->array_relation_class;				
			if(empty($this->obj->array_relation_class))
				return 0;
			$arr_relation=$arr_option[0];
			if(!$arr_relation=$this->obj->get_inheritance_class_info($this->obj->array_relation_class,$name_class_relation))
				return 0;
			return $arr_relation;
		}		
		function get_relation_direct($arr_option){//ERROR === get_relation_feedback				
			if(empty($arr_option[0]))
				return 0;
			$name_class_relation=trim($arr_option[0]);
			if(!$arr_relation=$this->obj->get_relations_class())
				return 0;			
			foreach($arr_relation as $name_class => $arr){
				if(($arr[0]==$name_class_relation||$arr[4]==$name_class_relation) && $arr[1]== _DIRECT){
					$arr_objects=$this->_get_relation_direct($this->id_object,$arr[3]);
					return $arr_objects;
				}
			}				
			return 0;

		}
		function get_relation_feedback($arr_option){
			if(empty($arr_option[0]))
				return 0;
			$name_class_relation=trim($arr_option[0]);
			if(!$arr_relation=$this->obj->get_relations_class())
				return 0;			
			foreach($arr_relation as $name_class => $arr){
				if(($arr[0]==$name_class_relation||$arr[4]==$name_class_relation) && $arr[1]== _FEEDBACK){
					$arr_objects=$this->_get_relation_feedback($this->id_object,$arr[3]);
					return $arr_objects;
				}
			}				
			return 0;
		}
		
		
	}//end class	
 ">
