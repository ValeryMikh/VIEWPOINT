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

 class db_category_table extends table_join{
	var $str_table;
	var $name_table="POINTER";

	function db_category_table($prefix_db=""){
		$this->table_join($prefix_db);
		$this->str_table=$this->prefix_table.$this->name_table;
	}
 	function create(){//templet=date
		$query=
		"CREATE TABLE $this->str_table (".
  			"ID VARCHAR(32) NOT NULL DEFAULT '',".
			"ID_PARENT VARCHAR(32) NOT NULL DEFAULT '',".
			"KEY `ID` (`ID`),".
 			"KEY `ID_PARENT` (`ID_PARENT`)".
		")";
		if(mysqli_query($GLOBALS["connection"],$query))
      		return 1;
    	return 0;
	}
	function edit($arr_options){
		if(count($arr_options)!=2)
			return 0;
		$id=$arr_options[1];//0
		$id_parent=$arr_options[0];//1
		if(!$this->is_table_name($this->str_table))
		 	if(!$this->create())
				return 0;
		$str_where ="ID=$id && ID_PARENT=$id_parent";
		$str_set="ID=$id,ID_PARENT=$id_parent";
		if(!$this->update_ins_table($this->str_table,$str_set,$str_where))
			return 0;
		return 1;			
	}			
	function add($arr_options){
		if(count($arr_options)!=2)
			return 0;
		$id=$arr_options[1];//0
		$id_parent=$arr_options[0];//1

		if(!$id) return 0;
		if(!$this->is_table_name($this->str_table))
		 	if(!$this->create())
				return 0;
		$str_where ="ID=$id && ID_PARENT=$id_parent";
		if(!$n=$this->is_query_into_table($this->str_table,$str_where)){			
			$str_set="ID=$id,ID_PARENT=$id_parent";
			if(!$this->ins_table($this->str_table,$str_set))
				return 0;
		}
		return 1;			
	}			
	function del($arr_options){
		if(count($arr_options)!=2)
			return 0;
		$id=$arr_options[1];//0
		$id_parent=$arr_options[0];//1
		if(!$id)return 0;
		$str_where ="ID=$id && ID_PARENT=$id_parent";
		if(!$this->del_table($this->str_table,$str_where))
			return 0;
		return 1;			
	}			
	function del_object($id_object){
		if(!$id_object)
			return 0;
		$str_where ="ID=$id_object"; //&&control='$control'";
		$this->del_table($this->str_table,$str_where);
		$str_where ="ID_PARENT=$id_object";
		$this->del_table($this->str_table,$str_where);	
		return 1;			
	}						

 }//end class
       /************************************************************
                            			db_category
		************************************************************/

 	class db_category extends table_join{
		function db_category($prefix_db=""){	
			$this->table_join($prefix_db);
		}			
		function name_to_id_class($name_class){
			$name_class=trim($name_class);
			$str_table=$this->prefix_table."CLASSES";
			$str_where ="NAME='$name_class' ";					
			if(!$arr=$this->get_array($str_table,"*",$str_where))
				return 0;
			$id_class=$arr["ID_CLASS"];
			return $id_class;					
		}	
		function _get_pointer_parent($id_current_object,$name_parent_class){
			$id_parent_class=$this->name_to_id_class($name_parent_class);
			$arr_parents=array();		
			$str_table=$this->prefix_table."POINTER AS POINTER,".$this->prefix_table."CLASS_OBJECT AS CLASS_OBJECT";
			$str_cond="DISTINCT POINTER.ID_PARENT AS ID_PARENT,POINTER.ID AS ID";
			$str_where=	" POINTER.ID='$id_current_object' && ".
						" POINTER.ID_PARENT=CLASS_OBJECT.ID_OBJECT && ".
						" CLASS_OBJECT.ID_CLASS='$id_parent_class'";
			if(!$table=$this->get_table_join($str_table,$str_cond,$str_where))
				return 0;
			while($myrow = mysqli_fetch_array($table)){
				$arr_parents[]=$myrow["ID_PARENT"];
			}
			if(!empty($arr_parents)){
				return $arr_parents;
			}
			return 0;
		}		
		// #### This function create List children nodes of current node;
		function _get_pointer_children($id_current_node,$name_children_class){
			$id_children_class=$this->name_to_id_class($name_children_class);
			$arr_children_nodes=array(); 
			
			$str_table=$this->prefix_table."POINTER AS POINTER,".$this->prefix_table."CLASS_OBJECT AS CLASS_OBJECT";
			$str_cond="DISTINCT POINTER.ID";
			$str_where=	" POINTER.ID_PARENT='$id_current_node' && ".
						" POINTER.ID=CLASS_OBJECT.ID_OBJECT && ".
						" CLASS_OBJECT.ID_CLASS='$id_children_class'";
			if(!$table=$this->get_table_join($str_table,$str_cond,$str_where))
				return 0;
			while($myrow = mysqli_fetch_array($table)){
				$arr_children_nodes[]=$myrow["ID"];
			}
			if(!empty($arr_children_nodes)){
				return $arr_children_nodes;
			}
			return 0;
		}			
		function _get_pointer_path($id_current_node,$name_parent_class){	
			$id_parent_class=$this->name_to_id_class($name_parent_class);		
			if(!$arr_parents=$this->_get_pointer_parent($id_current_node,$name_parent_class))
				return 0;
			$arr_pach_nodes=array();	
			for($i=0;$i<count($arr_parents);$i++){
				$pach_node=array();
				if(!$this->set_pointer_path($arr_parents[$i],$pach_node)){
					array_unshift($pach_node,$arr_parents[$i]);				
					array_unshift($pach_node,$id_current_node);	
					$arr_pach_nodes = array_merge($arr_pach_nodes,$pach_node);
				}
			}
			return $arr_pach_nodes;
		}
							
		function set_pointer_path($id_current_object,&$arr_parent_nodes){
			$str_table=$this->prefix_table."POINTER AS POINTER,".$this->prefix_table."CLASS_OBJECT AS CLASS_OBJECT";
			$str_cond="DISTINCT POINTER.ID_PARENT AS ID_PARENT,POINTER.ID AS ID";
			$str_where=	" POINTER.ID='$id_current_object'";
			if(!$myrow=$this->get_array_join($str_table,$str_cond,$str_where))
				return 0;
			if($id_parent_object=$myrow["ID_PARENT"]){
				$arr_parent_nodes[]=$id_parent_object;				
					if(!$this->set_pointer_path($id_parent_object,$id_parent_class,$arr_parent_nodes))//&$arr_parent_nodes
						return 0;				
			}
			return 0;				
		}
	
	}//end class		
?>