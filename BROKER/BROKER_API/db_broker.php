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
	#################################
    //       CLASS  DB_BROKER 
	#################################
	class db_broker{
		var $sess_name_broker="";
		var $name_base="";
		var $arr_vars=array();
		var $arr_special_agents=array()	;	// array( name   SPECIAL AGENTS .,..)
		var $arr_special_methods=array();	// array( name    AGENTS.METHOD .,..)
		var $arr_classes=array(); // array($name_AGENT =>$object .,..)

	    function __destruct  () {
			$this->save_broker(); 
		}
		################
		function get_sess_name_broker(){
		################
			return  $this->sess_name_broker;
		}
		################
		function save_broker(){ 
		################
			if(empty($this->sess_name_broker))
				exit( "! Conservation broker");
			$_SESSION[$this->sess_name_broker]=serialize($this);				
			return 1;			
		}		
		################
		function warning($message){ 
		################		
			echo "ERROR! ".$message."<BR>";
		}
		####################### 
		function broker_query($query){
		#######################
			$query=trim($query);
				if(strpos($query,"INITIALIZE ")===false || strpos($query,"AGENTS")===false)
					if(strpos($query,"BROKER ")===false || strpos($query,"NAME ")===false)
						if(strpos($query,"REQUEST ")===false)					
							if(strpos($query,"SET ")===false ||strpos($query,"SPECIAL ")===false || strpos($query,"AGENTS ")===false)
								if(strpos($query,"SET ")===false || strpos($query,"AGENTS ")===false)
									if(strpos($query,"GET ")===false || strpos($query,"AGENT ")===false)
											return 0;
									else
										return $this->get_class_query($query);
								else
									return $this->set_agents_query($query);	
							else
								return $this->set_special_agents_query($query);	
						else
							return $this->request_broker_query();
					else				
						return $this->name_session_query($query);
				else				
						return $this->initialize_agents_query();
		}
		
		function  name_session_query($query){
			$pos_1 = strpos($query,"NAME ")+5;
			if(!$name_broker=trim(substr($query,$pos_1,strlen($query)-$pos_1))){
				$this->warning("Syntaxerror B01 unexpected '$query'");
				return 0;
			}
			$this->sess_name_broker=$name_broker;
			return 1;
	    }
		
		function  initialize_agents_query(){
				foreach($this->arr_classes as $name_class=>$agent){
					if(method_exists($agent, 'initialize'))
						$agent-> initialize();
				}
			return 1;
	    }

		function  set_agents_query($query){
			$pos_1 = strpos($query,"AGENTS ")+7;
			if(!$str_tmp=trim(substr($query,$pos_1,strlen($query)-$pos_1))){
					$this->warning("Syntaxerror B02 unexpected '$query'");
					return 0;
			}
			$arr_token = preg_split("/,/", $str_tmp); 
			for($i=0;$i<count($arr_token);$i++){
 
				$arr_token_token= preg_split("/=/", $arr_token[$i]); 

				$name_agent=trim($arr_token_token[0]);
				if($name_agent=="BROKER"){
					$this->add_class($name_agent,$this);
					return 1;
				}
				$name_class=trim($arr_token_token[1]); 
				if(!$object=new $name_class($this)){
					$this->warning("Syntaxerror B02a '$name_class'unexpected '$query'");
					return 0;
				}
				if(!$this->add_class($name_agent,$object)){
					$this->warning("Syntaxerror B02b unexpected '$query'");
					return 0;
				}		
			}
			$this->info_classes();
			return 1;
	   }   
		############################
		function add_class($name_class,$object){
		############################
			if(empty($name_class) || empty($object))
				return 0;
			$name_class=trim($name_class);
			$this->arr_classes[$name_class]=$object;
			$this->info_classes();
			return 1;
		}
		 function is_class($str_class){
			if(empty($str_class))
				return 0;
			$str_class=trim($str_class);	
			if(empty($this->arr_classes[$str_class]) ){
				return 0;
			}				
			return 1;
		}		 
		function  get_class_query($query){
			$pos_1 = strpos($query,"AGENT ")+6;
			if(!$name_class=trim(substr($query,$pos_1,strlen($query)-$pos_1))){
				$this->warning("Syntaxerror B03 unexpected '$query'");
				return 0;
			}
			$obj=$this->get_class ($name_class);
			if(!$obj){
				$this->warning("Syntaxerror B03a unexpected '$query'");
				return 0;
			}
			return $obj;
		}
		############################
		function get_class ($name_class){
		############################
			if(empty($name_class))
				return 0;
			$name_class=trim($name_class);
			if($this->is_class($name_class) )
				return $this->arr_classes[$name_class];		
			return 0;
		}						
		
		#################################
		function set_special_agents_query($query){			
		#################################
			if(empty($query))
				return 0;
			$pos_1 = strpos($query,"AGENTS ")+7;
			if(!$str_agents=trim(substr($query,$pos_1,strlen($query)-$pos_1))){
					$this->warning("Syntaxerror B04 unexpected '$query'");
					return 0;
			}
			$arr_token = preg_split("/,/", $str_agents);
			for($i=0;$i<count($arr_token);$i++){
			   	$arr_token[$i]=trim($arr_token[$i]);
			}
			$this->arr_special_agents=$arr_token; 
			return 1;
		}
		function is_special_agent($name_agent){
			if(empty($name_agent))
				return 0;
			$name_agent=trim($name_agent);
			if( in_array($name_agent,  $this->arr_special_agents )){
				return 1;
			}
			return 0;
		}		
		############################
		function get_special_class ($name_class){
		############################
			if(empty($name_class))
				return 0;
			$name_class=trim($name_class);
			if($this->is_special_agent($name_class)&& $this->is_class($name_class) ){
				return $this->arr_classes[$name_class];
			}
			return 0;
		}				
		
		#################################
		function special_methods_query($query){			
		#################################
			if(empty($query))
				return 0;
			$pos_1 = strpos($query,"METHOD ")+7;
			if(!$str=trim(substr($query,$pos_1,strlen($query)-$pos_1))){
					$this->warning("Syntaxerror B05 unexpected '$query'");
					return 0;
			}
			$arr_token = preg_split("/,/", $str); 
			for($i=0;$i<count($arr_token);$i++){
			   	$arr_token[$i]=trim($arr_token[$i]);
			}
			$this->arr_special_methods =$arr_token;
			return 1;
		}

		function is_special_method($name_method){
			if(empty($name_method)){
				return 0;
			}
			$name_method=trim($name_method);
			if( in_array ($name_method,  $this->arr_special_methods )){
				return 1;
			}
			return 0;
		}

		############################
		function get_special_method($name_method){
		############################
			if(empty($name_method))
				return 0;
			$name_class=trim($name_method);
			$token = explode(".", $name_method);
			$agent=trim($token[0]);
			
			if($this->is_special_method($name_method) && $this->is_special_agent($agent)){
				return $this->arr_classes[$agent];
				}
			return 0;
		}				

		##################
		function info_classes(){
		##################
			$arr=array_keys($this->arr_classes);
			return $arr;
		}
		
		######################
		function info_special_agents(){
		######################
			$arr=$this->arr_special_agents;
			return $arr;
		}
		######################
		function info_special_methods(){
		######################
			$arr=$this->arr_special_methods;
			return $arr;
		}
		################
		function request_broker_query(){//REQUEST
		################
			if (!$method = $_SERVER['REQUEST_METHOD'])
				return;
			$arr_request=array();
			if ($method == 'POST')
				$arr_request=$_POST;
			elseif($method == 'GET')
				$arr_request=$_GET;
			else
				return; 
			if(empty($arr_request["COP"])){
				echo "Syntaxerror !";	
				return;
			}
			if ($method == "GET")
				return $this->pars_get($arr_request);							
			else{
				return $this->pars_post($arr_request);	
			}				
		}

		function request_($agent_name,$code=0){
		 	$agent=$this->get_class ($agent_name);
			if(method_exists($agent,'error_handler'))
				$agent->error_handler($code);
			 return 1;
		}
		//////////////////////////////////////////////////////////////////////////////		
		// 								PARSING GET
		// SITE					
		// URL"COP = агент_name.method_name (options)!агент_name.method_name (options)!...
		// 
		///////////////////////////////////////////////////////////////////////////////
		function pars_get($arr_get_vars){ //$arr_get_vars::=$HTTP_GET_VARS
			list($key,$str)= each($arr_get_vars);
			$arr_token = explode("!", $str);
			$arr_get=array();
			for($i=0;$i<count($arr_token);$i++){		
				if(empty($arr_token[$i]))
					continue; //break;
				if(strpos($arr_token[$i],".") && strpos($arr_token[$i],"(") && strpos($arr_token[$i],")")){
					$arr_get[$i]=$arr_token[$i];
					$arr_exp=explode(".",$arr_token[$i]);
					$agent=trim($arr_exp[0]);
					$str_method=trim($arr_exp[1]);
					$arr_exp=explode("(",$str_method);	
					$method=trim($arr_exp[0]);
					$str_options=trim($arr_exp[1]);
					$str_options= substr($str_options,0,strlen($str_options)-1);
					if(!$class=$this->get_special_class($agent)){
						echo "Not available ".$agent."!";
						return;
					}	
					$class->$method($str_options);
				}		
			}
			return;
		}	
		////////////////////////////// 	PARSING POST	///////////////////////////////
		/*
		// SITE	
		<FORM ACTION=' <"php $action"> 'METHOD='POST'>	
		<TABLE ALIGN="LEFT" BORDER="0" CELLSPACING="1" CELLPADDING="1">
			<TR><TD><P ALIGN='CENTER'>FORM</P></TD></TR>
			<TR>
				<TD><input type="text" NAME="value_Code_author"  SIZE="32" value=""></TD>
			</TR>
			<TR>
				<TD><TEXTAREA  NAME="value_Title" COLS="100%"  ROWS ="10" value="" ></TEXTAREA><TD>	
			</TR>
			<TR>
				<INPUT NAME="COP" type = "hidden" value="PM6.form(Code_author => Value_Code_author, ISBN => Value_ISBN, Title => Value_Title)"/>
				<TD><INPUT TYPE="SUBMIT"  VALUE="SUBMIT"></TD>
			</TR>	
		</TABLE>	
		</FORM>*/
		///////////////////////////////////////////////////////////////////////////////

		function pars_post($arr_post_vars){
			if(empty($arr_post_vars["COP"])){
				echo "Syntaxerror !";	
				return;
			}
			$pos=strpos($arr_post_vars["COP"],"(");
			$str_method=substr($arr_post_vars["COP"],0,$pos);					
			$str_options=trim(substr($arr_post_vars["COP"],$pos,strlen($arr_post_vars["COP"])-$pos));//OPTIONS
			$arr_exp=explode(".",$str_method);
			$agent=trim($arr_exp[0]);
			$method=trim($arr_exp[1]);
			$str_options=substr($str_options,1 ,strlen($str_options)-2);
			$arr_options=explode(",",$str_options);
			$arr_options_rez=array();
			for($i=0;$i<count($arr_options);$i++){
				$arr_token=$arr_options[$i];
				$arr_tmp=explode("=>",$arr_token);
				$arr_tmp_0=trim($arr_tmp[0]);
				$arr_tmp_1=trim($arr_tmp[1]);			
				$arr_options_rez[trim($arr_tmp_0)]=$arr_post_vars[$arr_tmp_1];
			}
			if($class=$this->get_special_class($agent)){
				$class->$method($arr_options_rez);
			}else
				echo "Not available ".$agent."!";
			return;
		}
		
	
	}//EndClass
 ">