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
                            			db_relation_table
				************************************************************/
 class db_relation_table extends table_join{
	var $str_table;
	var $name_table="RELATION";

	function db_relation_table($prefix_db=""){
		$this->table_join($prefix_db);
		$this->str_table=$this->prefix_table.$this->name_table;
	}
 	function create(){//templet=date
		$query=
		"CREATE TABLE $this->str_table (".
  			"ID_OBJECT    VARCHAR(32) NOT NULL DEFAULT '',".
			"ID_RELATION VARCHAR(32) NOT NULL DEFAULT '',".
			"KEY `ID_OBJECT` (`ID_OBJECT`),".
 			"KEY `ID_RELATION` (`ID_RELATION`)".
		")";
		if(mysqli_query($GLOBALS["connection"],$query))
      		return 1;
    	return 0;
	}
	function edit($arr_options){
		if(count($arr_options)!=2)
			return 0;
		$id_object=$arr_options[0];//
		$id_relation=$arr_options[1];//
		if(!$this->is_table_name($this->str_table))
		 	if(!$this->create())
				return 0;
		$str_where ="ID_OBJECT=$id_object && ID_RELATION=$id_relation";
				//"control=$control";
		$str_set="ID_OBJECT=$id_object,ID_RELATION=$id_relation";
				// "control=$control";
		if(!$this->update_ins_table($this->str_table,$str_set,$str_where))
			return 0;
		return 1;			
	}			
	function add($arr_options){
		if(count($arr_options)!=2)
			return 0;
		$id_object=$arr_options[0];//
		$id_relation=$arr_options[1];//
		if(!$this->is_table_name($this->str_table)){
		 	if(!$this->create())
				return 0;
			$str_set="ID_OBJECT=$id_object,ID_RELATION=$id_relation";	
			if(!$this->ins_table($this->str_table,$str_set))
			 	return 0;
			return 1;		
		}
		$str_where ="ID_OBJECT=$id_object && ID_RELATION=$id_relation";
				//"control='$control'";
		if(!$n=$this->is_query_into_table($this->str_table,$str_where)){			
			$str_set="ID_OBJECT=$id_object,ID_RELATION=$id_relation";
				// "control='$control'";
			if(!$this->ins_table($this->str_table,$str_set))
				return 0;
		}
		return 1;			
	}			
	function del($arr_options){
		if(count($arr_options)!=2)
			return 0;
		$id_object=$arr_options[0];//
		$id_relation=$arr_options[1];//
		$str_where ="ID_OBJECT=$id_object && ID_RELATION=$id_relation"; //&&control='$control'";
		if(!$this->del_table($this->str_table,$str_where))
			return 0;
		return 1;			
	}
	function del_object($id_object){
		if(!$id_object)
			return 0;
		$str_where ="ID_OBJECT=$id_object"; //&&control='$control'";
		$this->del_table($this->str_table,$str_where);////////	
		$str_where ="ID_RELATION=$id_object"; //////
		$this->del_table($this->str_table,$str_where);
		return 1;			
	}						

 }//end class

     			/************************************************************
                            			db_relation
				************************************************************/
	class db_relation extends table_join{
		function db_relation($prefix_db=""){
			$this->table_join($prefix_db);
		}		
		// #### This function create List children nodes of current node;
		function _get_relation_direct($id_object,$id_relation_class){
			$arr_relation_object=array(); 
			$str_table=$this->prefix_table."RELATION AS RELATION,".$this->prefix_table."CLASS_OBJECT AS CLASS_OBJECT";
			$str_cond="DISTINCT RELATION.ID_RELATION";
			$str_where=	" RELATION.ID_OBJECT='$id_object' && ".
						" RELATION.ID_RELATION=CLASS_OBJECT.ID_OBJECT && ".
						" CLASS_OBJECT.ID_CLASS='$id_relation_class'";

			if(!$table=$this->get_table_join($str_table,$str_cond,$str_where))
				return 0;
			while($myrow = mysqli_fetch_array($table)){
				$arr_relation_object[]=$myrow["ID_RELATION"];
			}
			if(!empty($arr_relation_object)){
				return $arr_relation_object;
			}
			return 0;
		}				
		function _get_relation_feedback($id_object,$id_relation_class){
			$arr_relation_object=array(); 
			$str_table=$this->prefix_table."RELATION AS RELATION,".
				$this->prefix_table."CLASS_OBJECT AS CLASS_OBJECT";
			$str_cond="DISTINCT RELATION.ID_OBJECT ";
			$str_where=	" RELATION.ID_RELATION='$id_object' && ".
						" RELATION.ID_OBJECT=CLASS_OBJECT.ID_OBJECT && ".
						" CLASS_OBJECT.ID_CLASS='$id_relation_class'";
			if(!$table=$this->get_table_join($str_table,$str_cond,$str_where))
				return 0;
			while($myrow = mysqli_fetch_array($table)){
				$arr_relation_object[]=$myrow["ID_OBJECT"];
			}
			if(!empty($arr_relation_object)){
				return $arr_relation_object;
			}
			return 0;
		}
				
	}//end class				
				
">