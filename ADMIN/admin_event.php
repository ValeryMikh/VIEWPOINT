<?php	
########################################################
#    Copyright  2010-2022 by Valery Mikhailovski
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
########################
#	           Agent   ADMIN		      #
########################

	########################
	class admin_event extends gddl{//dbpl 
	########################
		var $language="RU";
		var $query="";   
		var $str_echo="";		
		var $str_show="";
		var $flg_query=0;
		var $max_rows=0;	
		var $arr_query=array();
		var $arrrows=array();
		var $type_class="";
		var $name_type="";
		var $arr_obj_relation=array();
		//var $broker=0;
		
	//////////////////////////////////////////////////////////////////			
		function special_query($sess_query){
	//////////////////////////////////////////////////////////////////		
			if($sess_query){  
				$this->str_show="";
				$this->arrrows=array();
				$this->type_class="";
				$sess_query=stripslashes($sess_query);	
				$sess_query=trim($sess_query);
				$this->arr_query = explode(";;", $sess_query);
				if(count($this->arr_query)==1)
					$this->arr_query[]=array();
				for($i=0;$i<count($this->arr_query)-1;$i++){
						if($this->arr_query[$i]){		
							$this->str_show.=$this->arr_query[$i].";;<BR>";			
						}
				}			
				return 1;
			}
			return 0;
		}

	//////////////////////////////////////////////////////////////////		
		function 	even_type_class($arr_option){
	//////////////////////////////////////////////////////////////////
			$name_type=trim($arr_option[0]);
			if(empty($name_type))
				return 0;
			$this->type_class="";
			$this->name_type=$name_type;
			if($this->is_type_class($name_type)=="VIEW"){
				$this->type_class="VIEW";
			}elseif($this->is_type_class($name_type)=="COMPONENT"){
				$this->type_class="COMPONENT";
			}elseif($this->is_type_class($name_type)=="CONTAINER"){
				$this->type_class="CONTAINER";			
			}else
				$this->type_class="";
			if($this->type_class=="COMPONENT"||$this->type_class=="CONTAINER" ||$this->type_class=="VIEW"){
				$this->str_show="SHOW $this->type_class $this->name_type".";;";
				$this->show_form_admin();
				$this->type_class=""; $this->name_type="";
				exit; 
				//return 1;
			}
			
			return 0;
		}
	//////////////////////////////////////////////////////////////////
		function select_class($arr_option){
	/////////////////////////////////////////////////////////////////
			$this->arr_obj_relation=array();
			$name_class=trim($arr_option[0]);
			if(empty($name_class))
				return 0;
			$this->arrrows=array();
			//$query="SET CLASS $name_class";
			$query="SET TABLE $name_class";
			$this->str_show=$query.";;<BR>";			
			$this->arrrows[]=$this->dbpl_query($query);
			return 1;
		}
	//////////////////////////////////////////////////////////////////
		function set_view($arr_option){
	//////////////////////////////////////////////////////////////////
			$this->arr_obj_relation=array();
			$name_interface=trim($arr_option[0]);
			if(empty($name_interface))
				return 0;
			$this->arrrows=array();
			$query="SET  VIEW ($name_interface) " ;
			$this->str_show=$query.";;<BR>";			
			$this->arrrows=array();
			$this->arrrows[]=$this->dbpl_query($query);
			
			return 1;
		}
	//////////////////////////////////////////////////////////////////
		function set_object($arr_option){
		//////////////////////////////////////////////////////////////////
			$id_object=trim($arr_option[0]);
			if(empty($id_object))
				return 0;
			$this->arrrows=array();
			//$query="SET  OBJECT ($id_object )";
			$query="SET  RECORD ($id_object )";
			$this->str_show=$query.";;<BR>";			
			$this->arrrows=array();
			$this->arrrows[]=$this->dbpl_query($query);
			
			return 1;
		}
		//////////////////////////////////////////////////////////////////
		function obj_relation($arr_option){
		//////////////////////////////////////////////////////////////////
			$name_interface=trim($arr_option[0]);
			$this->arrrows=array();
			$query="SELECT( ID)$name_interface" ;
			$this->arr_obj_relation=array();
		  	if($arr_anser=$this->dbpl_query($query)){
				while($myrow = $this->class->fetch_row($arr_anser)){
					$id_item=$myrow["ID"];
					$this->arr_obj_relation[]=$id_item;
				}
			}
			$query="SET CLASS $name_interface" ;
			$this->str_show=$query.";;<BR>";			
			$this->arrrows[]=$this->gddl_query($query);
			$query_2="SET VIEW ( $name_interface )" ;
			$this->str_show="$this->str_show  $query_2 ;;<BR>" ;			
			$this->arrrows[]=$this->dbpl_query($query);
			
			return 1;
		}

	################
	function show_form_admin(){
	################
		$action=$_SERVER['PHP_SELF'];
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<HTML>
	<HEAD>
	<?php
		if($this->language=="RU"){?>
		<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="TEXT/HTML; CHARSET=WINDOWS-1251">
	<?php
		}else{?>
		<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="TEXT/HTML; CHARSET=WINDOWS-1255">
	<?php
		}?>
	</HEAD>
		<LINK REL="STYLESHEET" HREF="<?php echo strtolower('../../DATA/MYSTYLE.CSS') ?>" TYPE="TEXT/CSS">
	<BODY>

  <FORM ACTION="<?php echo $action ?>" METHOD="POST" >	
	<TABLE  ALIGN="CENTER" WIDTH="100%" BORDER="1" CELLSPACING="5" CELLPADDING="5"> 	
  	<TR><TD ALIGN="CENTER" COLSPAN="3">
	<p align="center"><strong>GRAPH &nbsp;&nbsp; DATABASE </strong></p> 	
	<strong>VISUAL&nbsp;  INTEGRATED&nbsp;  DEVELOPMENT &nbsp;ENVIRONMENT &nbsp;AND&nbsp; ADMINISTRATION</strong>
	</TD></TR> 
	<TR>
		<TD VALIGN="TOP">
<!--  LIST ALL CONTAINERS,VIEW S,CLASSES  -->
	<?php
		print "</FONT></LI>";	
		print "<BR>";	 
		if($list_classes=$this->get_type_class("VIEW ")){
			print "<B><FONT SIZE='-1'>VIEWS:</B></FONT><BR>";	
			foreach($list_classes as $name => $id){
				if($id)
				  print"<A HREF='$action?EVEN_TYPE_CLASS=$name'><LI><FONT SIZE='-2'>$name ($id)</FONT></LI></A>";
			}
			print "<BR>";
		}
		if($list_classes=$this->get_type_class("CLASS")){
			print "<B><FONT SIZE='-1'>TABLES:</B></FONT><BR>";	//CLASSES
			foreach($list_classes as $name => $id){
				if($id)
					print"<A HREF='$action?SELECT_CLASS=$name'><LI><FONT SIZE='-2'>$name ($id)</FONT></LI></A>";// ! EVEN_TYPE_CLASS($name)!'
			}
			print "<BR>";
		}
	?>
		</TD >	
<!--  WINDOW INPUT QUERY-->
		<TD ALIGN= "CENTER"  ><!--VALIGN="TOP"-->
		<BR><strong>QUERY  IN<strong> <br><br>
			<TEXTAREA  NAME="QUERY" COLS="100%"  ROWS ="20" ></TEXTAREA><BR>
<!--  WINDOW INPUT SUBMIT -->	
			<INPUT NAME="COP" type="hidden" value="SPECIAL_QUERY(QUERY)">						
			<INPUT NAME="Submit" TYPE="SUBMIT" VALUE="SUBMIT QUERY">
	      	<INPUT NAME="Reset" TYPE="RESET" VALUE="ERASE">
		<BR><BR></TD>
		<TD  VALIGN="TOP">
<!--  LIST ALL COMPONENTS, CONTAINERS -->			
	<?php	
		if($list_classes=$this->get_type_class("COMPONENT")){
			//print "<B><FONT SIZE='-1'>COMPONENTS:</B></FONT><BR>";	
			foreach($list_classes as $name => $id){
				print"<LI><FONT SIZE='-2'>$name</FONT></LI>";
			}
			print "<BR>";
		}
		if($list_classes=$this->get_type_class("CONTAINER")){
			print "<B><FONT SIZE='-1'>CONTAINERS:</B></FONT><BR>";	
			foreach($list_classes as $name => $id){
				if($id)
					print"<A HREF='$action?EVEN_TYPE_CLASS=$name'><LI><FONT SIZE='-2'>$name ($id)</FONT></LI></A>";
			}
			print "<BR>";
		}		
	?>
		</TD>	
	</TR>
  </TABLE>
  </FORM>  
<!-- QUERY -->	  
   <FONT SIZE='-1'><B>&nbsp;&nbsp;&nbsp;&nbsp;QUERY:</B></FONT>
   <TABLE  ALIGN="CENTER" WIDTH="95%"  BORDER="1" CELLSPACING="5" CELLPADDING="5"> 	
	  <TR>
		<TD>
			<?php 
			if($this->str_show){
				print $this->str_show;
			}
			?>	
		</TD>	
	  </TR>
  </TABLE> 
<!-- ANSER  -->   
  <FONT SIZE='-1'><B>&nbsp;&nbsp;&nbsp;&nbsp;ANSER:</B></FONT> 
  <TABLE  ALIGN="CENTER" WIDTH="95%" BORDER="1" CELLSPACING="5" CELLPADDING="5"> 	
  <TR>
    <TD>		
	<?php	
	if($this->type_class=="COMPONENT"||$this->type_class=="CONTAINER"||$this->type_class=="VIEW"){
		$query="SHOW $this->type_class $this->name_type";
		$this->dbpl_query($query);
	}elseif(!empty($this->arr_query)){
		$arr_query=$this->arr_query;
		$str_rez="";
		for($i=0;$i<count($arr_query)-1;$i++){
			$query_tmp=trim($arr_query[$i]);
			if($query_tmp){
				$str_rez=$this->dbpl_query($query_tmp);
				print_r($str_rez);echo "<BR>";
			}
		}
		$this->arrrows=array();//anser
		$this->arr_query=array();//query
	}
?>			
	</TD>	
  </TR>
  </TABLE> 
<!-- THE SELECTED CASS --> 
<?php		
	if(!empty($this->class) && !$this->type_class && !$this->flg_query){
?>											 
  <FONT SIZE='-1'><B>&nbsp;&nbsp;&nbsp;&nbsp;CURRENT TABLE:</B>&nbsp;&nbsp;&nbsp;&nbsp<?php echo $this->class->class_name?></FONT>   
  <TABLE  ALIGN="CENTER" WIDTH="95%" BORDER="1" CELLSPACING="2" CELLPADDING="2"> 	
  <TR>
  	<TD>
<?php
		print "<FONT SIZE='-1'><B>VIEWS:</B>";
		if($arr_interface=$this->class->info_interface()){
				foreach($arr_interface as $nn=>$name){
					print "<BR>&nbsp;&nbsp;&nbsp;&nbsp;";
					print "<A HREF='$action?SET_VIEW=$name'>$name</A>";///old
				}
		}
		print "</FONT><BR>";
?>
	</TD>	
  </TR>
  </TABLE>
<!-- THE SELECTED VIEW -->
                        <?php 	
		if($this->class->interface_name && $this->class->interface_name!=$this->class->class_name ){	
?>
<FONT SIZE='-1'><B>&nbsp;&nbsp;&nbsp;&nbsp;CURRENT  VIEW:</B>&nbsp;&nbsp;&nbsp;&nbsp<?php echo $this->class->interface_name ?></FONT>   
  <TABLE  ALIGN="CENTER" WIDTH="95%" BORDER="1" CELLSPACING="5" CELLPADDING="5"> 	
  <TR>
  	<TD>	
<?php
			$query_class="SELECT ID THIS ";
			//$query_class="SELECT ID FROM ". $this->class->get_name_interface();
			if($arr_anser=$this->dbpl_query($query_class)){
				print "<FONT SIZE='-1'><B>RECORDS:</B></FONT> ";//OBJECTS
				$nn=0;//21
				while($myrow = $this->class->fetch_row($arr_anser)){
					if(!$nn){//21
						$nn=1;
						continue;
					}//
					$id_item=$myrow["ID"];
					if(empty($this->arr_obj_relation)){
						$len=strlen($id_item);
						if($len==32)
							$short_id_item= substr($id_item,$len-7,$len);	
						else
							$short_id_item=$id_item; 
						print" <A HREF='$action?SET_OBJECT=$id_item'> $short_id_item;</A>";
					}
					elseif(in_array($id_item,$this->arr_obj_relation))
					 	print" <A HREF='$action?SET_OBJECT=$id_item'> $id_item;</A>";
					else
						print" $id_item; ";
				}
			}
			print "</FONT><BR>";
?>
	</TD>
  </TR>
 </TABLE>
<!-- THE SELECTED OBJECT -->
<?php
			if($this->class->id_object && $this->class->id_object!=$this->class->id_class && $arr_rows_extends=$this->class->info_extends()){
?>		  
	<B><FONT SIZE='-1'>&nbsp;&nbsp;&nbsp;&nbsp;CURRENT  RECORD:</FONT></B>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->class->id_object?><BR>	

  <TABLE  ALIGN="CENTER" WIDTH="95%" BORDER="1" CELLSPACING="5" CELLPADDING="5"> 	  
  <TR>
  	<TD height="30">
	<?php
				print "<B><FONT SIZE='-1'>CURRENT NODE </B>";// (echo $this->class->interface_name."<B> , </B>".$this->class->id_object."<B>)</B>";
				//print "<BR>";				 
				//print "<FONT SIZE='-1'><B>PROPERTIES:</B>";
				print "<BR>"; 
				while($myrow = $this->fetch_row($arr_rows_extends)){//class->fetch_row
					if(isset($myrow["Control"])){
						$name_component=$myrow['Control'][0];
						if(strpos($myrow["Control"][1],"ARRAY_")===false)
							$str_option=$name_component;
						else
							$str_option=$name_component;//."[*]";
						print("&nbsp;&nbsp;&nbsp;&nbsp"); 
						print ("<A HREF='$action?QUERY=SELECT($str_option);;'>$name_component</A>");
						print(" = ");
						if(!empty($myrow["Protection"]))//PRIVATE
							print " ( ".$myrow["Protection"]." ) ";
						if(!empty($myrow["Component"]))//STATIC
							print " ( ".$myrow["Component"]." ) ";
						print $myrow["Control"][1];
						print "<BR>";
					}elseif(isset($myrow["Relation"]))
						continue;
				}
				//print "<BR>";
				foreach($this->class->arr_extends_class as $key => $arr_extends){
					if(empty($arr_extends[0]))
						continue;
					if(!$name_interface_item=$arr_extends[4])
							return 0;
					if(!$str_icon=trim($arr_extends[1]))
						return 0;
					if($str_icon=="PARENT" ){
						$str_icon= "TARGET";
						$icon="<-";
					}elseif($str_icon=="DIRECT" || $str_icon=="FEEDBACK" ){ 
						$icon="<->";
						print("&nbsp;&nbsp;&nbsp;&nbsp"); print ("<A HREF='$action?QUERY=SELECT  $icon  (ID) $name_interface_item;;'>Current Node  $icon  $name_interface_item</A>  <BR>");					
						continue;
					}elseif($str_icon=="CHILDREN"){
						
						$str_icon= "SOURCE";
						$icon="->";
						//print("&nbsp;&nbsp;&nbsp;&nbsp"); print ("<A HREF='$action?QUERY=SELECT  PATH  (ID) $name_interface_item ;;'> C.N. $icon </A>   <BR>");		//$name_interface_item PATH 
					}else
						return 0;
					print("&nbsp;&nbsp;&nbsp;&nbsp"); print ("<A HREF='$action?QUERY=SELECT  $icon  (ID) $name_interface_item ;;'>Current Node $icon </A>  <BR>");	//. $str_icon."(CURENT)"//$name_interface_item
				}

			}
		}		
	}
?>
	</TD>	
  </TR>
  </TABLE>  
<p style="font-size: 12px;" align="center"> Copyright
�&nbsp;&nbsp; 2010-2021 All rights reserved.<br />
</BODY>
</HTML>
<?php
	}
}//END CLASS	
?>