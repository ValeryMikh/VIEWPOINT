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
   
	/*#######################################
	class_query($query)) ::= CLASS QUERY + SELECT+ OBJECT
	########################################*/		
	class  gddl extends db_db{//dbpl 
	    var $prefix_db="";		
		var $name_db="";		
		var $class;	//current
		var $arr_classes=array();
		var $table;
						
		function dbpl($prefix_db="",$name_db=""){
			$this->name_db=$name_db;
			$prefix_db="_";//222
			$this->prefix_db=$prefix_db;
			$this->db_db($prefix_db);
		}
		#################
		function info_db(){
		#################
			return array("Id_db"=>$this->prefix_db,"Name_db"=>$this->name_db);
		}
		#################
		function get_class(){
		#################
			return $this->class;	
		}
		#################
		function info_classes(){
		#################
			if(isset($this->class)){
				print($this->class->get_name_class()); echo"<br>";
				print_r(array_keys($this->arr_classes));echo"<br>";
			}
		}
		#################
		function info_class(){
		#################
			if(empty($this->class))
				return array();				
			$this->info_classes();
			$name_class=$this->class->get_name_class();
			$name_interface=$this->class->get_name_interface();
			$id_object=$this->class->get_id_object();
			$id_class=$this->class->get_id_class();			
			return  array("Name_class"=>$name_class, "Id_class"=>$id_class, "Name_interface"=>$name_interface, "Id_object"=>$id_object);
		}
		###################################
		function add_new_class($name_class){	
		###################################
			if(!$name_class)
				return 0;
			$name=trim($name_class);
			if(empty($this->arr_classes[$name])){
				//$name_class=trim($name_class);
				if(!$object=new db_class($name_class,$this->prefix_db)){
					$this->class=0;	
					return 0;
				}
				$this->arr_classes[$name]=$object;		
				$this->class=$this->arr_classes[$name];
			}
			return 1;
		}				
		###################################
		function query_select_class($query){					//query_set_class (SET  CLASS name_class [ ALIAS alias_class]) //SELECT  old
		###################################
			$pos_1=strpos($query,"TABLE ")+6;
			$pos_2=strlen($query);
			if(!$name_class=trim(substr($query,$pos_1,$pos_2-$pos_1))){
				$this->warning("Syntaxerror 01 unexpected '$query'");	
				return 0;
			}
			$name=trim($name_class);
			return $this->_select_class($name);
		}
		###################################
		function _select_class($name_class){					
		###################################
			$name=trim($name_class);
			if(!empty($this->arr_classes[$name])){
				$object=$this->arr_classes[$name];
				$this->class=$object;
				return 1;
			}else
				return $this->add_new_class($name);
		}
		##################################
		function _create_object($name_class,$id_object){
		###################################

			if(!$this-> _select_class($name_class))
				return 0;
			$id_class=$this->class->get_id_class();
			$str_table=$this->class->prefix_table."CLASS_OBJECT";		
			$str_set="ID_CLASS=$id_class,ID_OBJECT='$id_object'";		
			if(!$this->class->ins_table($str_table,$str_set))		
				return 0;
			if(!$this->class->_set_object($id_object))
				return 0;
				
			return 1;
		} 
		
		###################################
		function hash_relationship($str_obj){
		###################################
			$arr_token = explode(",",$str_obj);
			$str_hesh="";
			for($i=0;$i<count($arr_token);$i++){
				if(empty($i))
					$str_hesh=trim($arr_token[$i]);
				else
					$str_hesh=$str_hesh.",".trim($arr_token[$i]);
			}
			return $id_object=md5(trim($str_hesh)); 
		}
    	///////////////////////////////////////////////////////////////////////////////////////////////
		//						        INSERT RECORD 
		//
		// id_object:  INSERT RECORD [('ID_record, ID_record')] INTO Name_table;;   
		///////////////////////////////////////////////////////////////////////////////////////////////
	    ###################################
		function query_insert_record($query){
		###################################	
		// $query."<BR>";
			$pos_1=strpos($query,"INTO");
			$pos_2=$pos_1+4;
			$pos_len=strlen($query);
			if(!$name_table=trim(substr($query,$pos_2,$pos_len-$pos_2))){
				$this->warning("Syntaxerror 02a unexpected '$query'");	
				return 0;
			}
			if(empty($pos_3=strpos($query, "("))){
				$query="CREATE OBJECT $name_table";
				return $this->query_create_object($query);
			}else{
				if(empty($pos_4=strpos($query, ")"))){
					$this->warning("Syntaxerror 02b unexpected '$query'");	
					return 0;
				}else{
					$list_ID=trim(substr($query,$pos_3,$pos_4+1-$pos_3)) ;
					$query="CREATE OBJECT $name_table $list_ID";

					return $this->query_create_object($query);
				}
			}
		}
		///////////////////////////////////////////////////////////////////////////////////////////////
		//						          CREATE OBJECT 
		//
		// id_object:  CREATE OBJECT  Name_class [(ID_object, ID_object)];;   
		///////////////////////////////////////////////////////////////////////////////////////////////
		 												
		###################################
		function query_create_object($query){
		###################################		
			$pos_1=strpos($query,"OBJECT")+6;//$pos_1=strpos($query,"OBJECT")+6;
			if(empty($pos_2=strpos($query, "("))){
				$pos_len=strlen($query);
				if(!$name_class=trim(substr($query,$pos_1,$pos_len-$pos_1))){
					$this->warning("Syntaxerror 02 unexpected '$query'");	
					return 0;
				}
			}else
				$name_class=trim(substr($query,$pos_1,$pos_2-$pos_1) );
			if(!$this->_select_class($name_class)){
				$this->warning("Syntaxerror 03 unexpected '$name_class' in '$query'");	
				 return 0;  
			} 
			if(!$id_class=$this->class->get_id_class()) 
 				return 0;
			if(empty($pos_2)){
				//	CREATE OBJECT Name_class ;; 
				if(!$id_object=$this->class->add_object_to_class($id_class))
					return 0;
			}else{ 
				//	CREATE OBJECT Name_class (RELATIOSHIP )   
				$pos_3=strpos($query, ")");
				if(empty($pos_1) || empty($pos_2)||empty($pos_3)){
					$this->warning("Syntaxerror 04 unexpected '$query'");	
					return 0;
				}
				$pos_2+=1;
				$str=trim(substr($query,$pos_2,$pos_3-$pos_2));
				if(empty(strpos($str, ","))){
					$this->warning("Syntaxerror 05a unexpected '$str' in '$query'");	
					return 0;
				}
				$pattern="/'|’/"; //23
				$str=trim(preg_replace($pattern," ",$str)); 
				$arr_token = explode(',',$str);
				$count=count($arr_token);
				if($count>2){
					$this->warning("Syntaxerror 05aa unexpected '$str' in '$query'");	
					return 0;
				}
				if($count>1){ //	 CURRENT EDGE (view_name, ID_object);; 	
					if($arr_token[0]>$arr_token[1])
						$str=$arr_token[1].",".$arr_token[0]; 
				}		//
				if(!$id_object=$this->hash_relationship($str)){
					$this->warning("Syntaxerror 05 unexpected '$str' in '$query'");	
					return 0;
				}			
				if(!$this->class->add_object_to_class($id_class,$id_object))
					return 0;
  				$query="CURRENT NODE (".$name_class.".HASH,".$str.")";//CURRENT EDGE

				if($this->dbpl_query($query)){
					$query="INSERT(Hash='".$str."')";//23
					$this->dbpl_query($query);
				}
			}
			if(!$this->class->set_object($id_object))
				return 0;
			return $id_object;
		}		
		###################################
		function pars_query($str){
		###################################
			trim($str);
			$pos_1=strpos($str, "(")+1;
			$pos_2=strpos($str, ")");
			if(!$str=trim(substr($str,$pos_1,$pos_2-$pos_1)))
				return 0;
			$arr_token = explode(",",$str);
			if(count($arr_token)<2)
			 	return 0;
			$name_view=trim($arr_token [0]);
			if($name_view != 'THIS'){
				$token = explode(".",$str);
				if(!$name_class=trim($token [0]))
			 		return 0;
			}else
				$name_class="THIS";
			$pos_3=strpos($str,",")+1;
			$str_obj=trim(substr($str,$pos_3,strlen($str)-$pos_3));
			$arr=array ($name_class,$name_view,$str_obj);
			return $arr;
		}

		
  		//////////////////////////////////////////////////////////////////////////
		//						CURRENT  NODE|(EDGE)                            //
		//  1|0 CURRENT NODE (name_view |THIS, id_record |THIS});;              // 	id_record=ID_object
		//  1|0 CURRENT NODE (name_view |THIS, id_record,id_record});;      //
		//////////////////////////////////////////////////////////////////////////
		###################################
		function query_current_node($query){
		###################################
		$pos_1=strpos($query,"NODE")+4;
		if(!$str=trim(substr($query,$pos_1,strlen($query)-$pos_1))){
			$this->warning("Syntaxerror 06 unexpected '$query'");	
			return 0;
		}
			$arr = $this->pars_query($str);
			if(!$arr){
				$this->warning("Syntaxerror 07 unexpected '$query'");	
				return 0;
			}

			$name_class=$arr[0];
			$name_view=$arr[1];
			$str_obj=$arr[2];
			if($name_class != "THIS"){
				if(!$this->_select_class($name_class)){
					$this->warning("Syntaxerror 08 unexpected '$name_class' in '$query'");	
				 	return 0;
				}
				if(!$this->class->set_interface($name_view)){
					$this->warning("Syntaxerror 09a unexpected '$name_view' in '$query'");	
					return 0;
				}
			}			
			$arr_token = explode(',',$str_obj);
			$count=count($arr_token); //23
			if($count>2){
				$this->warning("Syntaxerror 09aa unexpected '$name_view' in '$query'");	
				return 0;
			}
			if($count>1){ //	 CURRENT EDGE (view_name, ID_object , ID_object );; 
				$pattern="/'|’/";
				$str_obj=trim(preg_replace($pattern," ",$str_obj)); 
				if($arr_token[0]>$arr_token[1])
					$str_obj=$arr_token[1].",".$arr_token[0]; //

				if(!$id_object=$this->hash_relationship($str_obj)){
					$this->warning("Syntaxerror 010 unexpected '$str_obj' in '$query'");	
					return 0;
				}
			}else{
				$id_object=trim($str_obj);
				if($id_object=="THIS" )
					return 1;
			}

			if(!$this->class->set_object($id_object)){
				$this->warning("Syntaxerror 60 unexpected '$query'");	
				return 0;
			}
			return 1;
		}

		###################################
		function query_reset_class($query){
		###################################
			$query= trim($query);
			$name_class= $this->is_build_query($query);
			$this->arr_classes[$name_class]=0;		
			$this->class=0;
			if(!$this->add_new_class($name_class)){
				$this->warning("Syntaxerror 011 unexpected '$name_class' in '$query'");	
				return 0;
			}
			return 1;
		}
		###################################//22
		function query_view_class($query){					
		###################################
			$pos_1=strpos($query,"CLASS ")+6;
			$pos_2=strlen($query);
			if(!$name_class=trim(substr($query,$pos_1,$pos_2-$pos_1))){
				$this->warning("Syntaxerror 012 unexpected '$query'");	
				return 0;
			}  
			$name=trim($name_class);
			print_r($this->_union_view($name));
		}
		 ###################################
		 function _union_view($name_class=""){// UNION//
		 ###################################
			if(!$object=new db_class($name_class,$this->prefix_db)){
				$this->warning("Syntaxerror 013 unexpected '$name_class' ");	
				return 0;
			}
			if($arr_interface=$object->info_interface()){
				$arr_view=array();
				foreach($arr_interface as $nn=>$name_view){
					if(!$object->set_interface($name_view)){
						$this->warning("Syntaxerror 014 unexpected '$name_view' ");	
						return 0;
					} 
					$control_class=$object->get_control_class();
					$arr_view=array_merge ($arr_view,$control_class);
				}
				return array_unique($arr_view);
			}
			$this->warning("Syntaxerror 015 unexpected '$arr_interface'");	
			return 0;
		}
 		####################
		function begin(){
		####################
			$query="BEGIN";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}		
		####################
		function commit(){
		####################
			$query="COMMIT";
			if(mysqli_query($GLOBALS["connection"],$query))
				return 1;
			return 0;
		}
		#########################	
		function gddl_query($query){
		#########################
			return $this->dbpl_query($query);
		}
		#########################	
		function dbpl_query($query){
		#########################

			$arr_tok = explode("'", $query);
			if(!is_string($query)){
				$this->warning("Syntaxerror 016 unexpected '$query'");	
				return 0;
			}
			$arr_token = explode(" ", $query);//str_condition
			$query="";			
			for($i=0;$i<count($arr_token);$i++){
				$query.=stripslashes(trim($arr_token[$i]))." ";
			}										
			$query=trim($query);//ALTER

			if(strpos($query," RELATION ")===false){//RELATIONSHIP
				if(strpos($query,"BEGIN")===false){
					if(strpos($query,"COMMIT")===false){			
						if(strpos($query,"REPLACE ")===false || strpos($query," CLASS ")===false){
							if(strpos($query,"PROPERTIES ")===false || strpos($query," CLASS ")===false){
								if(strpos($query,"SEARCH ")===false || strpos($query," FROM ")===false){
									if(strpos($query,"INSERT ")===false||strpos($query," FROM ")===false){
										if(strpos($query,"UPDATE ")===false||strpos($query," FROM ")===false){
											if(strpos($query,"UPSERT ")===false||strpos($query," FROM ")===false){
												if(strpos($query,"DELETE ")===false||strpos($query," FROM ")===false){
													if(strpos($query,"SELECT ")===false || strpos($query," FROM ")===false){
														if(strpos($query,"CLASS_INFO")===false ) {//PROJECTION			
															if(strpos($query,"INSERT ")===false||strpos($query," RECORD")===false){	
																if(strpos($query,"CURRENT ")===false || strpos($query," NODE")===false){
																	if(strpos($query,"THIS")===false && strpos($arr_tok[0],"(")===false) {
																		if(strpos($query,"SET ")===false || strpos($query," TABLE ")===false) {
																			return $this->db_query($query);
																		}else{
																			return $this->query_select_class($query);}
																	}else{
																		return $this->class->class_query($query);}		
																}else{
																	return $this->query_current_node($query);}	
															}else{
																return $this->query_insert_record($query);} 
														}else{
															return $this->info_class();}
													}else{
														return $this->class->select_query($query);}
												}else{
													return $this->class->select_query($query);}
											}else{
												return $this->class->select_query($query);}
										}else{
											return $this->class->select_query($query);}
									}else{
										return $this->class->select_query($query);}
								}else{
									return $this->db_query($query);}
							}else{
								return $this->query_view_class($query);}
					}else{
						return $this->query_reset_class($query);}
				}else{
						return $this->commit();}
			}else{
					return $this->begin();}
		}else{
			return $this->is_select_query($query);}
		}
}//EndClass		
">