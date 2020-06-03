<?
/*
* EvilBoard SQL BACKUP
* Description: MySQL Backup tool for EvilBoard
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighted under the LGPL License  - 2006
*********** @ How to use this tool @ ***********
* At the start of your script add this line:
* $dump = new sqlbackup;

* Config part:
* $dump->config("Database Server", "Database Username" , "Database Password", "Database");

* Example of config:
* $dump->config("localhost", "root", "xfx", "cds");

* To take the backup add this line:
* $dump->backup("filename","sql_backup_dir");

* Change filename to the name you want the file to be,
* Example:
* $dump->backup("backup_102.sql","dir");

* Warning: This file must be placed before any text, because it modifies your header info,
* To download the SQL file

*********** License *************
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
* 
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
* 
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
* http://www.gnu.org/licenses/lgpl.html
********* End of License **********
*/
// ====================
## Class sqlbackup // Create SQL Backup and get user to download it
// ====================
class sqlbackup
{
	//=============================//
	// Start EvilBoard Backup Tool //
	//=============================//
	
	// Define some variables.
	var $nocomments;
	var $db_serv;	
	var $db;			
	var $filename; 	
	//================================================================================//
	// Function: Config($db_serv, $user, $password, $db)							  //
	// Description: Do all the basic configuration for the connection to the database //
	//================================================================================//
	function config( $db_serv, $user, $password, $db)
	{	
		mysql_connect($db_serv, $user, $password);		
		mysql_select_db($db);
		$this->db=$db;
		$this->db_serv=$db_serv;
	}
	//======================================//
	// Function: db_write($fp,$val)			//
	// Description: Write text to textfile	//
	//======================================//
	function db_write($fp, $val){
	echo $val;
	}
	//======================================//
	// Function: write_header($filename)  	//
	// Description: Sets the content header //
	//======================================//
	function write_header($filename){
		header( "Content-type: application/force-download");  
    	header( "Content-Disposition: inline; filename=\"" . $filename . "\"");
		header( "Expires: Mon, 1 Jul 2010 01:00:00 GMT"); 
    	header( "Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
	}
	//==========================================================================//
	// Function: backup($b_file)												//
	// Description: Gets the SQL information and writes the backup to text		//
	//				Then gets the user to download the text file, if $b_file	//
	//				is empty it will write to a file called 					//
	//				backup_DBNAME_TODAYSDATE.sql								//
	//==========================================================================//
	function backup($b_file="")
	{
			# Not working at this moment # $this->nocomment=$nocomment;
			if($b_file){ 				
				$this->filename=$this->bdir.$b_file;
			}else{			
				$this->filename = $this->bdir."backup_".$this->db."_".date("Y_m_d__G_i").".sql";
			}
		   	$this->write_header($this->filename);
			$this->db_write($fp,"-- EvilBoard SQL Backup \n");
			$this->db_write($fm,"-- Copyright: 2006 Arne-Christian Blystad \n");
			$this->db_write($fp,"--\n");
			$this->db_write($fp,"-- Host : $this->db_serv     Database :  $this->db\n");
			$this->db_write($fp,"-- ---------------------------------------------\n");
			$ltable = mysql_list_tables($this->db); 
			$nb_row = mysql_num_rows($ltable);
					
			$i = 0;
			while ($i < $nb_row)
			{ 	$tablename = mysql_tablename($ltable, $i);
					if($this->nocomment!=1){
					$this->db_write($fp,"\n");
					$this->db_write($fp,"\n");
					$this->db_write($fp,"--\n");
					$this->db_write($fp,"-- Table structure for table '$tablename' \n");
					$this->db_write($fp,"--\n");
					$this->db_write($fp,"\n");
				}
				$this->db_write($fp,"DROP TABLE IF EXISTS `$tablename`;\n");
			  
				$query = "SHOW CREATE TABLE $tablename";
				$tbcreate = mysql_query($query);
				$row = mysql_fetch_array($tbcreate);
				$create = $row[1].";";
				$this->db_write($fp,"$create\n\n");
					if($this->nocomment!=1){
					$this->db_write($fp,"--\n");
					$this->db_write($fp,"-- Dumping data for table '$tablename' \n");
					$this->db_write($fp,"--\n");
					$this->db_write($fp,"\n");
				}
				$query = "SELECT * FROM $tablename";
				$datacreate = mysql_query($query);
				if (mysql_num_rows($datacreate) > 0) 
				{
					$qinsert = "LOCK TABLES $tablename WRITE; \n";
					$qinsert .= "INSERT INTO `$tablename` values \n  ";
					
					while($row12 = mysql_fetch_assoc($datacreate))
					{  	   //set_time_limit(30);  						// In case your server is in "SAFE MODE" uncomment this line to set excute time limit //
						   $row12 = array_map(array($this, 'sep_sql'), $row12);
						   $data = implode(",",$row12);			
						   $data = "$qinsert($data)";				
						   $this->db_write($fp,"$data\n");
						   $qinsert=", ";							
					}
					$this->db_write($fp,";\n");
					$this->db_write($fp,"UNLOCK TABLES; \n");
					$this->db_write($fp,"\n");
				}else{								
					if($this->nocomment!=1){
						$this->db_write($fp,"--\n");
						$this->db_write($fp,"-- table '$tablename' empty \n");
						$this->db_write($fp,"--\n");
						$this->db_write($fp,"\n");
					}

			}
		  $i++;
		  }   
	}
	//==================================================================//
	// Function: sql_sql($tbl)											//
	// Description: Adds ' to start and to end of each value in table.	//
	//==================================================================//
	function sep_sql($tbl) 
	{
		$tbl=mysql_escape_string($tbl); 	
		if(is_numeric($tbl)){ return $tbl;}	
		if(!$tbl){return "NULL";}			
		return "'".$tbl."'";
	}
	//===============================//
	// EvilBoard SQL Backup Tool End //
	//===============================//
}
?>