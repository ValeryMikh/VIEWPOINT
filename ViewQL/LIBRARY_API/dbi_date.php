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

 		  /*#########################################################################
										db_interface_date	
			#########################################################################*/
 
	//class db_interface_date	extends dbol_interface{
	class db_interface_date	extends db_interface{
		var $obj_component;
		function db_interface_date($name_component){
			$list_interface=array("SET","RESET","CREATE","SELECT","UPSERT","UPDATE","INSERT","DELETE",
							"EDIT","ERASE","SUBMIT","INCREMENT","DECREMENT");//,"SHOW_INTERFACE"							
			$this->db_interface($name_component,$list_interface);
		}
		function SET($arr_option){
			$obj_class=$arr_option[0];//$obj_class ::=object of class container inheritanse this components.
			$method=$arr_option[1];// $method::=name of method(control) this class($obj_class);
			$this->obj_component=$this->dbol_interface_get_object_component();
			if(!empty($arr_option[2]) && $arr_option[2]==_STATIC){
				if(!$this->obj_component->set($obj_class,$method,_STATIC))return 0;return 1;
			}else{
				if(!$this->obj_component->set($obj_class,$method))return 0;return 1;
			}
		}
		
		function RESET($arr_options=0){if(!$this->obj_component->reset_memory())return 0;return 1;}
		//	DB
		function CREATE($arr_options=0){if(!$this->obj_component->create())return 0;return 1;}
		function SELECT($arr_options,&$arr_buf){if(!$rezult=$this->obj_component->select($arr_options,$arr_buf))return 0;return $rezult;}//,$arr_buf
		function UPSERT($arr_options){return $this->obj_component->upsert($arr_options);}//22
		function UPDATE($arr_options){if(!$this->obj_component->update($arr_options))return 0;return 1;}	
		function INSERT($arr_options){if(!$this->obj_component->insert($arr_options))return 0;return 1;}
		function DELETE($arr_options){if(!$this->obj_component->delete())return 0;return 1;}
		
		function PUT($arr_options){$this-> UPDATE($arr_options);}
		function GET ($arr_options=0){$this-> SELECT($arr_options);}

		//	MEMERY
		function EDIT($arr_options){if(!$this->obj_component->edit($arr_options))return 0;return 1;}
		function ERASE($arr_options=0){if(!$this->obj_component->show())return 0;return 1;}
		function SUBMIT($arr_options=0){if(!$this->obj_component->submit())return 0;return 1;}

		function INCREMENT($arr_options=0){if(!$rezult=$this->obj_component->increment())return 0;return $rezult;}	
		function DECREMENT($arr_options=0){if(!$rezult=$this->obj_component->decrement())return 0;return $rezult;}	
		

	}//end_class
				   /************************************************************
                            		DATE
 				************************************************************/	
  	class DATE extends db_interface_date {
 		function DATE(){
			$this->db_interface_date("db_date_content");
		}
		function INCREMENT($arr_options=0){}
		function DECREMENT($arr_options=0){}
	}//end_class
  
 			   /************************************************************
                            		INTEGER
 				************************************************************/	
  	class INTEGER extends db_interface_date {
		function INTEGER(){
			$this->db_interface_date("db_integer_content");
		}
		
	}//end_class
	
 			   /************************************************************
                            			TINYINT
 				************************************************************/	
  	class TINYINT extends db_interface_date {
		//db_integer_content::=name of class implemention component.
		function TINYINT(){
			$this->db_interface_date("db_tinyint_content");
		}
		
	}//end_class
	
	 			   /************************************************************
                            		FLOAT
 				************************************************************/	
  	class FLOAT extends db_interface_date {
		//db_integer_content::=name of class implemention component.
		function FLOAT(){
			$this->db_interface_date("db_float_content");
		}
		
	}//end_class
 			   /************************************************************
                            	DECIMAL(10,2)
 				************************************************************/	
  	/*class DOUBLE extends db_interface_date {
		//db_integer_content::=name of class implemention component.
		function DECIMAL(){
			$this->db_double_content("db_decimal_content");
		}
		
	}//end_class */
 			   /************************************************************
                            	DECIMAL(10,8)
 				************************************************************/	
  	class DECIMAL extends db_interface_date {
		//db_integer_content::=name of class implemention component.
		function DECIMAL(){
			$this->db_interface_date("db_decimal_2_content");
		}
		
	}//end_class 

				   /************************************************************
										STRING
					************************************************************/	
  	class STRING extends db_interface_date {
		//db_string_content::=name of class implemention component.
		function STRING(){
			$this->db_interface_date("db_string_content");
		}
	}//end_class
	 			   /************************************************************
                            			TEXT
 					************************************************************/	
 	class TEXT extends db_interface_date {
		//db_text_content::=name of class implemention component.
		function TEXT(){
			$this->db_interface_date("db_text_content");
		}
		function INCREMENT($arr_options=0){}
		function DECREMENT($arr_options=0){}
	}//end_class
				/************************************************************
											CRZTEXT
				 ************************************************************/	
 	class TEXT_SERIALIZE extends db_interface_date {
		//db_text_content::=name of class implemention component.
		function TEXT_SERIALIZE(){
			$this->db_interface_date("db_text_serialize_content");
		}
		function INCREMENT($arr_options=0){}
		function DECREMENT($arr_options=0){}
	}//end_class

 			   		/************************************************************
                            			BLOB
 					************************************************************/	
  	class BLOB extends db_interface_date {
		//db_blob_content::=name of class implemention component.
		function BLOB(){
			$this->db_interface_date("db_blob_content");
		}
		function INCREMENT($arr_options=0){}
		function DECREMENT($arr_options=0){}
	}//end_class
	
		/*#########################################################################
											ARRAY DATE INTERFACES		
		#########################################################################*/
	class db_interface_array_date extends db_interface{
		var $obj_component;
		function db_interface_array_date($name_component) {
			//$name_component ::= name of class implemention this component.
			$list_interface=array("SET","RESET",
								"SET_KEY","SELECT_KEY",
								"EDIT","ERASE","SUBMIT",
								"CREATE","SELECT","UPSERT","UPDATE","INSERT","DELETE",//22
								"SET_INTERFACE","SHOW_INTERFACE");
			$this->db_interface($name_component,$list_interface);	
		}
		/*function warning($str){
			echo"WARNING '$str'!<BR>";
		}*/
		function SET($arr_option){
			//echo "SET-".$arr_option[1].$arr_option[2].$arr_option[0]; //print_r($arr_option);echo "<BR>";			
			$obj_class=$arr_option[0];//$obj_class ::=object of class container inheritanse this components.
			$method=$arr_option[1];// $method::=name of method(control) this class($obj_class);
			$this->obj_component=$this->dbol_interface_get_object_component();
			if(!empty($arr_option[2]) && $arr_option[2]==_STATIC){
				if(!$this->obj_component->set($obj_class,$method,_STATIC))return 0;return 1;
			}else{
				if(!$this->obj_component->set($obj_class,$method))return 0;return 1;
			}
		}
		function RESET($arr_options=0){if(!$this->obj_component->reset_memory())return 0;return 1;}
		//	DB
		function CREATE($arr_options=0){if(!$this->obj_component->create())return 0;return 1;}
		function SELECT($arr_options=0,& $arr_buf=0){//,$arr_buf
			return $this->obj_component->select($arr_options,$arr_buf);}
		function UPSERT($arr_options=0){
			if(empty($arr_options)){
				return 0;}		
			if(!$this->obj_component->upsert($arr_options))return 0;return 1;}
		function UPDATE($arr_options=0){
			if(empty($arr_options)){
				return 0;}		
			if(!$this->obj_component->update($arr_options))return 0;return 1;}	
		function INSERT($arr_options=0){
			if(!$this->obj_component->insert($arr_options))return 0;return 1;}
		function DELETE($arr_options=0){
			if(!$this->obj_component->delete($arr_options))return 0;return 1;}
		//	MEMERY
		function EDIT($arr_options=0){
			if(empty($arr_options)){
				return 0;}		
			if(!$this->obj_component->edit($arr_options))return 0;return 1;}
		function ERASE($arr_options=0){
			if(empty($arr_options)){
				return 0;}		
			if(!$this->obj_component->erase($arr_options))return 0;return 1;}
		function SUBMIT($arr_options=0){if(!$this->obj_component->submit())return 0;return 1;}
		//	KEY
		function SET_KEY($arr_options){if(!$this->obj_component->set_key($arr_options))return 0;return 1;}
		function SELECT_KEY($arr_options=0){return $this->obj_component->select_key();}
			
	}//end_class


 			   /************************************************************
                            		ARRAY STRING
 				************************************************************/	
  	class ARRAY_STRING extends db_interface_array_date {
		function ARRAY_STRING(){
			$this->db_interface_array_date("db_array_string_content");
		}
	}//end_class
			   /************************************************************
                            		ARRAY TEXT
 				************************************************************/	
  	class ARRAY_TEXT extends db_interface_array_date {
		function ARRAY_TEXT(){
			$this->db_interface_array_date("db_array_text_content");
		}
	}//end_class
			   /************************************************************
                            		ARRAY INTEGER
 				************************************************************/	
  	class ARRAY_INTEGER extends db_interface_array_date {
		function ARRAY_INTEGER(){
			$this->db_interface_array_date("db_array_integer_content");
		}
	}//end_class
			   /************************************************************
                            		ARRAY TINYINT
 				************************************************************/	
  	class ARRAY_TINYINT extends db_interface_array_date {
		function ARRAY_TINYINT(){
			$this->db_interface_array_date("db_array_tinyint_content");
		}
	}//end_class
	
				/************************************************************
                            		ARRAY FLOAT
 				************************************************************/	
  	class ARRAY_FLOAT extends db_interface_array_date {
		function ARRAY_FLOAT(){
			$this->db_interface_array_date("db_array_float_content");
		}
	}//end_class

?>