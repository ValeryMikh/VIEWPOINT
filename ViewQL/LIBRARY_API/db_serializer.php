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

			/************************************************************
                            db_serialize_class
 			*************************************************************/
 class db_serializer extends db_classes {//table_join //////////////////
 	function db_serializer($prefix_db=""){
		$this->db_classes($prefix_db);//table_join
	}	
	function create_table_serialize_classes(){
		$query=
		"CREATE TABLE ".$this->prefix_table."SERIALIZE_CLASSES"."(".
  			"ID_CLASS VARCHAR(32) NOT NULL DEFAULT '',".
			"SERIALIZE TEXT NOT NULL".			
		");";
		if(mysqli_query($GLOBALS["connection"],$query))
      		return 1;
    	return 0;
	}
	function edit_serialize_class($class){
		if(!$serialize=serialize($class))
			return 0;
		$id_class=$class->get_id_class();
		$str_table=$this->prefix_table."SERIALIZE_CLASSES";
		if(!$this->is_table_name($str_table))
		 	if(!$this->create_table_serialize_classes())
				return 0;
		$str_set="ID_CLASS=$id_class,SERIALIZE='$serialize'";
		$str_where="ID_CLASS=$id_class";		
		if(!$this->update_ins_table($str_table,$str_set,$str_where))
			return 0;
		return 1;			
	}
	function serialize_class($class){			
		if(empty($class))
			return 0;
		if(!$class->is_serializ())
			return 0;
		$id_class=$class->get_id_class();
		$name_class=$class->get_name_class();
		$class->set_extends_class();
		$class->empty_arr_class();
		if(!$this->edit_serialize_class($class)){
			return 0;			
		}		
		return $id_class;		
	}
	function get_serialize_class($id_class){
			$str_table=$this->prefix_table."SERIALIZE_CLASSES";
			$str_where="ID_CLASS='$id_class'";
			if(!$arr=$this->get_array($str_table,"*",$str_where))
				return 0;		
			$serialize=$arr["SERIALIZE"];// MENIAT
			$class=unserialize($serialize);
			return $class;
	}	
	function get_serialize_class_from_name($name_class){
		$name_class=trim($name_class);
		$table_serialize=$this->prefix_table."SERIALIZE_CLASSES";
		$table_classes=$this->prefix_table."CLASSES";
		$str_table="$table_serialize,$table_classes";
		$str_cond=$table_serialize .".SERIALIZE";	
		$str_where="$table_classes.NAME='$name_class' &&".
			"$table_serialize.ID_CLASS=$table_classes.ID_CLASS";
	
		if(!$arr=$this->get_array_join($str_table,"*",$str_where))
				return 0;
				
		$serialize=$arr["SERIALIZE"];// MENIAT
		$class=unserialize($serialize);
		return $class;
	}
	function get_serialize_class_from_object($id_object){
		if($id_object){
			$classes=$this->prefix_table."CLASSES";
			$class_object=$this->prefix_table."CLASS_OBJECT";
			$str_table="$classes,$class_object";
			$str_cond="$classes.ID_CLASS";
			$str_where ="$class_object.ID_OBJECT=$id_object && ".
						"$class_object.ID_CLASS=$classes.ID_CLASS ";					
			if(!$arr=$this->get_array($str_table,$str_cond,$str_where))
				return 0;
			$id_class=$arr["ID_CLASS"];
			if($class=$this->get_serialize_class($id_class))			
				return $class;
		}
		return 0;			
	}
	function new_class($name_class){
		if(!$name_class)
			return 0;
		if(!$class=$this->get_serialize_class_from_name($name_class)){
			return 0;
		}
		$class->flg_serialize = 0;	
		return $class;
	}		
	///////////////////////////////////////////////////// for db_class
	function name_to_id_class($name_class){
		$name_class=trim($name_class);
		$str_table=$this->prefix_table."CLASSES";
		$str_where ="NAME='$name_class' ";					
		if(!$arr=$this->get_array($str_table,"*",$str_where))
			return 0;
		$id_class=$arr["ID_CLASS"];
		return $id_class;					
	}	
	/////////////////////////////////////////////////////	
	function get_serialize_class_from_full_name($name_class,$class=0){			
			if(!$name_class)
				return 0;
				
			$name_class=trim($name_class);
			$full_name=$name_class;
			if(strpos($name_class,".")===false){//CLASS|COMPONENT
				$name_alter_class="";
			}else{ //INTERFACE|CLASS.CONTAINER 
				$arr_token = explode(".", $name_class);
				$name_class=$arr_token[0];
				if($this->is_class_name($full_name))//INTERFACE
					$name_alter_class=$full_name;
				else{
					return 0;
				}
			}
							//TEST CLASS				
	 		if(!$type_class=$this->is_type_class($name_class)){
				return 0;
			}							
			if($type_class!="CLASS"){
				return 0;
			}
			if(!$class){

						//SERIALISE CLASS
				if(!$class=$this->get_serialize_class_from_name($name_class)){

					return 0;
				}
				$class->interface_name=$name_class;
			}else{
				if($name_class!=$class->get_name_class()){
					return 0;
				}	
			}			
							// TEST "INTERFACE"
			if(!$name_alter_class){			
				$name_alter_class="$name_class.$name_class";				
				if(!$type_class)
					$name_alter_class="";				
				if($type_class!="VIEW")
					$name_alter_class="";									
			}else{
				$type_class=$this->is_type_class($name_alter_class);
				if(!$type_class){
					return 0;
				}				
				if($type_class!="VIEW"){
					return 0;
				}									
			}
			if($name_alter_class){
				if($name_alter_class!=$class->get_name_interface()){
					if(!$class->is_interface_class($name_alter_class))
						return 0;
					if(!$class_alter=$this->get_serialize_class_from_name($name_alter_class))
						return 0;
					$class_alter->set_extends_class();
					$class->interface_name=$name_alter_class;	
					$class->arr_extends_class=$class_alter->arr_extends_class;	
					$class->arr_control_object=$class_alter->arr_control_object;
					
					$class->array_extends_class=$class_alter->array_extends_class;
					$class->array_relations_class=$class_alter->array_relations_class;
					$class->array_pointers_class=$class_alter->array_pointers_class;
					$class->array_inheritance_class=$class_alter->array_inheritance_class;
					
					$class->list_inheritance=$class_alter->list_inheritance;
					$class->arr_interface=$class_alter->list_interface;
				}else{
					$class->flg_serializ=0;
					return "OKEY";
				}
			}
			$class->flg_serializ=0;
			return $class;
		}
}
?>