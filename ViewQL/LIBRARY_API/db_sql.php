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

		////////////////////////////////////////////////////////////////////////////// 
		//**************************************************************************//
		//								DB_FUNCTION_QUERY						    //		
		//**************************************************************************//
		//////////////////////////////////////////////////////////////////////////////
		
  class db_function_query extends db_build{
	var $arr_rows=array();
	var $state=0;
	var $prefix_db="";
	function db_function_query($prefix_db=""){
		$this->prefix_db=$prefix_db;
		$this->db_build($prefix_db);
	}
		//////////////////////////////////////////////////////////////////////////////////
		//							FUNCTION  QUERY 									//
		//////////////////////////////////////////////////////////////////////////////////
	function is_build_query($query){
				if(strpos($query,"SHOW ")===false || strpos($query," CLASS")===false)	
				if(strpos($query,"SHOW ")===false || strpos($query," VIEW")===false)	
				if(strpos($query,"SHOW ")===false || strpos($query," CONTAINER")===false)	
				if(strpos($query,"SHOW ")===false || strpos($query," COMPONENT")===false)
				if(strpos($query,"SHOW ")===false || strpos($query," BASE")===false)
				
					if(strpos($query,"CREATE COMPONENT ")===false)
						if(strpos($query,"CREATE CONTAINER ")===false)			
							if(strpos($query,"CREATE VIEW ")===false)	
							if(strpos($query,"DROP VIEW ")===false)
							if(strpos($query,"DROP CONTAINER ")===false)	
								if(strpos($query,"CREATE CLASS ")===false)
								if(strpos($query,"CREATE TABLE ")===false)//23
									if(strpos($query,"REPLACE CONTAINER ")===false)
										if(strpos($query,"REPLACE VIEW ")===false)
											if(strpos($query,"REPLACE CLASS ")===false)
														return -1;														
											else{								
												return $this->create_reset_class_query($query);}
										else{
											return $this->create_reset_class_query($query);}
									else{
										return $this->create_reset_class_query($query);}
								else{
									return $this->create_reset_class_query($query);}
								else{
									return $this->create_reset_class_query($query);}
							else{
								return $this->drop_container_query($query);}//VIEW
							else{
								return $this->drop_view_query($query);}//VIEW
							else{
								return $this->create_view_query($query);}//VIEW
						else{
							return $this->create_reset_class_query($query);}
					else{
						return $this->create_component_query($query);}

				else
					return $this->info_query($query);	
				else
					return $this->info_query($query);	
				else
					return $this->info_query($query);	
				else
					return $this->info_query($query);	
				else
					return $this->info_query($query);																							
	}	
	
		##############################################################################
		//**************************************************************************//
		//					CREATE DB prefix_db;;
		//					DROP DB prefix_db;;
		//					CONNECT DB prefix_db;;
		//**************************************************************************//
		##############################################################################		
 
 		function connect_db($query){
			$pos_1=strpos($query," DB ")+3;
			if(!$prefix_db=trim(substr($query,$pos_1,strlen($query)-$pos_1))){
				$this->warning("Syntaxerror 020 unexpected '$query'");	
				return 0;
			}
			$str_table=$prefix_db."_classes";			
			if($this->is_table_name($str_table))				
				return $this->select_db($prefix_db);// table
			print "ERROR CONNECT  $prefix_db!<br>";	
			return 0;
		}
 		function drop_db($query){
			$pos_1=strpos($query," DB ")+3;
			if(!$prefix_del=trim(substr($query,$pos_1,strlen($query)-$pos_1))){
				$this->warning("Syntaxerror 021 unexpected '$query'");	
				return 0;
			}
			$prefix_db=$this->is_connect_db();
			if($prefix_del==$prefix_db){				
				$query="DROP TABLE IF EXISTS ".
				$prefix_del."_CLASSES, ".
				$prefix_del."_CLASS_OBJECT, ".
				$prefix_del."_COUNTER_COMPONENTS, ".
				$prefix_del."_COUNTER_OBJECTS, ".
				$prefix_del."_POINTER, ".
				$prefix_del."_RELATION, ".
				$prefix_del."_SERIALIZE_CLASSES,".
				$prefix_del."_TEXT, ".
				$prefix_del."_ARRAY_TEXT,".				
				$prefix_del."_STRING, ".
				$prefix_del."_ARRAY_STRING,".				
				$prefix_del."_INTEGER, ".
				$prefix_del."_ARRAY_INTEGER, ".								
				$prefix_del."_ARRAY_TINYINT, ".
				$prefix_del."_TINYINT, ".
				$prefix_del."_DATE, ".
				$prefix_del."_BLOB, ".
				$prefix_del."_FLOAT, ".
				$prefix_del."_DECIMAL, ".
				$prefix_del."_DECIMAL_2 ";
				if(!mysqli_query($GLOBALS["connection"],$query)){						
					print "ERROR DROP TABLES!<br>";	
					return 0;
				}
				print "DB $prefix_del DROP!<br>";	
				return 1;
			}else{				
				print "ERROR $prefix_del!<br>";	
				return 0;
			}			
		}
 		function create_db($query){
			$pos_1=strpos($query," DB ")+3;
			if(!$prefix_create=trim(substr($query,$pos_1,strlen($query)-$pos_1))){
				print("ERROR!You Have An Error In Your DBOL Syntax: '$query'");
				return 0;
			}
			$str_table=$prefix_create."_classes";	
			if(!$this->is_table_name($str_table)){
				$prefix_db=$this->select_db($prefix_create);
				$this->add_component("STRING");
				$this->add_component("INTEGER");
				$this->add_component("TINYINT");
				$this->add_component("TEXT");
				$this->add_component("TEXT_SERIALIZE");
				$this->add_component("BLOB");
				$this->add_component("DATE");
				$this->add_component("ARRAY_STRING");
				$this->add_component("ARRAY_TEXT");
				$this->add_component("ARRAY_INTEGER");
				$this->add_component("ARRAY_TINYINT");
				$this->add_component("FLOAT");			
				$this->add_component("DECIMAL");			
				$this->add_component("DECIMAL_2");			
			}
			return 1;
		}

		##############################################################################
		//**************************************************************************//
		//																			//
		//							FUNCTION  QUERY									//		
		//																			//
		//**************************************************************************//
		##############################################################################		
  		//////////////////////////////////////////////////////////////////////////////
		//						SUB PARSING [PRIVATE] [STATIC]          			//
		//////////////////////////////////////////////////////////////////////////////

	function sub_pars($str_parsing){/////////////////25.05
		//[PRIVATE|PUBLIC] [STATIC] property_name = INTEGER[ARRAY_INTEGER][STRING][ARRAY_STRING][TEXT].,..	
		$content="";//_DYNAMIC
		$hiding="";	
		if(strpos($str_parsing,_PRIVATE)===false && strpos($str_parsing,_STATIC)===false){//&& strpos($str_parsing,_PUBLIC)===false 		
			;
		}else{
			if(strpos($str_parsing,_PRIVATE)===false){
				;				
			}else{
				$hiding=_PRIVATE;
			}
			if(strpos($str_parsing,_STATIC)===false){
				;					
			}else{
				$content=_STATIC;
			}
			if($content && $hiding){//==_PRIVATE
				if(strpos($str_parsing,$hiding) < strpos($str_parsing,$content))
					$arr_tmp = explode($content, $str_parsing);				
				else
					$arr_tmp = explode($hiding, $str_parsing);//_PRIVATE
			}elseif($content){
				$arr_tmp = explode($content, $str_parsing);
			}elseif($hiding){//==_PRIVATE
				$arr_tmp = explode($hiding, $str_parsing);//_PRIVATE
			}
			$str_parsing=trim($arr_tmp[1]);
		}
		$arr_parsing[0]=$str_parsing;//CLASS	
		$arr_parsing[1]=$content;//STATIC|DUNAMIC
		$arr_parsing[2]=$hiding;//PRIVATE|PUBLIC
		return $arr_parsing;
	}
  		/////////////////////////////////////////////////////////////////////////////////////////////
		//									PARSING EXTENDS	+ hiding								//
		//////////////////////////////////////////////////////////////////////////////////////////////	

	function pars_extends($name_class,$str_extend){/////////////////25.05
		//[[name_interface|name_container|name_class .,..] ,
		//	[[PRIVATE][STATIC] property_name = INTEGER|ARRAY_INTEGER|STRING|ARRAY_STRING|TEXT .,..]]
		$arr_parsing=$this->sub_pars($str_extend);
		$str_extend=$arr_parsing[0];
		if(!$str_extend)
			return 0;
		$content=$arr_parsing[1];
		$hiding=$arr_parsing[2];
		$arr_extends[0]=$name_class;
		if(strpos($str_extend,"=")===false){////////
			$arr_extends[1]="";//control
			$arr_extends[2]=$str_extend;//name_class component
			$arr_extends[3]="";
			$arr_extends[4]="";
		}else{
			$arr_token = explode("=", $str_extend);
			if(count($arr_token)==2 && $arr_token[0] && $arr_token[1]){
				$arr_extends[1]=$arr_token[0];//control
				$arr_extends[2]=$arr_token[1];//name_class component
				$arr_extends[3]=$content;
				$arr_extends[4]=$hiding;
			}else
				return 0;
		}
		return $arr_extends;
	} 
	 	//////////////////////////////////////////////////////////////////////////////////////////////
		//									PARS CREATE RELATION 									//
 		//////////////////////////////////////////////////////////////////////////////////////////////	
	//[STATICS.]CLASS[Incheritance CLASS]->[STATICS.]CLASS[Incheritance CLASS]														
	function pars_create_relation($str_relation,$name_class=""){
echo $str_relation." ".$name_class."yyyyyyyyyyyyyyyyyyy<br>";		
		// name_class[.name_object] -> name_class_relation[.name_object];
		if(strpos($str_relation,"<->")===false){//{$name_class for ALTER}
			if(strpos($str_relation,"->")===false){
				if(strpos($str_relation,"<-")===false){
					return 0;
				}else
					$arr_token_rez[6]=-1;//<->
					
			}else
				$arr_token_rez[6]=1;//<->
		}else
		 	$arr_token_rez[6]=2;//<->
		if($arr_token_rez[6]==2){			
			$arr_tmp = explode("<->", $str_relation);
			if(empty($arr_tmp[0]))
				$arr_tmp[0] = $arr_tmp[1];
			else
				$name_class="";				
		}elseif($arr_token_rez[6]==1)	
			$arr_tmp = explode("->", $str_relation);
		else
			$arr_tmp = explode("<-", $str_relation);
		if(!count($arr_tmp))
			return 0;
		foreach($arr_tmp as $key => $arr){		
			if(($arr_token_rez[6]==2)&& $key==0 && !empty($arr)){	
				if(!$arr_extends=$this->sub_pars($arr))
					return 0;
				$arr_token_rez[0]=$arr_extends[1];//STATIC[DUNAMIC]
				if($name_class)
					$arr_token_rez[1]=$name_class;//CLASS
				else
					$arr_token_rez[1]=$arr_extends[0];	
				$arr_token_rez[2]=$arr_extends[2];//Incheritance CLASS			
			}elseif(($arr_token_rez[6]==1 || $arr_token_rez[6]==-1 || $arr_token_rez[6]==2)&& $key==1 && !empty($arr)){
				if(!$arr_extends=$this->sub_pars($arr))
					return 0;
						//relation object
				$arr_token_rez[3]=$arr_extends[1];//STATIC|DUNAMIC
				$arr_token_rez[4]=$arr_extends[0];//CLASS
				$arr_token_rez[5]=$arr_extends[2];//""|PRIVATE|PUBLIC
			}
		}
		return $arr_token_rez;	
	}
  		//////////////////////////////////////////////////////////////////////////////////////////////
		//									PARSING CONDITION										//
		//////////////////////////////////////////////////////////////////////////////////////////////	
  		/////////////////////////////////////////////////////////////////////////////////////////////
		//									PARSING WHERE SELECT									//
		//////////////////////////////////////////////////////////////////////////////////////////////	
  		//////////////////////////////////////////////////////////////////////////////////////////////
		//									PARSING SET 											//
		//////////////////////////////////////////////////////////////////////////////////////////////	

	function pars_set(&$arr_set,$str_set,$method){
		if($method=="DELETE")
			$method="DELETE";
		elseif($method=="GET" || $method=="SELECT")
			$method="SELECT";
		elseif($method=="INSERT" || $method=="REPLACE")
			$method="INSERT";
		else
			return 0;			
		if(!$str_set)
			return 0;								
		$arr_token=array();		
		if($method=="INSERT"){// || $method=="EDIT"
			$arr_token= explode("',",$str_set);
			for($i=0;$i<count($arr_token);$i++){
			    if($i!=count($arr_token)-1)					
					$arr_token[$i]=$arr_token[$i]."'";					
				$pos_0=strpos($arr_token[$i],"=");
				$path_expression=trim(substr($arr_token[$i],0,$pos_0));				
				$value=trim(substr($arr_token[$i],$pos_0+1,strlen($arr_token[$i])));									
				$pos_1=strpos($path_expression,"[");
				if($pos_1===false)
					$pos_1=0;
				else{
					$pos_2=$pos_1+1;
					$pos_3=strpos($path_expression,"]");
					if($pos_3===false){
						return 0;
					}else
						$pos_4=$pos_3+1;
				}
				if(!$pos_1){
					$arr_set[]=$path_expression.".".$method."(".$value.")";//->
				}else{
					$index=trim(substr($path_expression,$pos_2,$pos_3-$pos_2));
					$path_expression=substr($path_expression,0,$pos_1);
					$arr_set[]=$path_expression.".".$method."(".$value.",".$index.")";//->
				}			
			}
		}elseif($method=="SELECT"||$method=="DELETE"){
			$arr_token= explode(",",$str_set);
			for($i=0;$i<count($arr_token);$i++){
				$path_expression=$arr_token[$i];
				$pos_1=strpos($path_expression,"[");
				if($pos_1===false)
					$pos_1=0;
				else{
					$pos_2=$pos_1+1;
					$pos_3=strpos($path_expression,"]");
					if($pos_3===false){
						return 0;
					}else
						$pos_4=$pos_3+1;
				}
				if(!$pos_1){
					$arr_set[]=$path_expression.".".$method."()";//->
				}else{
					$index=substr($path_expression,$pos_2,$pos_3-$pos_2);
					$path_expression=substr($path_expression,0,$pos_1);
					$arr_set[]=$path_expression.".".$method."(".$index.")";//->
				}				
			}				
		}else
			return 0;
		return 1;					
	}	

		//##########################################################################################//
		//##########################################################################################//
		//##########################################################################################//
		//#																					QUERY																									   #//
		//##########################################################################################//
		//##########################################################################################//
		//##########################################################################################//		
		//////////////////////////////////////////////////////////////////////////////////////////////
		//										SHOW 										//	
		//			SHOW CLASS name_class|ALL					    //	
		//			SHOW VIEW name_interface|ALL 		//	INTERFACE
		//			SHOW CONTAINER name_class|ALL			    //	
		//			SHOW COMPONENT ALL								    //	
		//			SHOW BASE ALL											    //	
		//														                                    //	
		//////////////////////////////////////////////////////////////////////////////////////////////_ 
		function info_query($query){
			if(strpos($query,"BASE ")===false){ 
				if(strpos($query,"CLASS ")===false){ 
					if(strpos($query,"VIEW ")===false){ // INTERFACE
						if(strpos($query,"CONTAINER ")===false){ 
							if(strpos($query,"COMPONENT ")===false){
								$this->warning("Syntaxerror 022 unexpected '$query'");	
								return 0;
							}else{
								$type_class="COMPONENT";
								$pos_1=strpos($query,"COMPONENT ")+10;		
							}
						}else{
							$type_class="CONTAINER";
							$pos_1=strpos($query,"CONTAINER ")+10;							
						}
					}else{
						$type_class="VIEW";
						$pos_1=strpos($query,"VIEW ")+5;						
					}
				}else{
					$type_class="CLASS";
					$pos_1=strpos($query,"CLASS ")+6;			
				}
			}else{
				$type_class="BASE";
				$pos_1=strpos($query,"BASE ")+5;			
			}
			if(strpos($query," ALL")===false && strpos($query," all")===false){
				$name_class=trim(substr($query,$pos_1,strlen($query)-$pos_1));				
				if(!$this->is_type_class($name_class) || $type_class!=$this->is_type_class($name_class)){
					 $this->warning("Syntaxerror 023 unexpected type '$type_class' in '$query'");	
					 return 0;
				}
				if($type_class=="CLASS"){
						if(!$class=$this->get_serialize_class_from_full_name($name_class)){
							$this->warning("Syntaxerror 024 unexpected class '$name_class' in '$query'");	
							return 0;
						}else{
							print "<BR><FONT SIZE='-1'><B>CLASS </B>".$class->get_name_class()."<BR>";
							$class->show_interfaces();
						}	
						return 1;
				}elseif($type_class=="VIEW"){
						if(!$class=$this->get_serialize_class_from_full_name($name_class)){
							$this->warning("Syntaxerror 025 unexpected view '$name_class' in '$query'");	
							return 0;
						}else{
							print "<BR><FONT SIZE='-1'><B>VIEW - </B>".$class->get_name_interface().":<BR>";
							$class->show_extends();
							print "<BR>";
						}	
						return 1;
				}elseif($type_class=="CONTAINER"){
						if(!$class=$this->get_serialize_class_from_name($name_class)){
							$this->warning("Syntaxerror 026 unexpected 'container' '$name_class' in '$query'");	
							return 0;
						}else{
							print "<BR><FONT SIZE='-1'><B>CONTAINER </B>".$name_class." <B>EXTEND</B> <BR>";
							$class->show_extends();
							print "<BR>";
						}
				}	
			/*		
					print "ALL EXTENDS-<br>";print_r($class->arr_extends_class);print "<BR>";
					//print "ALL COMPONENTS-";print_r($class->list_component);print "<BR>";
					$class->show_components();
					$class->show_extends();
					$class->show_inheritance();
					$class->show_relations();
					$class->show_pointers();*/
			}else{
				if($type_class=="COMPONENT")
 					$this->show_type_class("COMPONENT");
				elseif($type_class=="CONTAINER")
					$this->show_type_class("CONTAINER");
				elseif($type_class=="VIEW")
					$this->show_type_class("VIEW"); 
				elseif($type_class=="CLASS")
					$this->show_type_class("CLASS");
				elseif($type_class=="BASE"){
					$this->show_type_class("COMPONENT");
					$this->show_type_class("CONTAINER");
					$this->show_type_class("VIEW"); 
					$this->show_type_class("CLASS");
				}else
					 return 0;				
			}
		}
		//////////////////////////////////////////////////////////////////
		//						CREATE COMPONENT						//	
		//	CREATE COMPONENT name_class_component;;						//
		//////////////////////////////////////////////////////////////////
		//CREATE COMPONENT(1) _ 
		function create_component_query($query){
			$id_component=0;
			$pos_1=strpos($query,"COMPONENT ")+10;
			if($pos_1===false){
				$this->warning("Syntaxerror 027 unexpected '$query'");	
				return 0;
			}
			$name_component=trim(substr($query,$pos_1,strlen($query)-$pos_1));				
			if(!$id_component=$this->build_component($name_component)){
				$this->warning("Syntaxerror 028 unexpected '$name_component' in '$query'");	
				return 0;
			}
			$this->empty_anser();
			$this->arr_rows=$name_component;
			return $name_component;
		}

	//////////////////////////////////////////////////////////////////
	//					RESET TABLE $name_table	"""				    //	
	//////////////////////////////////////////////////////////////////
 
	function query_reset_table($query){
		$pos_1=strpos($query," TABLE ")+7;
		$name_table=trim(substr($query,$pos_1,strlen($query)));
		if (!$this->reset_class($name_table)){
			$this->warning("Syntaxerror '$query'");	
			return 0;		
		}
		return 1;
	}
	//////////////////////////////////////////////////////////////////
	//					reset_class($name_class)					//	
	//////////////////////////////////////////////////////////////////
 
		function reset_class($name_class){
			if(empty($name_class))
				return 0;
			$str_cond="NAME";
			$str_where="TYPE_CLASS ='VIEW'";
			if(!$srr_rez=$this->get_query_result($this->prefix_db."CLASSES",$str_cond,$str_where)){				
				return 0;
			}
			if(!$num_rows=mysqli_num_rows($srr_rez)){
				return 0;
			}
			$arr=array();
			while ($row = mysqli_fetch_array($srr_rez)) {
				$arr[]=$row["NAME"];
			}
			mysqli_free_result($srr_rez);
			$str_tmp=" ";
			foreach ($arr as $key => $value) {
				$arr=$this->pars_name_class($value);
				$name_class_inem=$arr[0];	
				if($name_class_inem==$name_class)
					$str_tmp.= $value.",";	
			}	
			$str_tmp = substr($str_tmp,0,strlen($str_tmp)-1);
			$query="REPLACE CLASS $name_class EXTEND $str_tmp"; 
			if(!$this->is_build_query($query)){
				return 0;
			}
			return 1;	

		}

	/*###########################################################################		
					CREATE VIEW Name_view EXTEND List;;
	###########################################################################*/									
		function create_view_query($query){
			if(!$query)
				return 0;
			$pos_0=strpos($query,"VIEW ");
			$pos_1=$pos_0+5;
			$pos_2=strpos($query,"EXTEND");
			$str_create = trim(substr($query,$pos_0,strlen($query)));

			if(!$str = substr($query,$pos_1,$pos_2-$pos_1)){
				$this->warning("Syntaxerror 061 unexpected '$query'");	
				return 0;
			}
			if(!$arr=$this->pars_name_class($str)){
				$this->warning("Syntaxerror 061a unexpected '$query'");	
				return 0;	
			}
			$name_class=$arr[0];
			$name_view=$arr[1];
			$query="CREATE $str_create";
			if(!$this->create_reset_class_query($query)){ //if(!$this->is_build_query($query)){
				$this->warning("Syntaxerror 061d unexpected '$query'");	
				return 0;
			}
			if(!$this->reset_class($name_class)){
				$this->warning("Syntaxerror 062 unexpected '$query'");	
				return 0;
			}
			return 1;				
		}		

///////////////////////////////////////////////////////////////////////////////////////
//					CREATE CONTAINER|VIEW|CLASS or REPLACE 	
//	CREATE|REPLACE CONTAINER name_container EXTEND 
//		{name_container .,..} | 
//	{[STATIC][PRIVATE]name_component = type_component .,..}		
//	
//	CREATE|REPLACE VIEW|CLASS name_interface EXTEND 		
//		{[IMMED]name_interface .,..} , {name_container .,..} | 
//		{[STATIC][PRIVATE]name_component = type_component .,..}	
//
//	CREATE|REPLACE CLASS name_interface EXTEND 		
//		name_interface .,..} 	
//
//	name_component::= INTEGER|ARRAY_INTEGER|STRING|ARRAY_STRING|TEXT|...
//	p.s. IMMED::=	immediately;				
//	Predicat "PRIVATE"  - zapreshaet IMMED this property,	
//	Definition "PUBLIC" - IMMED this property pazreshena ;	
//	Predicat "STATIC" 	- access to the property(component) have all objects of this class,	
//	Definition "DUNAMIC"- access to the property(component) have only this object of this class ;
/////////////////////////////////////////////////////////////////////////////////////////
		//CREATE CLASS(1) _ (2)EXTEND(3) _ (4){(5)- (6)}
		//CREATE CLASS(1) _ (2)EXTEND(3)
		function create_reset_class_query($query){

			$name_class="";
			$str_extend="";
			if(strpos($query,"CREATE CONTAINER ")===false){
				if(strpos($query,"REPLACE CONTAINER ")===false){
					if(strpos($query,"CREATE CLASS ")===false){
					if(strpos($query,"CREATE TABLE ")===false){	
						if(strpos($query,"REPLACE CLASS ")===false){
							if(strpos($query,"CREATE VIEW ")===false){
								if(strpos($query,"REPLACE VIEW ")===false){
									$this->warning("Syntaxerror 029 unexpected '$query'");	
									 return 0;
								}else{
									$pos_1=strpos($query,"REPLACE VIEW ")+13;
									$key="REPLACE_VIEW";
									$type="VIEW";
								}		
							}else{
								$pos_1=strpos($query,"CREATE VIEW ")+12;
								$key="CREATE_VIEW";
								$type="VIEW";
							}		
						}else{
							$pos_1=strpos($query,"REPLACE CLASS ")+14;
							$key="REPLACE_CLASS";
							$type="CLASS";
						}		
					}else{
						$pos_1=strpos($query,"CREATE TABLE ")+13; //23
						$key="CREATE_CLASS";
						$type="CLASS";
					}
					}else{
						$pos_1=strpos($query,"CREATE CLASS ")+13;
						$key="CREATE_CLASS";
						$type="CLASS";
					}		
				}else{
					$pos_1=strpos($query,"REPLACE CONTAINER ")+18;
					$key="REPLACE_CONTAINER";
					$type="CONTAINER";
				}		
			}else{
				$pos_1=strpos($query,"CREATE CONTAINER ")+17;
				$key="CREATE_CONTAINER";
				$type="CONTAINER";
			}		

			$pos_2=strpos($query,"EXTEND ");
			if($pos_2===false){
				$name_class=trim(substr($query,$pos_1,strlen($query)-$pos_1));
				$str_extend="";
			}else{							
				$pos_3=$pos_2+7;										
				$name_class=trim(substr($query,$pos_1,$pos_2-$pos_1));
				$str_extend=trim(substr($query,$pos_3,strlen($query)-$pos_3));
			}
					//DEBUG
			//echo  "NAME CLASS- 	".$name_class."<BR>";
			//echo  "TYPE CLASS- 	".$type."<BR>";
			//echo  "EXTEND-	".$str_extend."<BR>";
			if($key=="REPLACE_CLASS"||$key=="REPLACE_CONTAINER"||$key=="REPLACE_VIEW"){
				if(!$class=$this->get_serialize_class_from_name($name_class)){
					$this->warning("Syntaxerror 030 unexpected '$name_class' in '$query'");	
					return 0;
				}
				$id_class=$class->get_id_class();
						// EMPTY
				$class->array_extends_class=array();
				$class->array_relations_class=array();
				$class->array_pointers_class=array();
				$class->array_inheritance_class=array();
			}elseif($key=="CREATE_CONTAINER"){
				if(!$id_class=$this->get_id_new_component()){
				 	$this->warning("Container '$name_class' not created");	
					return 0;
				}																		
				$class=new db_class($name_class,$this->prefix_db,$id_class);
				if(empty( $class))
				 exit;
				//$class->flg_is_classs=0;
			}elseif($key=="CREATE_VIEW"){
				if(!$id_class=$this->get_id_new_component()){
					$this->warning("View '$name_class' not created");	
					return 0;
				}	
				$class=new db_class($name_class,$this->prefix_db,$id_class);
				//$class->flg_is_classs=0;
			}elseif($key=="CREATE_CLASS"){
				if(!$id_class=$this->get_id_new_object()){
					$this->warning("Class '$name_class' not created");	
					return 0;
				}						
				$class=new db_class($name_class,$this->prefix_db,$id_class);
			}
			if($str_extend){
				$arr_token = explode(",", $str_extend); 
				
				for($i=0;$i<count($arr_token);$i++){
					if(strpos($arr_token[$i],_IMMED)===false){
						$token=trim($arr_token[$i]);
						$flg_immed=0;
					}else{
						$token_tmp =str_replace( _IMMED,"",$arr_token[$i]);
						$token=trim($token_tmp);
						$flg_immed=1;
					}
									
					if(!$arr_extend = $this->pars_extends($name_class,$token)){
						
						return 0;
					}

					if(!$this->alter_extend($arr_extend,$class,$type,$flg_immed)){////// 09.006
						
						return 0;
					}					
				}
			}
			if($key=="CREATE_CONTAINER"){
				if(!$id_class=$this->build_component_container($name_class,$id_class)){
					$this->warning("Container '$name_class' Exist Already");
					return 0;
				}			
			}elseif($key=="CREATE_VIEW"){
					
				if(!$id_class=$this->build_interface_class($name_class,$id_class)){
					$this->warning("View '$name_class' Exist Already");	
					return 0;
				}		
			}elseif($key=="CREATE_CLASS"){
				if(!$id_class=$this->build_class($name_class,$id_class)){
					$this->warning("Class '$name_class' Exist Already");
					return 0;
				}
			}
			if(!$this->serialize_class($class)){/////09.006
				$this->warning("Syntaxerror 031 unexpected '$name_class'");
				return 0;
			}
			$this->empty_anser();
			$this->arr_rows=$id_class;
			if($key=="REPLACE_VIEW")//11.22
				$this->reset_class($name_class);
			if($key=="REPLACE_CLASS")
				return $name_class;		
			return $id_class;
		}				 
/////////////////////////////////////////////////////////////////////////////////////////////////
//										ALTER CLASS												//
//		ALTER CLASS class_name ADD EXTEND 														//
//			{[PRIVATE][STATIC] property_name=component_name|container_name|interface_name.,..}	//
//		component_name::=INTEGER|STRING|ARRAY_STRING|TEXT|...									//
//		ALTER CLASS name_class DELETE EXTEND property_name .,..;								//
//																								//
//		ALTER CLASS name_class ADD VIEW  interface_name .,..;								    //
//		ALTER CLASS name_class DELETE INHERITANCE  inheritance_name .,..;						//
//			INHERITANCE::=VIEW|CONTAINER; inheritance_name::=container_name|interface_name	    //														
//																								//														//		ALTER CLASS name_class  																//
//			ADD  RELATION  	->|<- [PRIVATE|PUBLIC][STATIC] name_class_relation .,.. ;			//															
//		ALTER CLASS name_class  																//
//			DELETE RELATION ->|<- name_class_relation .,.. ;									//	
//																								//		
//		ALTER CLASS name_class  																//
//			ADD POINTER  	->|<- [PRIVATE|PUBLIC][STATIC] name_class_pointer .,.. ;			//															
//		ALTER CLASS name_class  																//
//			DELETE POINTER  ->|<- name_class_pointer .,.. ;										//
//////////////////////////////////////////////////////////////////////////////////////////////////
			//ALTER CLASS(1) $name_class (2)ADD EXTEND(3) 	 $str_token STRLEN
			//ALTER CLASS(1) $name_class (2)DELETE EXTEND(3) $str_token STRLEN
/*	function alter_class_query($query){
			$name_class="";
			$pos_1=strpos($query,"CLASS ")+6;
			if($pos_1===false){
				$this->warning("Syntaxerror 032 unexpected '$query'");	
				return 0;
			}
			if(strpos($query,"ADD EXTEND ")===false){
				if(strpos($query,"DELETE EXTEND ")===false){
					if(strpos($query,"ADD VIEW ")===false){
						if(strpos($query,"DELETE VIEW ")===false){
					if(strpos($query,"ADD RELATION ")===false){
						if(strpos($query,"DELETE RELATION ")===false){
							if(strpos($query,"ADD POINTER ")===false){
								if(strpos($query,"DELETE POINTER ")===false){			
									 $this->warning("Syntaxerror 033 unexpected '$query'");	
									 return 0;
								}else{
									$pos_2=strpos($query,"DELETE POINTER ");
									$key="DEL_POINTER";
									$pos_3=$pos_2+15;
								}		
							}else{
								$pos_2=strpos($query,"ADD POINTER ");
								$key="ADD_POINTER";
								$pos_3=$pos_2+12;
							}		
						}else{
							$pos_2=strpos($query,"DELETE RELATION ");
							$key="DEL_RELATION";
							$pos_3=$pos_2+16;
						}		
					}else{
						$pos_2=strpos($query,"ADD RELATION ");
						$key="ADD_RELATION";
						$pos_3=$pos_2+13;
					}
					
						}else{
							$pos_2=strpos($query,"DELETE VIEW ");
							$key="DEL_VIEW";
							$pos_3=$pos_2+12;
						}
					}else{
						$pos_2=strpos($query,"ADD VIEW ");
						$key="ADD_EXTEND";
						$pos_3=$pos_2+9;
					}		
									
				}else{
					$pos_2=strpos($query,"DELETE EXTEND ");
					$key="DEL_EXTEND";
					$pos_3=$pos_2+14;
				}		
			}else{
				$pos_2=strpos($query,"ADD EXTEND ");
				$key="ADD_EXTEND";
				$pos_3=$pos_2+11;
			}		
			$name_class=trim(substr($query,$pos_1,$pos_2-$pos_1));
			$str_token=trim(substr($query,$pos_3,strlen($query)-$pos_3));
					//DEBUG
			//echo  "NAME CLASS- 	".$name_class."<BR>";
			//echo  "EXTEND-	".$str_token."<BR>";
			//echo  "KEY-	".$key."<BR>";			
			
			if(!$class=$this->get_serialize_class_from_name($name_class)){
				$this->warning("Syntaxerror 034 unexpected '$name_class' in '$query'");	
				return 0;
			}
			$flg_serialize = 0;				
			$arr_classes=array();								
			if($str_token && $name_class){
				if($key=="ADD_EXTEND"){
					$arr_token = explode(",", $str_token);
					for($i=0;$i<count($arr_token);$i++){
						if(!$arr_extend = $this->pars_extends($name_class,$arr_token[$i])){
							$this->warning("Syntaxerror 035 unexpected '$arr_token[$i]' in '$query'");	
							 return 0;
						}
						if(!$class_type=$this->is_type_class($name_class)){
							$this->warning("Syntaxerror 036 unexpected '$name_class' in '$query'");						
							return 0;
						}
						if($this->alter_extend($arr_extend,$class,$class_type))
							$flg_serialize = 1;
					 }
				}elseif($key=="DEL_EXTEND"){
					$arr_token = explode(",", $str_token);
					for($i=0;$i<count($arr_token);$i++){					
						if($class->del_extends_class(trim($arr_token[$i])))
							$flg_serialize = 1;
					}
				}elseif($key=="DEL_VIEW"){
					$arr_token = explode(",", $str_token);
					for($i=0;$i<count($arr_token);$i++){					
						if($class->del_inheritance_class(trim($arr_token[$i])))
							$flg_serialize = 1;
					}
				}elseif($key=="ADD_RELATION"){
						$arr_token = explode(",", $str_token);
						for($i=0;$i<count($arr_token);$i++){
							$arr_token[$i]=trim($arr_token[$i]);
							if(!$arr_relation = $this->pars_create_relation($arr_token[$i],$name_pars)){
								$this->warning("Syntaxerror 037 unexpected '$arr_token[$i]' in '$query'");	
								 return 0;
							}
							if($arr_relation[6]==2){
								if(!$this->alter_double_relation($arr_relation,$arr_classes,_RELATION,"ADD")){
									$this->warning("Syntaxerror 038 unexpected '$arr_token[$i]' in '$query'");	
									 return 0;
								}
							}else{
								if($this->alter_single_relation($class,$arr_relation,_RELATION,"ADD"))
									$flg_serialize = 1;
								else{
									$this->warning("Syntaxerror 039 unexpected '$arr_token[$i]' in '$query'");	
									 return 0;
								}
							}										
						}
				}elseif($key=="DEL_RELATION"){
						$arr_token = explode(",", $str_token);
						for($i=0;$i<count($arr_token);$i++){
							$arr_token[$i]=trim($arr_token[$i]);
							if(!$arr_relation = $this->pars_create_relation($arr_token[$i],$name_pars)){
								$this->warning("Syntaxerror 040 unexpected '$arr_token[$i]' in '$query'");	
								  return 0;
							}
							if($arr_relation[6]==2){
								if(!$this->alter_double_relation($arr_relation,$arr_classes,_RELATION,"DEL")){
									$this->warning("Syntaxerror 041 unexpected '$arr_token[$i]' in '$query'");	
									  return 0;
								}	
							}else{								
								if($this->alter_single_relation($class,$arr_relation,_RELATION,"DEL"))
									$flg_serialize = 1;
								else{
									$this->warning("Syntaxerror 042 unexpected '$arr_token[$i]' in '$query'");	
									return 0;
								}
							}											
						}
				}elseif($key=="ADD_POINTER"){
					if($str_token){
						$arr_token = explode(",", $str_token);
						for($i=0;$i<count($arr_token);$i++){
							$arr_token[$i]=trim($arr_token[$i]);
							if(!$arr_relation = $this->pars_create_relation($arr_token[$i],$name_pars)){
								$this->warning("Syntaxerror 043 unexpected '$arr_token[$i]' in '$query'");	
								return 0;
							}
							if($arr_relation[6]==2){
								if(!$this->alter_double_relation($arr_relation,$arr_classes,_POINTER,"ADD")){
									$this->warning("Syntaxerror unexpected '$arr_token[$i]' in '$query'");	
									return 0;
								}						
							}else{								
								if($this->alter_single_relation($class,$arr_relation,_POINTER,"ADD"))
									$flg_serialize = 1;
								else{
									$this->warning("Syntaxerror 044 unexpected '$arr_token[$i]' in '$query'");	
									return 0;
								}
							}	
						}
					}
				}elseif($key=="DEL_POINTER"){
					if($str_token){						
						$arr_token = explode(",", $str_token);
						for($i=0;$i<count($arr_token);$i++){
							$arr_token[$i]=trim($arr_token[$i]);
							if(!$arr_relation = $this->pars_create_relation($arr_token[$i],$name_pars)){
								$this->warning("Syntaxerror 045 unexpected '$arr_token[$i]' in '$query'");	
								return 0;
							}
							if($arr_relation[6]==2){
								if(!$this->alter_double_relation($arr_relation,$arr_classes,_POINTER,"DEL")){
									$this->warning("Syntaxerror 046 unexpected '$arr_token[$i]' in '$query'");	
									return 0;
								}								
							}else{								
								if($this->alter_single_relation($class,$arr_relation,_POINTER,"DEL"))
									$flg_serialize = 1;
								else{
									$this->warning("Syntaxerror 047 unexpected '$arr_token[$i]' in '$query'");	
									return 0;
								}
							}	
						}
					}				
				}else
					return 0;					
				if($flg_serialize){
					if(!$this->serialize_class($class)){/////09.006
						$this->warning("Syntaxerror 048 unexpected '$name_class' in '$query'");	
						return 0;
					}
				}elseif(!empty($arr_classes)){
					foreach($arr_classes as $id => $class){
						if(empty($class))
							continue;
						if(!$this->serialize_class($class)){
							$this->warning("Syntaxerror 049 unexpected '$name_class' in '$query'");	
							return 0;
						}
					}
				}			
				return 1;
			}
			return 0;
	}*/		
	/*############################## NEW ############################################
		
		INSERT|DELETE RELATION  name_view <-> name_view WHERE
		[name_view] name_component(value) == [name_view] name_component(list_values)
			
		INSERT|DELETE RELATION  name_view <-> name_view WHERE
			    [name_view] name_component == [name_view] name_component
			
		INSERT|DELETE RELATION name_view  ->|<-  name_view WHERE
		[name_view] name_component(value)  == [name_view] name_component(list_values)
	#################################################################################*/
		function query_relationship($query){
//INSERT|DELETE[0]RELATION[1]name_view <-> name_view [2]WHERE[3]
//name_view.name_component.value[4](value[5]) == name_view.name_component(list_values)
		$pos_0=strpos($query,'RELATION');//RELATIONSHIP
		$pos_1=$pos_0+8;
		$pos_2=strpos($query,'WHERE');
		$pos_3=$pos_2+5;
		if(strpos($query,"<->")===false)
			if(strpos($query,"->")===false )
				if(strpos($query,"<-")===false)
					return 0;
				else
					$mode="<-";
			else
				$mode="->";			
		else
			$mode="<->";
		if(empty(strpos($query,'=='))){
			$this->warning("Syntaxerror 061 unexpected '$query'");	
			return 0;
		}	
		$str_method=trim(substr($query,0,$pos_1));	
		$str_method.=" FROM ";
		//EDGE
		$str_edge=trim(substr($query,$pos_1,$pos_2-$pos_1));
		$arr_edge=explode($mode,$str_edge);	
		if(empty($arr_edge[0]) || empty( $arr_edge[1])){
			$this->warning("Syntaxerror 062 unexpected '$query'");	
			return 0;
		}
		$str_edge_left=trim($arr_edge[0]);
		$len_view_left=strlen($str_edge_left);	
		$str_edge_right=trim($arr_edge[1]);
		$len_view_right=strlen($str_edge_right);
		//WHERE
		$str_where=trim(substr($query,$pos_3,strlen($query)));

		$arr_where=explode("==",$str_where);
		//left
		if(empty($arr_where[0])){
			$this->warning("Syntaxerror 063 unexpected '$query'");
			return 0;
		}			
		$str_where_left=trim($arr_where[0]);
		$str_where_left=" ".$str_where_left;
		if(strpos($str_where_left,$str_edge_left)){
			$str_where_left=trim($str_where_left);
			$str_where_left=trim(substr($str_where_left,$len_view_left,strlen($str_where_left)-$len_view_left));	
		}		
		if(strpos($str_where_left,"(")){	
			$pos_4=strpos($str_where_left,"(");
			$pos_5=strpos($str_where_left,")");	
			$component_left=trim(substr($str_where_left,0,$pos_4));
			$value_left=trim(substr($str_where_left,$pos_4+1,$pos_5-$pos_4-1));			
			$str_where_left=$component_left."=".$value_left;
		}
		//right
		if(empty($arr_where[1])){
			$this->warning("Syntaxerror 064 unexpected '$query'");
			return 0;
		}
		$str_where_right=$arr_where[1];
		$str_where_right=trim($arr_where[1]);
		$str_where_right=" ".$str_where_right;
		if(strpos($str_where_right,$str_edge_right)){
			$str_where_right=trim($str_where_right);
			$str_where_right=trim(substr($str_where_right,$len_view_right,strlen($str_where_right)-$len_view_right));
		}		
		$str_query = $str_method." ".$str_edge_left." WHERE ".$str_where_left." ".$mode." ".$str_edge_right." WHERE ".$str_where_right;	

		return $this->_query_relationship($str_query);
	}
	/* ############################### OLD ######################################
		
		INSERT|DELETE RELATION FROM //WITH
			name class|name interface WHERE name_component='value'
			<-> 
			relationship_class WHERE name_component(list_values);			
		INSERT|DELETE RELATION  FROM //JOIN 
			name class|name interface WHERE name_component
			<->
			|relationship_class WHERE name_component;			
		INSERT RELATION FROM   
			name class|name interface WHERE name_component='value'
			 ->|<- 
			THIS|NULL|relationship_class WHERE name_component(list_values)}
	###########################################################################*/
		function _query_relationship($query){
		
		//INSERT|DELETE[0]RELATION FROM[1] [2]WHERE $mode[3] [2]WHERE	
		$pos_0=strpos($query,"RELATION");//RELATIONSHIP
		$str_method=trim(substr($query,0,$pos_0));
		if(strpos($query,"INSERT")===false)
			if(strpos($query,"DELETE")===false)
				return 0;
			else
				$method	="DELETE_RELATIONSHIP";
		else
			$method	="INSERT_RELATIONSHIP";
		
		if(strpos($query,"<->")===false)
			if(strpos($query,"->")===false )
				if(strpos($query,"<-")===false)
					return 0;
				else{
					$mode="<-";
					$pos_3=strpos($query,"<-")+2;}
			else{
				$mode="->";
				$pos_3=strpos($query,"->")+2;}
		else{
			$mode="<->";
			$pos_3=strpos($query,"<->")+3;}
			
		if($mode=="<->"){
			if(strpos($query,"(")===false && strpos($query,")")===false && strpos($query,"=")===false)//222
				$mode="JOIN";
			elseif(strpos($query,"(")=== false || strpos($query,")")=== false || strpos($query,"=")=== false){
				$this->warning("Syntaxerror 050a unexpected '$query'");	
				return 0;		
			}
			else
				$mode="WITH";
		} 
		if(strpos($query,"FROM")===false || strpos($query,"WHERE")===false ){
			$this->warning("Syntaxerror 050b unexpected '$query'");	
			return 0;			
		}
		$pos_1=strpos($query,"FROM")+4;
		$pos_2=strpos($query,"WHERE");
		$name_interface=trim(substr($query,$pos_1,$pos_2-$pos_1));

		$str_expression=trim(substr($query,$pos_2,strlen($query)-$pos_2));
		$str_expression = "THIS ".$str_expression;	
		//$str_expression = ereg_replace($name_interface,"THIS",$str_expression);preg_replace 
		$str_expression = preg_replace('/'.$name_interface.'/',"THIS",$str_expression);
				//DEBUG
		/*echo "NAME_VIEW - $name_interface<br>";
		echo "STR_EXPRESSION- $str_expression<br>";
		echo "MODE-$mode<br>";
		echo "METHOD-$method<br>";
		echo "PREFIX_DB-$this->prefix_db<br>";
		*/
		if(!$name_interface)
			return 0;
		if(!$class=new db_class($name_interface,$this->prefix_db))
			return 0;
		if($mode!="WITH")
			if($mode!="JOIN")
				if($mode!="->")
					if($mode!="<-")
						return 0;
					else{
						return $class->_class_query_pointer($str_expression,$method);
					}
				else{		
					return $class->_class_query_pointer($str_expression,$method);
				}
			else{
				return $class->_query_relation_join($str_expression,$method);
			}
		else{ 
			return $class->_query_relation_wich($str_expression,$method);
		}		
	}	

	 /* ###########################################################################		
			SELECT RELATIONSHIP select_list FROM relationship_class ->|<-|WITH 
				name class|name interface WHERE name_component='value';

			Exampl: 
			SELECT RELATIONSHIP Name,Address ,City FROM CUSTOMER.ADDRESS 
				WITH ORDER.FORM  WHERE Code='002';;		
			SELECT RELATIONSHIP Title FROM BOOKS
				WITH ORDER.FORM  WHERE Code='003';;  			
			SELECT RELATIONSHIP ID FROM CATEGORY.BAD -> CATEGORY.BAD WHERE ID='242';;
			SELECT RELATIONSHIP ID FROM PRODUCT.BAD WHERE CATEGORY.BAD WHERE ID='242';;				
		###########################################################################*/	
		
		//SELECT RELATIONSHIP 1_2 FROM 3_4 ->|<-|WITH 5_6 WHERE 7_8
	function query_relationship_select($query){
		$pos_1=strpos($query,"RELATION ")+9;//RELATIONSHIP/12
		$pos_2=strpos($query,"FROM");
		$pos_3=$pos_2+4;
		$pos_6=strpos($query,"WHERE");
		$pos_7=$pos_6+5;		
		if(strpos($query,"<->")===false)//WITH
			if(strpos($query,"->")===false)
				if(strpos($query,"<-")===false){
					$this->warning("Syntaxerror 051 unexpected '$query'");	
					return 0;
				}		
				else{
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
			$pos_4=strpos($query,"<->");//WITH
			$pos_5=$pos_4+3;//4
			$dir="";
		}
		$str_exp=trim(substr($query,$pos_1,$pos_2-$pos_1));
		if(!$name_interface_relationship=trim(substr($query,$pos_3,$pos_4-$pos_3))){
			$this->warning("Syntaxerror 052 unexpected '$query'");	
			return 0;
		}
		if(!$name_interface=trim(substr($query,$pos_5,$pos_6-$pos_5))){	
			$this->warning("Syntaxerror 053 unexpected '$query'");	
			return 0;
		}		
		if(!$str_where=trim(substr($query,$pos_7,strlen($query)-$pos_7))){
			$this->warning("Syntaxerror 054 unexpected '$query'");	
			return 0;
		}
				//DEBUG
		//echo  "NAME_VIEW - ".$name_interface."<BR>";		
		//echo  "STR_WHERE- ".$str_where."<BR>";		
		//echo  "STR_EXP-	".$str_exp."<BR>";
		//echo  "DIR-	".$dir."<BR>";
		//echo  "NAME_INTERFASE_RELATIONSHIP-	$this->prefix_db-".$name_interface_relationship."<BR>";						
		$class= new db_class($name_interface,$this->prefix_db);
		$query_obj=	"SELECT ID THIS WHERE $str_where LIMIT 1";
	
		if(!$arr_anser=$class->db_class_query($query_obj))
			return 0;
		$myrow = $class->fetch_row($arr_anser);
		$id_object=$myrow["ID"];
		$class->set_object($id_object);
		$this->empty_anser();		
		$arr_temp=$class->_query_select_relationship($str_exp,$name_interface_relationship,$dir);		
		if(!empty($arr_temp))
			$this->arr_rows=$arr_temp;
		return $this->arr_rows;
	}
	/*###########################################################################		
					DROP CONTAINER Name_container;;
	###########################################################################*/									
	function drop_container_query($query){//dbol_
		if(!$query)
			return 0;
		$pos_1=strpos($query,"CONTAINER ")+10;
		if(!$name_container = substr($query,$pos_1,strlen($query))){
			$this->warning("Syntaxerror 063 unexpected '$query'");	
			return 0;
		}	
		$str_table="CLASSES";//!$id_class=
		$str_where ="NAME='$name_container'";
		$str_cond="ID_CLASS";
		$obj=$this->get_query_result($this->prefix_db.$str_table,$str_cond,$str_where);
		if(!mysqli_num_rows($obj)){
			$this->warning("Syntaxerror 063 unexpected '$query'");	
			return 0;
		}
		$arr=mysqli_fetch_array($obj);
		$id_container = $arr["ID_CLASS"];
		$str_where="ID_CLASS = $id_container";
		$this->del_table($this->prefix_db.$str_table,$str_where);
				
		$str_table="SERIALIZE_CLASSES";	
		$this->del_table($this->prefix_db.$str_table,$str_where);
		print "CONTAINER $name_container DROP";
	
		return 1;
	}
	/*###########################################################################		
					DROP VIEW Name_view;;
	###########################################################################*/									
	function drop_view_query($query){//dbol_
		if(!$query)
			return 0;
		$pos_1=strpos($query,"VIEW ")+5;
		if(!$name_view = substr($query,$pos_1,strlen($query))){
			$this->warning("Syntaxerror 061 unexpected '$query'");	
			return 0;
		}	
		$str_table="CLASSES";
		$str_where ="NAME='$name_view'";
		$str_cond="ID_CLASS";
		$obj=$this->get_query_result($this->prefix_db.$str_table,$str_cond,$str_where);
		if(!mysqli_num_rows($obj)){
			$this->warning("Syntaxerror 062 unexpected '$query'");	
			return 0;
		}
		$arr=mysqli_fetch_array($obj);
		$id_view = $arr["ID_CLASS"];
		$str_where="ID_CLASS = $id_view";
		$this->del_table($this->prefix_db.$str_table,$str_where);
				
		$str_table="SERIALIZE_CLASSES";	
		$this->del_table($this->prefix_db.$str_table,$str_where);
		print "VIEW $name_view DROP";
		//
		if(!$arr=$this->pars_name_class($name_view)){
			$this->warning("Syntaxerror 061a unexpected '$query'");	
			return 0;	
		}
		$name_class=$arr[0];

		if(!$this->reset_class($name_class)){
			$this->warning("Syntaxerror 062 unexpected '$query'");	
			return 0;
		}
		//		
		return 1;
	}	
		/*###########################################################################		
					GET ANSER; 
		###########################################################################*/									
	function get_anser(){//dbol_
		if(!($this->arr_rows))
			return array();
		reset($this->arr_rows);
		return $this->arr_rows;
	}
	function query_get_anser(){
		return $this->get_anser();
	}
	
		/*###########################################################################		
					EMPTY ANSER; 
		###########################################################################*/									
	function empty_anser(){//dbol_
		 $this->arr_rows=array();
		 return 1;
	}
	function query_empty_anser(){
		return $this->empty_anser();
	}
	
		/*###########################################################################		
				SHOW ANSER;
		###########################################################################*/									
	function show_anser(){//dbol_
		print "Anser: "; print_r($this->arr_rows); print "<BR>";					
	}
	function query_show_anser(){
		return $this->show_anser();
	}

}//end class
/*############################################################################################*/
/*############################################################################################*/
/*############################################################################################*/
/*############################################################################################*/
/*############################################################################################*/

 		//////////////////////////////////////////////////////////////////////////////
		//**************************************************************************//
		//								DB_BUILDER + QUERY									//		
		//**************************************************************************//
		//////////////////////////////////////////////////////////////////////////////
	//class dbol_db_builder extends db_function_query{
	class db_builder extends db_function_query{
		function db_builder($prefix_db=""){			
			$this->db_function_query($prefix_db);
		}			
		function db_query($str_query){ 	
			if(!is_string($str_query))
				return 0;
			$arr_token = explode(" ", $str_query);//str_condition
			$str_query="";			
			for($i=0;$i<count($arr_token);$i++){
				$str_query.=trim($arr_token[$i])." ";									
			}										
			$str_query=trim($str_query);
			$this->arr_rows=array();
			$arr_query = explode(";;", $str_query);
			foreach($arr_query as $key => $query){					
				if(!empty($query))
					if(($arr_rows=$this->is_build_query($query))==-1)
						if(($arr_rows=$this->is_select_query($query))==-1){						
							$this->warning("Syntaxerror 055 unexpected '$query'");	
						}						
			}
			$this->arr_rows=$arr_rows;
			return $this->arr_rows; 
		}
	}//end class
 
/*############################################################################################*/
/*############################################################################################*/
/*############################################################################################*/
/*############################################################################################*/
/*############################################################################################*/

		//////////////////////////////////////////////////////////////////////////////
		//**************************************************************************//
		//								DB_DB									//		
		//**************************************************************************//
		//////////////////////////////////////////////////////////////////////////////

	class db_db extends db_builder{
		function db_db($prefix_db=""){
			$this->db_builder($prefix_db);
		}			
		##############################################################################
		//**************************************************************************//
		//																			//
		//							FUNCTION  PARSING								//		
		//																			//
		//**************************************************************************//
		##############################################################################		
					

	  	///////////////////////////////////////////////////////////////////////////////
		//						(FUN OPTIMUM - RELATIONS)			 				 //
		///////////////////////////////////////////////////////////////////////////////		
	function get_extends_relation_info($arr_id_from,$class){
		$id_class=$class->get_id_class();		
		//$arr_relation_class::=array([0]-$name_relation,[1]-$name_class_exst,[2]-$content),[3]-id_relation);
		$arr_info=array();
		$arr_info[]=0;
		$arr_info[]=$id_class;
		if($arr_relation_class=$class->get_relations_class()){
			//$id_class " .$class->show_relations(); 
			foreach($arr_relation_class as $key =>$arr){
				$id_relation=$arr[3];
				if(!in_array($id_relation,$arr_info) && in_array($id_relation,$arr_id_from))
					$arr_info[]=$id_relation;
			}
		}
		if($arr_pointer_class=$class->get_pointers_class()){
			foreach($arr_pointer_class as $key =>$arr){
				$id_pointer=$arr[3];
				if(!in_array($id_pointer,$arr_info) && in_array($id_pointer,$arr_id_from))
					$arr_info[]=$id_pointer;
			}
		}
		return $arr_info;					
	}
	  	//////////////////////////////////////////////////////////////////////////////////////////////
		//									PARSING FROM SELECT(+ OPTIMUM) 							//
		//////////////////////////////////////////////////////////////////////////////////////////////
	function pars_from_select(&$arr_from,$str_from,$mode=0){//0-select,1-search
						// FROM								
		$arr_token = explode(",", $str_from);
		for($i=0;$i<count($arr_token);$i++){
			if(!$obj_class=$this->get_serialize_class_from_full_name(trim($arr_token[$i]))){/////""""""""""""""""""
				$this->warning("Class '".$arr_token[$i]."' doesn't exist;");
				return 0;
			}
			if(!$id_class=$obj_class->get_id_class()){
				$this->warning("Class '".$arr_token[$i]."' doesn't exist;");
				return 0;
			}
			$arr_from[$id_class]=$obj_class;
		}		
		if(count($arr_token)==1)
			return 1;
		if($mode)//-select
			return 1;				
		$arr_keys=array_keys ($arr_from);
		for($i=0;$i<count($arr_keys);$i++){	
			if(!$arr_relation_class=
				$this->get_extends_relation_info($arr_keys,$arr_from[$arr_keys[$i]]))
				continue;
			$arr_nodes[$arr_keys[$i]]=$arr_relation_class;		
			$arr_ves[$arr_keys[$i]]=count($arr_relation_class)-1;
		}
		arsort ($arr_ves);
		$arr_relation=array();
		$arr_nodes_rez=array();
		$arr_relation_rez=array();
		$nn=0;
		$this->referent_nodes($arr_nodes,$arr_ves,$arr_nodes_rez,$arr_relation_rez,$nn);
		return $arr_relation_rez;	
	}
	function referent_nodes(&$arr_nodes,$arr_ves,$arr_nodes_rez,&$arr_relation_rez,$nn){//optimum
		$n_nodes=count($arr_ves);
		if(count($arr_nodes_rez)==$n_nodes){
			return;
		}	
		$nn++;
		if($nn>10){
			return ;
		}		
		$arr_nodes_rez=array();
		$count=0;
		while(list($key,$ves)= each($arr_ves)){
		 	if($arr_nodes[$key][0]==1){
				$count++;
				continue;
			}
			if(count($arr_nodes[$key])==$n_nodes+1){//all nodes
				$arr_nodes_rez=array();
				for($i=2;$i<count($arr_nodes[$key]);$i++){
					$arr_relation_rez[]=$arr_nodes[$key][1]."_".$arr_nodes[$key][$i];
				}							
				return;
			}
			if(!in_array($arr_nodes[$key][1],$arr_nodes_rez)&& count($arr_nodes_rez)!=0)
				continue;
			if(!in_array($arr_nodes[$key][1],$arr_nodes_rez))
				$arr_nodes_rez[]=$arr_nodes[$key][1];
			for($i=2;$i<count($arr_nodes[$key]);$i++){
				if(!in_array($arr_nodes[$key][$i],$arr_nodes_rez)){
					$arr_nodes_rez[]=$arr_nodes[$key][$i];
					$arr_relation_rez[]=$arr_nodes[$key][1]."_".$arr_nodes[$key][$i];
				}							
			}
			$arr_nodes[$key][0]=1;
			$count++;
		}
		if($count==$n_nodes){
			return;
		}
		$this->referent_nodes($arr_nodes,$arr_ves,$arr_nodes_rez,$arr_relation_rez,$nn);
		return;
	}				
 		//////////////////////////////////////////////////////////////////////////////////////
		//							PARSING CONDITION(+CATEGORY)							//
		//////////////////////////////////////////////////////////////////////////////////////	
 	function pars_condition(&$arr_condition,$arr_from,&$arr_aliases,&$arr_str_where,$str_exp){
					// CONDITION
		$arr_token = explode(",", $str_exp);//str_condition
		for($i=0;$i<count($arr_token);$i++){
			$arr_condition[$i][5]="";
			$arr_token[$i]=trim($arr_token[$i]);
			$arr_tok=explode(".",$arr_token[$i]);
			$arr_condition[$i][0]=trim($arr_token[$i]);
			$obj_stik=new db_stik;
			$obj_stik->query_to_array($arr_condition[$i][0]);
			$name_class=trim($obj_stik->shift());
			if(!$id_class=$this->get_id_class_from_name($name_class))
				return 0;
			if(!$id_class || !$this->is_class($id_class))
				return 0;
			$arr_condition[$i][1]=$id_class;						
			$arr_condition[$i][2]=trim($name_class);
			$method=trim($obj_stik->shift());
			
			$arr_condition[$i][3]=$method;
			if(!empty($arr_condition[$i][3])){
				if(strpos($arr_condition[$i][3]," AS ")===false){
					if($arr_token_substr=$this->pars_substr($arr_condition[$i][3],"[","]"))					
						$arr_condition[$i][4]=$arr_token_substr[0];						
					else
						$arr_condition[$i][4]=$arr_condition[$i][3];
				}else{
					$arr_token_as = explode(" AS ", $arr_condition[$i][3]);
					$arr_condition[$i][3]=trim($arr_token_as[0]);
					$arr_condition[$i][4]=trim($arr_token_as[1]);
				}
				if($arr_condition[$i][3]=="*")
					$arr_condition[$i][4]="*";
			}else{
				$arr_condition[$i][3]=_CLASS;
				$arr_condition[$i][4]=_CLASS;
			}
		}
		$str_cond="";
		$arr_str_condition=array();			
		for($i=0;$i<count($arr_condition);$i++){		
						// WHERE
			$id_class	=$arr_condition[$i][1];//class ITEM
			if(!$id_class)
				continue;
			$alias_oc="OC".$id_class;													
			$name_class	=$arr_condition[$i][2];						
			$method		=$arr_condition[$i][3];
			if(!isset($arr_from[$id_class]))
				continue;
			$obj_class=$arr_from[$id_class];
			if($method==_CLASS){
					$arr_str_condition[]=" $alias_oc.ID_OBJECT AS ". _OBJECT ." ";
					$arr_str_condition[]=" $alias_oc.ID_CLASS AS ". _CLASS ." ";
			}elseif($method=="*"){
				return 0;///////////////////////////////////////////////////// DAET ERROR
				$arr_control=array_keys($obj_class->arr_extends_class);
				for($j=0;$j<count($arr_control);$j++){
						// info extends
					$control=$arr_control[$j];//control extend class ITEM
					$arr_info=$obj_class->arr_extends_class[$control];
					//arr_info=arr(0-""[name_relation],1-name_class_exst,2-content(class or object)[,3=id_relation]
						  //EXTENDS  
					$name_extends_class	=$arr_info[1];//name extend class ITEM [new name()]
					$type_object		=$arr_info[2];//class or object
					if(!isset($arr_aliases[$alias_oc]))//////////20.06				
						$arr_aliases[$alias_oc]=$this->prefix_table."CLASS_OBJECT";					
					if($name_extends_class==_DIRECT || $name_extends_class==_FEEDBACK ||
					   $name_extends_class==_PARENT || $name_extends_class==_CHILDREN )
							continue;
					else{
						$alias=$control;
							$alias_ex=$control.$id_class;							
							if(!in_array($alias_ex,$arr_aliases))
								$arr_aliases[$alias_ex]=$this->prefix_table.$name_extends_class;
							if(!in_array($alias,$arr_aliases)){
								$arr_str_condition[]=" $alias_ex.VALUE AS $alias ";
								if($type_object==_STATIC)
									if(!in_array(" $alias_ex.ID_OBJECT = $alias_oc.ID_CLASS ",$arr_str_where))
										$arr_str_where[]=" $alias_ex.ID_OBJECT = $alias_oc.ID_CLASS ";
								else
									if(!in_array(" $alias_ex.ID_OBJECT = $alias_oc.ID_OBJECT ",$arr_str_where))
										$arr_str_where[]=" $alias_ex.ID_OBJECT = $alias_oc.ID_OBJECT ";
								if(!in_array(" $alias_ex.CONTROL = '$control' ",$arr_str_where))		
									$arr_str_where[]=" $alias_ex.CONTROL = '$control' ";///////////07.05
							}
					}					
				}
			}elseif($method!="*" && $method!=_CLASS){
				if($arr_token_substr=$this->pars_substr($method,"[","]")){
					$control=$arr_token_substr[0];
					$key_array=$arr_token_substr[1];
				}else{
					$control=$method;
					$key_array="";
				}
				$alias=$arr_condition[$i][3];//$alias=$arr_condition[$i][4];
				if(!isset($obj_class->arr_extends_class[$control])){
					
					continue;
				}				
				$arr_info=$obj_class->arr_extends_class[$control];
						  //EXTENDS  
				$id_extends_class	=$arr_info[0];//ID extend class ITEM	
				$name_extends_class	=$arr_info[1];//name extend class ITEM [new name()]
				$type_object		=$arr_info[2];//class or object
				if(!isset($arr_aliases[$alias_oc]))////////20.06				
					$arr_aliases[$alias_oc]=$this->prefix_table."CLASS_OBJECT";
					if($name_extends_class==_DIRECT || $name_extends_class==_FEEDBACK ||
					   $name_extends_class==_PARENT || $name_extends_class==_CHILDREN )
							continue;
						$alias_ex=$alias.$id_class;//$alias_ex=$control.$id_class;
						if(!in_array($alias_ex,$arr_aliases))
							$arr_aliases[$alias_ex]=$this->prefix_table.$name_extends_class;							
						if(!in_array(" $alias_ex.VALUE AS $alias ",$arr_str_condition))	
							$arr_str_condition[]=" $alias_ex.VALUE AS $alias ";
						if($type_object==_STATIC){
							if(!in_array(" $alias_ex.ID_OBJECT = $alias_oc.ID_CLASS ",$arr_str_where))
								$arr_str_where[]=" $alias_ex.ID_OBJECT = $alias_oc.ID_CLASS ";							
						}else{
							if(!in_array(" $alias_ex.ID_OBJECT = $alias_oc.ID_OBJECT ",$arr_str_where))
								$arr_str_where[]=" $alias_ex.ID_OBJECT = $alias_oc.ID_OBJECT ";
						}
						if(!in_array(" $alias_ex.CONTROL = '$control' ",$arr_str_where))		
							$arr_str_where[]=" $alias_ex.CONTROL = '$control' ";
							
						if(!empty($key_array)){
							if(!in_array(" $alias_ex.KEY_ARRAY = '$key_array' ",$arr_str_where)){
								$key_array=str_replace("'","",$key_array);
								$arr_str_where[]=" $alias_ex.KEY_ARRAY = '$key_array' ";
							}						
						}
			}
		}						
		$str_condition="";
		if(empty($arr_str_condition[0]))
			return 0;
		$str_condition.=$arr_str_condition[0];
		for($i=1;$i<count($arr_str_condition);$i++){
			$str_condition.=" , ".$arr_str_condition[$i];
		}
		return $str_condition;		
	}//END_CLASS
  		//////////////////////////////////////////////////////////////////////////////////
		//  					PARSING WHERE SELECT(+CATEGORY+FROM)					// 
		//////////////////////////////////////////////////////////////////////////////////	
	function pars_where_select($arr_where,$arr_from,&$arr_aliases,&$arr_str_where,$str_where,$arr_relation){
		$this->pars_where($arr_where,$str_where);
		//Array ( [0] => Array ( [0] => PRODUCT.Name[EN]='Cleopatra' [5] => [4] => = 'Cleopatra' [1] => 123 [2] => PRODUCT [3] => Array ( [0] => Name[EN] ) ) ) 
		
		while(list($key,$val)=each($arr_from)){
			$id_class		=$key;//class ITEM						
			$obj_class		=$val;									
			$arr_control=array_keys($obj_class->arr_extends_class);
			for($j=0;$j<count($arr_control);$j++){
						// info extends
				$control			=$arr_control[$j];//control extend class ITEM
				$arr_info=$obj_class->arr_extends_class[$control];
				//[control]=>arr(0-""[name_relation],1-name_class_exst,2-content(class or object)[,3=id_relation]
						  //EXTENDS  
				//$id_extends_class	=$arr_info[0];//ID extend class ITEM	
				$name_extends_class	=$arr_info[1];//name extend class ITEM [new name()]
				$type_object		=$arr_info[2];//class or object
				$id_relation		=$arr_info[3];//ID relation class [ID pointer]NEXT
				$alias_oc="OC".$id_class;
				if(!isset($arr_aliases[$alias_oc]))				
					$arr_aliases[$alias_oc]=$this->prefix_table."CLASS_OBJECT";
				if(!in_array(" $alias_oc.ID_CLASS = $id_class ",$arr_str_where))
					$arr_str_where[]=" $alias_oc.ID_CLASS = $id_class ";
				if($name_extends_class==_DIRECT ||$name_extends_class==_FEEDBACK ){				
					if(!isset($arr_from[$id_relation]) && $id_class==$id_relation)
						continue;																
					$alias_r1=$id_class."_".$id_relation;
					$alias_r2=$id_relation."_".$id_class;//////////////
					if($arr_relation!=1){
						if((!isset($arr_aliases[$alias_r1])&&!isset($arr_aliases[$alias_r2])) &&
						(in_array($alias_r1,$arr_relation) || in_array($alias_r2,$arr_relation))){
							$alias_ocr="OC".$id_relation;				
							if(!isset($arr_aliases[$alias_ocr]))				
								$arr_aliases[$alias_ocr]=$this->prefix_table."CLASS_OBJECT";					
							if(!in_array(" $alias_ocr.ID_CLASS = $id_relation ",$arr_str_where))
								$arr_str_where[]=" $alias_ocr.ID_CLASS = $id_relation ";						
							if($name_extends_class==_DIRECT){
								$alias_r=$alias_r1;
								$alias_ocd=$alias_oc;
								$alias_ocf=$alias_ocr;															
							}elseif($name_extends_class==_FEEDBACK){
								$alias_r=$alias_r2;	
								$alias_ocd=$alias_ocr;
								$alias_ocf=$alias_oc;															
							}														
							$arr_aliases[$alias_r]=$this->prefix_table._RELATION;
							
							if($type_object!=_STATIC)
								$arr_str_where[]=" $alias_ocd.ID_OBJECT = $alias_r.ID_OBJECT ";
							else
								$arr_str_where[]=" $alias_ocd.ID_CLASS = $alias_r.ID_OBJECT ";
							if($type_object!=_STATIC)
								$arr_str_where[]=" $alias_ocf.ID_OBJECT = $alias_r.ID_RELATION ";
							else
								$arr_str_where[]=" $alias_ocf.ID_CLASS = $alias_r.ID_RELATION ";
						}
					}
				}elseif($name_extends_class==_PARENT || $name_extends_class==_CHILDREN ){				
					if(!isset($arr_from[$id_relation]))
						continue;
					if($id_class!=$id_relation){
						$alias_p1=$id_class."_".$id_relation."_"._POINTER;
						$alias_p2=$id_relation."_".$id_class."_"._POINTER;
						if(!isset($arr_aliases[$alias_p1])&&!isset($arr_aliases[$alias_p2])){
							$alias_ocr="OC".$id_relation;
							if(!isset($arr_aliases[$alias_ocr]))				
								$arr_aliases[$alias_ocr]=$this->prefix_table."CLASS_OBJECT";					
							if(!in_array(" $alias_ocr.ID_CLASS = $id_relation ",$arr_str_where))
								$arr_str_where[]=" $alias_ocr.ID_CLASS = $id_relation ";																			
							if($name_extends_class==_PARENT){
								$alias_p=$alias_p1;
								$alias_ocp=$alias_oc;
								$alias_ocf=$alias_ocr;								
							}elseif($name_extends_class==_CHILDREN){
							 	$alias_p=$alias_p2;
								$alias_ocp=$alias_ocr;
								$alias_ocf=$alias_oc;								
							}
							$arr_aliases[$alias_p]=$this->prefix_table._POINTER;
							
							if($type_object!=_STATIC)
								$arr_str_where[]=" $alias_ocp.ID_OBJECT = $alias_p.ID ";
							else
								$arr_str_where[]=" $alias_ocp.ID_CLASS = $alias_p.ID ";
							if($type_object!=_STATIC)
								$arr_str_where[]=" $alias_ocf.ID_OBJECT = $alias_p.ID_PARENT ";
							else
								$arr_str_where[]=" $alias_ocf.ID_CLASS = $alias_p.ID_PARENT ";
						}
					}
				}else{
				
					for($i=0;$i<count($arr_where);$i++){		
						//			WHERE
						if(isset($arr_where[$i][1]) && $id_class==$arr_where[$i][1]){//class ITEM						
							$name_class		=$arr_where[$i][2];						
							$path_expression=$arr_where[$i][3];
							$expression		=$arr_where[$i][4];
							$logic			=$arr_where[$i][5];
							$control_ext	=$arr_where[$i][7];
							$code_ext		=$arr_where[$i][8];

							if(empty($path_expression[0])){
								if(!in_array(" $alias_oc.ID_OBJECT $expression ",$arr_str_where))
									$arr_where[$i][6]=" $logic $alias_oc.ID_OBJECT $expression ";
								continue;
							}
							$path_exp=$path_expression[0];
							if($arr_token_exp=$this->pars_substr($path_expression[0],"[","]")){
								$key_array=$arr_token_exp[1];
								$path_expression[0]=$arr_token_exp[0];
							}else
								$key_array=0;
							if($control!=$path_expression[0])
								continue;
							$alias_ex=$path_exp.$id_class;//$alias_ex=$control.$id_class;
							
							if(!in_array($alias_ex,$arr_aliases))
								$arr_aliases[$alias_ex]=$this->prefix_table.$name_extends_class;		
							$arr_where[$i][6]=" $logic(";	
							if($type_object==_STATIC)
								$arr_where[$i][6].=" $alias_ex.ID_OBJECT = $alias_oc.ID_CLASS ";
							else
								$arr_where[$i][6].=" $alias_ex.ID_OBJECT = $alias_oc.ID_OBJECT ";								
							
							
							$arr_where[$i][6].=" && $alias_ex.CONTROL = '".$path_expression[0]."' ";
							$arr_where[$i][6].=" && $alias_ex.VALUE $expression ";	
							if($key_array){//$name_extends_class
								if(!in_array(" $alias_ex.KEY_ARRAY = '$key_array' ",$arr_str_where)){
									$key_array=str_replace("'","",$key_array);///////						
									$arr_where[$i][6].=" && $alias_ex.KEY_ARRAY = '$key_array' )";
								}
							}else
								$arr_where[$i][6].=")";
						}
					}
				}
			}	
		}
		$str_where="";
		if(empty($arr_str_where[0]))
			return 0;
		$str_where.=$arr_str_where[0];
		for($i=1;$i<count($arr_str_where);$i++){			
			$str_where.=" && ".$arr_str_where[$i];
		}
		if(count($arr_where)){
			$str_tmp="";
			for($i=0;$i<count($arr_where);$i++){
				if(!empty($arr_where[$i][6])){
					$str_tmp.=$arr_where[$i][6];//////
				}		
			}
			if($str_tmp)
				$str_where.="&&( $str_tmp )";
		}
		return $str_where;
	}			
		//##########################################################################################//
		//##########################################################################################//
		//									 	SEARCH												//																							
		//##########################################################################################//
		//##########################################################################################//
		######################################################################################			
	 	//////////////////////////////////////////////////////////////////////////////////////////////
		//									PARSING SUBSTR											//
		//////////////////////////////////////////////////////////////////////////////////////////////	
 	function pars_substr($string,$patern_1,$patern_2){
		if(!$string || !$patern_1 || !$patern_2)
			return 0;
		if(strpos($string,$patern_1)===false)
			return 0;
		$pos_1=strpos($string,$patern_1);
		if(strpos($string,$patern_2)===false)
			return 0;
		$pos_2=strpos($string,$patern_2);	
		$arr_token=array();
		$arr_token[0]=substr($string,0,$pos_1);
		$arr_token[1]=substr($string,$pos_1+1,$pos_2-$pos_1-1);
		$arr_token[2]=substr($string,$pos_2+1,strlen($string));
		return $arr_token;
	}
	  	//////////////////////////////////////////////////////////////////////////////////////////////
		//									PARSING GROOP											//
		//////////////////////////////////////////////////////////////////////////////////////////////	
 	function pars_group($arr_group){
					// CONDITION
		$arr_condition=array();
		$str_group_by="";			
		for($i=0;$i<count($arr_group);$i++){
			$arr_condition[$i][0]=trim($arr_group[$i]);
			$obj_stik=new db_stik;
			$obj_stik->query_to_array($arr_condition[$i][0]);
			$name_class=trim($obj_stik->shift());
			if(!$id_class=$this->get_id_class_from_name($name_class)){
				
				return 0;
			}
			if(!$id_class || !$this->is_class($id_class)){
				
				return 0;
			}
			$arr_condition[$i][1]=$id_class;						
			$arr_condition[$i][2]=trim($name_class);
			$method=trim($obj_stik->shift());
			$arr_condition[$i][3]=$method;
			$str_group_by.=$arr_condition[$i][3].$arr_condition[$i][1].".VALUE,";
		}
		if($str_group_by)
			$str_group_by=substr($str_group_by,0,strlen($str_group_by)-1);
		return $str_group_by;
	}

  		/////////////////////////////////////////////////////////////////////////////////////////////
		//									PARSING CONDITION SET 									//
		//////////////////////////////////////////////////////////////////////////////////////////////	
	function pars_cond_set($arr_from,&$arr_aliases,&$arr_str_where,$id_class){
					// CONDITION
		if(!isset($arr_from[$id_class]))
			return 0;
		$alias_oc="OC".$id_class;
		if(!isset($arr_aliases[$alias_oc]))				
			$arr_aliases[$alias_oc]=$this->prefix_table."CLASS_OBJECT";
		else
			return 0;	
		$str_condition=" $alias_oc.ID_OBJECT AS ". _OBJECT ." ";
		$str_condition .=" ,$alias_oc.ID_CLASS AS ". _CLASS ." ";	
		return $str_condition;
	}
  		/////////////////////////////////////////////////////////////////////////////////////////////
		//									PARSING WHERE											//
		//////////////////////////////////////////////////////////////////////////////////////////////	
	function pars_where(&$arr_where,$str_where){
		$nn=-1;
	  	if($str_where){
			$arr_token_or = explode("||", $str_where);
			$logic="";
			if(count($arr_token_or)>1){
				for($i=0;$i<count($arr_token_or);$i++){
					if($i)
						$logic="||";
					$arr_token_and = explode("&&", $arr_token_or[$i]);
					if(count($arr_token_or)>1){
						for($j=0;$j<count($arr_token_and);$j++){
							if($j)
								$logic="&&";
							$token_expression=$arr_token_and[$j];
							$this->pars_expression($arr_where,$token_expression,$logic,$nn);
						}
					}
				}
			}elseif(count($arr_token_or)==1){	
				$arr_token_and = explode("&&", $str_where);											
				for($i=0;$i<count($arr_token_and);$i++){
					if($i)
						$logic="&&";
					else
						$logic="";							
					$token_expression=$arr_token_and[$i];
					$this->pars_expression($arr_where,$token_expression,$logic,$nn);
				}				
			}
		}
	}
  		/////////////////////////////////////////////////////////////////////////////////////////////
		//									PARSING EXPRESSION 										//
		//////////////////////////////////////////////////////////////////////////////////////////////	
	function pars_expression(&$arr_where,$token_expression,$logic,&$nn){
		$nn++;
		$i=$nn;
		$arr_where[$i][0]=$token_expression;
		$arr_where[$i][5]=$logic;							
				if(strpos($arr_where[$i][0]," LIKE ")===false)				
					if(strpos($arr_where[$i][0],"=")===false)
					 	if(strpos($arr_where[$i][0],"<")===false)
							if(strpos($arr_where[$i][0],">")===false)
								if(strpos($arr_where[$i][0],">=")===false)
									if(strpos($arr_where[$i][0],"<=")===false)
										if(strpos($arr_where[$i][0],"!=")===false){
											$this->warning("Syntaxerror 056 unexpected parsing WHERE");	
											return 0;
										}else
											$pat="!=";
									else
										$pat="<=";
								else
									$pat=">=";
							else
								$pat=">";
						else
							$pat="<";
					else
						$pat="=";
				else
					$pat="LIKE";
		$arr_stoken =explode($pat,$arr_where[$i][0]);
		if(count($arr_stoken)){
				$expression=trim($arr_stoken[0]);
				$arr_stoken[1]=trim($arr_stoken[1]);
				if($arr_stoken[1]){
						$arr_where[$i][4]=" ".$pat." ".$arr_stoken[1];
						$obj_stik=new db_stik;
						$obj_stik->query_to_array($expression);			
						$name_class=$obj_stik->shift();						
						if(!$id_class=$this->get_id_class_from_name($name_class)){
							$this->warning("Not class '$name_class' in parsing WHERE");	
							return 0;
						}
						if(!$id_class || !$this->is_class($id_class)){
							$this->warning("Not class '$name_class' in parsing WHERE");	
							return 0;
						}						
						$arr_where[$i][1]=$id_class;						
						$arr_where[$i][2]=$name_class;	
						$path_expression=$obj_stik->get_stik();
						$arr_where[$i][3]=$path_expression;
						//print_r($arr_where);
						if(!empty($arr_where[$i][3])){////////01-09
							if($arr_token_substr=$this->pars_substr($arr_where[$i][3][0],"[","]"))					
								$arr_where[$i][7]=$arr_token_substr[0];						
							elseif(isset($arr_condition[$i][3]))
								$arr_where[$i][7]=$arr_condition[$i][3];
							else
								$arr_where[$i][7]="";
							if(isset($arr_token_substr[1]))
								$arr_where[$i][8]=$arr_token_substr[1];
							else
								$arr_where[$i][8]="";	
						}else{/////////
							$arr_where[$i][3]=array();///////
							$arr_where[$i][7]="";//////////
							$arr_where[$i][8]="";	/////////						
						}///////////
						
				}
		}else{
				$arr_where[$i][1]="";
		}					
		return 1;
	}
	  						
		##############################################################################
		//**************************************************************************//
		//																			//
		//							FUNCTION  QUERY									//		
		//																			//
		//**************************************************************************//
		##############################################################################		
	function is_select_query($query){
		if(strpos($query,"SHOW ")===false || strpos($query," ANSER")===false)		
			if(strpos($query,"GET ")===false || strpos($query," ANSER")===false)
				if(strpos($query,"EMPTY ")===false || strpos($query," ANSER")===false)
					if(strpos($query," RELATION ")===false || strpos($query,"SELECT")===false)//RELATIONSHIP
						if(strpos($query," RELATION ")===false)//RELATIONSHIP
												if(strpos($query,"SEARCH ")===false || strpos($query," SHOW ")===false || strpos($query," FROM ")){
													if((strpos($query,"SEARCH ")===false) || (strpos($query," FROM ")===false)){ 
														$this->warning("Syntaxerror 057 unexpected '$query'");	
														return -1; 
													}else{	
														return $this->search_query($query);}																						
												}else{
													return $this->show_search_guery($query);}	
						else	
							return $this->query_relationship($query);
					else
						return $this->query_relationship_select($query);
				else
					return $this->query_empty_anser();
			else
				return $this->query_get_anser();
		else
			return $this->query_show_anser();
	}				
		
	//////////////////////////////////////////////////////////////////////////////////////
	//						SEARCH	+ MATCH												//	
	//	SEARCH class_name[.*|.property_name['key'][AS aliass]].,..						//
	//		FROM inteface_name .,.. 												//
	//		[WHERE class_name.property_name  =|!=|<|>|<=|>=|LIKE 'value' .{&&}|{||}..]	//
	//		[{MATCH class_name.property_name .,.. AGAINST  'pattern'|'%pattern%'}]  	//
	//		[GROUP BY class_name.property_name .,..]									//
	//		[LIMIT [offset,] n] 														//
	//																					//
	//////////////////////////////////////////////////////////////////////////////////////
	//SELECT (1)(2) FROM(3)(4)WHERE 5) (6)MATCH(7) (8)AGAINST(9) (10)GROUP BY(11)(12)LIMIT(13) (14)
/*	SELECT DESCRIPTION.Description[RU],ARTICLE.Article[RU]					
	 FROM DESCRIPTION,ARTICLE,PRODUCT.BAD 												
	 WHERE PRODUCT.Name[EN]='Cleopatra' 
	 MATCH DESCRIPTION.Description[RU],ARTICLE.Article[RU] AGAINST 'Light Flow' 
	 GROUP BY DESCRIPTION.Description[RU] 								
	 LIMIT 1,10	
	 SELECT DESCRIPTION.Description[RU],ARTICLE.Article[RU]DESCRIPTION,ARTICLE,PRODUCT.BAD WHERE PRODUCT.Name[EN]='Cleopatra' MATCH DESCRIPTION.Description[RU],ARTICLE.Article[RU] AGAINST '' GROUP BY DESCRIPTION.Description[RU] LIMIT 1,10;;
 */ 												
	function search_query($query){
			$this->empty_anser();

						// SELECT //
			$pos_1=strpos($query,"SEARCH ")+7;				
			$pos_2 = strpos($query,"FROM ");
			if($pos_2===false){
				$this->warning("Syntaxerror 058 unexpected '$query'");	
				return 0;
			}else
				$pos_3=$pos_2+5;
			$str_exp = trim(substr($query,$pos_1,$pos_2-$pos_1));	
			//SELECT (1)(2) FROM(3)(4)WHERE(5) (6)MATCH(7) (8)AGAINST(9) (10)GROUP BY(11)(12)LIMIT(13) (14)	
	//22		
			if(strpos($str_exp,"(")){
				$flag=0;
				$str_group=""; 
				$class_group="";
				$arr_group = explode(",", $str_exp);		
				foreach($arr_group as $i=>$group){
					$group=trim($group);
					if($flag==0 && strpos($group,".")){
						$str_group.=trim($group).",";
					}elseif($flag==0 && strpos($group,"(") && strpos($group,")")){
						$pos_01=strpos($group,"("); 
						$class_group=trim(substr($group,0,$pos_01));	
						if(strpos($class_group,".")){	
							$this->warning("Syntaxerror 63 unexpected '$str_exp'");	
							return 0;
						}
						$pos_01+=1;
						$pos_02=strpos($group,")");
						$str_tmp=substr($group,0,$pos_02);
						$nane_item =trim(substr($str_tmp,$pos_01,strlen($str_tmp)));
						$str_group.=$class_group.".".$nane_item.",";
					}elseif($flag==0 && strpos($group,"(")){
						$pos_01=strpos($group,"(");
						$class_group=trim(substr($group,0,$pos_01));	
						if(strpos($class_group,".")){	
							$this->warning("Syntaxerror 64 unexpected '$str_exp'");	
							return 0;
						}	
						$pos_01+=1;
						$nane_item=trim(substr($group,$pos_01,strlen($group)));		
						$str_group.=$class_group.".".$nane_item.",";;	
						$flag=1;
					}elseif($flag==1 && !strpos($group,")")){
						$str_group.=$class_group.".".$group.",";
					}elseif($flag==1 && strpos($group,")")){	
						$pos_02=strpos($group,")");
						$nane_item=trim(substr($group,0,$pos_02));		
						$str_group.=$class_group.".".$nane_item.",";
						$flag=0;
					}
					continue;
				}
				if($flag==1){
					$this->warning("Syntaxerror 65 unexpected ')' in '$str_exp'");	
					return 0;
				}
				$pos_03=strrpos($str_group,",");
				$str_group=trim(substr($str_group,0,$pos_03));				
				$str_exp = $str_group;
			}
//////		
			$pos_4 = strpos($query,"WHERE ");
			if($pos_4===false){
				$str_where = "";
			}else{
				$pos_5=$pos_4+6;
			}										
			$pos_6 = strpos($query,"MATCH ");
			if($pos_6===false){
				$str_match="";
				$str_against="";				 
			}else{
				$pos_7=$pos_6+6;
				$pos_8 = strpos($query,"AGAINST ");	
				if($pos_8===false){
					$str_match="";
					$str_against="";				 
				}else{
					$pos_9=$pos_8+8;
					$str_match=trim(substr($query,$pos_7,$pos_8-$pos_7));;
				}	
			}
			$pos_10 = strpos($query,"GROUP BY ");
			if($pos_10===false)
				$str_group=""; 
			else
				$pos_11=$pos_10+9;
				
			$pos_12 = strpos($query,"LIMIT ");	
			if($pos_12===false)
				$str_limit=""; 
			else
				$pos_13=$pos_12+6;
			//SELECT (1)(2) FROM(3)(4)WHERE 5) (6)MATCH(7) (8)AGAINST(9) (10)GROUP BY(11)(12)LIMIT(13) (14)
			if(!empty($pos_4))	
				$str_from=trim(substr($query,$pos_3,$pos_4-$pos_3));
			elseif(!empty($pos_6))	
				$str_from=trim(substr($query,$pos_3,$pos_6-$pos_3));
			elseif(!empty($pos_10))	
				$str_from=trim(substr($query,$pos_3,$pos_10-$pos_3));
			elseif(!empty($pos_12))	
				$str_from=trim(substr($query,$pos_3,$pos_12-$pos_3));
			else
				$str_from=trim(substr($query,$pos_3,strlen($query)-$pos_3));
									
			if(!isset($str_where)){
				if(!empty($pos_6))	
					$str_where=trim(substr($query,$pos_5,$pos_6-$pos_5));
				elseif(!empty($pos_10))	
					$str_where=trim(substr($query,$pos_5,$pos_10-$pos_5));
				elseif(!empty($pos_12))	
					$str_where=trim(substr($query,$pos_5,$pos_12-$pos_5));					
				else
					$str_where=trim(substr($query,$pos_5,strlen($query)-$pos_5));
			}
			if(!isset($str_against)){
				if(!empty($pos_10))	
					$str_against=trim(substr($query,$pos_9,$pos_10-$pos_9));
				elseif(!empty($pos_12))	
					$str_against=trim(substr($query,$pos_9,$pos_12-$pos_9));
				else
					$str_against=trim(substr($query,$pos_9,strlen($query)-$pos_9));
			}
			//SELECT (1)(2) FROM(3)(4)WHERE 5) (6)MATCH(7) (8)AGAINST(9) (10)GROUP BY(11)(12)LIMIT(13) (14)										
			if(!isset($str_group)){
				if(!empty($pos_12))	
					$str_group=trim(substr($query,$pos_11,$pos_12-$pos_11));
				else
					$str_group=trim(substr($query,$pos_11,strlen($query)-$pos_11));
			}
			if(!isset($str_limit)){
				$str_limit=trim(substr($query,$pos_13,strlen($query)-$pos_13));
				$arr_token = explode(",", $str_limit);
				$str_limit="";
				if(isset($arr_token[0])){
					$str_limit.=$arr_token[0];
					if(isset($arr_token[1]))
						$str_limit.=",".$arr_token[1];
				}	
			}
					
				//DEBUGER
/*		echo  "<BR>";
		echo  "CONDITION-".$str_exp."-<BR>";
		echo  "FROM-".$str_from."-<BR>";
		echo  "WHERE-".$str_where."-<BR>";
		echo  "MATCH- ".$str_match."-<BR>";
		echo  "AGAINST-	".$str_against."-<BR>";
		echo  "GROUP-".$str_group."-<BR>";
		echo  "LIMIT-".$str_limit."-<BR><BR>";	
*/			
										/// FROM ///
		$arr_from=array();
		$arr_relation=$this->pars_from_select($arr_from,$str_from);
		//$arr_relation::=1|array()|Array
		//1::=Only one class is FROM; array()::=Not relationship of FROM; Array::=Array([0]=>123_121[1]=123_120) 
		if($arr_relation==array()){
			$this->warning("Syntaxerror 059 unexpected FROM '$str_from' in '$query'");	
			return 0;
		}
									/////	CONDITION 	/////
		$arr_aliases=array();
		$arr_condition=array();
		$arr_str_where=array();								
		if(!$str_condition=$this->pars_condition($arr_condition,$arr_from,$arr_aliases,$arr_str_where,$str_exp)){
			$this->warning("Syntaxerror 067 unexpected '$query'");	
			return 0;
		}				

				//DEBUG
/*		echo  "<BR>str_exp: ".$str_exp."<BR><BR>";
		echo  "str_condition: $str_condition<BR><BR>";
		echo  "arr_condition: ";print_r($arr_condition);echo "<BR><BR>";
		echo  "arr_aliases: ";print_r($arr_aliases);echo "<BR><BR>";
		echo  "arr_str_where: ";print_r($arr_str_where);echo "<BR><BR>";		
*/		
/*
str_condition: Description121.VALUE AS Description , Article120.VALUE AS Article 
arr_condition: Array ( [0] => Array ( [5] => [0] => DESCRIPTION.Description[RU] [1] => 121 [2] => DESCRIPTION [3] => Description[RU] [4] => Description ) [1] => Array ( [5] => [0] => ARTICLE.Article[RU] [1] => 120 [2] => ARTICLE [3] => Article[RU] [4] => Article ) ) 
arr_aliases: Array ( [OC121] => M_CLASS_OBJECT [Description121] => M_ARRAY_TEXT [OC120] => M_CLASS_OBJECT [Article120] => M_ARRAY_TEXT ) 
arr_str_where: Array ( [0] => Description121.ID_OBJECT = OC121.ID_OBJECT [1] => Description121.CONTROL = 'Description' [2] => Description121.KEY_ARRAY = 'RU' [3] => Article120.ID_OBJECT = OC120.ID_OBJECT [4] => Article120.CONTROL = 'Article' [5] => Article120.KEY_ARRAY = 'RU' ) 
*/
		$str_group_by="";
		if($str_group){
			$arr_group = explode(",", $str_group);
			foreach($arr_group as $i=>$group){
				$group=trim($group);
				foreach($arr_condition as $j=>$condition){
					$condition[0]=trim($condition[0]);	
					if($condition[0]==$group)
						$str_group_by.=$condition[4].$condition[1].".VALUE,";
				}
			}			
			$str_group_by=substr($str_group_by,0,strlen($str_group_by)-1);			
		}else
			$str_group_by="";
		if(strrpos($str_exp,"->")!=strlen($str_exp) && strrpos($str_exp,"->")!=""){//&& !$str_where ){
			$this->warning("Syntaxerror 060 unexpected '$query'");	
			return 0;
		}			
									/////	MATCH 	/////
		$str_match_res="";
		if($str_match){
			$arr_match=array();
			if(!$str=$this->pars_condition($arr_match,$arr_from,$arr_aliases,$arr_str_where,$str_match)){
				$this->warning("Syntaxerror 066 unexpected '$query'");	
				return 0;
			}		
					//DEBUG
/*			echo  "str:: $str<BR><BR>";
			echo  "arr_match: $str_match<BR><BR>";
			echo  "arr_aliases: ";print_r($arr_aliases);echo "<BR><BR>";
			echo  "arr_match: ";print_r($arr_match);echo "<BR><BR>";
			echo  "arr_str_where: ";print_r($arr_str_where);echo "<BR><BR>";
*/		
			//arr_match: Array ( [0] => Array ( [5] => [0] => DESCRIPTION.Description[RU] [1] => 121 [2] => DESCRIPTION [3] => Description[RU] [4] => Description ) [1] => Array ( [5] => [0] => ARTICLE.Article[RU] [1] => 120 [2] => ARTICLE [3] => Article[RU] [4] => Article ) )
			$str_match_res="(";			 
			foreach($arr_match as $nn =>$arr){
				if($nn)
					$str_match_res.="||";	
				$str_match_res.=" MATCH(".$arr[3].$arr[1].".VALUE) AGAINST($str_against)";
			}
			$str_match_res.=")";			
		}	 							
		
							///// 	WHERE 	/////
		$arr_where=array();
		//$arr_str_where=array();
		$str_where_rez=$this->pars_where_select($arr_where,$arr_from,$arr_aliases,$arr_str_where,$str_where,$arr_relation);	
		
		if($str_where_rez && $str_match_res)
			$str_where=$str_where_rez."&&".$str_match_res;
		elseif($str_where_rez || $str_match_res)
			if($str_where_rez)
				$str_where=$str_where_rez;
			else
				$str_where=$str_match_res;
		else
			$str_where="";
				/// ALIASES ///
		reset($arr_aliases);
		$str_table="";
		list($alias,$table)= each($arr_aliases);
			$str_table.="$table AS $alias";
		while(list($alias,$table)= each($arr_aliases)){
			$str_table.=",$table AS $alias";	
		}
		//$pattern="[\[|\]]";
		//$replacement = ""; 		
		$pattern="[\[]"; /////////
		$replacement = "__"; ////////
		
		$str_table=preg_replace($pattern,$replacement,$str_table);
		$str_condition=preg_replace($pattern,$replacement,$str_condition);
		$str_where=preg_replace($pattern,$replacement,$str_where);
		
		$pattern="[\]]";////////////
		$replacement = ""; //////
		
		$str_table=preg_replace($pattern,$replacement,$str_table);//////////
		$str_condition=preg_replace($pattern,$replacement,$str_condition);//////
		$str_where=preg_replace($pattern,$replacement,$str_where);/////////

		//DEBUGER
/*		echo "<BR>****************<BR>STR TABLE-<BR> $str_table";
		echo "<BR>STR CONDITION- <BR>$str_condition";
		echo "<BR>STR WHERE-<BR> $str_where<BR>";
		echo "<BR>";
*/	
						/// QUERY ///
		$str_condition=" DISTINCT ".$str_condition;
		if(!$table_join=$this->get_table_join($str_table,$str_condition,$str_where,$str_group_by,$str_limit)){
			return 0;
		}
/*		
Array ( [0] => Array ( [Code] => 521 [Label_Title__RU] => Íàçâàíèå øåäåâðà [Label_Title__EN] => Title Art ) ) 
to
SEARCH PHOTO.Code,PHOTO.Title_class[EN],PHOTO.Title_class[RU], PHOTO.Label_Title[RU], PHOTO.Label_Title[EN] FROM PHOTO.PHOTO WHERE PHOTO='521';;
SEARCH GENRE_PHOTO,GENRE_PHOTO.Code,GENRE_PHOTO.Name[EN],GENRE_PHOTO.Name[RU] FROM GENRE_PHOTO.GENRE_PHOTO,PHOTO.PHOTO WHERE PHOTO='521';;
*/
		$this->empty_anser();
		//////// 22.04.10 ///////
		$arr_temp=array();
		while($myrow = mysqli_fetch_array($table_join,MYSQLI_ASSOC)){
		
			foreach($myrow as $alias => $value){	
				$pos=strpos($alias,"__");		
				if($pos===FALSE){
					if(is_string($alias)){
						if($alias==strtolower("OBJECT"))
							$arr_temp["ID"]=$value;
						$arr_temp[$alias]=$value;
					}
					continue;
				}
				$pos_code=$pos+2;	
				$key = substr($alias,$pos_code,strlen($alias)-$pos_code); 
				$name_arr = substr($alias,0,$pos);
				$arr_temp[$name_arr][$key]=$value;//new
			}
			$this->arr_rows[]=$arr_temp;
		}

		return $this->arr_rows;
	}

	//////////////////////////////////////////////////////////////////////////////////////////
	//										SHOW_QUERY										//	
	//	SHOW SEARCH class_name[.*|.property_name['key'][AS aliass]].,..							//
	//		FROM name_inteface .,.. 													//
	//		[WHERE class_name.property_name  =|!=|<|>|<=|>=|LIKE 'value' .,..]				//
	//		[GROUP BY class_name.property_name .,..]										//
	//		[LIMIT [offset,] n] 															//
	//																						//
	//////////////////////////////////////////////////////////////////////////////////////////
	function show_search_guery($query){
		$query=trim($query);
		$pos_1=strpos($query,"SHOW ")+4;
		$query = substr($query,4,strlen($query)-4);
		if(!$arr_rows=$this->search_query($query))
			return 0;
			
		for($i=0;$i<count($arr_rows);$i++){
			while(list($key,$value)= each($arr_rows[$i])){
				print "&nbsp;&nbsp;&nbsp;&nbsp;$key - $value <BR>" ;
			}
		}
	}	
}//end class
	/*
	query MATCH & AGAINST
		MATCH (col1,col2,...) AGAINST (expr [search_modifier])
	search_modifier:
	  {
		   IN BOOLEAN MODE
		 | IN NATURAL LANGUAGE MODE
		 | IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION
		 | WITH QUERY EXPANSION
	SELECT * FROM articles WHERE MATCH (title,body)
		-> AGAINST ('+MySQL -YourSQL' IN BOOLEAN MODE);
	
	 }
	 */
?>