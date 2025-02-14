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

/************************************************************
                            TABLE_JOIN
 ************************************************************/
 class table_join extends table{
 	function table_join($prefix_db=""){
		$this->table($prefix_db);
	}		
  function get_array_join($str_table,$str_cond="*",$str_where="",$str_order="",$str_limit=""){ 
    if($str_table==""||$str_cond=="")
      return 0;
    if($str_where!="")
      $str_where=" WHERE ".$str_where;
    if($str_order!="")
      $str_order=" GROUP BY ".$str_order;
    if($str_limit!="")
      $str_limit=" LIMIT ".$str_limit;
    $query ="SELECT $str_cond FROM $str_table $str_where $str_order $str_limit";
    if($table=$this->is_table($query))
      return mysqli_fetch_array($table);
    return 0;
  }

  function get_table_join($str_table,$str_cond="*",$str_where="",$str_order="",$str_limit=""){
     if($str_table==""||$str_cond=="")
      return 0;
    if($str_where!="")
      $str_where=" WHERE ".$str_where;
    if($str_order!="")
      $str_order=" GROUP BY ".$str_order;
    if($str_limit!="")
      $str_limit=" LIMIT ".$str_limit;
    $query ="SELECT $str_cond FROM $str_table $str_where $str_order $str_limit";
    $rezult=$this->is_table($query);
    return $rezult; 
  }
}

?>