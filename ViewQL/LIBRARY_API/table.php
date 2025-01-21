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
                            TABLE
 ************************************************************/ 
 	class table extends t_error{
		var $prefix_table;
		var $prefix_db="";
		function table($prefix_db=""){
			if(empty($prefix_db)){
				$this->prefix_table="_";//_PREFIX."_";//222
				$this->prefix_db="_";//_PREFIX;
			}else{
				$this->prefix_table=$prefix_db;//."_";//222
				$this->prefix_db=$prefix_db;
			}	
		}
//////////////////// DB /////////////////								
	  	function select_db($prefix_db){
			if(empty($prefix_db)){
				$this->prefix_table="";//_PREFIX."_";
				$this->prefix_db="";//_PREFIX;
			}else{
				$this->prefix_table=$prefix_db."_";
				$this->prefix_db=$prefix_db;
			}
			return $this->prefix_db;
	  	}
		function is_connect_db(){
			return $this->prefix_db;
	  	}
	  	function is_table_name($name){
			$query="SELECT COUNT(*) FROM $name";
			if(mysqli_query($GLOBALS["connection"],$query))
			  return 1;
			return 0;
	  	}
	  	function is_table($query){
			if($table = mysqli_query($GLOBALS["connection"],$query))

				if(mysqli_num_rows($table))
					return $table;
			return 0;
	  	}
	  	function is_query($query){
			if($table = mysqli_query($GLOBALS["connection"],$query))
			  if($n=mysqli_num_rows($table)!=0)
				return $n;
			else
			  return 0;
	  	}
	  	function empty_query($query){
			if($table = mysqli_query($GLOBALS["connection"],$query))
			  if(mysqli_num_rows($table)!=0)
				return 0;
			else
			  return 1;
		 }
	 	 function get_count($str_table){//,$limit_count)
			$query ="SELECT * FROM $str_table";
			if(!$arr = mysqli_query($GLOBALS["connection"],$query))
				return 0;
			if(!$row=mysqli_fetch_row($arr))
				return 0;;		

			$finfo=mysqli_fetch_field_direct($arr,0);		
			$field_name=$finfo->name;
			$limit_next=$row[0]+1;
			$query="UPDATE $str_table SET $field_name = '$limit_next'";
			if(mysqli_query($GLOBALS["connection"],$query))
				return $limit_next;
			return 0;	
		}
		function get_array($table,$str_cond="*",$str_where="",$str_order=""){
			if($table==""||$str_cond=="")
			  return 0;
			elseif($str_where =="" && $str_order =="")
			  $query ="SELECT $str_cond FROM $table";
		
			elseif($str_where !="" && $str_order =="")
			  $query ="SELECT $str_cond FROM $table  WHERE  $str_where";
		
			elseif($str_where =="" && $str_order!="")
			  $query ="SELECT $str_cond FROM $table ORDER BY $str_order";
		
			elseif($str_where !="" && $str_order !="")
			  $query ="SELECT $str_cond FROM $table  WHERE  $str_where ORDER BY $str_order"; 
			if($table =$this->is_table($query))
			  return mysqli_fetch_array($table);
			return 0;
	  	}
	  	function get_table($table,$str_cond="*",$str_where="",$str_order=""){
			if($table==""||$str_cond=="")
			  return 0;
			elseif($str_where =="" && $str_order =="")
			  $query ="SELECT $str_cond FROM $table";
		
			elseif($str_where !="" && $str_order =="")
			  $query ="SELECT $str_cond FROM $table  WHERE  $str_where";
		
			elseif($str_where =="" && $str_order!="")
			  $query ="SELECT $str_cond FROM $table ORDER BY $str_order";
		
			elseif($str_where !="" && $str_order !="")
			  $query ="SELECT $str_cond FROM $table  WHERE  $str_where ORDER BY $str_order"; 
			  
			return $this->is_table($query);
	  	}
	  	function del_table($table,$str_where=""){
			if($table=="")
			  return 0;
			elseif($str_where=="")
			  $query ="DELETE FROM $table";
			else 
			  $query ="DELETE FROM $table  WHERE  $str_where"; 	  
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
		function ins_table($table,$str_set){
			if($table=="")
			  return 0;
			$table=trim($table);  
			$str_set=trim($str_set);  
			$query ="INSERT INTO $table SET  $str_set"; 
			if(mysqli_query($GLOBALS["connection"],$query)) 
				return 1;
			return 0;
		}
		function ins_table_id($table,$str_set){
			if($table=="")
			  return 0;
			$query ="INSERT INTO $table SET $str_set"; 
			if(mysqli_query($GLOBALS["connection"],$query)) 
			  return mysqli_insert_id($GLOBALS["connection"]);
			return 0;
		}
		function update_table($table,$str_set,$str_where=""){ 
			if($table=="")
			  return 0;
			elseif($str_where=="")
			  $query = "UPDATE $table SET $str_set";
			else
			  $query ="UPDATE $table SET $str_set  WHERE  $str_where";
			if(mysqli_query($GLOBALS["connection"],$query))  
				return 1; 
			return 0;
		}
		function update_ins_table($table,$str_set,$str_where=""){
			if(!$table||!$str_set)
			  return 0;
			if($str_where!="")
			  $str_where = "  WHERE  $str_where";
			$query ="SELECT '*' FROM $table $str_where ";
			if($this->is_table($query))
			  $query ="UPDATE $table SET $str_set $str_where";
			else
			  $query ="INSERT INTO $table SET $str_set";;	
			  if(mysqli_query($GLOBALS["connection"],$query)) 
				return 1; 
			return 0;
		}
		function replace_table($table,$str_set,$str_where=""){
			if(!$table|| !$str_set)
			  return 0;
			if(!$str_where){
				$query ="INSERT INTO $table SET $str_set";
				if(mysqli_query($GLOBALS["connection"],$query)) 
					return 1; 
				return 0;
			}
			$query ="SELECT '*' FROM $table WHERE $str_where ";
			if($this->is_table($query)){
				$query ="UPDATE $table SET $str_set WHERE $str_where";
			}else{
				$query ="INSERT INTO $table SET $str_set";
			}  

			if(mysqli_query($GLOBALS["connection"],$query)) 
				return 1; 
			return 0;
		}
		function  is_table_empty($str_table,$str_where){
			if(!$str_table || !$str_where)
			  return -1;
			$query ="SELECT *FROM $str_table  WHERE  $str_where LIMIT 1";
			return $this->empty_query($query);
		}		  
		function is_query_into_table($str_table,$str_where){
			$query ="SELECT * FROM $str_table  WHERE  $str_where LIMIT 0 , 1";
			if($table = mysqli_query($GLOBALS["connection"],$query)){
				if($n=mysqli_num_rows($table)!=0)
					return $n;
				else
					return 0;
			}else
				return 0;
		}

		function get_query_result($table,$str_cond="*",$str_where="",$str_order=""){
			if($table==""||$str_cond==""){
			  return 0;

			}elseif($str_where =="" && $str_order =="")
			  $query ="SELECT $str_cond FROM $table";
		
			elseif($str_where !="" && $str_order =="")
			  $query ="SELECT $str_cond FROM $table  WHERE  $str_where";
		
			elseif($str_where =="" && $str_order!="")
			  $query ="SELECT $str_cond FROM $table ORDER BY $str_order";
		
			elseif($str_where !="" && $str_order !="")
			  $query ="SELECT $str_cond FROM $table  WHERE  $str_where ORDER BY $str_order"; 	  
			return mysqli_query($GLOBALS["connection"],$query); 
		}
		/*###########################################################################		
				FETCH ROW 
		###########################################################################*/									
	function fetch_row(&$arr_rows){//dbol_
		if(empty($arr_rows))
			return 0;
		$current=array_shift($arr_rows);
		if(empty($current))
			return 0;
		return $current;
	}				
		  
}//end class
">
