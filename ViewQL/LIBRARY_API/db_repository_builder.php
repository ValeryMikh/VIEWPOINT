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

													##############################
													#	REPOSITORY BUILDER	     #
													##############################
	#########################
 	class repository_builder extends gddl{//dbpl 
	######################### 
		function build_repository($password,$login){	
		##################
			$name_table="CLASSES";
			if($this->is_table_name($name_table)){
				print("WARNING! REPOSITORY EXIST ALREADY.");
				exit;
			}
			$this->new_db($this);

			$query="CREATE CLASS DB";		//DB.CONNECT
			if(!$this->dbpl_query($query)){
				$this->warning("Syntaxerror unexpected '$query'");	
				return 0;
			}

			$query="CREATE VIEW DB.DB EXTEND 		
				Date_creation=DATE,
				Status = STRING";
			if(!$this->dbpl_query($query)){
				$this->warning("Syntaxerror unexpected '$query'");	
				return 0;
			}
			$query="CREATE VIEW DB.ACCESS EXTEND 		
				Status = STRING,
				Login = STRING,
				Word = STRING";
			if(!$this->dbpl_query($query)){
				$this->warning("Syntaxerror unexpected '$query'");	
				return 0;
			}

			$date_creation = date("Y-m-d");
			$query="INSERT RECORD INTO DB"; //$query="CREATE OBJECT DB";
			if(!$id_db=$this->dbpl_query($query)){
				return 0;
			}
			$query="CURRENT NODE(DB.DB,THIS)";
			if(!$this->dbpl_query($query)){
				return 0;
			}
			$query="UPSERT(Date_creation = '$date_creation')";
			if(!$this->dbpl_query($query)){
				return 0;
			}
			$query="CURRENT NODE(DB.ACCESS,THIS)";
			if(!$this->dbpl_query($query)){
				return 0;
			}
			$query=$query="INSERT(Login='".$login."',Word='".$password."',Status='ADMIN')";
			if(!$this->dbpl_query($query)){
				return 0;
			}
			
			print("REPOSITORY  CREATED");
			return 1;
		}
		##################
		function new_db($id_class){				
		##################
				$id_class->add_component("INTEGER");
				$id_class->add_component("TINYINT");
				$id_class->add_component("FLOAT");
				$id_class->add_component("STRING");
				$id_class->add_component("TEXT");
				$id_class->add_component("BLOB");
				$id_class->add_component("DATE");
				$id_class->add_component("ARRAY_STRING");
				$id_class->add_component("ARRAY_TEXT");
				$id_class->add_component("ARRAY_INTEGER");
				$id_class->add_component("ARRAY_TINYINT");
				$id_class->add_component("ARRAY_FLOAT");
			return ;
		}	
		
		##################
		function drop_repository($db_name){				//222
		##################
			$query="DROP TABLE IF EXISTS ".
				 "$db_name._classes, ".
				 "$db_name._class_object, ".
				 "$db_name._counter_components, ".
				 "$db_name._counter_objects, ".
				 "$db_name._pointer,  ".
				"$db_name._relation, ".
				"$db_name._serialize_classes, ".
				"$db_name._text,  ".
				"$db_name._string,  ".
				"$db_name._array_string, ".			
				"$db_name._integer,  ".
				"$db_name._array_integer, ".							
				"$db_name._array_float, ".							
				"$db_name._array_tinyint, ".
				"$db_name._array_text, ".
				"$db_name._tinyint, ".
				"$db_name._date, ".
				"$db_name._blob, ".
				"$db_name._float ";
				//$id_db.strtoupper("double,";
				//$id_db.strtoupper("decimal";
			if(!mysqli_query($GLOBALS["connection"],$query)){						
				print ("ERROR DROP TABLES DB $id_db!<br>");	
				exit;
			}
			$name_table="CLASSES";
			if($this->is_table_name($name_table)){
				print("WARNING! REPOSITORY EXIST ALREADY.");
				exit;
			}
			print("REPOSITORY DROP.");
			exit;
		}	
}//END CLASS	
?>
