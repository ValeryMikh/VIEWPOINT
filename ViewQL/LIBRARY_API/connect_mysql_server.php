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
 
	class  connect_mysql_server{

		/*var $mySQL = "";			//<Host name>
		var $mySQL_User = "";		//<User name>
		var $password = "";			//<Password 6 -:- 30 symbols>
		var $mySQL_Database = "";	//<Database name>*/
		
		var $mySQL = "localhost";
		var $mySQL_User ="root";//"valery";
		var $password =  "";
		var $mySQL_Database = "platform";//"platform";
	
		var $connection=0;
		function connect(){
			if(empty($this->mySQL) || empty($this->mySQL_User) || empty($this->mySQL_Database)){
				echo "WARNING! CONNECTION OPTIONS.";
				exit;
			}
			$connect=mysqli_connect($this->mySQL, $this->mySQL_User,$this->password,$this->mySQL_Database) ;		
			if(!$connect)
				die("Total Fail".mysqli_connect_error()) ;
			else{
				echo "<BR>CONNECTION SUCCESSFUL.<BR><BR>";
				$this->connection=$connect;	
			}
			//var_dump(get_object_vars($connection));
		}
		function get_connection(){
			if(!isset($this->connection)){
				echo 'Variable name is not set';
				exit;
			}
			return $this->connection;
		}
		function get_name_Database(){
			return $this->mySQL_Database ;
		}

	}//END CLASS
">