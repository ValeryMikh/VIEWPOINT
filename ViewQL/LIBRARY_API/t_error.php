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
                            T_ERROR
 ************************************************************/
 class t_error{ 
   	function error($error,$class_name=""){
		print("<DIV ALIGN='LEFT'><FONT COLOR='#FF3333'>ERROR");
	  	if($this->flg_debuger=="YES"){
		  	if(!$class_name){
				if($this->class_name)
					print "(</FONT>CLASS- ".
					$this->class_name.
					"<FONT COLOR='#FF3333'>)";
		  	}else
				print "(</FONT>CLASS- ".
				$class_name.
				"<FONT COLOR='#FF3333'>)";
	  	}
	  	print '</FONT>&nbsp;&nbsp;&nbsp;"'.
	  	$error.
	  	' ";<BR>';
	  	print("</DIV>");
	  	exit;	
   	}

  	function warning($warning,$class_name=""){
		print("<DIV ALIGN='LEFT'><FONT COLOR='#FF3333'>WARNING");
		if($this->flg_debuger=="YES"){
			if(!$class_name){
				if($this->class_name)
					print "(</FONT>CLASS- ".
					$this->class_name.
					"<FONT COLOR='#FF3333'>)";
			 }else
				print "(</FONT>CLASS- ".
				$class_name.
				"<FONT COLOR='#FF3333'>)";
	  	  }
	  	print '</FONT>&nbsp;&nbsp;&nbsp;"'.
	  	$warning.
	  	' ";<BR>';
		print("</DIV>");
		return 0;	
   }
	var $class_name="";
    var $flg_debuger="NO";

	function set_debuger_yes(){
		$this->flg_debuger="YES";
	}
	function set_debuger_no(){
		$this->flg_debuger="NO";
	}
   	function is_debuger(){
		if($this->flg_debuger=="YES")
			return 1;
		return 0;
    }
   	function get_str_error($key){
   		global $arr_str_error;
		if(!empty($arr_str_error[$key]))
			return $arr_str_error[$key];
		return 0;
	}
	function set_flg_error_yes(){
		$this->flg_error="YES";
	}
	function set_flg_error_no(){
		$this->flg_debuger="NO";
	}
   	function is_flg_error(){
		if($this->flg_debuger=="YES")
			return 1;
		return 0;
   }

}

">
