<?php

//  author:   David Tucker
//  reviewed: 8-9-2011
//  modified: 10-23-2011
//  status:   beta (debugged, untested) - out of date as of 12-18-2011
//
//  Description
//      allows for the use of databases
//
//
//  Constants
//      $DATABASE_ACCOUNT
//          an associative array of 5 login credentials for the DBMS server:
//
//          (read-only)
//              an account with SELECT privileges
//
//          (create)
//              an account with SELECT, INSERT, & CREATE privileges
//
//          (edit)
//              an account with SELECT, UPDATE, & ALTER privileges
//
//          (destory)
//              an account with SELECT, DELETE, & DROP privileges
//
//          (super)
//              the account with all privileges
//              It is NOT recommended this account be created or used.
//
//      ACCOUNT_PREFIX
//          the prefix of all DBMS accounts
//
//      ACCOUNT_SUFFIX
//          the suffix of all DBMS accounts
//
//      DATABASE_SERVER
//          the host of the default database
//
//      db
//          the object handle to the current DBMS
//
//      DBMS
//          the database management system to be used
//          Currently supported DBMSs are:
//
//              MySQL
//
//      DEFAULT_DATABASE
//          the name of the default database to connect to
//
//      TESTING_SERVER
//          the hostname of the testing server
//          If no testing server exists, set this to FALSE
//
//      TESTING_PASSWORD
//          the password of the root account on the testing server
//          To use the default account scheme, set this and TESTING_USERNAME to FALSE.
//
//      TESTING_USERNAME
//          the username of the root account on the testing server
//          To use the default account scheme, set this and TESTING_PASSWORD to FALSE.
//
//  Database
//      n/a
//
//  Functions (7)
//     xconnection_mode
//      close_connection
//      count_all
//      create
//      destroy
//      edit
//      open_connection
//      read
//
//  Globals
//
//  Requirements
//      modules
//          errors
//      $_SERVER["SERVER_NAME"]
//          used only to detect the testing server, if one exists
//
//
//  Notes
//      none

require_once "errors.php";

class connection
{
	private $mode;
	private $handle;
	
public function __construct($mode, $handle)
{
	$this->mode   = $mode;
	$this->handle = $handle;
}

/**/
public function mode ()
{
	return $this->mode;
}

/**/
public function handle ()
{
	return $this->handle;
}

/**/

}

class database
{
	// settings ///////////////////////////////////////////////////
	
	private $PRIMARY_SERVER     = "localhost:3306";
	private $ACCOUNT_PREFIX     = "";
	private $ACCOUNT_SUFFIX     = "";
	private $TESTING_SERVER     = "localhost";
	private $TESTING_USERNAME   = "root";
	private $TESTING_PASSWORD   = "root";
	private $DEFAULT_DATABASE   = "bayfitted";
	private $ACCOUNT_CREDENTIAL = array("read"    => "password",
										"create"  => "password",
										"edit"    => "password",
										"destroy" => "password",
										"super"   => "");
	
	///////////////////////////////////////////////////////////////
	
	private $DBMS;

/*********************************************************************
Description
	getter functions

Order Θ(1)

Parameters
	none

Return Values
	the value of the setting corresponding to the name of the function

*********************************************************************/
function primary_server ()
{
	return $this->PRIMARY_SERVER;
}

function testing_server ()
{
	return $this->TESTING_SERVER;
}

function default_database ()
{
	return $this->DEFAULT_DATABASE;
}

/*************************
Description
	initializes a database

Order Θ(1)

Parameters
	none

Return Values
	n/a

*************************/
public function __construct($dbms)
{
	//pre-condition
	assert (in_array($dbms, array("MySQL"), TRUE));
	
	$this->DBMS = $dbms;
}

/******************************************************************************************
Description
	gets the mode an existing connection is running in

Order Θ(1)

Parameters
	connection
		a database link identifier in any mode

Return Values
	the mode of the connection

******************************************************************************************
function connection_mode ($connection)
{
	//pre-condition
	$key = intval($connection);
	global $OPEN_CONNECTION;
	if (!array_key_exists($key, $OPEN_CONNECTION)) throw new Exception("An attempt to get the mode of a connection has failed.");
	
	return $OPEN_CONNECTION[$key];
}

/***********************************
Description
	closes a database connection

Order Θ(1)

Parameters
	connection
		the database link identifier

Return Values
	No value is returned.

***********************************/
public function close_connection ($connection)
{
	switch ($this->DBMS) {
		case "MySQL":
			
			//pre-condition
			assert (mysql_ping($connection->handle()));
			
			mysql_close($connection->handle()) or report_error("An unclosable database connection has been detected. Details: ".mysql_error($connection));
			unset($connection);
			break;
			
		default: throw new Exception("An unsupported DBMS has been detected.");
	}
}

/******************************************************************************************
Description
	gets information from a database

Order Θ(1)

Parameters
	table
		the table name where the data is located
	
	[connection]
		a database link identifier in any mode
		If unspecified, a temporary one will be created for the life of the function.

Return Values
	an associative array of strings that corresponds to the requested row
		Note: There may be multiple rows that match the desired search string.
		To retrieve, simply fetch results directly using the same database link identifier.
	FALSE if the requested row does not exist

******************************************************************************************/
public function count_all ($table, $connection=FALSE)
{
	//pre-conditions
	assert (is_string($table));
	
	$info = array();
	
	switch ($this->DBMS) {
		case "MySQL":
			//pre-condition
			if (!($connection === FALSE || mysql_ping($connection->handle()))) {
				$connection = FALSE;
				report_error("An unusable database connection has been detected. Details: ".mysql_error($connection->handle()));
			}
			
			$link  = ($connection === FALSE) ? $this->open_connection("read-only") : $connection;
			$table = mysql_real_escape_string($table, $link->handle());
			assert ($table !== FALSE);
			
			$result = mysql_query("SELECT * FROM `".$table."`", $link->handle());
			assert ($result !== FALSE);
			$all = mysql_num_rows($result);
			
			if ($connection === FALSE) $this->close_connection($link);
			break;
		
		default: throw new Exception("An unsupported DBMS has been detected.");
	}
	
	return $all;
}

/************************************************************************************
Description
	inserts information into a database

Order Θ(1)

Parameters
	table
		the table name to put the data in
	
	field
		the column name to put the data under
	
	data
		the information to add
	
	[cConnection]
		a database link identifier in create mode
		If unspecified, a temporary one will be created for the life of the function.

Return Values
	No value is returned.

************************************************************************************/
public function create ($table, $field, $data, $cConnection=FALSE)
{
	//pre-conditions
	assert (is_string($table));
	assert (is_string($field));
	
	switch ($this->DBMS) {
		case "MySQL":
			//pre-condition
			if (!($cConnection === FALSE || mysql_ping($cConnection->handle()))) {
				$cConnection = FALSE;
				report_error("An unusable database connection has been detected. Details: ".mysql_error($cConnection));
			}
			
			$link  = ($cConnection === FALSE) ? $this->open_connection("create") : $cConnection;
			$table = mysql_real_escape_string($table, $link->handle());
			$field = mysql_real_escape_string($field, $link->handle());
			assert ($table !== FALSE && $field !== FALSE && $data !== FALSE);
			
			$result = mysql_query("INSERT INTO `".$table."` (".$field.") VALUES (".$data.")", $link->handle());
			if ($result === FALSE)
				throw new Exception('An attempt to insert data ('.$data.') into the database table "'.$table.'" ('.$field.') has failed. Details: '.mysql_error($link->handle()));
			
			if ($cConnection === FALSE) $this->close_connection($link);
			break;
		
		default: throw new Exception("An unsupported DBMS has been detected.");
	}
}

/************************************************************************************
Description
	deletes information in a database

Order Θ(1)

Parameters
	table
		the table name to put the data in
	
	where
		the field-value conditional that evaluates true for values to be updated
	
	[dConnection]
		a database link identifier in destroy mode
		If unspecified, a temporary one will be created for the life of the function.

Return Values
	No value is returned.

************************************************************************************/
public function destroy ($table, $where, $dConnection=FALSE)
{
	//pre-conditions
	assert (is_string($table));
	assert (is_string($where));
	
	switch ($this->DBMS) {
		case "MySQL":
			//pre-condition
			if (!($dConnection === FALSE || mysql_ping($dConnection->handle()))) {
				$dConnection = FALSE;
				report_error("An unusable database connection has been detected. Details: ".mysql_error($dConnection));
			}
			
			$link  = ($dConnection === FALSE) ? $this->open_connection(): $dConnection;
			$table = mysql_real_escape_string($table, $link->handle());
			assert ($table !== FALSE && $where !== FALSE);
			
			$result = mysql_query("DELETE FROM `".$table."` WHERE ".$where, $link);
			if ($result === FALSE) throw new Exception("An attempt to delete data (where ".$where.") from the database table \"".$table."\" has failed. Details: ".mysql_error($link));
			
			if ($dConnection === FALSE) $this->close_connection($link);
			break;
		
		default: throw new Exception("An unsupported DBMS has been detected.");
	}
}

/************************************************************************************
Description
	updates information in a database

Order Θ(1)

Parameters
	table
		the table name where the data is located
	
	update
		the field-value pairs to update
	
	where
		the field-value conditional that evaluates true for values to be updated
	
	[eConnection]
		a database link identifier in edit mode
		If unspecified, a temporary one will be created for the life of the function.

Return Values
	No value is returned.

************************************************************************************/
public function edit ($table, $update, $where, $eConnection=FALSE)
{
	//pre-conditions
	assert (is_string($table));
	assert (is_string($update));
	assert (is_string($where));
	
	switch ($this->DBMS) {
		case "MySQL":
			//pre-condition
			if (!($eConnection === FALSE || mysql_ping($eConnection->handle()))) {
				$eConnection = FALSE;
				report_error("An unusable database connection has been detected. Details: ".mysql_error($eConnection));
			}
			
			$link  = ($eConnection === FALSE) ? $this->open_connection("edit") : $eConnection;
			$table  = mysql_real_escape_string($table,  $link->handle());
			assert ($table !== FALSE && $update !== FALSE && $where !== FALSE);
			
			$result = mysql_query("UPDATE `".$table."` SET ".$update." WHERE ".$where, $link->handle());
			assert ($result);
			
			if ($eConnection === FALSE) $this->close_connection($link);
			break;
			
		default: throw new Exception("An unsupported DBMS has been detected.");
	}
}

/********************************************************************************
Description
	opens a database connection

Order Θ(1)

Parameters
	[mode]
		See $DATABASE_ACCOUNT under Constants for a description of each option:
		
		"read-only"
		"create"
		"edit"
		"destroy"
		"super"
		
		If unspecified, a "read-only" connection will be opened.
	
	[database]
		the database to connect to
		If unspecified, a connection to the default database will be established.

Return Values
	a database link identifer

********************************************************************************/
public function open_connection ($mode="read-only", $database=FALSE)
{
	if ($database === FALSE) $database = $this->DEFAULT_DATABASE;
	
	//pre-conditions
	assert (array_key_exists("SERVER_NAME", $_SERVER));
	assert (is_string($mode) && in_array($mode, array("read-only", "create", "edit", "destroy", "super")));
	assert (($this->TESTING_USERNAME === FALSE && $this->TESTING_PASSWORD === FALSE) || !($this->TESTING_USERNAME === FALSE || $this->TESTING_PASSWORD === FALSE));
	
	$server = ($this->TESTING_SERVER === FALSE || strstr($_SERVER["SERVER_NAME"], $this->TESTING_SERVER) === FALSE) ? $this->PRIMARY_SERVER: $this->TESTING_SERVER;
	if ($server == $this->PRIMARY_SERVER || ($this->TESTING_USERNAME === FALSE && $this->TESTING_PASSWORD === FALSE)) {
		$options = array_keys($this->ACCOUNT_CREDENTIAL);
		switch ($mode) {
			default: case "read-only": $username = $options[0]; break;
					 case "create"   : $username = $options[1]; break;
					 case "edit"     : $username = $options[2]; break;
					 case "destroy"  : $username = $options[3]; break;
					 case "super"    : $username = $options[4]; break;
		}
		$password = $this->ACCOUNT_CREDENTIAL[$username];
		$username = $this->ACCOUNT_PREFIX.$username.$this->ACCOUNT_SUFFIX;
	} else {
		$username = $this->TESTING_USERNAME;
		$password = $this->TESTING_PASSWORD;
	}
	assert ($username !== FALSE && $password !== FALSE);
	
	switch ($this->DBMS) {
		case "MySQL":
			$handle = mysql_connect($server, $username, $password);
			if ($handle === FALSE)                    throw new Exception("A connection to the MySQL server could not be established.");
			if (!mysql_select_db($database, $handle)) throw new Exception("A database (".$database.") could not be selected.");
			break;
			
		default: throw new Exception("An unsupported DBMS has been detected.");
	}
	
	$connection = new connection($mode, $handle);
	
	return $connection;
}

/******************************************************************************************
Description
	gets information from a database

Order Θ(1)

Parameters
	table
		the table name where the data is located
	
	where
		the field-value conditional that evaluates true for values to be updated
	
	[connection]
		a database link identifier in any mode
		If unspecified, a temporary one will be created for the life of the function.

Return Values
	an associative array of strings that corresponds to the requested row
		Note: There may be multiple rows that match the desired search string.
		To retrieve, simply fetch results directly using the same database link identifier.
	FALSE if the requested row does not exist

******************************************************************************************/
public function read ($table,$where,$connection=FALSE)
{
	//pre-conditions
	assert (is_string($table));
	assert (is_string($where) || is_array($where));
	
	$info = array();
	
	switch ($this->DBMS) {
		case "MySQL":
			//precondition
			if (!($connection === FALSE || mysql_ping($connection->handle()))) {
				$connection = FALSE;
				report_error("An unusable database connection has been detected. Details: ".mysql_error($connection->handle()));
			}
			
			$link   = ($connection === FALSE) ? $this->open_connection("read-only") : $connection;
			$table  = mysql_real_escape_string($table, $link->handle()); assert ($table !== FALSE);
			if (is_array($where)) {
				$clause = $where[0];
				for ($conditioni=count($where); $conditioni > 1 ;--$conditioni) $clause .= " AND ".$where[$conditioni];
				$where = $clause;
			}
			$result = mysql_query("SELECT * FROM `".$table."` WHERE ".$where, $link->handle()); assert ($result !== FALSE);
			for ($matchi=mysql_num_rows($result)-1; $matchi > -1; --$matchi) $info[$matchi] = mysql_fetch_assoc($result);
			
			if ($connection === FALSE) $this->close_connection($link);
			break;
		
		default: throw new Exception("An unsupported DBMS has been detected.");
	}
	
	return $info;
}

/**/

}

$db = new database("MySQL");

?>