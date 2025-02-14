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
function broker(){
	$sess_name_broker=$GLOBALS["broker_name"];		
	if(empty($_SESSION[$sess_name_broker])){
	    $broker=new db_broker() or die ('I_cannot_build_to_the_BROKER ');
		$query="BROKER NAME $sess_name_broker ";
	    $broker->broker_query($query);
		//After executing this command, each class from the list will be assigned a unique name and will be connected to the broker.		 	
		$query="SET AGENTS ". $GLOBALS["list_agents"];  //,<name agent>=<name class>.,..
	    $broker->broker_query($query);
		//Allows these program modules to receive requests from clients.			
	 	$query="SET SPECIAL AGENTS " . $GLOBALS["list_cpecial_agents"]; //,<name agent>.,.. 
	    $broker->broker_query($query);
		//Initialization of program modules.
		$query="INITIALIZE AGENTS";
		$broker->broker_query($query); 	
	}else{		
		$serialize_broker=$_SESSION[$sess_name_broker];	
		$broker=unserialize($serialize_broker);;	
	}
	//The command saves the current state of the program modules in the session and puts the broker into the mode of waiting for a request from the client.
	$query="REQUEST BROKER";
	$broker->broker_query($query);
	exit; 
}
?>