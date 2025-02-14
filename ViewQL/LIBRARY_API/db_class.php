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

	 /*##############################  
                            db_class
	 ##############################*/
		//$class = new db_class(Name_DB_class);	   
 class db_class extends db_serializer {//db_classes
 	var $id_object_set=0;//set query_set_object
 	var $id_class=0;//ID of class 
	var $id_object=0;//ID object of class; 
	var $class_name="";
	var $interface_name="";
	var $arr_extends_class=array();	
		//::=array({[control]=>array({[1]=>name_class_exst,[2]=>STATIC,[3]=>PRIVAT.,..)) 
	var $arr_control_object=array();
		//::=array({[control]=>object_exst}.,..)
	var $array_extends_class=array();
		//::=array({[control]=>array({[1]=>name_class_exst,[2]=>STATIC,[3]=>PRIVAT,[4]=>inheritance}.,..)) 
		//hiding: empty|PUBLIC|DYNAMIC ; empty::="""
	var $array_relations_class=array();
		//::=array([control]=>array({[0]=>name_class_relation,
		//[1]=>name_class_relations,
		//[2]=>content,::=plase(DINAMIC|CTATIC)
		//[3]=>hiding},::=access(PUBLIC|PRIVATE)
		//[4]=>interface}::=interface --> name_interface(USER.DATE) 
		//[5]=>immed}::=immediately(IMMED)
		//}.,..));
		//$array_relations_class ::=Array ( 
		//[0] => Array ( [0] => UNIT [1] => DIRECT [2] => [3] => [4] => UNIT.UNIT [5] => IMMED )
		//[1] => Array ( [0] => CURRENCY [1] => DIRECT [2] => [3] => [4] => CURRENCY.CURRENCY [5]=> )}	
	var $array_pointers_class=array();
		//::=array([control]=>array({[0]=>name_class_pointer,[1]=>name_class_exst,[2]=>content,[3]=>hiding}}.,..));	
	var $array_inheritance_class=array();
		//::=tree inheritance classes
		//tree inheritance classes::=array([name_class]=>array([name_class]=>array(...).,..).,..)
	var $flg_serializ=1;//::={1|0}; 1::=Razreshena serializer 0::=Zapreshena	
	var $list_inheritance=array();//::=array(name_class_inheritance.,..)
	var $list_interface=array();//array([control]=>NN.,..)
	var $list_component=array();//array([name_component]=>NN.,..)//(array_keys($this->arr_control_object)
	var $arr_extends_class_full=array();//SHOW
		//$array_relations_complete::0=array([name_class_relationship]=>dir.,..)
	var $arr_anser=array();//REZULT	
	var $state=0;
	var $arr_id_immed=array();//array(USER => 116} 116::=ID_obj
		//Exempl::=[USER_Fax] => Array ( [0] => [1] => STRING [2] => [3] => [6] => FINAL [7] => 116 )

	//###################################################################
	
	function db_class($name_class,$prefix_db="",$id_class=0){
		//$id_class,$name_class::=name_class[.$name_interface]
		$prefix_db="_";//222
		$this->db_serializer($prefix_db);//db_classes
			 // CREATE CLASSES			
		if($name_class && $id_class){
			$this->id_class=$id_class;
			$this->class_name=$name_class;
			$this->set_extends_class();
			$this->interface_name=$name_class;
			return 1;
			// NEW CLASSES
		}elseif($name_class && !$id_class){
			if($this->class_name){
				if(!$class=$this->get_serialize_class_from_full_name($name_class,$this)){
					$this->warning("Syntaxerror 0120 unexpected '$name_class'");	// "Class '.....' doesn't exist; ";
					return 0;
				}
			}else{
				if(!$class=$this->get_serialize_class_from_full_name($name_class)){
					$this->warning("Syntaxerror 070 unexpected '$name_class'");	// "Class '.....' doesn't exist; ";
					return 0;
				}
			}		
			if($class=="OKEY")
				return 1;	
			$this->class_name=$class->get_name_class();
			$this->interface_name=$class->get_name_interface();
			$this->id_class=$class->get_id_class();;
			$this->arr_extends_class=$class->arr_extends_class;	
			$this->arr_control_object=$class->arr_control_object;			
			$this->array_extends_class=$class->array_extends_class;
			$this->array_relations_class=$class->array_relations_class;
			$this->array_pointers_class=$class->array_pointers_class;
			$this->array_inheritance_class=$class->array_inheritance_class;			
			$this->list_interface=$class->list_interface;
			$this->list_component=$class->list_component;
			$this->list_inheritance=$class->list_inheritance;			
		}else{ 
			$this->warning("Syntaxerror 071 unexpected '$name_class'");	
			return 0;
		}	
		$this->flg_serializ=0;
		$this->set_extends_class();
		$arr_control=array_keys($this->arr_extends_class);
		//::=array({[control]=>array({[1]=>name_class_exst,[2]=>{class|object},[3]=>id_relation}}.,..))
		for($i=0;$i<count($arr_control);$i++){
					//extends
			$control=$arr_control[$i];
			$arr_info=$this->arr_extends_class[$control];		    
			$name_extends_class=$arr_info[1];
			$type_object=$arr_info[2];	//class or object == dinamic or static
			if(isset($arr_info[5]))
				$immed=$arr_info[5];//IMMED
			if($type_object==_STATIC && $this->id_class
			&& $name_extends_class!=_DIRECT && $name_extends_class!=_FEEDBACK 
			&& $name_extends_class!=_CHILDREN && $name_extends_class!=_PARENT){
				if(!$this->is_class_name($name_extends_class))///////
					continue;
				$obj_extends=new $name_extends_class();
				$arr_option=array($this,$control,_STATIC);
				if(!$obj_extends->SET($arr_option)){
					$this->arr_control_object[$control]=0;
					
				}
		  	}else
				continue;
			$this->arr_control_object[$control]=$obj_extends;
		}
		$arr_control_object=array();
		foreach($this->arr_control_object as $control_tmp => $obj_extends_tmp){
			if($obj_extends_tmp)
				$arr_control_object[$control_tmp]=$obj_extends_tmp;
		}
		$this->arr_control_object=$arr_control_object;
		if(!empty($this->arr_control_object)){
			$this->list_component=array_keys($this->arr_control_object);
			$this->list_component=array_flip($this->list_component);
		}else
			$this->list_component=array();
		if($this->id_object){
			$this->set_object($this->id_object);
		}
		$this->arr_extends_class_full=$this->arr_extends_class;//SHOW				
        return 1; 
	}
	 	/*###########################################################################		
					SET OBJECT(query);	query::=id_object
		###########################################################################*/				
	function query_set_object($query){
		$this->empty_anser();
		$pos_1 = strpos($query,"(")+1;
		$pos_2 = strpos($query, "(");		
		if(!$id_object=trim(substr($query,$pos_1,$pos_2-$pos_1))){
			$this->warning("Syntaxerror 072 unexpected '$query'");	
			return 0;
		}
		$id_object=trim($id_object);
		return	$this->set_object($id_object);
	}
	function set_object($id_object){
		$id_class=$this->get_id_class();	
		if(!$this->is_object_to_class($id_object,$id_class)){

			return 0;
		}
		$this->arr_id_immed=array();
		$rez=$this->_set_object($id_object);
		return $rez;
	}
	function _set_object($id_object){//set inside
		if(!$id_object)
			 return 0;
		$this->id_object=$id_object; 
		$this->flg_serializ=0;
								//PROTECTION
		$this->set_extends_class();
		reset($this->arr_extends_class);				
		$arr_control=array_keys($this->arr_extends_class);
		$count=count($arr_control);		
		for($i=0;$i<count($arr_control);$i++){
							//extends
			$control=$arr_control[$i];
			$arr_info=$this->arr_extends_class[$control];
			$name_extends_class=$arr_info[1];
			//$type_object=$arr_info[2];	//class or object == dinamic or static			
			if(isset($arr_info[2]))
				$type_object=$arr_info[2];//DYNAMIC ="" or STATIC 
			else
				$type_object="";
				
			if(isset($arr_info[5]))//immed
				$immed=$arr_info[5];
			else
				$immed=0;
			if($this->id_object 
				&& $name_extends_class!=_DIRECT && $name_extends_class!=_FEEDBACK 
				&& $name_extends_class!=_CHILDREN && $name_extends_class!=_PARENT){
				if(empty($arr_info[6]) || $arr_info[6]!=_FINAL){
					$obj_extends=new $name_extends_class();
					$arr_option=array($this,$control,$type_object);//////////
					$obj_extends->SET($arr_option);
					$this->arr_control_object[$control]=$obj_extends;
				}
										//IMMED
		  	}elseif($name_extends_class==_DIRECT ||$name_extends_class==_FEEDBACK){
				if(empty($arr_info[4])|| empty($arr_info[0]))//////ONLY INHERITANS
					continue;
				$this->arr_extends_class[$control][7]=$this->id_object;					
				$name_class_relation=$arr_info[0];
				$name_interface_relation=$arr_info[4];//_relation				
				$obj_relation = new db_relation($this->prefix_db);
				$id_class_relation=$this->get_id_class_from_name($name_class_relation);
				if($name_extends_class==_DIRECT)								
					$arr_obj=$obj_relation->_get_relation_direct($id_object,$id_class_relation);
				else
					$arr_obj=$obj_relation->_get_relation_feedback($id_object,$id_class_relation);
				$id_relation=$arr_obj[0];
				if(!$id_relation || !$relation=$this->get_serialize_class_from_full_name($name_interface_relation))
					continue;
				if($immed =="IMMED"){
					if(!empty($arr_obj[0])){					
						if($relation->_set_object($id_relation)){
							if(!empty($relation->arr_extends_class)){
								reset($relation->arr_extends_class)	;	
								foreach($relation->arr_extends_class as $key => $arr){
									$arr_info=$this->arr_extends_class[$control];
									$key_full=$name_class_relation."_".$key;
									if(isset($arr[6])&& $arr[6]==_FINAL){
										if($arr[1]!=_DIRECT 
										&& $arr[1]!=_FEEDBACK 
										&& $arr[1]!=_CHILDREN 
										&& $arr[1]!=_PARENT){
											$this->arr_control_object[$key]=$relation->arr_control_object[$key];
										}
										$this->arr_extends_class[$key]=$relation->arr_extends_class[$key];									
									}else{
										if($arr[1]!=_DIRECT 
										&& $arr[1]!=_FEEDBACK 
										&& $arr[1]!=_CHILDREN 
										&& $arr[1]!=_PARENT ){
											if($arr[3]!= _PRIVATE){
												$this->arr_control_object[$key_full]=
													$relation->arr_control_object[$key];
												$this->arr_extends_class[$key_full]=
													$relation->arr_extends_class[$key];
												$this->arr_extends_class[$key_full][6]=_FINAL;
												$this->arr_extends_class[$key_full][7]=$relation->id_object;
												if(!isset($this->arr_id_immed[$name_class_relation]))												
													$this->arr_id_immed[$name_class_relation]=$relation->id_object;
											}
										}else{
											if(!empty($relation->arr_extends_class[$key][5])&&$relation->arr_extends_class[$key][5]=="IMMED"){
												continue;
											}else{
												$this->arr_extends_class[$key_full]=
													$relation->arr_extends_class[$key];
												$this->arr_extends_class[$key_full][6]=_FINAL;
												$this->arr_extends_class[$key_full][7]=$relation->id_object;
											}
										}
									}
								}
							}
						}
					}				
				}else{//_FINAL								
					$this->arr_extends_class[$control][7]=$this->id_object;
				}
			}
		}		
		if(!empty($this->arr_control_object)){
			$this->list_component=array_keys($this->arr_control_object);
			
			$this->list_component=array_flip($this->list_component);			
		}else{
			$this->list_component=array();
		}		
        return 1; 
	}
		/*#########################################		
				 					FUNCTION
		#########################################*/
	function set_id_object($id_object){//GET_ID_CLASS
		$this->id_object=$id_object;
		return 1;
	}	
	function get_id_class(){//GET_ID_CLASS
		return $this->id_class;
	}	
	function get_name_class(){//  GET_NAME_CLASS
		return $this->class_name;
	}
	function get_name_interface(){// GET_NAME_INTERFACE
		return $this->interface_name;
	}
	function get_id_object(){//GET_ID_OBJECT
		if(!$this->id_object)
			return 0;//$this->id_class
		return $this->id_object;
	}
	function get_component($component_name){//GET_COMPONENT
		if(isset($this->arr_control_object[$component_name]))
		  	return $this->arr_control_object[$component_name];
		return 0;
	}
	function is_component($name_component){//$name_component into container
		if(in_array($name_component,$this->list_component)){  
			return 1;
		}
		return 0;	
	}
	function is_component_extends($name_component){//$name_component into container
		$arr_name_extends=array_keys($this->array_extends_class);
		if(in_array($name_component,$arr_name_extends)){  
			return 1;
		}
		return 0;	
	}	
	function is_serializ(){
		return $this->flg_serializ;
	}
				// CONTROL	
	function get_control_class(){
		reset($this->array_extends_class);
		return array_keys($this->array_extends_class);
	}	
	function get_object($control){
		if(isset($this->arr_control_object[$control]))
		  	return $this->arr_control_object[$control];
		return 0;
	}
					//EMPTY
	function empty_arr_class(){
		$this->arr_control_object=array();
		return 1;
	}
	function remove_empty($array_in){
		if(!$array_in)
			return array();	
		$array_out=array();
		foreach($array_in as $key => $val){
			if(!empty($val))
				$array_out[$key]=$val;
		}
		return $array_out;
	}			
		/*####################
				INFO 
		#####################*/									
	function info_components(){
		return $this->list_component;
	}														
	function info_interface(){
		return $this->list_interface;
	}														
	function info_inheritance(){
		return $this->array_inheritance_class;
	}							
	function info_extends(){
		if(empty($this->arr_extends_class))
			return 0;
		$arr_rows=array();
		reset($this->arr_extends_class);			
		while(list($control,$arr)= each($this->arr_extends_class)){
			$arr_row=array();			
			if($arr[1]!=_DIRECT && $arr[1]!=_FEEDBACK && $arr[1]!=_CHILDREN && $arr[1]!=_PARENT){
					$arr_row["Control"]=array($control,$arr[1]);
					if(!empty($arr[2]))
						$arr_row["Component"]=$arr[2];
					if(!empty($arr[3]))
						$arr_row["Protection"]=$arr[3];
					if(!empty($arr[5]))
						$arr_row["Immediately"]=$arr[5];
					if(!empty($arr[6]))
						$arr_row["Finalise"]=$arr[6];
			}else{
					if(!empty($arr[7]))//$arr[7] ::= ID_Object
						$arr_row["Relation"]=array($control,$arr[1],$arr[7]);
					else	
						$arr_row["Relation"]=array($control,$arr[1],"");	
					$arr_row["Class"]=$arr[0];
					$arr_row["ID"]=$arr[3];
					$arr_row["Interface"]=$arr[4];
					if(!empty($arr[2]))
						$arr_row["Protection"]=$arr[2];
					if(!empty($arr[5]))
						$arr_row["Immediately"]=$arr[5];
					if(!empty($arr[6]))
						$arr_row["Finalise"]=$arr[6];
			}
			$arr_rows[]=$arr_row;
		}		
		return $arr_rows;		
	}
	function info_relations(){
		if(empty($this->array_relations_class)){
			return 0;		
		}
		$arr_rows=array();
		$array_relations=$this->array_relations_class;
		foreach($array_relations as $name_class => $arr){
			$arr_row=array();
			$arr_row["Relation"]=array($arr[0],$arr[1]);
			if(!empty($arr[2]))
				$arr_row["Type"]=$arr[2];
			if(!empty($arr[3]))
				$arr_row["Protection"]=$arr[3]; 
			if(!empty($arr[4]))
				$arr_row["Inheritance"]=$arr[4]; 
			if(!empty($arr[5]))
				$arr_row["Immediately"]=$arr[5]; 
				
			$arr_rows[]=$arr_row;
		}		
		return $arr_rows;
	}
	function info_pointers(){
		if(empty($this->array_pointers_class)){
			return 0;		
		}
		$arr_rows=array();
		$array_pointers=$this->array_pointers_class;
		foreach($array_pointers as $name_class => $arr){
			$arr_row=array();
			$arr_row["Relation"]=array($arr[0],$arr[1]);
			if(!empty($arr[2]))
				$arr_row["Type"]=$arr[2];
			if(!empty($arr[3]))
				$arr_row["Protection"]=$arr[3]; 
			if(!empty($arr[4]))
				$arr_row["Inheritance"]=$arr[4]; 
			if(!empty($arr[5]))
				$arr_row["Immediately"]=$arr[5]; 
				
			$arr_rows[]=$arr_row;
		}		
		return $arr_rows;
	}									
	function show_components(){
		print "<B>LIST OF COMPONENTS OF CURRENT OBJECT:</B><BR>";
		if(empty($this->list_component)){
			print "EMPTY;<BR>";
			return;		
		}
		reset($this->list_component);
		foreach($this->list_component as $control=>$nn){
			$control=trim($control);
			if(empty($this->arr_extends_class[$control]))
				continue;
			$arr=$this->arr_extends_class[$control];
			print "&nbsp;&nbsp;&nbsp;&nbsp;$control='".$arr[1]."'<BR>";
		}
	}
	function show_interfaces(){	
		if(empty($this->list_interface)){
			print "<BR><FONT SIZE='-1'>VIEWS :   NO</FONT>";	
			return 0;		
		}
		print "<BR><FONT SIZE='-1'>VIEWS:</FONT><BR>";				
		reset($this->list_interface);
		foreach($this->list_interface as $nn=>$control){
			print "&nbsp;&nbsp;&nbsp;&nbsp;$control<BR>";
		}
	}
	function show_inheritance(){
	
		if(empty($this->array_inheritance_class)){
			print "INHERITANCE CONTAINERS:  EMPTY;<BR>";
			return;		
		}
		print "LIST OF INHERITANCE CONTAINERS:<B>'$this->class_name' </B>(ID- $this->id_class):<BR>";				
				print_r($this->get_list_inheritance());echo "<BR>";				
		print "<BR><BR>";
	}
	function show_extends(){		
		$arr_rows= $this->info_extends();
		if(!empty($arr_rows)){
			while($myrow = $this->fetch_row($arr_rows)){
				if(isset($myrow["Control"])){
						print("&nbsp;&nbsp;&nbsp;&nbsp;".$myrow["Control"][0]." = ");							
						if(!empty($myrow["Protection"]))//PRIVATE
							print " ( ".$myrow["Protection"]." ) ";
						if(!empty($myrow["Component"]))//STATIC
							print " ( ".$myrow["Component"]." ) ";
						print $myrow["Control"][1];
						print "<BR>";
				}
			}							
		}
		
		$arr_rows_relations=$this->info_relations();
		$arr_rows1=$arr_rows_relations;
		if(!empty($arr_rows_relations)){
		print "<BR><FONT SIZE='-1'><B>RELATIONSHIP:</B>";				
			print "<BR>";	
			while($myrow = $this->fetch_row($arr_rows1)){
					print "&nbsp;&nbsp;&nbsp;&nbsp;=>";
					if(!empty($myrow["Immediately"]))
						print " ( ".$myrow["Immediately"]." )";
					print " ".$myrow["Inheritance"];
					print "<BR>";
			}
		}
		$arr_rows_pointers=$this->info_pointers();
		if(!empty($arr_rows_pointers)){
			$arr_rows2=$arr_rows_pointers;
			while($myrow = $this->fetch_row($arr_rows2)){
					print "&nbsp;&nbsp;&nbsp;&nbsp;=>";
					print " ( ".$myrow["Relation"][1]." ) ";
					print $myrow["Inheritance"];
					print "<BR>";
			}
		}
		$arr_inheritance=$this->get_list_inheritance();
		if(!empty($arr_inheritance)){
			print "<BR><FONT SIZE='-1'><B>CONTAINERS:</B><BR>";			 
			foreach($arr_inheritance as $nn => $name_container){
				if($nn!=0)
				print "&nbsp;&nbsp;&nbsp;&nbsp;$name_container <BR>";
			}
		}
		print "</FONT><BR>";
	}	
	function show_relations(){
		print   "RELATION OF THE CLASS '$this->class_name' (ID-$this->id_class):<BR>";
		if(empty($this->array_relations_class)){
			echo "&nbsp;&nbsp;&nbsp;&nbsp;- NO!:<BR>";
			return;		
		}
		$array_relations=$this->array_relations_class;
		foreach($array_relations as $name_class => $arr){
			print "&nbsp;&nbsp;&nbsp;&nbsp;Relation class- '".$arr[0]."';  Component- '".$arr[1]."'";
			if(!empty($arr[2]))
				print ", Type- '".$arr[2]."'";
			if(!empty($arr[3])) 
				print ", Hiding- '".$arr[3]."'";
			if(!empty($arr[4])) 
				print ", Inheritance- '".$arr[4]."'";
			if(!empty($arr[5])) 
				print ", Immediately- '".$arr[5]."'";
				
			print "<BR>";	
		}
		print "<BR>";		
	}
	function show_pointers(){
		print   "POINTERS OF THE CLASS '$this->class_name' (ID-$this->id_class):<BR>";
		if(empty($this->array_pointers_class)){
			echo "&nbsp;&nbsp;&nbsp;&nbsp;- NO!:<BR>";
			return;		
		}
		$array_pointers=$this->array_pointers_class;
		foreach($array_pointers as $name_class => $arr){
			print "&nbsp;&nbsp;&nbsp;&nbsp;Pointer class: Name- ".$arr[0].";  Component- '".$arr[1]."'";
			if(!empty($arr[2]))
				print ", Type- '".$arr[2]."'";
			if(!empty($arr[3])) 
				print ", Hiding- '".$arr[3]."'";
			if(!empty($arr[4])) 
				print ", Inheritance- '".$arr[4]."'";

			print "<BR>";	
		}
		print "<BR>";
	}
		/*###########################################################################		
				EXTENDS
		###########################################################################*/									
				// ALL EXTENDS
	function set_extends_class(){	
		$this->arr_extends_class=array();
		if(!empty($this->array_pointers_class))
			$this->arr_extends_class=array_merge($this->arr_extends_class,$this->get_pointers_class());
		if(!empty($this->array_relations_class))
			$this->arr_extends_class=array_merge($this->arr_extends_class,$this->get_relations_class());
		if(!empty($this->array_extends_class))
			$this->arr_extends_class=array_merge($this->arr_extends_class,$this->get_extends_class());
		if(empty($this->arr_extends_class))
			return 0;
		return 1;							
	}
	function get_extends_class(){
		if(empty($this->array_extends_class))
			return 0;
		return $this->array_extends_class;
	}
	function is_extends_class(){
		if(empty($this->array_extends_class))
			return 0;
		return 1;
	}
	function add_extends_class($control,$name_class_exst,$content,$hiding=""){
		if(!isset($this->array_extends_class))
			return 0;
		$this->array_extends_class[$control]=array("",$name_class_exst,$content,$hiding);
		return 1;
	}
	function del_extends_class($control){
		if(!isset($this->array_extends_class[$control]))
			return 0;
		$this->array_extends_class[$control]="";
		$this->array_extends_class=$this->remove_empty($this->array_extends_class);
			return 1;					
	}
	function get_extend_class_info($control){
		if(empty($this->array_extends_class[$control]))
			return 0;
		return $this->array_extends_class[$control];
	}
	function is_extend_class($control){	
		if(empty($this->array_extends_class[$control]))
			return 0;
		return 1;
	}
	/*#########################################################
						 RELATIONSHIP									
	  #########################################################*/
	function set_direction_relationship($name_relation,$dir){
		$arr_tmp=$this->pars_name_class($name_relation);
		$name_relation=$arr_tmp[0];
		$this->array_relationship[$name_relation]=$dir;
		return 1;
	}	
	function get_direction_relationship($class_relationship,$dir=""){
		if(!$class_relationship)
			return 0;
		if(!$dir)			
			$dir=_DIRECT;
		elseif($dir!=_DIRECT && $dir!=_FEEDBACK)
			return 0;
		$name_relationship=$class_relationship->get_name_class();
		$arr_tmp=$this->pars_name_class($name_relationship);
		$name_relationship=$arr_tmp[0];
		
		$name_class=$this->get_name_class();
		$arr_tmp=$this->pars_name_class($name_class);
		$name_class=$arr_tmp[0];
				//THIS
    	if(!empty($this->array_relationship[$name_relationship])){
			$dir=$this->array_relationship[$name_relationship];
			return $dir;
		}
				//RELATION
		if(!empty($class_relationship->array_relationship[$name_class])){	
			$dir_tmp=$class_relationship->array_relationship[$name_class];
			if($dir_tmp==_DIRECT)
				$dir=_FEEDBACK;
			else
				$dir=_DIRECT;
		}
		$this->array_relationship[$name_relationship]=$dir;
		return $dir;
	}
	function select_relationship($name_class_relationship,$dir=""){
		$id_object=$this->get_id_object();
		return $this->select_relationship_object($name_class_relationship,$id_object,$dir);
	}
	function select_relationship_object($name_class_relationship,$id_object,$dir=""){
		if(!$name_class_relationship || !$id_object)
			return 0;
		$direction="";
		$relationship="";
		$dir_r="";
		$dir_p="";					
		if($dir){
			$img_direction=trim($dir);
			if(strpos($img_direction,"->")===false){
				if(strpos($img_direction,"<-")===false){
					$dir_r="";
					$dir_p="";					
				}else{
					$dir_r=_DIRECT;
					$dir_p=_PARENT;					
				}					
			}else{
				$dir_r=_FEEDBACK;
				$dir_p=_CHILDREN;
			}
		}
		if($this->is_parent_class($name_class_relationship)&&($dir_p==_PARENT||$dir==_PARENT ||
		!$dir)){
			$direction=_PARENT;
			$relationship=_POINTER;;
		}elseif($this->is_parent_class($name_class_relationship)&&($dir_p==_CHILDREN||$dir==_CHILDREN ||!$dir)){
			$direction=_CHILDREN;
			$relationship=_POINTER;
		}else{
			foreach($this->arr_extends_class as $key =>$arr){
				if($arr[1]==_DIRECT && $arr[4]==$name_class_relationship &&($dir_r==_DIRECT||$dir==_DIRECT||!$dir)){
					$direction=_DIRECT;
					$relationship=_RELATION;
					$id_obj_relation=$arr[7];					
				}elseif($arr[1]==_FEEDBACK && $arr[4]=$name_class_relationship &&($dir_r==_FEEDBACK||$dir==_FEEDBACK ||!$dir)){
					$direction=_FEEDBACK;
					$relationship=_RELATION;
					$id_obj_relation=$arr[7];		
				}
			}
		}
		if(!$direction || !$relationship)
			return 0;
		$id_relationship_class=$this->get_id_class_from_full_name($name_class_relationship);
		$str_fun=strtolower("_get_".$relationship."_".$direction);
		if($relationship==_RELATION){
			$obj=new db_relation($this->prefix_db);
			if(!$arr_id_relationship=$obj->$str_fun($id_obj_relation,$id_relationship_class))										
				return 0;
		}elseif($relationship==_POINTER){
			$arr=$this->pars_name_class($name_class_relationship);
			$name_relationship=$arr[0]; 
			$obj=new db_category($this->prefix_db);
			if(!$arr_id_relationship=$obj->$str_fun($id_object,$name_relationship))										
				return 0;
		}else	
			return 0;							
		return $arr_id_relationship;
	}	
	function create_relationship($obj_relationship){//$obj_relationship:==$obj_relation|$obj_pointer
		if(!$name_class_relationship=$obj_relationship->get_name_class())
			return 0;
		if(!$name_class=$this->get_name_class())
			return 0;			
		if($this->is_direct_class($name_class_relationship)){
			$img_direction="->";
			$obj=$this;
			$relationship=$obj_relationship;			
		}elseif($this->is_feedback_class($name_class_relationship)){
			$img_direction="<-";
			$obj=$this;
			$relationship=$obj_relationship;
		}elseif($obj_relationship->is_direct_class($name_class)){
			$img_direction="->";
			$obj=$obj_relationship;			
			$relationship=$this;
		}elseif($obj_relationship->is_feedback_class($name_class)){
			$img_direction="<-";
			$obj=$obj_relationship;
			$relationship=$this;				
		}else{
			$this->warning("Syntaxerror 073 unexpected ");	
			return 0;
		}		
		if(!$obj->create_relation($img_direction,$relationship))
			return 0;
		return 1;	
	}		
	function drop_relationship($obj_relationship){//$obj_relationship:==$obj_relation|$obj_pointer
		if(!$name_class=$obj_relationship->get_name_interface())
			return 0;
		if(!$arr_relationship=$this->get_relation_info($name_class)){
			if($this->is_direct_class($name_class))
				$img_direction="->";			
			elseif($this->is_feedback_class($name_class))
				$img_direction="<-";	
			else
				return 0;
			if(!$this->drop_relation($img_direction,$obj_relationship))
				return 0;
			return 1;	
		}else
			return 0;
	}
	/*#########################################################
						 RELATION									
	  #########################################################*/
	//var array_relations_class::=
		//::=array([control]=>array({
		//[0]=>relation,::=relation --> name_class(USER)
		//[1]=>direction,::=direction(_DIRECT|_FEEDBACK(->|<-))
		//[2]=>plase_content,::=plase(DINAMIC|CTATIC(CLASS|OBJECT))
		//[3]=>access,::=access(PUBLIC|PRIVATE)
		//[4]=>interface}::=interface --> name_interface(USER.DATE)
		//[5]=>immed}::=IMMED|""
		//[6]=>name_class_immed::=name_class_immed|""//NEW
		//}.,..));
	function get_relations_class(){
		if(empty($this->array_relations_class))
			return 0;
		$arr_relations=array();	
		$nn=-1;
		foreach($this->array_relations_class as $i => $arr){
			$key="R_".++$nn;
			$arr_relations[$key]=$arr;
			$arr_relations[$key][3]=$this->name_to_id_class($arr_relations[$key][0]);	
		}
		return	$arr_relations;
	}
	function add_relation_class($name_relation,$name_class_exst,$content,$visibility="",$name_interface_relation="" ,$is_immed=0){
		if($is_immed)
			$immed="IMMED";
		else
			$immed="";
		if(strpos($name_relation,".")===false){
			$name_interface_relation=$name_relation;
		}else{
			$arr_exp=explode(".",$name_relation);
			$name_interface_relation=trim($name_relation);
			$name_relation=trim($arr_exp[0]);
		}
		$this->del_relation_class($name_relation,$name_class_exst);/////								
		$this->array_relations_class[]=array($name_relation,$name_class_exst,$content,$visibility,$name_interface_relation,$immed);
		return 1;
	}	
	function del_relation_class($name_relation,$name_class_exst){
		if(!isset($this->array_relations_class))
			return 0;
		if(strpos($name_relation,".")===false){///////05.07///////////////
			$name_relation=$name_relation;
		}else{
			$arr_exp=explode(".",$name_relation);
			$name_relation=trim($arr_exp[0]);
		}				
		if(strpos($name_class_exst,".")===false){
			$name_class_exst=$name_class_exst;
		}else{
			$arr_exp=explode(".",$name_class_exst);
			$name_class_exst=trim($arr_exp[0]);
		}							
		foreach($this->array_relations_class as $key => $arr){
			if(!isset($arr))
				continue;
			if($arr[0]== $name_relation && $arr[1] == $name_class_exst ){
				$this->array_relations_class[$key]="";
				$this->array_relations_class=$this->remove_empty($this->array_relations_class);
				return 1;
			}
		}
		return 0;	
	}
	function is_relation_class($name_relation,$direction=""){
		if(!empty($this->array_relations_class)){
			if($direction){
				foreach($this->array_relations_class as $key => $arr){
					if(($arr[0] == trim($name_relation)|| $arr[4] == trim($name_relation))&& $arr[1] == $direction)
						return 1;
				}
			}else{
				foreach($this->array_relations_class as $key => $arr){
					if($arr[0] == trim($name_relation)|| $arr[4] == trim($name_relation))
						return 1;
				}			
			}
		}
		return 0;
	}	
	function is_direct_class($name_relation){
		if(empty($this->array_relations_class))
			return 0;
		foreach($this->array_relations_class as $key => $arr){
			if(($arr[0] == trim($name_relation)|| $arr[4] == trim($name_relation)) && $arr[1] ==_DIRECT){			
				return 1;
			}	
		}
		return 0;	
	}
	function is_feedback_class($name_relation){
		if(empty($this->array_relations_class))
			return 0;
		foreach($this->array_relations_class as $key => $arr){
			if(($arr[0] == trim($name_relation)|| $arr[4] == trim($name_relation)) && $arr[1] ==_FEEDBACK)			
					return 1;
		}
		return 0;
	}
	function get_relation_info($name_relation){
		if(!empty($this->array_relations_class)){
			foreach($this->array_relations_class as $key => $arr){
				if($arr[0] == $name_relation)
					return $arr;//array([0]-$name_relation,[1]-$name_class_exst,[2]-$content,[3]-$hiding;
			}
		}
		return 0;
	}
	function create_relation($img_direction,$obj_relation){//$img_direction::=_DIRECT(->),_FEEDBACK(<-);
		if(!$arr_options=$this->sub_relation($img_direction,$obj_relation))
			return 0;
		$obj=new db_relation_table($this->prefix_db);	
		if(!$obj->add($arr_options))	
			return  0;
		return 1;
	}		
	function drop_relation($img_direction,$obj_relation){//$img_direction::=_DIRECT(->),_FEEDBACK(<-);
		if(!$arr_options=$this->sub_relation($img_direction,$obj_relation))
			return 0;
		$obj=new db_relation_table($this->prefix_db);
		if(!$obj->del($arr_options))	
			return  0;
		return 1;
	}
	function sub_relation($img_direction,$obj_relation){//$img_direction::=_DIRECT(->),_FEEDBACK(<-);
		if(empty($obj_relation)||empty($img_direction))
			return 0;		
		if(!$id_object_this=$this->get_id_object())
			$id_object_this=$this->get_id_class();			

		if(strpos($img_direction,"->")===false){
			if(strpos($img_direction,"<-")===false){
				return 0;
			}else
				$direction=_FEEDBACK;//<-(_CHILDREN|_FEEDBACK )
		}else
			$direction=_DIRECT;// ->(_PARENT|_DIRECT )
		$name_class_relation=$obj_relation->get_name_class();
		if($direction==_DIRECT){	
			$id_direct=$id_object_this;
			if($obj_relation->get_id_object()&& $this->is_class($obj_relation->get_id_object()))
				$id_feedback=$obj_relation->get_id_class();
			else
				$id_feedback=$obj_relation->get_id_object();
			// TEST
			if(!$this->is_direct_class($name_class_relation)){
				$this->warning("Syntaxerror 074 unexpected 'relation $name_class_relation or direction ->' ");	
				return 0;
			}			
		}elseif($direction==_FEEDBACK){
			if($obj_relation->get_id_object()&& $this->is_class($obj_relation->get_id_object()))
				$id_direct=$obj_relation->get_id_class();
			else
				$id_direct=$obj_relation->get_id_object();
			$id_feedback=$id_object_this;		
			// TEST
			if(!$this->is_feedback_class($name_class_relation)){
				$this->warning("Syntaxerror 075 unexpected 'relation $name_class_relation or direction <- '");	
				return 0;
			}			
		}else
			return 0;			
		if(!is_numeric($id_direct))
			$id_direct="'".$id_direct."'";
		if(!is_numeric($id_feedback))
			$id_feedback="'".$id_feedback."'";
		$arr_options[0]=$id_direct;
		$arr_options[1]=$id_feedback;
		return $arr_options;
	}	
	/*#########################################################
						 POINTER									
	  #########################################################*/									
	//var array_pointers_class
		//::=array([control]=>array({
		//[0]=>pointer,::=pointer --> name_class(USER)
		//[1]=>direction,::=direction(_PARENT|_CHILDREN(->|<-))
		//[2]=>plase_content,::=plase(DINAMIC|CTATIC(into CLASS|OBJECT))
		//[3]=>access,::=access(PUBLIC|PRIVATE)
		//[4]=>interface}::=interface --> name_interface(USER.DATE)
		//}.,..));					
	function get_pointers_class(){
		if(empty($this->array_pointers_class))
			return 0;
		$arr_pointers=array();
		$nn=-1;
		foreach($this->array_pointers_class as $i => $arr){
			$key="P_".++$nn;
			$arr_pointers[$key]=$arr;
			$arr_pointers[$key][3]=$this->name_to_id_class($arr_pointers[$key][0]);	
		}
		return	$arr_pointers;
	}
	function add_pointer_class($name_pointer,$name_class_exst,$content,$hiding=""){
		if(strpos($name_pointer,".")===false){
			$name_interface_pointer=$name_pointer;
		}else{
			$arr_exp=explode(".",$name_pointer);
			$name_interface_pointer=trim($name_pointer);
			$name_pointer=trim($arr_exp[0]);
		}				
		if(!empty($this->array_pointers_class)){
			foreach($this->array_pointers_class as $key => $arr){
				if($arr[0] == $name_pointer && $arr[1] == $name_class_exst && $arr[2] == $content&& $arr[4] == $name_interface_pointer)////////////
					return 1;//////////////
			}			
		}
		$this->array_pointers_class[]=array($name_pointer,$name_class_exst,$content,$hiding,$name_interface_pointer);
		return 1;
	}										
	function del_pointer_class($name_pointer,$name_class_exst){		
		if(!isset($this->array_pointers_class))
			return 0;			
		if(strpos($name_pointer,".")===false){
			$name_pointer=$name_pointer;
		}else{
			$arr_exp=explode(".",$name_pointer);
			$name_pointer=trim($arr_exp[0]);
		}				
		if(strpos($name_class_exst,".")===false){
			$name_class_exst=$name_class_exst;
		}else{
			$arr_exp=explode(".",$name_class_exst);
			$name_class_exst=trim($arr_exp[0]);
		}///////////////////				
		foreach($this->array_pointers_class as $key => $arr){
			if(!isset($arr))
				continue;
			if($arr[0]== $name_pointer && $arr[1] == $name_class_exst){
				$this->array_pointers_class[$key]="";
				$this->array_pointers_class=$this->remove_empty($this->array_pointers_class);
				return 1;
			}
		}
		return 0;	
	}
	function is_pointer_class($name_pointer,$direction=""){
		if(!empty($this->array_pointers_class)){
			if($direction){
				foreach($this->array_pointers_class as $key => $arr){
					if(($arr[0] == trim($name_pointer)|| $arr[4] == trim($name_pointer)) && $arr[1] == $direction)///////
						return 1;
				}
			}else{
				foreach($this->array_pointers_class as $key => $arr){
					if($arr[0] == trim($name_pointer)|| $arr[4] == trim($name_pointer))///////
						return 1;
				}
			}
		}
		return 0;
	}
	function is_parent_class($name_pointer){
		if(empty($this->array_pointers_class))
			return 0;
		foreach($this->array_pointers_class as $key => $arr){
			if(($arr[0] == trim($name_pointer)|| $arr[4] == trim($name_pointer))&& $arr[1] == _PARENT )			
					return 1;
		}
		return 0;	
	}
	function is_children_class($name_pointer){
		if(empty($this->array_pointers_class))
			return 0;
		foreach($this->array_pointers_class as $key => $arr){
			if(($arr[0] == trim($name_pointer)|| $arr[4] == trim($name_pointer))&& $arr[1] == _CHILDREN )			
					return 1;
		}
		return 0;
	}
	function get_pointer_info($name_pointer){
		if(!empty($this->array_pointers_class)){
			foreach($this->array_pointers_class as $key => $arr){
				if($arr[0] == trim($name_pointer))
					return $arr;//array([0]-$name_pointer,[1]-$name_class_exst,[2]-$content,[3]-$hiding;
			}
		}
		return 0;
	}
	function create_pointer($img_direction,$obj_pointer){//$direction::=_PARENT(->),_CHILDREN(<-);
		if(!$arr_options=$this->sub_pointer($img_direction,$obj_pointer))
			return 0;		
		$obj=new db_category_table($this->prefix_db);//_POINTER	
		if(!$obj->add($arr_options))	
			return  0;
		return 1;
	}		
	function drop_pointer($img_direction,$obj_pointer){//$direction::=_PARENT(->),_CHILDREN(<-);
		if(!$arr_options=$this->sub_pointer($img_direction,$obj_pointer))
			return 0;
		$obj=new db_category_table($this->prefix_db);//_POINTER	
		if(!$obj->del($arr_options))	
			return  0;
		return 1;
	}
	function sub_pointer($img_direction,$obj_pointer){//$img_direction::=(_PARENT)->,(_CHILDREN)<-;
		if(empty($obj_pointer)||empty($img_direction))
			return 0;
		if(!$id_object_this=$this->get_id_object())
			$id_object_this=$this->get_id_class();
		if(strpos($img_direction,"->")===false){
			if(strpos($img_direction,"<-")===false){
				$this->warning("Syntaxerror 076 pointer unexpected $img_direction ");
				return 0;
			}else
				$direction=_CHILDREN;//<-(_CHILDREN|_FEEDBACK )
		}else
			$direction=_PARENT;// ->(_PARENT|_DIRECT )
			
		if($obj_pointer!="NULL"){
			$name_class_pointer=trim($obj_pointer->get_name_class());
			if($direction==_PARENT){	
				$id_direct=$id_object_this;
				if($obj_pointer->get_id_object()&& $this->is_class($obj_pointer->get_id_object()))
					$id_feedback=$obj_pointer->get_id_class();
				else
					$id_feedback=$obj_pointer->get_id_object();
				// TEST
				if(!$this->is_parent_class($name_class_pointer)){
					$this->warning("Syntaxerror 077 unexpected 'pointer $name_class_pointer or direction -> '");	
					return 0;
				}
			}elseif($direction==_CHILDREN){
				if($obj_pointer->get_id_object()&& $this->is_class($obj_pointer->get_id_object()))
					$id_direct=$obj_pointer->get_id_class();
				else
					$id_direct=$obj_pointer->get_id_object();
				$id_feedback=$id_object_this;		
				// TEST
				if(!$this->is_children_class($name_class_pointer)){
					$this->warning("Syntaxerror 078 unexpected 'pointer $name_class_pointer or direction <-' ");	
					return 0;
				}			
			}else
				return 0;
		}else{
			if($direction==_PARENT){	
				$id_direct=$id_object_this;
				$id_feedback=0;
			}elseif($direction==_CHILDREN){
				$id_direct=0;
				$id_feedback=$id_object_this;		
			}else
				return 0;
		
		}			
		if(!is_numeric($id_direct))     //01.20
			$id_direct="'".$id_direct."'";
		if(!is_numeric($id_feedback))
			$id_feedback="'".$id_feedback."'";
		$arr_options[0]=$id_direct;
		$arr_options[1]=$id_feedback;
		return $arr_options;
	}
	/*#########################################################
						 INTERFACE									
	#########################################################*/									
	function get_list_interface(){
		if(!empty($this->list_interface))
			return 0;
		reset($this->list_interface);
		return $this->list_interface;
	}
	function is_interface_class($name_interface){
		$name_interface=trim($name_interface);
		if(empty($this->list_interface))
			return 0;
		if(!in_array($name_interface,$this->list_interface))
			return 0;
		return 1;
	}
	function get_interface_info($array_class,$name_class){
		if(empty($array_class))
			return 0;
		$name_class=trim($name_class);
		$arr_info=array();
		for($i=0;$i<count($array_class);$i++){
			if($array_class[$i][0] == $name_class){
				$arr_info[]=$array_class[$i];
			}else{
				if($class=$this->get_serialize_class_from_name($array_class[$i][0]))
					if($class->is_interface_class($name_class))
						$arr_info[]=$array_class[$i];			
			}
		}
		return $arr_info;
	}	
	function get_interface(){
		if(!empty($this->list_interface))
			return 0;
		reset($this->list_interface);
		return array_keys($this->list_interface);
	}
	/*#########################################################
						 INHERRITANCE									
	  #########################################################*/									
	//var array_inheritance_class=array(name_class_inheritance=>$arr_inheritance_inheritance_class);		
	function get_inheritance(){
		if(!empty($this->array_inheritance_class))
			return 0;
		reset($this->array_inheritance_class);
		return array_keys($this->array_inheritance_class);
	}
	function inheritance_level($arr_inheritance){
		if(empty($arr_inheritance))
			return;
		foreach ($arr_inheritance as $key => $val){
			if(in_array($key,$this->list_inheritance))
				continue;
			$this->list_inheritance[] = $key;		
			if($val!="EMPTY" )
				$this->inheritance_level($val);
			continue;		
		}
		return;
	}	
	function set_list_inheritance(){
		$this->list_inheritance=array();		
		$this->list_inheritance[]=$this->get_name_class();
		if(!empty($this->array_inheritance_class)){
			$this->inheritance_level($this->array_inheritance_class);
		}			
		return $this->list_inheritance;
	}
	
 	function get_list_inheritance(){
		if(empty($this->array_inheritance_class))
			return 0;
		return $this->set_list_inheritance();			
	}							
	function is_inheritance_class($name_inheritance){// new
		$name_inheritance=trim($name_inheritance);
		if(empty($this->array_inheritance_class))
			return 0;
		if(empty($this->list_inheritance))	
			$this->set_list_inheritance();	
		if(!in_array($name_inheritance,$this->list_inheritance))
			return 0;
		return 1;
	}
	// #### This function gruperuet relations and pointers ####
	function get_inheritance_class_info($array_class,$name_class){
	//$array_class ::= array_relations_class[array_parent_class][array_children_class]
	//$name_class ::= name_class[name_component_class]
		if(empty($array_class))
			return 0;
		$name_class=trim($name_class);
		$arr_info=array();
		for($i=0;$i<count($array_class);$i++){
			if($array_class[$i][0] == $name_class){
				$arr_info[]=$array_class[$i];
			}else{
				if($class=$this->get_serialize_class_from_name($array_class[$i][0]))
					if($class->is_inheritance_class($name_class))
						$arr_info[]=$array_class[$i];			
			}
		}
		return $arr_info;
	}	
	function del_inheritance_class($name_inheritance){
		$name_inheritance=trim($name_inheritance);
		if(empty($this->array_inheritance_class))
			return 0;
		if(!isset($this->array_inheritance_class[$name_inheritance]))
			return 0;
		foreach($this->array_inheritance_class as $name => $arr){
			if(!isset($arr))
				continue;
			if($name!= $name_inheritance)
				$this->array_inheritance_class[$name]=$arr;	
		}
		$this->set_list_inheritance();	
		return 1;	
	}
	function add_inheritance_class($name_inheritance,$arr_inheritance){
		$name_inheritance=trim($name_inheritance);
		if(isset($this->array_inheritance_class[$name_inheritance])&& $name_inheritance && !empty($arr_inheritance))
			return 0;
		if(isset($this->array_inheritance_class[$name_inheritance]))
			$this->array_inheritance_class[$name_inheritance]=$arr_inheritance;	
		$this->set_list_inheritance();	
		return 1;	
	}					

	/*#################################################*/		
	function parser_options($str_options,$method,$class){//22                            
	/*#################################################*/		
	//$str_options='Code_author,ISBN';
	//array ( [0] => Array ( [ Code_author ] => Array ( ) ) [1] => Array ( [ISBN] => Array ( ) ) );
	//$arr_options = array([0]=>array([control] =>array(o-value, 1- Key)...)
	/*#################################################*/		
		$arr_options=array();
		$str_options=" ".$str_options;
		
		if(strpos($str_options, "*")){
			if(!empty($class))
				$arr_control=$class->get_control_class();
			else
				$arr_control=$this->get_control_class();
			for($i=0;$i<count($arr_control);$i++){
				$control=trim($arr_control[$i]);						
				if($class)
					$arr_info=$class->arr_extends_class;		
				else
					$arr_info=$this->arr_extends_class;		 
					$component=$arr_info[$control][1];
				$arr_options[]=array($control=>array(2=>$component));
			}
			return $arr_options;
		}elseif($method=="SELECT"||$method=="DELETE"||$method=="INCREMENT"||$method=="DECREMENT"){
				$arr_control = explode(",",$str_options);//19
				for($i=0;$i<count($arr_control);$i++){
					$control=trim($arr_control[$i]);						
					if($class){
						$arr_info=$class->arr_extends_class;		
					}else
						$arr_info=$this->arr_extends_class;		
					if(($pos_1 = strpos($control, "["))&&($pos_2 = strpos($control, "]"))){
						$pos_1+=1;
						if(empty($key=trim(substr($control,$pos_1,$pos_2-$pos_1))))
							return 0;
						$control=trim(substr($control,0,$pos_1-1));
						$component=$arr_info[$control][1];
						$arr_options[]=array($control=>array(1=>$key,2=>$component));
					}elseif(!empty($arr_info[$control])){
					    $component=$arr_info[$control][1];
						$arr_options[]=array($control=>array(2=>$component));
					}elseif($control=="ID")
						$arr_options[]=array($control=>array());
					$control_options[]=trim($control);
				}
		}elseif($method=="INSERT"||$method=="UPDATE"||$method=="UPSERT"){
			$expression_arr = explode(",",$str_options);//
			$arr_token_expression=array();
			$str_token="";
			foreach($expression_arr as $nn=> $arr_arr){		
				$arr_token_tmp= explode("=",$arr_arr);
				if(!empty($arr_token_tmp[1])&& empty($str_token))
					$str_token=$arr_token_tmp[0]."=". $arr_token_tmp[1];
				elseif(!empty($arr_token_tmp[1])&& !empty($str_token)){
					$arr_token_expression[]=$str_token;
					$str_token=$arr_token_tmp[0]."=". $arr_token_tmp[1];
				}elseif(empty($arr_token_tmp[1])&& !empty($str_token))
					$str_token.=",".$arr_token_tmp[0];
			}
			$arr_token_expression[]=$str_token;
			for($i=0;$i<count($arr_token_expression);$i++){
				$arr_token= explode("=",$arr_token_expression[$i]);
				$control=trim($arr_token[0]);
				$value=trim($arr_token[1]);
				if($class)
					$arr_info=$class->arr_extends_class;		
				else
					$arr_info=$this->arr_extends_class;		
				if(($pos_1 = strpos($control, "["))&&($pos_2 = strpos($control, "]"))){
					$pos_1+=1;
					$key=trim(substr($control,$pos_1,$pos_2-$pos_1));
					$control=trim(substr($control,0,$pos_1-1));
					$component=$arr_info[$control][1];
					$arr_options[]=array($control=>array(0=>$value,1=>$key,2=>$component));
				}elseif(!empty($arr_info[$control])){
					$component=$arr_info[$control][1];
					$arr_options[]=array($control=>array(0=>$value,2=>$component));
				}elseif($control=="ID")
					$arr_options[]=array($control=>array());
				$control_options[]=trim($control);
			}
		} 
		if($class)
			$control_class=$class->get_control_class();
		else 
			$control_class=$this->get_control_class();
			$arr_tmp=array("ID");
			$control_class_tmp = array_merge($control_class, $arr_tmp);
		if($method=="SELECT"||$method=="INCREMENT"||$method=="DECREMENT"){
		/*#########################################*/
			$arr_tmp=array("ID");
			$control_class = array_merge($control_class, $arr_tmp);
			$arr_tmp=array("ID","*");
			$control_class_tmp = array_merge($control_class, $arr_tmp);
			if(!empty(array_diff($control_options,$control_class)))
				return 0;//ERROR				
			return $arr_options;
		}elseif($method=="DELETE"){
		/*#########################################*/
			if(!empty(array_diff($control_options,$control_class))) 
				return 0;//ERROR
			/*if(!empty($arr_rez= array_diff($control_class,$control_options))) //22
				return 0;//ERROR*/
		}elseif($method=="UPDATE"||$method=="UPSERT"){
		/*#########################################*/
			if(!empty(array_diff($control_options,$control_class))) 
				return 0;
		}elseif($method=="INSERT"){
		/*#########################################*/
			if(!empty($arr_rez=array_diff($control_options,$control_class)))
				return 0;//ERROR
			if(!empty($arr_rez= array_diff($control_class,$control_options)))
				return 0;//ERROR		
		} 
		return $arr_options;
	}//END
 
							/*        DB_COMPONENT_QUERY
										
	###############################################################################			
			FORM 0:
				query::= COP(name component[key][='value'].,..)
				COP::= INSERT|UPDATE|SELECT|DELETE|INCREMENT|DECREMENT
				Exempl:"SELECT(UNIT_Unit[RU]);;"
	###############################################################################				
									
	###############################################################################
			FORM 1;
			query::=  COP <->|->|<- | PATH (*|ID[name component[key].,..]) LIMIT [offset,] n														
			COP::= SELECT
			Exempl:
				SELECT(*|ID,Code,Name[RU]) LIMIT 0,2;;
	###############################################################################*/			
		
	function db_component_query($query){
			//	FORM 0,1				
		if(!$query || !is_string($query)||!$this->id_class)
			return 0;
		$query=trim($query);				
		$pos_1 = strpos($query, "(");
		$pos_2 = strpos($query, ")");
		if($pos_1===false ||$pos_2===false){
			$this->warning("Syntaxerror079 unexpected '$query'");	
			return 0;
		}
		if($pos_3 = strpos($query, "<->"))
			$str_relation="<->";
		elseif($pos_3 = strpos($query, "->"))
			$str_relation="->";
		elseif($pos_3 = strpos($query, "<-"))
			$str_relation="<-";
		elseif($pos_3 = strpos($query, "PATH"))
			$str_relation="PATH";
		else
			$str_relation="";
		if(empty($str_relation))
			$form = 0;
		else
			$form = 1;
		$str_relation=trim($str_relation);
		if($str_relation)
			$method=trim(substr($query,0,$pos_3)); 
		else
			$method=trim(substr($query,0,$pos_1)); 
		$pos_1+=1;
		$str_options=trim(substr($query,$pos_1,$pos_2-$pos_1)); 
		$pos_2+=1;
		if($pos_2== strlen($query))
			$str_view="";		
		else {
			$str_view=trim(substr($query,$pos_2,strlen($query)));
			if(!strpos($str_view, ".")){
				$this->warning("Syntaxerror 080 unexpected 'VIEW' in '$query'");	
				return 0;
			}
		}
		
		/*echo  "METHOD-".$method."<BR>";
		//echo  "FROM-	".$str_from."<BR>";
		echo  "EXCEPTION-	".$str_options."<BR>";
		//echo  "WHERE-		".$str_where."<BR>";
		echo  "VIEW-		".$str_view."<BR>";
		echo  "RELATION-		".$str_relation."<BR>";
		echo  "FORM-		".$form."<BR>";*/
		if(!$form){
			if($method=="SELECT"||$method=="INSERT"||$method=="UPSERT"||$method=="UPDATE"||$method=="DELETE"||$method=="INCREMENT"||$method=="DECREMENT"){
				if(!$arr_options=$this->parser_options($str_options,$method,FALSE)){
					//$this->warning("Syntaxerror 081 unexpected  '$query'");	
					return 0;
				}		
				$buf=array();
				foreach($arr_options as $nn=>$arr_token){
					foreach($arr_token as $control=>$arr_options){
						$control=trim($control);
						if($control=="ID"){
							$buf["ID"]=$this->id_object;
							continue;
						}
						if($this->is_component($control)){//is_component_extends($control)
							if(!empty($this->arr_control_object[$control])){
								$obj=$this->arr_control_object[$control];
								if(!$obj->is_method($method)){				
									$this->warning("Syntaxerror 082 unexpected '$method' into $query");	
									return 0;//continue;
								}else{
									if($method=="SELECT"){
										$obj->$method($arr_options,$buf);
									}else
	
										$rez=$obj->$method($arr_options);
								}
							}else
								continue;
						}					
					}
				}
				if($method=="SELECT")
					return $buf;
				return $rez;
			}else{
				//$this->warning("Syntaxerror 083 unexpected  '$method'  in '$query'");	
				return 0;
			}
		}else{	
			$buf=array();
			if($str_relation!=="->"&& $str_relation!=="<-" && $str_relation!=="PATH" && $str_relation!=="<->"){
				$this->warning("Syntaxerror 084 unexpected 'CURRENT NODE' in '$query'");	
				return 0;
			}
			if($method !== "SELECT"){
				$this->warning("Syntaxerror 085 unexpected   '$method'  in '$query'");	
				return 0;
			}
			$arr_rez=array();
			if(empty($this->arr_extends_class)){
				$this->warning("Syntaxerror 086 unexpected 'CURRENT NODE' in '$query'");	
				return 0;
			}
	
			$arr_rez=array();
			foreach($this->arr_extends_class as $key => $arr_extends){
				if(empty($arr_extends[0]))
					continue;
				if(!$name_interface_item=$arr_extends[4])
						return 0;
				if( !empty($str_view)  &&   $name_interface_item!==$str_view)
						continue;
				if(!$dir_item=trim($arr_extends[1]))
						return 0;
				if(!$class=new db_class($name_interface_item,$this->prefix_db))
					return 0;
				if(!$this->parser_options($str_options,$method,$class)){ 
					continue;
				}
				if(($dir_item=="DIRECT" || $dir_item=="FEEDBACK" ) && $str_relation=="<->"){
					$mode="RELATION";
					$str_fun=strtolower("_get_relation_".$dir_item);
					$obj=new db_relation($this->prefix_db);
				}elseif(($dir_item=="PARENT" || $dir_item=="CHILDREN" ) && ($str_relation=="->" || $str_relation=="<-" || $str_relation=="PATH")){
					$mode="POINTER";
					if($dir_item=="CHILDREN"  && $str_relation=="PATH")
						$str_fun=strtolower("_get_pointer_PATH");
					elseif($dir_item=="CHILDREN"  && $str_relation=="->")
						$str_fun=strtolower("_get_pointer_CHILDREN");
					elseif($dir_item=="PARENT"  && $str_relation=="<-")
						$str_fun=strtolower("_get_pointer_PARENT");
					else
						continue;

					$obj=new db_category($this->prefix_db);
				}else
				 	continue;
				if($mode=="RELATION"){
					if(!$id_class_item=$arr_extends[3])
						return 0;						
					if(empty($arr_extends[7])||!$id_object_item=$arr_extends[7])
						return 0;
					if(!$arr_id_obj=$obj->$str_fun($id_object_item,$id_class_item))										
						continue;
				}
				if($mode=="POINTER"){
					if(!$name_class_item=$this->get_name_class())
						return 0;
					if(!$id_object_item=$this->get_id_object())
						return 0;
					if(!$arr_id_obj=$obj->$str_fun($id_object_item,$name_class_item))										
						continue;
				}

				if(empty($arr_id_obj ))
					return array();				
				$str_tmp="";
				$str_query= "SELECT $str_options FROM $name_interface_item WHERE";

				for($i=0;$i<count($arr_id_obj);$i++){	
					if($i==0)
						$str_tmp.=" ID= '".$arr_id_obj[$i]."'";
					else
						$str_tmp.=" || ID= '".$arr_id_obj[$i]."'";
				}
				$str_query.=$str_tmp;
				$arr_rez[$name_interface_item]=$class->select_query($str_query);; 
			}
			if(empty($arr_rez ))
				return array();
			return $arr_rez;
		
		}
	}//END
	
	/*#################################################
	/*#################################################*/		
	function parser_where($str_where,$class,&$arr_where){//22   
	/*#################################################*/		
	/*#################################################*/
		//$arr_where=array();
		if($str_where){	
			$pattern='/[&]{1,2}|[\|]{1,2}/';
			$arr_split_where=preg_split($pattern,$str_where);
			foreach($arr_split_where as $n=>$tok_where){
				$pattern='/[=]|[!=]|[<]|[>]|[<=]|[>=]/';
				$arr_token_tmp=preg_split($pattern,$tok_where);
				if(empty($arr_token_tmp[0])|| empty($arr_token_tmp[1]))
					return 0;
				$full_name_token=trim($arr_token_tmp[0]); //if( !$full_name_token      )->return 0;
				$value=trim($arr_token_tmp[1]);
				if(($pos_1 = strpos($full_name_token, "["))&&($pos_2 = strpos($full_name_token, "]"))){
					$pos_1+=1;
					$key=trim(substr($full_name_token,$pos_1,$pos_2-$pos_1));
					$control=trim(substr($full_name_token,0,$pos_1-1));
				}else{
					$key="";
					$control=trim($full_name_token);
				}
				$control_options[]=trim($control); //
				if($control=="ID")
					$component="";
				else{
					if(empty($class->arr_extends_class[$control])) {
						return 0;
						}
					$arr_info=$class->arr_extends_class[$control] ; 
					$component=$arr_info[1];
				}
			$arr_split_where_n=trim($arr_split_where[$n]);
				$arr_where[]=array($control=>array(0=>$value,1=>$key,2=>$component,3=>$arr_split_where_n)); //0-value, 1-key, 2-type, 3-full_name_token;
			}
			$control_class=$class->get_control_class();
			$arr_tmp=array("ID");
			$control_class_tmp = array_merge($control_class, $arr_tmp);
			if(!empty(array_diff($control_options,$control_class_tmp)))
			return 0;//ERROR				
		}
			return $arr_where;
	}
		/////////////////////////////////////////////////////////////////////////////////
		//																		       //
		//	SELECT|INSERT|REPLACE|UPDATE|DELETE ID|[name_component[key] .,..]  		   //
		//		FROM interface_name 											       //
		//		[WHERE ID|name_component {=|or|!=|<|>|<=|>=} 'value' [&&]|[||] .,..]   //
		//		[LIMIT [offset,] n]
		//
		// 	//SELECT ID,ISBN FROM BOOK.BOOK WHERE Title ='Код бытия/The Genesis Code/ ';;											                           //
		//////////////////////////////////////////////////////////////////////////////////

	function select_query($query){	
		$str_exp="";
		if(strpos($query,"INSERT ")===false || strpos($query,"FROM")===false)
			if(strpos($query,"UPSERT ")===false || strpos($query,"FROM")===false)
				if(strpos($query,"UPDATE ")===false || strpos($query,"FROM")===false)
					if(strpos($query,"DELETE ")===false || strpos($query,"FROM")===false) 
						if(strpos($query,"SELECT ")===false || strpos($query,"FROM")===false){
							$this->warning("Syntaxerror 087 unexpected  $query");
							return 0;
						}
						else{
							$method="SELECT";
							$pos_1=strpos($query,"SELECT ")+7;
						}								
					else{
						$method="DELETE";
						$pos_1=strpos($query,"DELETE ")+7;
					}
				else{
					$method="UPDATE";
					$pos_1=strpos($query,"UPDATE ")+7;
				}	
			else{
				$method="UPSERT";
				$pos_1=strpos($query,"UPSERT ")+7;	
			}
		else{
			$method="INSERT";
			$pos_1=strpos($query,"INSERT ")+7;	
		}
		
		$pos_2=strpos($query,"FROM");//THIS
		$pos_3=$pos_2+4;
		if($pos_1===false || $pos_2===false){
			$this->warning("Syntaxerror 088 unexpected $query");	
			return 0;
		}
		$str_exp = substr($query,$pos_1,$pos_2-$pos_1);			
		//	UPDATE[1]  [2]THIS[3]  [4]WHERE[5]  [6]LIMIT[7]   [strlen] 
		//	UPDATE[1]  [2]FROM[3]  [4]WHERE[5]  [6]LIMIT[7]   [strlen] 		
		$pos_4=strpos($query,"WHERE ");
		$pos_5=$pos_4+6;
		$pos_6=strpos($query,"LIMIT ");
		$pos_7=$pos_6+6;			
		if($pos_4===false){
			$str_from =trim(substr($query,$pos_3,strlen($query)-$pos_3));	
			$str_where ="";	
			if($pos_6===false)
				$str_limit ="";
			else
				$str_limit =trim(substr($query,$pos_7,strlen($query)-$pos_7));			
		}else{
				$str_from =trim(substr($query,$pos_3,$pos_4-$pos_3));
				if($pos_6===false){
					$str_where =trim(substr($query,$pos_5,strlen($query)-$pos_5));
					$str_limit ="";
				}else{
					$str_where =trim(substr($query,$pos_5,$pos_6-$pos_5));
					$str_limit =trim(substr($query,$pos_7,strlen($query)-$pos_7));
				}
		}
		
		if(empty($str_from)){
			$this->warning("Syntaxerror 089 unexpected 'FROM' into $query");	
			return 0;
		}
				//DEBUG
		/*echo  "METHOD-".$method."<BR>";
		echo  "FROM-	".$str_from."<BR>";
		echo  "EXCEPTION-	".$str_exp."<BR>";
		echo  "WHERE-		".$str_where."<BR>";
		echo  "LIMIT-		".$str_limit."<BR>";*/
		$str_exp=trim($str_exp);
		if($str_exp=="*" && $method=="DELETE"){
			$this->warning("Syntaxerror 090 unexpected '*' into $query");	
			return 0;
		}
		$str_exp=trim($str_exp);
		if(!$class=new db_class($str_from,$this->prefix_db)){
			$this->warning("Syntaxerror 091 unexpected $query");	
			return 0;
		}
		
		if(!$arr_options=$class->parser_options($str_exp,$method,$class)){
			$this->warning("Syntaxerror 092 unexpected '$query'");	
		}
		$str_condition_query=$str_exp;
		$str_exp='ID';
		$arr_options=array() ;
		$arr_options[]=array ( 'ID' => array() ) ;

		$where_keys=array();	$i=0;
		$from_keys=array();  $j=0;
		$where_keys["CLASS_OBJECT.ID_CLASS=".$class->get_id_class()]=$i; $i+=1;
		$from_keys["CLASS_OBJECT AS CLASS_OBJECT"]=$j; $j+=1;
		
										// WHERE
		$where_replace="";
		$arr_where=array();
		if(!empty($str_where)){
			$str_where=trim($str_where);
			if(!$class->parser_where($str_where,$class,$arr_where)){//
				$this->warning("Syntaxerror 093 unexpected  '$query'");	
				return 0;
			}
			foreach($arr_where as $n=>$arr_tmp){
				foreach($arr_tmp as $control=>$arr_wher){
					if($arr_wher[1])
						$comp=$arr_wher[1]."_".$arr_wher[2];
					else
						$comp=$arr_wher[2];
					if($control=='ID'){
						$str_replace=trim("CLASS_OBJECT.ID_OBJECT=".$arr_wher[0]);
						$str_replace="(".$str_replace.")";
					}else{
			
						$str_replace=trim($comp."_".$control.".VALUE=".$arr_wher[0]);
						$str_replace="(".$str_replace.")";
						$where_keys[$comp."_".$control.".CONTROL='".$control."'"]=$i; $i+=1;
						$where_keys[$comp."_".$control.".ID_OBJECT=CLASS_OBJECT.ID_OBJECT "]=$i; $i+=1;
						if(!empty($arr_wher[1]))
							$where_keys[$comp."_".$control.".KEY_ARRAY="."'".$arr_wher[1]."'"]=$i; $i+=1;
					}
					$replace[]=$str_replace;
					$search[]= $arr_wher[3];
				}
			}
			$subject =$str_where;
			$where_replace=str_replace($search, $replace, $subject);
			$search=array(") || (", ") && (");
			$replace=array(" || ", " && ");
			$subject=  $where_replace; 
			$where_replace=str_replace($search, $replace, $subject);
			$where_replace=" &&".$where_replace;
		}															
															// EXCEPTION
			$condition="";
			$str_condition_where="";
			foreach($arr_options as $n=>$arr_tmp){
				foreach($arr_tmp as $control=>$arr_opti){
					
					if($control=='ID'){
						$condition=" CLASS_OBJECT.ID_OBJECT AS ID,";
						continue;
					}

					if(!empty($arr_opti[1]))
						$comp=$arr_opti[1]."_".$arr_opti[2];
					else
						$comp=$arr_opti[2];
						
					if($method=="INSERT" || $method=="UPDATE" || $method=="UPSERT" )//|| $method=="INSERT"  
						$condition.=$comp."_".$control.".VALUE = " .$arr_opti[0].",";
					if($method=="SELECT")
						if(empty($arr_opti[1])     )
							$condition.=$comp."_".$control.".VALUE  AS ".$control.",";
						else
							$condition.=$comp."_".$control.".VALUE  AS ".$control."_".$arr_opti[1].",";
							
					$where_keys[$comp."_".$control.".CONTROL = '".$control."'"]=$i; $i+=1;
					$where_keys["CLASS_OBJECT.ID_OBJECT=".$comp."_".$control.".ID_OBJECT "]=$i; $i+=1;
					if(!empty($arr_opti[1]))
						$where_keys[$comp."_".$control.".KEY_ARRAY='".$arr_opti[1]."'"]=$i; $i+=1;
				}
			}
			$condition=substr($condition,0,strlen($condition)-1);
															// FROM
		if(!$arr_from=array_merge_recursive($arr_options,$arr_where )){
				$this->warning("Syntaxerror 094 unexpected  '$query'");	
				return 0;
		}
		foreach($arr_from as $n=>$arr_tmp){
			foreach($arr_tmp as $control=>$arr_frm){
				if($control=='ID')
					continue;
				if(!empty($arr_frm[1]))
					$comp=$arr_frm[1]."_".$arr_frm[2];
				else
					$comp=$arr_frm[2];
				$from_keys[$arr_frm[2]." AS ".$comp."_".$control]=$j; $j+=1; 
			}
		}
		$from="";
		foreach($from_keys as $from_conthol=>$nn){
			$from.=$from_conthol.",";
		}
		$from=substr($from,0,strlen($from)-1);
		$tok_form=explode(",",$from);
		$from="";
		for($i=0;$i<count($tok_form);$i++){	
			$token_form=$this->prefix_db.$tok_form[$i].",";
			$from.=$token_form;
		}
		$from=substr($from,0,strlen($from)-1);
		$where="";
		foreach($where_keys as $where_conthol=>$nn){
			if($nn==0)
				$where.=$where_conthol;
			else
				$where.=" && ".$where_conthol;
		}
		$where=$where.$where_replace;

														// METHOD
			if(!$table_join=$class->get_table_join($from,$condition,$where,"",$str_limit)){
				$this->warning("Syntaxerror 095 unexpected   '$query'");	
				return 0;
			}
			$arr_temp=array();
			$nn=0;
			while($myrow = mysqli_fetch_array($table_join)){
				$str_tmp='';
				foreach($myrow as $control=>$value){
					if(!is_numeric($control)) {
						$arr_temp[$nn][$control] = $value;
					}
				}
				$nn+=1;
			}
		$str_condition_query=trim($str_condition_query);
		if($str_condition_query=="ID" && $method=="SELECT")
			return $arr_temp;

		if(!$class->set_interface($str_from)){
			$this->warning("Syntaxerror 096 unexpected '$query'");	
			return 0;
		}
		$arrr_summ=array(); 
		foreach($arr_temp as $nn=>$arr_token){
			foreach($arr_token as $id=>$id_object){
				if(!$class->set_object($id_object)){
					$this->warning("Syntaxerror 097 unexpected '$id_object' in '$query'");	
					return 0;
				}
				$query=$method."(".$str_condition_query.")";
				$arrr_summ[]=$class->db_component_query($query);
				

			}
		}
		if($method=="SELECT"){
			return $arrr_summ;
		}
		return 1;
	}//END CLASS
	
		//////////////////////////////////////////////////////////////////////////////////////
		//								DB_CLASS_QUERY										//
		//	SELECT|INSERT|REPLACE|UPDATE|DELETE [ID|name_component[key] .,..] THIS 			//
		//		[WHERE ID|name_component {=|or|!=|<|>|<=|>=|LIKE} 'value' [&&]|[||] .,..]	//
		//		[LIMIT [offset,] n] 														//
		//////////////////////////////////////////////////////////////////////////////////////		
	function db_class_query($query){
		$str_exp="";
		if(strpos($query,"INSERT ")===false || strpos($query,"THIS")===false)
			if(strpos($query,"REPLACE ")===false || strpos($query,"THIS")===false)
				if(strpos($query,"UPDATE ")===false || strpos($query,"THIS")===false)
					if(strpos($query,"DELETE ")===false || strpos($query,"THIS")===false) 
						if(strpos($query,"SELECT ")===false || strpos($query,"THIS")===false){
							$this->warning("Syntaxerror 098 unexpected '$query'");	
							return 0;
						}else{
							$method="SELECT";
							$pos_1=strpos($query,"SELECT ")+7;
						}								
					else{
						$method="DELETE";
						$pos_1=strpos($query,"DELETE ")+7;
					}
				else{
					$method="UPDATE";
					$pos_1=strpos($query,"UPDATE ")+7;
				}	
			else{
				$method="REPLACE";
				$pos_1=strpos($query,"REPLACE ")+8;	
			}
		else{
			$method="INSERT";
			$pos_1=strpos($query,"INSERT ")+7;	
		}
		
			$pos_2=strpos($query,"THIS");
			$pos_3=$pos_2+4;
			if($pos_1===false || $pos_2===false){
				$this->warning("Syntaxerror 099 unexpected '$query'");	
				return 0;
			}
			$str_exp = substr($query,$pos_1,$pos_2-$pos_1);			
			//	UPDATE[1]  [2]FROM THIS[3]  [4]WHERE[5]  [6]LIMIT[7]   [strlen] 		
			$pos_4=strpos($query," WHERE ");
			$pos_5=$pos_4+7;
			$pos_6=strpos($query," LIMIT ");
			$pos_7=$pos_6+7;			
			if($pos_4===false){
				$str_where ="";	
				if($pos_6===false)
					$str_limit ="";
				else
					$str_limit =substr($query,$pos_7,strlen($query)-$pos_7);			
			}else{
				if($pos_6===false){
					$str_where =substr($query,$pos_5,strlen($query)-$pos_5);
					$str_limit ="";
				}else{
					$str_where =substr($query,$pos_5,$pos_6-$pos_5);
					$str_limit =substr($query,$pos_7,strlen($query)-$pos_7);
				}
			}
		$str_from="";//"
		$str_exp=trim($str_exp);
		$str_where=trim($str_where);
		return $this->sub_db_class_query($method,$str_exp,$str_where,$str_limit);
	}
	function sub_db_class_query($method,$str_exp,$str_where,$str_limit=""){
		$arr_extends =$this->get_extends_class();
		if(empty($arr_extends)){
			return 0;
		}
		$arr_from=array();
		foreach($arr_extends as $name_com =>$arr){
			if(empty($arr))
				continue;
			$name_com=trim($name_com);
			$arr_from[$name_com]=$arr[1];//name class
			$arr_type[$name_com]=$arr[2];//STATIC|0(DUNAMIC)			
		}
		$arr_token_exp=array();
		if($str_exp && $str_exp!="ID"){						
			if($method=="INSERT" || $method=="REPLACE" || $method=="UPDATE" ){
				$pattern='/[\'][,]|[\'][[:space:]][\,]|\'$/';
				$arr_split_exp=preg_split($pattern,$str_exp);
				foreach($arr_split_exp as $n=>$tok_exp){
					if(!$tok_exp)
						continue;
					$tok_exp.="'";				
					$arr_token_tmp=preg_split('/[=][\']|[=][[:space:]][\']/',$tok_exp);
					$full_name_token=trim($arr_token_tmp[0]);
					$value_token="'".trim($arr_token_tmp[1]);
					$arr_token_exp[$full_name_token]=$value_token;
				}
			}else{
				$pattern='/[\,]/';
				$arr_split_exp=preg_split($pattern,$str_exp);
				foreach($arr_split_exp as $n=>$full_name_token){
					$full_name_token=trim($full_name_token);
					$arr_token_exp[$full_name_token]="";
				}
			}
		}
		$pattern='/[&]{1,2}|[\|]{1,2}/';
		$arr_split_where=preg_split($pattern,$str_where);
	foreach($arr_split_where as $n=>$tok_where_item){
			$pattern='/[\(]|[\)]/'; // ()
			$arr_split_where_item=preg_split($pattern,$tok_where_item);// [0] =:: name ,[1] =:: val .,....
			if(!empty($arr_split_where_item) && count($arr_split_where_item)>=2){
				$pattern='/[\,]/'; // ,
				$arr_token_value=preg_split($pattern,$arr_split_where_item[1]);//[0] =:: n ,[1] =:: val
				$str_temp_where="";//"(";
				foreach($arr_token_value as $n=>$value){
					$str_temp_where.=" $arr_split_where_item[0]=$value ||";;
				}
				$str_temp_where =substr($str_temp_where,0,strlen($str_temp_where)-2);
				$pattern=$str_temp_where;
				$str_where=str_replace($tok_where_item,$pattern,$str_where);
			}
		}	
		$arr_token_where=array();
		if($str_where){	
			$pattern='/[&]{1,2}|[\|]{1,2}/';
			$arr_split_where=preg_split($pattern,$str_where);
			foreach($arr_split_where as $n=>$tok_where){
				$pattern='/[=]|[!=]|[<]|[>]|[<=]|[>=]/';
				$arr_token_tmp=preg_split($pattern,$tok_where);
				$full_name_token=trim($arr_token_tmp[0]);
				$value_token=trim($arr_token_tmp[1]);
				$arr_token_where[$full_name_token]=$value_token;
			}
		}	
		$id_object=0;
		if(!empty($arr_token_where["ID"])){
			$id_object=$arr_token_where["ID"];
			$str_where="";
			$arr_token_where=array();
		}

		$arr_token=$arr_token_where+$arr_token_exp;//
		$arr_from_alias=array();
		$arr_full_name=array();
		foreach($arr_token as $full_name =>$val){
			$pattern1='/\[|\]/';//|\,							
			$arr_split=preg_split($pattern1,$full_name);
			$arr_split[0]=trim($arr_split[0]);
			if($arr_split[0]=="ID")/////05_04_09
				continue;
			if(isset($arr_from[$arr_split[0]])){/////05_04_09
				$name=$arr_split[0];
				if(empty($arr_from[$name]))/////05_04_09
				 	continue;
				$arr_from_alias[$name]["full_name"]=$full_name;
				$arr_from_alias[$name]["table"]=$this->prefix_table.$arr_from[$name];
				$arr_from_alias[$name]["alias"]=$arr_from[$name]."_".$name;
				$arr_from_alias[$name]["statc"]=$arr_type[$name];//STATIC|0
			}else
				//continue;
				return 0;/////05_04_09
			if(isset($arr_split[1])){
				$key_array=$arr_split[1];
				$arr_from_alias[$name]["alias"].="_".$key_array;
				$arr_from_alias[$name]["key"]=$key_array;
			}
			$arr_full_name[$full_name]=$name;
	
		}	
		if($method=="SELECT" || $method=="UPDATE")
			
			$arr_token=$arr_token_where+$arr_token_exp;
		else
			$arr_token=$arr_token_where;
						// FROM	
		$str_from=$this->prefix_table."CLASS_OBJECT AS CLASS_OBJECT";//_PREFIX
		foreach($arr_token as $full_name =>$tmp){
			if($full_name=="ID")
				continue;
			$arr=$arr_from_alias[$arr_full_name[$full_name]];
			if(!empty($arr["table"]))
				$str_from.=",".$arr["table"]." AS ".$arr["alias"];		
		}			
				//WHERE
		if(!$id_class=$this->get_id_class())
			return 0;								
		$str_where_temp="CLASS_OBJECT.ID_CLASS=$id_class" ;
		foreach($arr_token as $full_name =>$tmp){
			if($full_name=="ID")
				continue;		
			$name=$arr_full_name[$full_name];
			$arr=$arr_from_alias[$name];
			if(empty($arr))
				continue;
			$str_where_temp.=" && ".$arr["alias"].".CONTROL='$name'";
			if($arr["statc"]=="STATIC")
				$str_where_temp.=" && ".$arr["alias"].".ID_OBJECT=CLASS_OBJECT.ID_CLASS"; 
			else
				$str_where_temp.=" && ".$arr["alias"].".ID_OBJECT=CLASS_OBJECT.ID_OBJECT"; 			
			if(isset($arr["key"]))
				$str_where_temp.=" && ".$arr["alias"].".KEY_ARRAY='".$arr["key"]."'";		
		}			
		if($str_where && !$id_object){
			foreach($arr_token as $full_name =>$tmp){
				if($full_name=="ID")
					continue;
				$arr=$arr_from_alias[$arr_full_name[$full_name]];;		
				$pattern=$arr["alias"].".VALUE";
				$str_where=str_replace($full_name,$pattern,$str_where);
			}
		}		
		if($str_where)
			$str_where=$str_where_temp." && ($str_where)";
		else
			$str_where=$str_where_temp;
		if($id_object && $str_where)
			$str_where.=" && CLASS_OBJECT.ID_OBJECT=$id_object" ;
		elseif($id_object && !$str_where)	
			$str_where.=" CLASS_OBJECT.ID_OBJECT=$id_object" ;
		$str_condition=$str_exp;		
		if($method=="SELECT" && $str_exp && $str_exp!="ID"){
			foreach($arr_from_alias as $name =>$arr){
				$pattern=$arr["alias"].".VALUE AS ".$name;			
				$str_condition=str_replace($arr["full_name"],$pattern,$str_condition);
			}
			$pattern="CLASS_OBJECT.ID_OBJECT AS ID";			
			$str_condition=str_replace("ID",$pattern,$str_condition);
		}else{
			$str_condition=" CLASS_OBJECT.ID_OBJECT AS ID";
		}
		$str_group_by="";
		$str_condition=" DISTINCT ".$str_condition;
		if(!$table_join=$this->get_table_join($str_from,$str_condition,$str_where,$str_group_by,$str_limit))
			return 0;

		$this->empty_anser();
		while($myrow = mysqli_fetch_array($table_join)){
			foreach($myrow as $control=>$value){
				if(is_string($control)) 
					$arr_temp[$control]=$value;
			}
			if(!empty($arr_temp))
				$this->arr_anser[]=$arr_temp;
		}
		if($method=="SELECT"){				
			return $this->arr_anser;
		}elseif($str_exp && $str_exp!="ID"){//////////
						// EXP	
			foreach($this->arr_anser as $n=>$arr_object){
				$id_object=$arr_object["ID"];
				foreach($arr_token_exp as $full_name=>$value){				
						//continue;;		
					$name=$arr_full_name[$full_name];
								// TABLE						
					$table=" ".$arr_from_alias[$name]["table"]." ";
								 //SET or EXP
					$str_set=" ";
					if($value)
						$str_set=" VALUE=$value ";
					elseif($method!="DELETE")
						continue;			
					if($method=="INSERT" || $method=="REPLACE"){
						if($arr_from_alias[$name]["statc"]=="STATIC")
							$str_set.=",ID_OBJECT='$id_class'"; 
						else
							$str_set.=",ID_OBJECT='$id_object'"; 			
						$str_set.=",CONTROL='$name'";
						if(!empty($arr_from_alias[$name]["key"]))
							$str_set.=",KEY_ARRAY='".$arr_from_alias[$name]["key"]."' ";
					}				
					if($method=="INSERT" ){									
						if(!$this->ins_table($table,$str_set)){
							$this->warning("Syntaxerror 0100 unexpected '$method'");	
							return 0;
						}
					}elseif($method=="UPDATE"|| $method=="DELETE" || $method=="REPLACE"){
								// WHERE
						$str_where=" ";		
						if($arr_from_alias[$name]["statc"]=="STATIC")
							$str_where.="ID_OBJECT='$id_class'"; 
						else
							$str_where.="ID_OBJECT='$id_object'"; 			
						$str_where.=" && CONTROL='$name'";
						if(!empty($arr_from_alias[$name]["key"]))
							$str_where.=" && KEY_ARRAY='".$arr_from_alias[$name]["key"]."'";
						if($method=="UPDATE"){
							if(!$this->update_table($table,$str_set,$str_where))
								$this->warning("Syntaxerror 0101 unexpected '$method'");	
								//return 0;
						}elseif($method=="REPLACE"){
							if(!$this->replace_table($table,$str_set,$str_where))
								$this->warning("Syntaxerror 0102 unexpected '$method'");	
						}elseif($method=="DELETE"){						
							if(!$this->del_table($table,$str_where))
								$this->warning("Syntaxerror 0103 unexpected '$method'");	;
						}
					}
				}						
			}
			return 1;			
		}
		return 0;			
	}	
 /*###########################################################################		
	SELECT select_list THIS {WITH relationship_class} |PARENT |CHILDREN |PATH ;
		Sample:
			SELECT WITH(Price,PRODUCT_Price  SHOPPING) ;;//SELECT Price,PRODUCT_Price THIS WITH SHOPPING ;;
			SELECT PARENT(Code);;//SELECT Code THIS PARENT ;;
			SELECT CHILDREN(Code);;//SELECT Code THIS CHILDREN ;;
			SELECT PATH(Code)  ;;//SELECT Code THIS PATH ;;
	###########################################################################*/
	//SELECT 1_2 THIS WITH 3_4 THIS
	function pars_query_relationship($query){
		$pos_1=strpos($query,"SELECT ")+7;
		$pos_2=strpos($query," THIS ");	
		$pos_3=0;
		$mode="";
		if(strpos($query," WITH ")===false)
			if($pos_3=strpos($query," PATH")===false)
				if($pos_3=strpos($query," PARENT")===false)
					if($pos_3=strpos($query," CHILDREN")===false){
						$this->warning("Syntaxerror 0104 unexpected '$query'");	
						return 0;
					}else
						$mode="CHILDREN";
				else
					$mode="PARENT";
			else
				$mode="PATH";				
		else{
			$mode="WITH";
			$pos_3=strpos($query," WITH ")+6;			
		}
		$str_exp=trim(substr($query,$pos_1,$pos_2-$pos_1));
		if($mode=="WITH")
			$str_with=trim(substr($query,$pos_3,strlen($query)-$pos_3));
		else
			$str_with="";
		$arr_pars=array("EXP"=>$str_exp,"NAME"=>$str_with,"MODE"=>$mode);
		return $arr_pars;
	}
	
 	function set_class_relationship($name_class,$id_object=0){
			if(empty($name_class)) 
				return 0;
			$name_class=trim($name_class);
			$arr_name=$this->pars_name_class($name_class);
			$name_class=trim($arr_name[0]);
			$name_interface=trim($arr_name[1]);
			if($name_interface)
				$name_interface="$name_class.$name_interface";
			else
				$name_interface=$name_class;
				if(!$class=new db_class($name_class,$this->prefix_db)){
					$this->warning("Syntaxerror 0105 unexpected '$name_class'");	
					return 0;
				}							
			if($name_interface!=$class->interface_name){
				if(!$class->set_interface($name_interface))
						return 0;
				if($id_object)
					if(!$class->set_object($id_object))
						return 0;
			}else{
				if($id_object && $class->id_object!=$id_object)
					if(!$class->set_object($id_object))
						return 0;
			}
			return $class;
	}
	/*###################################################
									sub function from dbol_db_builder
										builder RELATIONSHIP
   #####################################################
 		SELECT RELATIONSHIP select_list FROM relationship_class ->|<-|WITH 
			name class|name interface WHERE name_component='value';   				
		INSERT|DELETE RELATIONSHIP FROM  
			THIS|name class|name interface WHERE name_component='value'
			WITH 
			relationship_class WHERE name_component(list_values);
		INSERT|DELETE RELATIONSHIP FROM  
			THIS|name class|name interface WHERE name_component
			JOIN 
			|relationship_class WHERE name_component;
		INSERT RELATIONSHIP FROM  
			THIS|name class|name interface WHERE name_component='value'
			 ->|<- 
			NULL|relationship_class WHERE name_component(list_values)}"""
	###################################################*/	
	/*function pars_class_relationship_old($query){
		$pos_1=strpos($query,"RELATIONSHIP ")+13;		
		$pos_2=strpos($query,"FROM");
		$pos_3=$pos_2+4;
		$pos_6=strpos($query,"WHERE");
		$pos_7=$pos_6+5;		
		if(strpos($query,"WITH")===false)
			if(strpos($query,"->")===false)
				if(strpos($query,"<-")===false){
					$this->warning("Syntaxerror 0106 unexpected '$query'");
					return 0;		
				}else{
					$pos_4=strpos($query,"<-");
					$pos_5=$pos_4+2;
					$dir=trim(substr($query,$pos_4,$pos_5-$pos_4));
				}
			else{
				$pos_4=strpos($query,"->");
				$pos_5=$pos_4+2;
				$dir=trim(substr($query,$pos_4,$pos_5-$pos_4));
			}
		else{
			$pos_4=strpos($query,"WITH");
			$pos_5=$pos_4+4;
			$dir="";
		}
		$str_exp=trim(substr($query,$pos_1,$pos_2-$pos_1));
		if(!$name_class_relationship=trim(substr($query,$pos_3,$pos_4-$pos_3))){
			$this->warning("Syntaxerror 0107 unexpected '$query'");
			return 0;
		}	
		$name_class=trim(substr($query,$pos_5,$pos_6-$pos_5));	
		if($name_class!="THIS"){
			$this->warning("Syntaxerror 0108 unexpected '$query'");
			return 0;
		}		
		if(!$str_where=trim(substr($query,$pos_7,strlen($query)-$pos_7))){
			$this->warning("Syntaxerror 0109 unexpected '$query'");
			return 0;
		}
		$query_obj=	"SELECT ID THIS WHERE $str_where LIMIT 1";
		$arr_anser=$this->db_class_query($query_obj);
		$myrow = $this->fetch_row($arr_anser);
		$id_object=$myrow["ID"];
		$this->set_object($id_object);				
		$arr_pars=array("EXP"=>$str_exp,"NAME"=>$name_class_relationship,"DIR"=>$dir);
		return $arr_pars;
	}
 				//SELECT RELATIONSHIP 1_2 FROM 3_4 ->|<-|WITH 5_6 WHERE 7_8
	function query_select_relationship_old($query){
		if(!$arr_pars=$this->pars_class_relationship($query))
			return 0;

		$str_exp=$arr_pars["EXP"];
		$name_interface_relationship=$arr_pars["NAME"];
		$dir=$arr_pars["DIR"];
		return $this->_query_select_relationship($str_exp,$name_interface_relationship,$dir);
	}*/
	function _query_select_relationship($str_exp,$name_interface_relationship,$dir=""){
		if($name_interface_relationship=="THIS"){
			$name_interface_relationship=$this->get_name_interface();
		}
		if(!$class_relation=$this->set_class_relationship($name_interface_relationship))
			return 0;
		if(!$arr_id_obj=$this->select_relationship($name_interface_relationship,$dir))
			return 0;
		$arr_rez=array();
		for($i=0;$i<count($arr_id_obj);$i++){	
			if(!$class_relation->set_object($arr_id_obj[$i]))
				return 0;
			$query_relation="SELECT $str_exp THIS WHERE ID='$arr_id_obj[$i]'";//
			$arr_anser=$class_relation->db_class_query($query_relation);
			while($myrow = $class_relation->fetch_row($arr_anser)){
				$arr_rez[$name_interface_relationship.",".$arr_id_obj[$i]]=$myrow;
			}
		}
		$class_relation->arr_anser=$arr_rez;
		return $class_relation->arr_anser;		
	}			
	function _query_relation_join($str_expression,$method){
		$pattern='/<->/'; //JOIN
		$arr_where=preg_split($pattern,$str_expression);
		$pos_2 =strpos($arr_where[0],"WHERE")+6;
		$name_component=trim(substr($arr_where[0],$pos_2,strlen($arr_where[0])-$pos_2));	
				// id_object
		$query="SELECT ID THIS";
		if(!$arr_query=$this->db_class_query($query))
			return 0;
		
		$pattern='/WHERE/';
		$arr_where_relation=preg_split($pattern,$arr_where[1]);
		$name_class_relation=trim($arr_where_relation[0]);
		if($name_class_relation=="THIS"){
			return 0;
		}else{
			if(!$class_relation=new db_class($name_class_relation,$this->prefix_db))
				return 0;
		}
		$pattern="THIS";
		$arr_where[1]=str_replace($name_class_relation,$pattern,$arr_where[1]);			
		foreach($arr_query as $n => $arr){
			if(!$id_object=$arr["ID"])
				continue;
			$this->set_object($id_object);
			$query="SELECT($name_component)";
			$arr_rows=$this->class_query($query);
			list($name_item,$value)=each($arr_rows);
			if(!$value)
				continue;			
			$query="SELECT ID ".$arr_where[1]."='$value'";
			if(!$arr_query_relation=$class_relation->db_class_query($query) )
				continue;			
			foreach($arr_query_relation as $nn => $arr_relation){
				$id_object_relation=$arr_relation["ID"];
				if(!$id_object_relation)
					continue;
				$class_relation->set_object($id_object_relation);	
				if($method=="INSERT_RELATIONSHIP"){
					$this->create_relationship($class_relation);
				}elseif($method=="DELETE_RELATIONSHIP") {		
					$this->drop_relationship($class_relation);	
				}
			}		
		}
		return 1;
	}	
	function _query_relation_wich($str_expression,$method){
		$pattern='/<->/';//WITH
		$arr_where=preg_split($pattern,$str_expression);
		$str_where[0]="SELECT ID ".trim($arr_where[0]);		
				// id_object
		if(!$arr_query=$this->db_class_query($str_where[0]))
			return 0;

		list($n,$arr)=each($arr_query);
		$id_object=$arr["ID"];
		$this->set_object($id_object);		
				// id_object_relation
		$arr_where[1]=trim($arr_where[1]);
					// NULL
		if($arr_where[1]=="NULL" || $arr_where[1]=="'NULL'" ){		
			return 0;		
		}
		$str_where[1]="SELECT ID ".trim($arr_where[1]);			
		$pattern='/WHERE/';
		$arr_where_relation=preg_split($pattern,$arr_where[1]);
		$name_class_relation=trim($arr_where_relation[0]);
		if($name_class_relation=="THIS"){
			$name_class_relation=$this->get_name_interface();
			if(!$class_relation=new db_class($name_class_relation,$this->prefix_db))
				return 0;
			$arr_query=$class_relation->db_class_query($str_where[1]) ;
		}else{
			if(!$class_relation=new db_class($name_class_relation,$this->prefix_db))
				return 0;
			$pattern="THIS";
			$str_where[1]=str_replace($name_class_relation,$pattern,$str_where[1]);
			$arr_query=$class_relation->db_class_query($str_where[1]);
		}
		if(empty($arr_query))
			return 0;
		$arr_id_object_relation=array();
		foreach($arr_query as $n=>$arr){
			$id_object_relation=$arr["ID"];
			
			$class_relation->set_object($id_object_relation);

			if($method=="INSERT_RELATIONSHIP"){			
				$this->create_relationship($class_relation);
			}elseif($method=="DELETE_RELATIONSHIP") {		
				$this->drop_relationship($class_relation);	
			}		
		}
		return 1;	
	}
	function _class_query_pointer($str_expression,$method){	
		if(strpos($str_expression,"->")===false )
			if(strpos($str_expression,"<-")===false)
				return 0;
			else
				$img_direction="<-";
		else
			$img_direction="->";				
		$pattern='/->|<-/';
		$arr_where=preg_split($pattern,$str_expression);
		$str_where[0]="SELECT ID ".trim($arr_where[0]);
				// id_object
		if(!$arr_query=$this->db_class_query($str_where[0]))
			return 0;
		list($n,$arr)=each($arr_query);
		$id_object=$arr["ID"];
		$this->set_object($id_object);
				// id_object_pointer
		$arr_where[1]=trim($arr_where[1]);
					// NULL
		if($arr_where[1]=="NULL" || $arr_where[1]=="'NULL'" ){
			if($method=="INSERT_RELATIONSHIP") {			
				$this->create_pointer($img_direction,"NULL");
			}elseif($method=="DELETE_RELATIONSHIP") {		
				$this->drop_pointer($img_direction,"NULL");	
			}		
			return 1;		
		}
		$str_where[1]="SELECT ID ".trim($arr_where[1]);	
				
		$pattern='/WHERE/';
		$arr_where_pointer=preg_split($pattern,$arr_where[1]);
		$name_class_pointer=trim($arr_where_pointer[0]);
		if($name_class_pointer=="THIS"){
			$name_class_pointer=$this->get_name_interface();
			if(!$class_pointer=new db_class($name_class_pointer,$this->prefix_db))
				return 0;
			$arr_query=$class_pointer->db_class_query($str_where[1]) ;
		}else{
			if(!$class_pointer=new db_class($name_class_pointer,$this->prefix_db))
				return 0;
			$pattern="THIS";
			$str_where[1]=str_replace($name_class_pointer,$pattern,$str_where[1]);			
			$arr_query=$class_pointer->db_class_query($str_where[1]);
		}		
		if(!$arr_query)
			return 0;
		$arr_id_object_pointer=array();
		foreach($arr_query as $n=>$arr){
			$id_object_pointer=$arr["ID"];
			$class_pointer->set_object($id_object_pointer);	
			if($method=="INSERT_RELATIONSHIP") {	
				$this->create_pointer($img_direction,$class_pointer);
			}elseif($method=="DELETE_RELATIONSHIP") {		
				$this->drop_pointer($img_direction,$class_pointer);	
			}		
		}
		return 1;
	}
		/*###########################
				SET INTERFACE(name_interface) 
		###########################*/
	function query_set_interface($query){
		$pos_1 = strpos($query,"(")+1;
		$pos_2 = strpos($query, "(");		
		if(!$name_interface=trim(substr($query,$pos_1,$pos_2-$pos_1))){
			$this->warning("Syntaxerror 0110 unexpected '$query'");	
			return 0;
		}
		if(!$this->set_interface($name_interface)){
			$this->warning("Syntaxerror 0111 unexpected '$query'");	
			return 0;
		}
		return 1;	
	}	
	function set_interface($str_interface){//$str_interface::=name_class[.name_interface]
		$str_interface=trim($str_interface);
		if($str_interface==$this->interface_name)
			return 1;
		if(!$arr=$this->pars_name_class($str_interface)){
			return 0;
		}
		$name_class_this=$this->get_name_class();
		if($name_class_this!=$arr[0]){

			return 0;
		}
		$id_object=	$this->id_object;						
		if($this->interface_name != $str_interface){
			if(!$this->db_class($str_interface,$this->prefix_db)){

				return 0;
			}
		}
		if($id_object){				
				if(!$this->set_object($id_object)){

					return 0;
				}
		}						
		return 1;
	}	
	  /*######################################		
					DROP OBJECT(List ID)
		#####################################*/			

	function _drop_object($list_object){//class_
		$arr_token = explode(",", $list_object);
		foreach($arr_token as $key =>$id_object){
			$pattern="/'/";
			$id_object=trim(preg_replace($pattern," ",$id_object));
			$id_class=$this->get_id_class();
			if($this->is_object_to_class($id_object,$id_class)){
				if(!$this->drop_object($id_object))
					return 0;
			}else{
				$this->warning("Does not exist $id_object ! ");	
				//return 0;
			} 			
		}
		return 1;
	}
	function query_drop_object($query){// OBJECT (id_object ..,.);;	
		$pos_1 = strpos($query,"(")+1;
		$pos_2 = strpos($query, ")");		
		if(empty($list_object=trim(substr($query,$pos_1,$pos_2-$pos_1)))){
			$this->warning("Syntaxerror 0112 unexpected '$query'");	
			return 0;
		}
		if(!$this->_drop_object($list_object)){
			$this->warning("Syntaxerror 0112a unexpected '$query'");	
			return  0;
		}
		return  1;
	}
		  /*######################################		
					EMPTY OBJECT(ID)
		#####################################*/			

	function _empty_object(){
		$this->id_object=0;
		return 1;			 
	}
	function query_empty_object(){
		return $this->_empty_object();
	}

	function query_show_object($query){//SHOW OBJECT (id_object);;
		$pos_1 = strpos($query,"(")+1;
		$pos_2 = strpos($query, ")");		
		if(!$id_object=trim(substr($query,$pos_1,$pos_2-$pos_1))){
			$this->warning("Syntaxerror 0113 unexpected '$query'");	
			return 0;
		}
		$pattern="/'/";
		$id_object=trim(preg_replace($pattern," ",$id_object));
		print_r($this->show_object($id_object));
	}
	  /*######################################		
				ERASE OBJECT(ID)
		#####################################*/			

	function query_erase_object($query){//ERASE OBJECT (id_object);;
		$pos_1 = strpos($query,"(")+1;
		$pos_2 = strpos($query, "(");		
		if(!$id_object=trim(substr($query,$pos_1,$pos_2-$pos_1))){
			$this->warning("Syntaxerror 0114 unexpected '$query'");	
			return 0;
		}
		$pattern="/'/";
		$id_object=trim(preg_replace($pattern," ",$id_object));
		print_r($this->erase_object($id_object));
	}
		/*#########################
							FETCH ROW(); 
		#########################*/									
	function fetch_row(&$arr_anser){
		if(empty($arr_anser))
			return 0;
		$current=array_shift($arr_anser);
		if(empty($current))
			return 0;
		return $current;
	}				
	
		/*#############################
					GET,EMPTY,SHOW ANSER(); 
		#############################*/									
	function get_anser(){
		if(empty($this->arr_anser))
			return 0;
		return $this->arr_anser;
	}
	function query_get_anser(){
		return $this->get_anser();
	}
	function empty_anser(){
		return $this->arr_anser=array();
	}
	function query_empty_anser(){
		return $this->empty_anser();
	}
	function show_anser(){
		reset($this->arr_anser);
		echo "Anser: "; print_r($this->arr_anser); echo "<BR>";					
	}
	function query_show_anser(){
		return $this->show_anser();
	}
	  /*############################################
			GET,SHOW CONTAINER(NAME_CONTAINER)
		###########################################*/				
	function get_container($name_container){//class_
		if(!$name_container)
			return 0;
		$name_container=trim($name_container);
		if($this->is_type_class($name_container)=="CONTAINER"){			
			if($class_container=$this->get_serialize_class_from_name($name_container))
				return $class_container;
			else
				$this->warning("Syntaxerror 0115 unexpected '$name_container' ");	
		}
		return 0;		
	}			
	function show_container($name_container){//class_
		if(!$name_container)
			return 0;
		$name_container=trim($name_container);
		if($this->is_type_class($name_container)=="CONTAINER"){			
			if($class_container=$this->get_serialize_class_from_name($name_container)){
				print "<FONT SIZE='-1'><B>COMPONENT OF THE CONTAINER</B> &nbsp;&nbsp;$name_container<B>:</B>";
				$arr_rows=$class_container->info_extends();
				if(empty($arr_rows))
					print " EMPTY;<BR>";
				else{ 
					print "<BR>";				
					while($myrow = $class_container->fetch_row($arr_rows)){
						if(isset($myrow["Control"])){
							print($myrow["Control"][0]." = ");
							if(!empty($myrow["Protection"]))//PRIVATE
								print " ( ".$myrow["Protection"]." ) ";
							if(!empty($myrow["Component"]))//STATIC
								print " ( ".$myrow["Component"]." ) ";
							print $myrow["Control"][1];
							print "<BR>";
						}
					}							
				}				
				print "</FONT>";
				return 1;
			}else
				$this->warning("Syntaxerror 0116 unexpected '$name_container' ");	
		}	
		return 0;
	}
	function query_show_container($query){
		$pos_1 = strpos($query,"(")+1;
		$pos_2 = strpos($query, "(");		
		if(!$name_container=trim(substr($query,$pos_1,$pos_2-$pos_1))){
			$this->warning("Syntaxerror 0117 unexpected '$query'");	
			return 0;
		}
		return $this->show_container($name_container);
	}
	  /*###########################################################################		
							GET_LIST_EXTENDS()
		###########################################################################	*/		
	function get_list_extends(){
		if(empty($this->arr_extends_class))
			return 0;
		$arr_rows=array();
		reset($this->arr_extends_class);			
		while(list($control,$arr)= each($this->arr_extends_class)){
			$arr_row=array();			
			if($arr[1]!=_DIRECT && $arr[1]!=_FEEDBACK && $arr[1]!=_CHILDREN && $arr[1]!=_PARENT){
				$arr_rows[]=$control;
			}
		}		
		return $arr_rows;		
	}
			//##############################################################	
			//##############################################################	
			//							QUERY
			//##############################################################	
			//##############################################################	
		
		function class_query($query){//PARSER QUERY TO METHODS OF DB_CLASS 
			if(!is_string($query)){
				$this->warning("Syntaxerror 0118 unexpected '$query'");
				return 0;
			}
			$arr_token = explode(" ", $query);//str_condition
			$query="";			
			for($i=0;$i<count($arr_token);$i++){
				$query.=trim($arr_token[$i])." ";								
			}										
			$query=trim($query);
			if(strpos($query,"THIS")===false){
					if(strpos($query,"(")===false || strpos($query,")")===false){
						$this->warning("Syntaxerror 0119 unexpected '$query'");
						return 0;
					}else{						//FORM 0 (METHODS OF DB_CLASS)
							if(strpos($query," ANSER")===false||strpos($query,"GET ")===false)
								if(strpos($query," ANSER")===false||strpos($query,"EMPTY ")===false)
									if(strpos($query," ANSER")===false||strpos($query,"SHOW ")===false)
										if(strpos($query," VIEW ")===false||strpos($query,"SET ")===false)  //INTERFACE
												if(strpos($query," RECORD")===false||strpos($query,"SET ")===false)
														if(strpos($query," RECORD")===false||strpos($query,"DROP ")===false)
														if(strpos($query," RECORD")===false||strpos($query,"ERASE ")===false)
														if(strpos($query," RECORD")===false||strpos($query,"SHOW ")===false)
																//		DB_COMONENT_QUERY	
																return $this->db_component_query($query);	//////////////////	 =>															
														else{
															return $this->query_show_object($query);}	
														else{
															return $this->query_erase_object($query);}	
														else{
															return $this->query_drop_object($query);}	
												else{
													return $this->query_set_object($query);}	
										else{
											return $this->query_set_interface($query);}	
									else{
										return $this->query_show_anser();}	
								else{
									return $this->query_empty_anser();}	
							else{
								return $this->query_get_anser();}
							}
					}else
						// 		DB_CLASS_QUERY	
						return $this->db_class_query($query);	
		}
}//END CLASS

?>