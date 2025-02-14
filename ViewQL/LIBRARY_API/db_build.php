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
	class db_build extends db_serializer {//db_classes 212
		function db_build($prefix_db=""){
			$this->db_serializer($prefix_db);//db_classes
		}
		
		//////////////////////////////////////////////////////////////////////////////
		//								BUILD  CLASS									//	
		//////////////////////////////////////////////////////////////////////////////
		function build_class($name_class,$id_class){			
			if($this->is_class_name($name_class))
				return 0;
			if(!$this->add_class($name_class,$id_class))
				return 0;
			return $id_class;
		}
		//////////////////////////////////////////////////////////////////////////////
		//					BUILD COMPONENT_CONTAINER								//	
		//////////////////////////////////////////////////////////////////////////////
		function build_component_container($name_container,$id_container){
			if($this->is_class_name($name_container))
				return 0;
			if(!$this->add_component_container($name_container,$id_container))
				return 0;
			return $id_container;
		}
		//////////////////////////////////////////////////////////////////////////////
		//					BUILD VIEW OF CLASS								//	
		//////////////////////////////////////////////////////////////////////////////
		function build_interface_class($name_interface,$id_interface){
			if($this->is_class_name($name_interface))
				return 0;
			if(strpos($name_interface,".")===false)		
				return 0;
			if(!$this->add_interface_class($name_interface,$id_interface))
				return 0;
			return $id_interface;
		}
		//////////////////////////////////////////////////////////////////////////////
		//							BUILD COMPONENT									//	
		//////////////////////////////////////////////////////////////////////////////
		function build_component($name_component){
			if($this->is_class_name($name_component))
				return 0;
			if(!$this->add_component($name_component))
				return 0;
			return 1;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		//								INHERITANCE									//	
		//////////////////////////////////////////////////////////////////////////////
		
		function alter_inheritance($class_extend,&$class){
			if(!$class_extend->is_extends_class())
				return 0;
			if(!empty($class_extend->array_extends_class)){	
				foreach($class_extend->array_extends_class as $key => $arr){
					if(empty($class->array_extends_class[$key])&& 	// OVERLOAD METHOD:
					!empty($arr) && empty($arr[3]))					// HIDING METHOD: PRIVATE|"" //|PUBLIC 							
						$class->array_extends_class[$key]=$arr;	
				}
			}
			if(!empty($class_extend->array_relations_class)){
				foreach($class_extend->array_relations_class as $key => $arr){
					if(!$class->is_relation_class($arr[0],$arr[1]) && 	// OVERLOAD METHOD:				
					!empty($arr) && empty($arr[3]))						// HIDING METHOD: PRIVATE|"" //|PUBLIC 	 						
						$class->add_relation_class($arr[0],$arr[1],$arr[2]);
				}
			}
			if(!empty($class_extend->array_pointers_class)){
				foreach($class_extend->array_pointers_class as $key => $arr){
				
					if(!$class->is_pointer_class($arr[0],$arr[1]) && 	// OVERLOAD METHOD:				
					!empty($arr) && empty($arr[3]))						//HIDING METHOD: PRIVATE|"" //|PUBLIC 	 						
						$class->add_pointer_class($arr[0],$arr[1],$arr[2]);	
				}
			}
			return 1;
		}
		/////////////////////////////////////////////////////////////////////////////////////////////
		function alter_inheritance_delete(&$class){
			if(!empty($class->array_extends_class)){
				foreach($class->array_extends_class as $key => $arr){
						$class->arr="";
				}				
				$class->array_extends_class=$class->remove_empty($class->array_extends_class);
			}
			if(!empty($class->array_relations_class)){
				foreach($class->array_relations_class as $key => $arr){
						$class->arr="";
				}
				$class->array_relations_class=$class->remove_empty($class->array_relations_class);
			}
			if(!empty($class->array_pointers_class)){
				foreach($class->array_pointers_class as $key => $arr){
						$class->arr="";
				}
				$class->array_pointers_class=$class->remove_empty($class->array_pointers_class);
			}
			return 1;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		//								ALTER CLASS									//	
		//////////////////////////////////////////////////////////////////////////////
				
		function alter_extend($arr_extend,&$class,$type_class,$flg_immed=0){////////////// 09.006

			/*$arr_extends[0]//$name_class;
			$arr_extends[1]//control
			$arr_extends[2]//name_class component
			$arr_extends[3]//STATIC|"" 
			$arr_extends[4]//PRIVATE|""*/
			$name_class=trim($arr_extend[0]);
								// inheritance class
			$control_extend=trim($arr_extend[1]);
			$name_extend=trim($arr_extend[2]);//PRODUCT}STRING
			$flg_relation=0;
			$pos_1=strpos($name_extend,'!');
			if($pos_1){
				$name_extend = trim(substr($name_extend,0,strlen($name_extend)-1));	
				$flg_relation=1;
			}
			$name_extend=trim($name_extend);

			$static_extend=$arr_extend[3];//STATIC|"" content_extend
			$privat_extend=$arr_extend[4];//PRIVATE|""
			$id_extend=0;
			
			$flg_is_component=0;
			$flg_is_extend=0;
			$flg_is_interface=0;
			$flg_is_pointer=0;
			$flg_is_relation=0;
			if($name_class==$name_extend)
				$class_extend_type=	$type_class;			
			elseif(!$class_extend_type=$this->is_type_class($name_extend)){
				if(strpos($name_extend,".")===false)
					$class_extend_type="CLASS";
				else{
					$arr_exp=explode(".",$name_extend);
					if(isset($arr_exp[1]))
						$class_extend_type="VIEW";
					else{
						$this->warning("Syntaxerror unexpected ");	
						return 0;
					}
				}
			}				
			$full_name_extend=$name_extend;
			if($class_extend_type=="VIEW"){
				$arr_exp=explode(".",$name_extend);
				$name_interface_extend=$name_extend;
				$name_extend_extend=trim($arr_exp[1]);
				$name_extend=trim($arr_exp[0]);
			}
			//		CONTROL
			if($type_class=="CONTAINER"){
				if($class_extend_type=="COMPONENT"){
					$flg_is_component=1;
				}elseif($class_extend_type=="CONTAINER"){
					$flg_is_extend=1;				
				}else
					return 0;
			}elseif($type_class=="VIEW"){
				$arr_exp=explode(".",$name_class);
				$name_interface=$name_class;
				$name_class_extend=trim($arr_exp[1]);
				$name_class=trim($arr_exp[0]);

				if($class_extend_type=="COMPONENT"){
					$flg_is_component=1;
				}elseif($class_extend_type=="CONTAINER"){
					$flg_is_extend=1;							
				}elseif($class_extend_type=="VIEW" || $class_extend_type=="CLASS"){
echo $name_interface_extend."==".$name_interface ."==". $flg_relation;
					if($name_interface_extend==$name_interface && $flg_relation==0){//SELF //222
						$flg_is_pointer=1;
					}else{
						$flg_is_relation=1;//ANOTHER												
					}
				}else{
					return 0;
				}

			}elseif($type_class=="CLASS"){
				if($class_extend_type=="VIEW"){
					if($name_extend==$name_class)
						$flg_is_interface=1;
				}else{
					return 0;
				}		
			}else{
				return 0;
			}
								//ALTER
			if($flg_is_component){
			   if(!$class->add_extends_class($control_extend,$name_extend,$static_extend,$privat_extend))
					return 0;			
			}elseif($flg_is_extend){
				if(!$class_extend=$this->get_serialize_class_from_name($full_name_extend))
					return 0;
				if(!empty($class_extend->array_inheritance_class))
					$class->array_inheritance_class[$full_name_extend]=$class_extend->array_inheritance_class;
				else
					$class->array_inheritance_class[$full_name_extend]="EMPTY";//$name_extend
				if(!$this->alter_inheritance($class_extend,$class))
					return 0;	
			}elseif($flg_is_relation){///////////////
					$type_operation ="ADD";//::= ADD[DEL]
					$type_relation ="RELATION";//::= RELTION[POINTER]
					$arr_relation[3]="";//::= STATIC[DUNAMIC] 
					$arr_relation[4]=$full_name_extend;// ::= NAME CLASS
					$arr_relation[5]="";//::= PRIVATE[""::=PUBLIC] (hiding)  //old Incheritance CLASS
					$arr_relation[6]=1;// ::= {1::= ->[-1::= <-]}			
					//$arr_relation[7]=$flg_is_self;//SBOI=1|CHUGOI=0
					$arr_relation[7]=0;
					$arr_relation[8]=$flg_immed;//IMMED=1|0
					if(!$this->alter_single_relation($class,$arr_relation,$type_relation,$type_operation))
						return 0;
			}elseif($flg_is_pointer){
					$content="";//DINAMIC|CTATIC		
					if(!$class->add_pointer_class($full_name_extend,_PARENT,$content))
						return 0;
					if(!$class->add_pointer_class($full_name_extend,_CHILDREN,$content))
						return 0;					
					//var array_pointers_class
						//::=array([control]=>array({
						//[0]=>pointer,::=pointer --> name_class(USER)
						//[1]=>direction,::=direction(_PARENT|_CHILDREN(->|<-))
						//[2]=>plase_content,::=plase(DINAMIC|CTATIC(into CLASS|OBJECT))
						//[3]=>access,::=access(PUBLIC|PRIVATE)
						//[4]=>interface}::=interface --> name_interface(USER.DATE)
						//}.,..));					
			}elseif($flg_is_interface){
				if(!in_array($name_interface_extend,$class->list_interface))
					$class->list_interface[]=$name_interface_extend;
			}
			return 1;
		}		

		//////////////////////////////////////////////////////////////////////////////
		//				ALTER CLASS IHERITANCE (SET PROTECTION OF CLASS)						//	
		//////////////////////////////////////////////////////////////////////////////
							
		function alter_class_inheritance($name_alter_class,&$class){//
		 	if(!$class->is_inheritance_class($name_alter_class))
				return 0;
			if(!$class_alter=$this->get_serialize_class_from_name($name_alter_class))
				return 0;
			$id_class=$class->get_id_class();
			$name_class=$class->get_name_class();
			
			$this->alter_inheritance_delete($class);	///////////////////		
			//$class->array_extends_class=array();
			//$class->array_relations_class=array();
			//$class->array_pointers_class=array();
			//$class->array_inheritance_class=array();
			$class->flg_serializ=0;
			if(!$this->alter_inheritance($class_alter,$class))
				return 0;
			return 1;
		}
		//////////////////////////////////////////////////////////////////////////////
		//					ALTER CLASS ADD[DELETE] RELATION[POINTER] -> | <- 		//	
		//////////////////////////////////////////////////////////////////////////////

		function alter_single_relation(&$class,$arr_relation,$type_relation,$type_operation){
			//$type_relation::=_RELATION|_POINTER					
		/*	$class ::= current class object 
			$type_operation ::= ADD[DEL]
			$type_relation ::= RELTION[POINTER]
			$arr_relation ::=
				$arr_relation[3] ::= STATIC[DUNAMIC] 
				$arr_relation[4] ::= NAME CLASS
				$arr_relation[5] ::= PRIVATE[""::=PUBLIC] (hiding)  //old Incheritance CLASS			
			$arr_relation[6] ::= {1::= ->[-1::= <-]}
			$arr_relation[7] ::=($flg_is_self)SBOI=1|CHUGOI=0
			$arr_relation[8]=$flg_immed;//IMMED=1|0
			*/
			$name_relation=trim($arr_relation[4]);
			$hiding=trim($arr_relation[5]);					
			$content=trim($arr_relation[3]);
			$flg_is_self=trim($arr_relation[7]);				
			$flg_immed=trim($arr_relation[8]);//IMMED=1|0
			if($arr_relation[6]==1 || !$arr_relation[6])			
					$dir=_DIRECT;
				else	
					$dir=_FEEDBACK;
				$arr_relation[6]=$dir;
			if($type_relation=="RELATION" && $type_operation=="ADD"){
				if($relation=$class->get_serialize_class_from_name($name_relation))
					$arr_relation[6]=$class->get_direction_relationship($relation,$dir);
				else
					$class->set_direction_relationship($name_relation,$dir);
			//Array ( [1] => Array ( [0] => PRODUCT [1] => DIRECT [2] =>""[3] =>""[4]=>PRODUCT.BAD [5] =>IMMED)
			}
			if($arr_relation[6]==_DIRECT){			
				if($type_relation=="RELATION"){
					$name_extend=_DIRECT;
					if($type_operation=="ADD"){
						if(!$class->add_relation_class($name_relation,$name_extend,$content,$hiding,"",$flg_immed))
							return 0;
					}elseif($type_operation=="DEL"){
						if(!$class->del_relation_class($name_relation,$name_extend))
							return 0;
					}
				}else{						// POINTER
					$name_extend=_PARENT;
					if($type_operation=="ADD"){
						if(!$class->add_pointer_class($name_relation,$name_extend,$content,$hiding))
							return 0;
					}elseif($type_operation=="DEL"){
						if(!$class->del_pointer_class($name_relation,$name_extend))
							return 0;
					}
				}
			}
			if($arr_relation[6]==_FEEDBACK){
				if($type_relation=="RELATION"){
					$name_extend=_FEEDBACK;
					if($type_operation=="ADD"){
						if(!$class->add_relation_class($name_relation,$name_extend,$content,$hiding,"",$flg_immed))
							return 0;
					}elseif($type_operation=="DEL"){
						if(!$class->del_relation_class($name_relation,$name_extend))
							return 0;
					}
				}else{// POINTER
					$name_extend=_CHILDREN;
					if($type_operation=="ADD"){
						if(!$class->add_pointer_class($name_relation,$name_extend,$content,$hiding))
							return 0;
					}elseif($type_operation=="DEL"){
						if(!$class->del_pointer_class($name_relation,$name_extend))
							return 0;
					}
				}
			}
			return 1;
		}										
		//////////////////////////////////////////////////////////////////////////////
		//					ALTER|CLASS ADD[DEL] RELATION[POINTER] + (<->) 						//	
		//////////////////////////////////////////////////////////////////////////////
		
		function alter_double_relation($arr_relation,&$arr_classes,$type_relation,$type_operation){
		/*	$type_relation::=_RELATION|_POINTER				
			$type_operation ::= ADD[DEL]
			$type_relation ::= RELTION[POINTER]
			$arr_classes ::= array classes, kotorie neobhodimo serializovat
			$arr_relation ::=
				$arr_relation[0]::= STATIC[DUNAMIC]
				$arr_relation[1]::= CLASS	
				$arr_relation[2]::= PRIVATE[""]  ""::=PUBLIC(hiding)
							// relation
				[
				$arr_relation[3]::= STATIC[DUNAMIC] 
				$arr_relation[4]::= CLASS
				$arr_relation[5]::= PRIVATE[""]  ""::=PUBLIC(hiding)
				]			
			$arr_token_rez[6]::= 1 ::= -> [-1::= <-][2 ::= <->]*/			
			for($i=0;$i<count($arr_relation);$i++){
				$arr_relation[$i]=trim($arr_relation[$i]);
			}
			$name_class=$arr_relation[1];			
			$id_class=$this->get_id_class_from_name($name_class);
			if(!$class=$this->get_serialize_class_from_name($name_class))
				return 0;
			if($arr_relation[1]!=$arr_relation[4]){
				$name_relation=$arr_relation[4];
				$id_relation=$this->get_id_class_from_name($name_relation);			
				if(!$relation=$this->get_serialize_class_from_name($name_relation))
					return 0;
			}else{			
				$name_relation=$name_class;
				$id_relation=$id_class;			
				$relation=$class;
			}
			//Test na sushectvuet DIRECT. Esli uge sushectvuet	DIRECT sviaz to knei dobavitsia _FEEDBACK
			if($arr_relation[6]==2 || $arr_relation[6]==1){
				$hiding_relation=$arr_relation[5];
				$content_class=$arr_relation[0];
				if($type_relation=="RELATION"){
					$name_extend_class=_DIRECT;
					if($type_operation=="ADD"){
						if($class->add_relation_class($name_relation,$name_extend_class,$content_class,$hiding_relation))
							if(empty($arr_classes[$id_class]))
								$arr_classes[$id_class]=$class;
					}elseif($type_operation=="DEL"){
						if($class->del_relation_class($name_relation,$name_extend_class,$content_class))
							if(empty($arr_classes[$id_class]))
								$arr_classes[$id_class]=$class;
					}
				}else{							// POINTER
					$name_extend_class=_PARENT;
					if($type_operation=="ADD"){					
						if($class->add_pointer_class($name_relation,$name_extend_class,$content_class,$hiding_relation))
							if(empty($arr_classes[$id_class]))
								$arr_classes[$id_class]=$class;
					}elseif($type_operation=="DEL"){
						if($class->del_pointer_class($name_relation,$name_extend_class,$content_class))
							if(empty($arr_classes[$id_class]))
								$arr_classes[$id_class]=$class;
					}
				}
			}
			if($arr_relation[6]==2 || $arr_relation[6]==-1){
				$hiding_class=$arr_relation[2];						
				$content_relation=$arr_relation[3];				
				if($type_relation=="RELATION"){
					$name_extend_relation=_FEEDBACK;
					if($type_operation=="ADD"){
						if($relation->add_relation_class($name_class,$name_extend_relation,$content_relation,$hiding_class))
							if(empty($arr_classes[$id_relation]))
								$arr_classes[$id_relation]=$relation;
					}elseif($type_operation=="DEL"){
						if($relation->del_relation_class($name_class,$name_extend_relation,$content_relation))
							if(empty($arr_classes[$id_relation]))
								$arr_classes[$id_relation]=$relation;
					}		
				}else{						// POINTER
					$name_extend_relation=_CHILDREN;
					if($type_operation=="ADD"){
						if($relation->add_pointer_class($name_class,$name_extend_relation,$content_relation, $hiding_class))
							if(empty($arr_classes[$id_relation]))
								$arr_classes[$id_relation]=$relation;
					}elseif($type_operation=="DEL"){
						if($relation->del_pointer_class($name_class,$name_extend_relation,$content_relation))
							if(empty($arr_classes[$id_relation]))
								$arr_classes[$id_relation]=$relation;
					}
				}
			}
			return 1;
		}
		//////////////////////////////////////////////////////////////////////////////
		//					ADD|DELETE RELATION[POINTER] 							//	
		//////////////////////////////////////////////////////////////////////////////
		//$type_relation::=_RELATION|_POINTER	
		function ins_update_relation($str_from,$str_relation,$type_relation,$type_operation){
			$arr_from=array();
			$arr_token = explode(",", $str_from);
			for($i=0;$i<count($arr_token);$i++){
				if(!$obj_class=$this->get_serialize_class_from_full_name(trim($arr_token[$i]))){
					$this->error("Class '".$arr_token[$i]."' doesn't exist;");
					return 0;
				}
				$obj_class->show_extends();
				$arr_tmp = explode(".", $arr_token[$i]);
				$arr_from[trim($arr_tmp[0])]=$obj_class;
			}
			$arr_from_key=array_keys($arr_from);
			$arr_relation = explode(",", $str_relation);
			for($i=0;$i<count($arr_relation);$i++){
				$arr_relation_info=array();
				if(strpos($arr_relation[$i],"->")===false){
					if(strpos($arr_relation[$i],"<-")===false){
						if(strpos($arr_relation[$i],"<->")===false){					
							$this->warning("Syntaxerror unexpected ");	
							return 0;
						}else
							$flg_direction=2;//<-(_CHILDREN|_FEEDBACK )|(_PARENT|_DIRECT )
					}else
						$flg_direction=-1;//<-(_CHILDREN|_FEEDBACK )
				}else
					$flg_direction=1;// ->(_PARENT|_DIRECT )
					
				if($flg_direction==1)	
					$arr_relation_item = explode("->", $arr_relation[$i]);
				elseif($flg_direction==-1)
					$arr_relation_item = explode("<-", $arr_relation[$i]);
				elseif($flg_direction==2)
					$arr_relation_item = explode("<->", $arr_relation[$i]);
					
				if(empty($arr_relation_item) || count($arr_relation_item)!=2 ){
					
					continue;
				}					
				if($flg_direction==1 || $flg_direction==2){
					$id_direct=$arr_relation_item[0];//id_
					$id_feedback=$arr_relation_item[1];
				}else{
					$id_direct=$arr_relation_item[1];
					$id_feedback=$arr_relation_item[0];
				}
					//TEST					
				if(empty($id_direct)||empty($id_feedback)){
					
					continue;
				}					
				
				$info_direct=$this->get_name_info($id_direct);
				$info_feedback=$this->get_name_info($id_feedback);
				
				$name_direct=trim($info_direct[1]);
				$name_feedback=trim($info_feedback[1]);

				if(!$name_direct||!$name_feedback){
					
					continue;
				}
					
				$direct=$arr_from[$name_direct]; 
				$feedback=$arr_from[$name_feedback];
				if($flg_direction==1 || $flg_direction==-1){
					if($type_relation==_RELATION){
						if(!$direct->is_direct_class($name_feedback)){//.".GALLERY "
							
							continue;
						}
					}elseif($type_relation==_POINTER){	
						if(!$direct->is_parent_class($name_feedback))	{
							
							continue;
						}
					}else{
						
						continue;
					}
				}
				
				if($flg_direction==2){
					if($type_relation==_RELATION){
						if(!$direct->is_direct_class($name_feedback) || !$feedback->is_feedback_class($name_direct)){						
							if($direct->is_direct_class($name_direct) && $feedback->is_feedback_class($name_feedback)){
								$tmp=$id_direct;
								$id_direct=$id_feedback;
								$id_feedback=$tmp;
								$tmp=$name_direct;
								$name_direct=$id_feedback;
								$name_feedback=$tmp;
							}else{
								
								continue;
							}
						}
					}elseif($type_relation==_POINTER){		
						if(!$direct->is_parent_class($name_feedback) || !$feedback->is_children_class($name_direct)){						
							if($direct->is_parent_class($name_direct) && $feedback->is_children_class($name_feedback)){
								$tmp=$id_direct;
								$id_direct=$id_feedback;
								$id_feedback=$tmp;
								$tmp=$name_direct;
								$name_direct=$id_feedback;
								$name_feedback=$tmp;
							}else{
								
								continue;
							}
						}
					}
				}
				if($type_relation==_RELATION)
					$obj_relation=new db_relation_table($this->prefix_db);
				else
					$obj_relation=new db_category_table($this->prefix_db);//_POINTER	
				
				$arr_options[0]=$id_direct;
				$arr_options[1]=$id_feedback;
	
				if($type_operation=="INSERT"){
					if(!$obj_relation->add($arr_options))	
						return  0;
				}else{	
					if(!$obj_relation->del($arr_options))
						return  0;
				}
			}
			return 1;
		}
		//////////////////////////////////////////////////////////////////////////////
		//								MAKE OBJECT									//
		//////////////////////////////////////////////////////////////////////////////
		
		function build_object($name){//name_class.name_object
			$arr_token = explode(".", $name);
			if(count($arr_token)==1){
				$name_class=trim($arr_token[0]);
				$name="";
			}elseif(count($arr_token)==2){
				$name_class=trim($arr_token[0]);
				$name=trim($arr_token[1]);
			}else{
				echo "ERROR: Do'n build  Object! <BR>";
				return 0;
			}
			if(!$this->is_class_name($name_class)){
				echo "ERROR: Class name! <BR>";
				return 0;
			}
			if(!$id_class=$this->get_id_class_from_name($name_class)){
				return 0;
			}
			$obj_table = new db_tables($this->prefix_db);	
			if(!$id_object=$obj_table->add_object_to_class($id_class)){//,$name
				
				return 0;				
			}
			return $id_object;
		}
		
	}//end class
	
?>