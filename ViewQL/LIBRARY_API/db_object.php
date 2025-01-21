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
                            			db_stik
 	***********************************************************/
 class db_stik extends table{//db_table_join
    var $arr_stik=array();
	function db_stik($arr_stik=array()){
		$this->arr_stik=$arr_stik;
	}		
	function shift(){
		$variable=array_shift($this->arr_stik);
		return $variable;		
	}	
	function push($variables){
		array_push($this->arr_stik,$variables);
	}
	function pop(){
		$variable=array_pop($this->arr_stik);
		return $variable;		
	}	
  	function parsing($str_token,$str="."){ 
		if($token=strtok($str_token,$str)){
	  		$this->push($token);
	  		while($token){
        		if($token=strtok($str)){
		  			$this->push($token);
				}
	  		}
		}
  	}
	function query_to_array($query){
		$this->parsing($query);
		return $this->arr_stik;
	}
	function set($arr_stik){
		$this->arr_stik=$arr_stik;
	}
 	function get_stik(){
		return $this->arr_stik;
	}
    
 }//end class
	/************************************************************
                            t_counter
	 ************************************************************/
 class t_counter extends table_join{ //db_serializer
 	function t_counter($prefix_db=""){
		$this->table_join($prefix_db);//db_serializer
	}		
  	function create_counter($str_table,$nn_finish=1){
		$query=
		"CREATE TABLE $str_table (".
	  		"COUNT INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,".
  			"PRIMARY KEY  (COUNT)".
		");";
		if(mysqli_query($GLOBALS["connection"],$query)){		
		//if(mysql_query($query)){
			$query="INSERT $str_table SET COUNT=$nn_finish";
			if(mysqli_query($GLOBALS["connection"],$query))
      		   	return mysqli_insert_id($GLOBALS["connection"]);
		}	
    	return 0;		
	}
	
  }//end class  
	/************************************************************
                            db_counter 
	 ************************************************************/
	 class db_counter extends t_counter{
		var $count_finish_object=1110;
		function db_counter($prefix_db=""){
			$this->t_counter($prefix_db);
		}	
						//objects
		///////////////////////////////////////////////
		function create_counter_objects(){
			$str_table=$this->prefix_table."COUNTER_OBJECTS";
			if($this->create_counter($str_table,$this->count_finish_object))
				return 1;
			return 0;		
		}		
		function get_id_new_object(){
			$str_table=$this->prefix_table."COUNTER_OBJECTS";
			if(!$this->is_table_name($str_table)){
				if(!$id_new=$this->create_counter($str_table,$this->count_finish_object))
					return 0;
				return $id_new;
			}
			if($id_new=$this->get_count($str_table))
				return $id_new;
			return 0;	
		}
						//components
		///////////////////////////////////////////////		
		function create_counter_components(){
			$str_table=$this->prefix_table."COUNTER_COMPONENTS";
			if($this->create_counter($str_table))
				return 1;
			return 0;		
		}		
		function get_id_new_component(){
			$str_table=$this->prefix_table."COUNTER_COMPONENTS";
			if(!$this->is_table_name($str_table)){
				if(!$id_new=$this->create_counter($str_table))
					return 0;
				return $id_new;
			}
			if($id_new=$this->get_count($str_table))
				return $id_new;
			return 0;	
		}
	 }//end class   
				/************************************************************
                            		db_tables
 			************************************************************/
 class db_tables extends db_counter{
	var $arr_component=array(
			"CLASS_OBJECT",
			"STRING",
			"INTEGER",
			"TINYINT",
			"FLOAT",
			"TEXT",
			"TEXT_SERIALIZE",
			"BLOB",
			"DATE",
			"ARRAY_STRING",
			"ARRAY_TEXT",
			"ARRAY_INTEGER",
			"ARRAY_TINYINT",
			"POINTER",
			"RELATION"
		); 
 	function db_tables($prefix_db=""){
		$this->db_counter($prefix_db);
	}
 	function get_arr_component(){
		return $this->arr_component;
	}
  	function create_table_classes(){
		$query=
		"CREATE TABLE ".$this->prefix_table."CLASSES (".
  			"ID SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT ,".
			"NAME VARCHAR(60) NOT NULL DEFAULT '',".
			"ID_CLASS VARCHAR(32) NOT NULL DEFAULT '',".
			"TYPE_CLASS VARCHAR(10) NOT NULL DEFAULT 'CLASS',".
			"PRIMARY KEY (ID)".
		");";
		if(mysqli_query($GLOBALS["connection"],$query))
      		return 1;
    	return 0;
	}
	function create_table_class_object(){
		$query=
		"CREATE TABLE ".$this->prefix_table."CLASS_OBJECT (".
  			"ID_CLASS VARCHAR(32) NOT NULL DEFAULT '',".
			"ID_OBJECT VARCHAR(32) NOT NULL DEFAULT '' ".
		");";
		if(mysqli_query($GLOBALS["connection"],$query))
      		return 1;
    	return 0;
	}
					//ADD new CLASS
	function add_class($name_class,$id_class){
		if(!$name_class || !$id_class)
			return 0;
		$name_class=trim($name_class);
							// class
		$str_table=$this->prefix_table."CLASSES";		
		if(!$this->is_table_name($str_table))
		 	if(!$this->create_table_classes())
				return 0;																		
		$str_set="NAME='$name_class',ID_CLASS='$id_class'";		
	    if(!$this->ins_table_id($str_table,$str_set))
			return 0;
		$str_table=$this->prefix_table."CLASS_OBJECT";		
		if(!$this->is_table_name($str_table))
		 	if(!$this->create_table_class_object())
				return 0;
		$str_set="ID_CLASS='$id_class',ID_OBJECT='$id_class'";		
		if(!$this->ins_table($str_table,$str_set)){			
			return 0;}
		return 1;
	}
	function add_component_container($name_class,$id_class=0){
		if(!$name_class)
			return 0;
		$name_class=trim($name_class);
							// class
		$str_table=$this->prefix_table."CLASSES";		
		if(!$this->is_table_name($str_table))
		 	if(!$this->create_table_classes())
				return 0;
		$str_set="NAME='$name_class',ID_CLASS='$id_class',TYPE_CLASS='CONTAINER'";		
	    if(!$this->ins_table_id($str_table,$str_set))
			return 0;
		return 1;
	}
	function add_interface_class($name_class,$id_class){
		if(!$name_class)
			return 0;
							// class
		$str_table=$this->prefix_table."CLASSES";		
		if(!$this->is_table_name($str_table))
		 	if(!$this->create_table_classes())
				return 0;
		$str_set="NAME='$name_class',ID_CLASS='$id_class',TYPE_CLASS='VIEW'";		
	    if(!$this->ins_table_id($str_table,$str_set))
			return 0;
		return 1;
	}

	function add_component($name_class){
		if(!$name_class)
			return 0;
		$name_class=trim($name_class);
							// class
		$str_table=$this->prefix_table."CLASSES";		
		if(!$this->is_table_name($str_table))
		 	if(!$this->create_table_classes())
				return 0;
		$str_set="NAME='$name_class',TYPE_CLASS='COMPONENT'";		
	    if(!$this->ins_table_id($str_table,$str_set))
			return 0;
		return 1;
	}
		//ADD new OBJECT to CLASS					
	function add_object_to_class($id_class,$id_object=""){//01.22
		
		if(!$id_class)
			return 0;			
		if(empty($id_object)){
			if(!$id_object=$this->get_id_new_object())
				return 0;
		}
		$str_table=$this->prefix_table."CLASS_OBJECT";		
		if(!$this->is_table_name($str_table))
		 	if(!$this->create_table_class_object())
				return 0;
		$str_set="ID_CLASS=".$id_class.",ID_OBJECT='".$id_object."'";//23		
		if(!$this->ins_table($str_table,$str_set)){			
			$this->warning("OBJECT EXIST");			
			return 0;
		}
		return $id_object;		
	}
	function delete_object($id_class,$id_object){
		$str_table=$this->prefix_table."CLASS_OBJECT";		
		if(!$this->is_table_name($str_table))
			return 0;
		$str_where="ID_CLASS='$id_class' && ID_OBJECT='".$id_object."' LIMIT 1";		
		if(!$this->del_table($str_table,$str_where))			
			return 0;
		return 1;
	}

	function show_object($id_object){
		if(!$id_object)
			return 0;
		//if(!is_numeric($id_object))
		$id_object="'".$id_object."'";//23
		$arr_table=$this->get_arr_component();
		for($i=0;$i<count($arr_table);$i++){
			$str_table=$this->prefix_table.$arr_table[$i];		
			if(!$this->is_table_name($str_table))
				continue;	
			if($arr_table[$i]=="RELATION")
				continue;	
			if($arr_table[$i]=="POINTER")
				continue;	
			if($arr_table[$i]=="CLASS_OBJECT")
				continue;	
			$str_where="ID_OBJECT=$id_object";
			if(strpos($arr_table[$i],"RRAY_"))
				$str_cond=" CONTROL,VALUE,KEY_ARRAY ";
			else
				$str_cond=" CONTROL,VALUE ";
			$obj=$this->get_query_result($str_table,$str_cond,$str_where);
			if(!mysqli_num_rows($obj))
				continue;	
			while($arr=mysqli_fetch_array($obj)){
			 	print($arr["CONTROL"]." =".$arr["VALUE"]."<BR>");
			}
			continue;	
		}
		return 1;
	}
	function erase_object($id_object){
		if(!$id_object)
			return 0;
		print( "OBJECT ".$id_object."<BR>");
		$arr_table=$this->get_arr_component();
		for($i=0;$i<count($arr_table);$i++){
			$str_table=$this->prefix_table.$arr_table[$i];		
			if(!$this->is_table_name($str_table))
				continue;
			if($arr_table[$i]=="RELATION")
				continue;	
			if($arr_table[$i]=="POINTER")
				continue;	
			if($arr_table[$i]=="CLASS_OBJECT")
				continue;
			print( "ERASE FROM ".$arr_table[$i]."<BR>");
			$str_where="ID_OBJECT='".$id_object."'";//23
			$this->del_table($str_table,$str_where);
			continue;	
		}
		return 1;
	}	
	////
	function drop_object($id_object){
		if(!$id_object)
			return 0;
		print( "DROP OBJECT ".$id_object."<BR>");
		$id_object="'".$id_object."'";//23
		$arr_table=$this->get_arr_component();
		for($i=0;$i<count($arr_table);$i++){
			$str_table=$this->prefix_table.$arr_table[$i];		
			if(!$this->is_table_name($str_table))
				continue;
			if($arr_table[$i]=="RELATION"){
				$str_where="ID_OBJECT=$id_object";
				$this->del_table($str_table,$str_where);
				$str_where="ID_RELATION=$id_object";
				$this->del_table($str_table,$str_where);
			}elseif($arr_table[$i]=="POINTER"){
				$str_where="ID=$id_object";
				$this->del_table($str_table,$str_where);
				$str_where="ID_PARENT=$id_object";
				$this->del_table($str_table,$str_where);			
			}else{
				print( "DROP FROM ".$arr_table[$i]."<BR>");
				$str_where="ID_OBJECT=$id_object";			
				$this->del_table($str_table,$str_where);
			}
		}			
		return 1;
	}		
  }//end_class
  
/*
		//CLASS 
function is_class($name_class){
function is_name_class($name_class){
	
function is_type_class($name_class){
function is_type_component($name_class){
function is_type_container($name_class){
function is_type_interface($name_class){

function get_name_class($id_class){
function get_type_class($name_class){
function get_id_class($name_class){
function class_info($name_class){//$name_class::=name_class|name_interface|name_class.name_container

function get_list_classes()
function get_list_classes_of_type()
function show_list_classes()
function show_list_classes_of_type()
		// OBJECT
function is_object_class($id_object,$name_class){
function get_class_from_object($id_object,$name_class){
function object_info($id_object){

function get_list_objects_of_class($name_class)
function show_list_objects_of_class($name_class)
*/
			/************************************************************
                            		db_classes
 			************************************************************/
 class db_classes extends db_tables{
 	function db_classes($prefix_db=""){
		$this->db_tables($prefix_db);
	}
						//LIST
	function show_list_classes(){
		$str_table=$this->prefix_table."CLASSES";
		if(!$this->is_table_name($str_table))
		  print "LIST CLASSES EXIST<BR>";
		print "LIST OF CLASSES:<BR>";
		$str_cond="*";
		if($arr_class=$this->get_table($str_table,$str_cond)){
			while($arr = mysqli_fetch_array($arr_class)){
				print "&nbsp;&nbsp;ID- ".$arr["ID_CLASS"].",  ".$arr["NAME"]."<BR>";
			}					
		}
	}
	function get_list_classes(){
		$str_table=$this->prefix_table."CLASSES";
		if(!$this->is_table_name($str_table))
			return 0;
		$str_cond="*";
		if($arr_class=$this->get_table($str_table,$str_cond)){
			while($arr = mysqli_fetch_array($arr_class)){
				$list_classes[$arr["ID_CLASS"]]=$arr["NAME"];
			}					
		}
		return $list_classes;
	}
	function is_type_class($name_class){
		$str_table=$this->prefix_table."CLASSES";
		if(!$this->is_table_name($str_table))
			return 0;
		$str_cond="TYPE_CLASS";
		$name_class=trim($name_class);
		$str_where="NAME='$name_class'";
		if($arr=$this->get_array($str_table,$str_cond,$str_where)){
			if(!$arr["TYPE_CLASS"])
				return 0;
			return trim($arr["TYPE_CLASS"]);
		}
		return 0;
	}

	function get_type_class($type_class){
		$str_table=$this->prefix_table."CLASSES";
		if(!$this->is_table_name($str_table))
			return 0;
		$str_cond="*";
		$list_classes=array();
		$str_where="TYPE_CLASS='$type_class'";
		if($arr_class=$this->get_table($str_table,$str_cond,$str_where)){
			while($arr = mysqli_fetch_array($arr_class)){
				$list_classes[$arr["NAME"]]=$arr["ID_CLASS"];
			}					
		}
		return $list_classes;
	}
	function show_type_class($type_class){
		if(!$list_classes=$this->get_type_class($type_class))
			return 0;
		print "<B>$type_class</B>:<BR>";	
		foreach($list_classes as $name => $id){
			if($id)
				print "<LI>$name ($id)</LI>";
			else
				print "<LI>$name</LI>";
		}
		print "<BR>";
	}	
	function get_list_objects_of_class($id_class){
		$str_table=$this->prefix_table."CLASS_OBJECT";
		if(!$this->is_table_name($str_table))
			return 0;
		$str_cond="*";
		$str_where ="ID_CLASS=$id_class";
		if($table=$this->get_table($str_table,$str_cond,$str_where)){
			while($arr = mysqli_fetch_array($table)){
				$arr_objects[]=$arr["ID_OBJECT"];
			}
			return $arr_objects;					
		}
		return 0;		
	}
	function show_objects_of_class($name_class){
		if(!$id_class=$this->get_id_class_from_name($name_class))
			print "CLASS EXIST<BR>";
		if(!$arr_objects=$this->get_list_objects_of_class($id_class)){
			print "LIST OF CLASS EXIST<BR>";
			return;
		}
		print "LIST OBJECTS OF CLASS ID- $id_class,&nbsp;$name_class :<BR>";
		for($i=0;$i<count($arr_objects);$i++){
			print   "&nbsp;&nbsp;ID-".$arr_objects[$i]." <BR>";
		}
	}	
	function get_class_info($id_class){
	    if(!$this->is_class($id_class))
			return $this->get_class_info_to_object($id_class);
		else{	
			$str_table=$this->prefix_table."CLASSES";
			$str_cond="*";
			$str_where ="ID_CLASS=$id_class ";					
			if(!$arr=$this->get_array($str_table,$str_cond,$str_where))
				return 0;
				
			$id_class=$arr["ID_CLASS"];
			settype($id_class,strtolower("INTEGER"));
			$arr_info=array($arr["ID"],$id_class,$arr["NAME"],$arr["TYPE_CLASS"]);
			//0-id(class),1-id_class(object class),2-name(class)
			return $arr_info;
		}					
	}	
	function get_class_info_from_name($name_class){
			$str_table=$this->prefix_table."CLASSES";
			$str_cond="*";
			$str_where ="NAME='$name_class'";					
			if(!$arr=$this->get_array($str_table,$str_cond,$str_where))
				return 0;				
			$id_class=$arr["ID_CLASS"];
			settype($id_class,strtolower("INTEGER"));
			$arr_info=array($arr["ID"],$id_class,$arr["NAME"],$arr["TYPE_CLASS"]);
			//0-id(class),1-id_class(object class),2-name(class)
			return $arr_info;				
	}	
	function get_class_info_to_object($id_object){
		$classes=$this->prefix_table."CLASSES";
		$class_object=$this->prefix_table."CLASS_OBJECT";
		$str_table="$classes,$class_object";
		$str_cond="$classes.ID,$classes.ID_CLASS,$classes.NAME AS NAME_CLASS,$classes.TYPE_CLASS AS TYPE_CLASS";
		$str_where ="$class_object.ID_OBJECT='".$id_object."' && ".//23
					"$class_object.ID_CLASS=$classes.ID_CLASS ";					
		if(!$arr=$this->get_array($str_table,$str_cond,$str_where))
			return 0;
		$id_class=$arr["ID_CLASS"];
		settype($id_class,strtolower("INTEGER"));
		$arr_info=array($arr["ID"],$id_class,$arr["NAME_CLASS"],$arr["TYPE_CLASS"]);
		//0-id(class),1-id_class(object class),2-name(class)							
		return $arr_info;					
	}
	function get_class_from_object($id_object){
		if(!$arr_info=$this->get_class_info_to_object($id_object))
			return 0;
		return $arr_info[1];
	}
	function is_object_to_class($id_object,$id_class){
//echo $id_object."  ".$id_class; 
		$str_table=$this->prefix_table."CLASS_OBJECT";
		$str_where ="ID_OBJECT='".$id_object."' && ID_CLASS=$id_class";//23
		$query="SELECT  * FROM $str_table WHERE $str_where";
//echo $query;		
    	if(!$table=mysqli_query($GLOBALS["connection"],$query))									
			return 0;
		return mysqli_num_rows($table);							
	}
	function yes_objects($id_class){
		$str_table=$this->prefix_table."CLASS_OBJECT";
		$str_where ="ID_CLASS=$id_class";
		$query="SELECT  * FROM $str_table WHERE $str_where";
    	if(!$table=mysqli_query($GLOBALS["connection"],$query))					
			return 0;
		return mysqli_num_rows($table);							
	}
	function is_class($id_class){
		$str_table=$this->prefix_table."CLASSES";
		$str_where ="ID_CLASS=$id_class";
		$query="SELECT  * FROM $str_table WHERE $str_where";
		if(!$table=mysqli_query($GLOBALS["connection"],$query))					
			return 0;
		return mysqli_num_rows($table);
	}
	function is_class_name($name_class){
		$str_table=$this->prefix_table."CLASSES";
		$str_where ="NAME='$name_class' ";					
		$query="SELECT * FROM $str_table WHERE $str_where";
		if(!$table=mysqli_query($GLOBALS["connection"],$query))					
			return 0;
		return mysqli_num_rows($table);
	}
	function get_id_class_from_full_name($name_class){
		$name_class=trim($name_class);
		if(strpos($name_class,".")===false){			
			$name_class_hiding="";
		}else{
			$arr_token= explode(".", $name_class);
			$name_class=trim($arr_token[0]);
			$name_interface=trim($arr_token[1]);//$name_class_hiding::=iname_interface
		}				
		$str_table=$this->prefix_table."CLASSES";
		$str_cond="*";
		$str_where ="NAME='$name_class' ";					
		if(!$arr=$this->get_array($str_table,$str_cond,$str_where))
			return 0;
		$id_class=$arr["ID_CLASS"];
		return $id_class;					
	}
	function get_id_class_from_name($name_class){
		$name_class=trim($name_class);
		$str_table=$this->prefix_table."CLASSES";
		$str_cond="*";
		$str_where ="NAME='$name_class' ";					
		if(!$arr=$this->get_array($str_table,$str_cond,$str_where))
			return 0;
		$id_class=$arr["ID_CLASS"];
		return $id_class;					
	}	
	function get_object_sys_name($id_object){
		$str_table=$this->prefix_table."STRING";
		$str_cond="VALUE";
		$str_where ="ID_OBJECT='$id_object' && CONTROL='_Id'";		
		if(!$arr=$this->get_array($str_table,$str_cond,$str_where))
			return 0;
		return $arr["VALUE"];		
	}		
	function get_name_info($token){//name: (string)class_name;(integer)id_class or (integer)id_object 
		$id=$token;
			if(!$this->is_class($id)){	
				$id_object=$id;
				if(!$arr_info=$this->get_class_info_to_object($id_object))
					return 0;
				$id_class=$arr_info[1];
				$name_class=$arr_info[2];
				if(!$object_sys_name=$this->get_object_sys_name($id_object))	
					$object_sys_name="";
			 }else{						//class
				if(!$arr_info=$this->get_class_info($id))
					return 0;
				$id_class=$arr_info[1];
				$name_class=$arr_info[2];
				$id_object=0;
			 	$object_sys_name="";
			 }
			 return array($id_class,$name_class,$id_object,$object_sys_name);
		return 0;
	}
	function pars_name_class($str_name){
			$type_class=$this->is_type_class($str_name);			
			if(!$str_name || ($type_class!="VIEW" && $type_class!="CLASS"))
				return 0;
			if(strpos($str_name,".")===false){
				$name_class=$str_name;
				$name_interface="";
			}else{
				$arr_token = explode(".", $str_name);
				$name_interface=trim($arr_token[1]);
				$name_class=trim($arr_token[0]);
			}
			if(!$name_class)
				return 0;	
			return array($name_class,$name_interface);
	}
  }//end class
">