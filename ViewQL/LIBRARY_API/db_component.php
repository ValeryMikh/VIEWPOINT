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

		/**********************************************************************************************
                            			DB_DATE
		 //Doctup k claasy $this->obj-> , Doctup k objectam(companentam) classa  $this->obj->control->					
		***********************************************************************************************/
  	class db_date extends table_join{
    	var $obj;//$obj_parents
		
		var $id_object;	// $id_object && $control::=id from $value
		var $control;  	//
		var $value="";	//
		
		var $prefix_table;
		var $str_table;
		var $flg_serialize=0;
		var $flg_memory=0;
		
		var $type_object="";
				
 		function set($obj_class,$control,$type_object=""){//$obj_class,$control::= id_record into table "text";
		//$obj_class::=object of class-inheritanse this components.
		//$type_object::=_STATIC|0; 0::=_DUNAMIC
			$this->obj=$obj_class;
	   		$this->prefix_table=$this->obj->prefix_table;
			$this->str_table=$this->prefix_table.$this->name_table;
			if($type_object==_STATIC){
				$this->type_object=_STATIC;//////
				$this->id_object=$this->obj->get_id_class();
			}else{
				$this->type_object="";///////
				$this->id_object=$this->obj->get_id_object();
			}
			$this->control=$control;
			$name=$this->prefix_table.$this->name_table;
			
			if(!$this->is_table_name($name)){
				if(!$this->create())
					return 0;
				return 1;
			}
			
			return 1;
		}
		function create(){return 1;}

		function select($arr_options,&$arr_buf){
			if(!isset($arr_buf))
				return 0;
			$str_cond="VALUE";		
			$str_where="ID_OBJECT='$this->id_object' && CONTROL='$this->control' ";
			if($arr_text=$this->get_array($this->str_table,$str_cond,$str_where)){			
				$value =$arr_text["VALUE"];
				$arr_buf[$this->control]=$value;
				return $value;
			}else
				return 0;
		}
						// DB
		function upsert($arr_options){//22
			if(empty($arr_options[0]))
				return 0;				
			$value=	$arr_options[0];
			$str_set=" ID_OBJECT='$this->id_object',CONTROL='$this->control',VALUE = $value";
			$str_where=" ID_OBJECT='$this->id_object' && CONTROL='$this->control' ";			
			if(!$this->update_ins_table($this->str_table,$str_set,$str_where))
				return 0;
			return 1;			
		}				

		function insert($arr_options){
			if(empty($arr_options[0]))
				return 0;
			$value=	$arr_options[0];
			$str_set=" ID_OBJECT='$this->id_object',CONTROL='$this->control',VALUE = $value";
			$str_where=" ID_OBJECT='$this->id_object' && CONTROL='$this->control' ";
			if($this->type_object==_STATIC){/////
				if(!$this->update_ins_table($this->str_table,$str_set,$str_where))////
					return 0;
			}else{
				if(!$this->ins_table($this->str_table,$str_set))
					return 0;
			}
			return 1;			
		}
		function update($arr_options){
		
			if(empty($arr_options[0]))
				return 0;
			$value=	$arr_options[0];
			$str_set=" ID_OBJECT='$this->id_object',CONTROL='$this->control',VALUE =$value";
			$str_where=" ID_OBJECT='$this->id_object' && CONTROL='$this->control' ";			
			if(!$this->update_table($this->str_table,$str_set,$str_where))
				return 0;
			return 1;			
		}												
		function delete(){
			$str_where=" ID_OBJECT='$this->id_object' && CONTROL='$this->control' ";
			if($this->del_table($this->str_table,$str_where)){
				$this->value=0;
				return 1;
			}
			return 0;
		}
		function increment($arr_options=""){
			$str_cond="VALUE";		
			$str_where="ID_OBJECT='$this->id_object' && CONTROL='$this->control' ";
			if($arr_text=$this->get_array($this->str_table,$str_cond,$str_where))			
				$value =$arr_text["VALUE"];
			else
				$value = 0;
			$value ++;
			$str_set=" ID_OBJECT='$this->id_object',CONTROL='$this->control',VALUE = $value";
			$str_where=" ID_OBJECT='$this->id_object' && CONTROL='$this->control' ";			
			if(!$this->update_ins_table($this->str_table,$str_set,$str_where))
				return 0;
			return $value;			
		}		
		function decrement($arr_options=""){
			$str_cond="VALUE";		
			$str_where="ID_OBJECT='$this->id_object' && CONTROL='$this->control' ";
			if($arr_text=$this->get_array($this->str_table,$str_cond,$str_where))			
				$value =$arr_text["VALUE"];
			else
				$value = 0;
			if(!$value)
				return $value;	
			$value --;
			$str_set=" ID_OBJECT='$this->id_object',CONTROL='$this->control',VALUE = $value";
			$str_where=" ID_OBJECT='$this->id_object' && CONTROL='$this->control' ";			
			if(!$this->update_ins_table($this->str_table,$str_set,$str_where))
				return 0;
			return $value;			
		}	
  	}//end class 
				/************************************************************
                            			DATE
 				************************************************************/
								
  class db_date_content extends db_date{
  	var $name_table="DATE";	
	function create(){
		if($this->is_table_name($this->str_table))
			return 1;
		$query=
		"CREATE TABLE $this->str_table (".
			"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
			"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
  			"VALUE  	DATE NOT NULL,".
			"KEY `CONTROL` (`CONTROL`)".

		")";
		if(mysqli_query($GLOBALS["connection"],$query))
      		return 1;
    	return 0;
	}
  }//end class
				/************************************************************
                            			INTEGER
 				************************************************************/
								
	class db_integer_content extends db_date{
		var $name_table="INTEGER";		
		function create(){
			if($this->is_table_name($this->str_table))
				return 1;
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	INT(10) NOT NULL DEFAULT '0',".
				"KEY `CONTROL` (`CONTROL`)".
			")";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
  }//end class
  				/************************************************************
                            			TINYINT
 				************************************************************/
								
	class db_tinyint_content extends db_date{
		var $name_table="TINYINT";		
		function create(){
			if($this->is_table_name($this->str_table))
				return 1;
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	TINYINT NOT NULL ,".
				"KEY `CONTROL` (`CONTROL`)".
			")";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
  }//end class
  
 				/************************************************************
                            			FLOAT
 				************************************************************/
								
	class db_float_content extends db_date{
		var $name_table="FLOAT";		
		function create(){
			if($this->is_table_name($this->str_table))
				return 1;
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	FLOAT NOT NULL ,".
				"KEY `CONTROL` (`CONTROL`)".
			")";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
  }//end class
 
 				/************************************************************
                            			DOUBLE
 				************************************************************/
	/*
	class db_double_content extends db_date{
		var $name_table="DOUBLE";		
		function create(){
			if($this->is_table_name($this->str_table))
				return 1;
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	DOUBLE NOT NULL DEFAULT '0.0',".
				"KEY `CONTROL` (`CONTROL`)".
			")";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
  }//end class
*/
  				/************************************************************
                            			DECIMAL(10,8)
 				************************************************************/
/*
	class db_decimal_content extends db_date{
		var $name_table="DECIMAL";		
		function create(){
			if($this->is_table_name($this->str_table))
				return 1;
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	DECIMAL (10,8)  NOT NULL DEFAULT '0.0',".
				"KEY `CONTROL` (`CONTROL`)".
			")";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
  }//end class
 */
   				/************************************************************
                            			STRING
 				************************************************************/  
  				
	class db_string_content extends db_date{
		var $name_table="STRING";		
		function db_string_content(){;}	
		function create(){
			if($this->is_table_name($this->str_table))
				return 1;
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	TINYTEXT 	NOT NULL ,".
				"FULLTEXT KEY `VALUE` (`VALUE`),".
				"KEY `CONTROL` (`CONTROL`)".				
			") ";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
		
	}//end class
    			/************************************************************
                            			TEXT
 				************************************************************/  
  				
	class db_text_content extends db_date{
		var $name_table="TEXT";		
		function create(){
			if($this->is_table_name($this->str_table))
				return 1;
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	TEXT NOT NULL ,".
				"FULLTEXT KEY `VALUE` (`VALUE`),".
				"KEY `CONTROL` (`CONTROL`)".				
			") ";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}		
	}//end class
	    	/************************************************************
                            			TEXT_SERIALIZE
 			 ************************************************************/  
  				
	/*class db_text_serialize_content extends db_text_content{
		var $name_table="TEXT_SERIALIZE";		
		function db_text_serialize_content($obj_parents){
			$this->db_text_content($obj_parents);
			$this->flg_serialize=1;
		}	
	}//end class*/

   			/************************************************************
                            			BLOB
 			************************************************************/  
  				
	class db_blob_content extends db_date{
		var $name_table="BLOB";		
		function create(){
			if($this->is_table_name($this->str_table))
				return 1;
			$query=
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	BLOB NOT NULL ,".
				"KEY `CONTROL` (`CONTROL`)".
			")";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}	
	}//end class

  				/************************************************************
                            			DB_ARRAY
 				************************************************************/  
 	class db_array_date extends table_join{
    	var $obj;//$obj_parents
		
		var $id_object;//item
		var $control;//item
		var $prefix_table;
		var $str_table;

		var $arr_keys=array();
		var $arr_values=array();
		var $arr_keys_select=array();
		var $flg_memory=0;
		
		var $type_object="";
		
		function db_array_date(){
		}
				
 		function set($obj_class,$control,$type_object=""){//$obj_class,$control::= id_record into table "text";
		//$obj_class::=object of class-inheritanse this components.
		//$type_object::=_STATIC|0; 0::=_DUNAMIC
			$this->obj=$obj_class;
	   		$this->prefix_table=$this->obj->prefix_table;
			$this->str_table=$this->prefix_table.$this->name_table;

			if($type_object==_STATIC){
				$this->type_object=_STATIC;//////
				$this->id_object=$this->obj->get_id_class();
			}else{
				$this->type_object="";///////
				$this->id_object=$this->obj->get_id_object();
			}
			$this->control=$control;
			if(!$this->is_table_name($this->str_table))
				if(!$this->create())
					return 0;
			$this->get_key();			
			return 1;				
		}
		
					// KEY
		function get_key(){//($arr_options
			$this->arr_keys_select=array();
			$str_cond ="KEY_ARRAY";		
			$str_where="ID_OBJECT='$this->id_object'&& CONTROL='$this->control'";
			if(!$table=$this->get_table($this->str_table,$str_cond,$str_where))
				return 0;								
			while($myrow = mysqli_fetch_array($table)){
				if($myrow["KEY_ARRAY"])
					$this->arr_keys_select[$myrow["KEY_ARRAY"]]=0;
			}			
			return 1;
		}
						//	KEY			
		function set_key($arr_options){
			if(empty($arr_options))
				return 0;
			$arr_keys_select=array();
			foreach($this->arr_keys_select as $key=>$value){
				 $this->arr_keys_select[$key]=0;
			}
			foreach($arr_options as $nn=>$key){
				$key=str_replace("'","",$key);
				$this->arr_keys_select[$key]=1;
			}
			return $this->arr_keys_select; 
		}							
		function select_key(){
			$arr_keys_select=array();
			foreach($this->arr_keys_select as $key=>$value){
				$key=str_replace("'","",$key);
				if($value)
				 	$arr_keys_select[]=$key;
			}
			return $arr_keys_select; 
		}		
							// DATE BASE
							
		function select($arr_options,&$buf){
			if(empty($arr_options))
				$key="";
			else{
				if(!empty($key)){//22
					$key=$arr_options[1];
					$key=str_replace("'","",$key);
					$key=str_replace('"','',$key);
				}
			}
			if(!empty($key)){//!$key::=select//22
				$str_cond ="VALUE,KEY_ARRAY";		
				$str_where="ID_OBJECT='$this->id_object'&& CONTROL='$this->control'&& KEY_ARRAY='$key'";
			}else{
				$str_cond ="VALUE,KEY_ARRAY";		
				$str_where="ID_OBJECT='$this->id_object'&& CONTROL='$this->control'";
			}
			if(!$table=$this->get_table($this->str_table,$str_cond,$str_where))
				return 0;
			while($myrow = mysqli_fetch_array($table)){;						
				if($myrow["VALUE"] && $myrow["KEY_ARRAY"]){//22
					$buf[$this->control][$myrow["KEY_ARRAY"]]=$myrow["VALUE"];
				}
			}
			return $buf;	
		}
		function insert($arr_options){
			if(!empty($arr_options)&& $arr_options[0] && !empty($arr_options[1])){
				$key=$arr_options[1];
				$key=str_replace("'","",$key);
				$key=str_replace('"','',$key);
				$value=$arr_options[0];
				$str_set="ID_OBJECT='$this->id_object',CONTROL='$this->control',".
					"KEY_ARRAY='$key',VALUE=$value";
				$str_where="ID_OBJECT='$this->id_object'&& CONTROL='$this->control'&& KEY_ARRAY='$key'";
				if($this->type_object==_STATIC){/////
					if(!$this->update_ins_table($this->str_table,$str_set,$str_where))////
						return 0;
				}else{									
					if(!$this->ins_table($this->str_table,$str_set,$str_where))
						return 0;
				}
				return 1;
			}
		}				
		function update($arr_options){
			if(!empty($arr_options)&& $arr_options[0] && !empty($arr_options[1])){
				$key=$arr_options[1];
				$key=str_replace("'","",$key);
				$key=str_replace('"','',$key);
				$value=$arr_options[0];
				$str_set="ID_OBJECT='$this->id_object',CONTROL='$this->control',".
					"KEY_ARRAY='$key',VALUE=$value";
				$str_where="ID_OBJECT='$this->id_object'&& CONTROL='$this->control'&& KEY_ARRAY='$key'";	
				if(!$this->update_table($this->str_table,$str_set,$str_where))
					return 0;
				return 1;
			}
		}				

		function upsert($arr_options){
			if(!empty($arr_options)&& $arr_options[0] && !empty($arr_options[1])){
				$key=$arr_options[1];
				$key=str_replace("'","",$key);
				$key=str_replace('"','',$key);
				$value=$arr_options[0];
				$str_set="ID_OBJECT='$this->id_object',CONTROL='$this->control',".
					"KEY_ARRAY='$key',VALUE=$value";
				$str_where="ID_OBJECT='$this->id_object'&& CONTROL='$this->control'&& KEY_ARRAY='$key'";	
				if(!$this->update_ins_table($this->str_table,$str_set,$str_where))
					return 0;
				return 1;
			}
		}				

		function delete($arr_options){
			if(empty($arr_options[1]))
				$key="";
			else{
				$key=$arr_options[1];
				$key=str_replace("'","",$key);
				$key=str_replace('"','',$key);
			}
			$str_where="ID_OBJECT='$this->id_object'&& CONTROL='$this->control'";
			if(!empty($key_array))
				$str_where.="&& KEY_ARRAY='$key_array'";			
			if($myrow=$this->del_table($this->str_table,$str_where))
				return 1;
			return 0;
		}
  	}//end class
    			/************************************************************
                            			ARRAY INTEGER
 				************************************************************/  

	class db_array_integer_content extends db_array_date{
		var $name_table="ARRAY_INTEGER";
		function create(){
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	INT(10) NOT NULL DEFAULT '0',".
				"KEY_ARRAY	VARCHAR(60) NOT NULL DEFAULT '',".
				"KEY `CONTROL` (`CONTROL`)".
			")";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
	
	 }//end class
    			/************************************************************
                            			ARRAY_TINYINT
 				************************************************************/  
	class db_array_tinyint_content extends db_array_date{
		var $name_table="ARRAY_TINYINT";
		function create(){
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	TINYINT(10) NOT NULL DEFAULT '0',".
				"KEY_ARRAY	VARCHAR(60) NOT NULL DEFAULT '',".
				"KEY `CONTROL` (`CONTROL`)".
			")";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
	
	 }//end class
	 
    			/************************************************************
                            			ARRAY FLOAT
 				************************************************************/  
	class db_array_float_content extends db_array_date{
		var $name_table="ARRAY_FLOAT";
		function create(){
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	FLOAT NOT NULL DEFAULT '0',".
				"KEY_ARRAY	VARCHAR(60) NOT NULL ,".
				"KEY `CONTROL` (`CONTROL`)".
			")";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
	
	 }//end class*/
	 
  				/************************************************************
                            			db_array_string
 				************************************************************/  
 	class db_array_string_content extends db_array_date{
		var $name_table="ARRAY_STRING";
		function create(){
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	TINYTEXT NOT NULL ,".
				"KEY_ARRAY	VARCHAR(60) NOT NULL ,".
				"FULLTEXT KEY `VALUE` (`VALUE`),".
				"KEY `CONTROL` (`CONTROL`)".				
			") ";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
	}//end class
  				/************************************************************
                            			db_array_text
 				************************************************************/  
 	class db_array_text_content extends db_array_date{
		var $name_table="ARRAY_TEXT";
		function create(){
			$query=
			"CREATE TABLE $this->str_table (".
				"ID_OBJECT   VARCHAR(32) NOT NULL DEFAULT '',".
				"CONTROL 	VARCHAR(60) NOT NULL DEFAULT '',".
				"VALUE  	TEXT NOT NULL ,".
				"KEY_ARRAY	VARCHAR(60) NOT NULL,".
				"FULLTEXT KEY `VALUE` (`VALUE`)".				
			") ";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
	}//end class  				

">