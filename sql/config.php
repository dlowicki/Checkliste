<?php
	sqlsrv_configure('WarningsReturnAsErrors',0);
	$serverName = "SQL2016\MATRIX42"; //serverName\instanceName
	$datenbank = "M42Production";
	$name = "sa";
	$pwd = "";
	
	$connectionInfo = array( "Database"=>$datenbank, "UID"=>$name, "PWD"=>$pwd);
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	
	if( !$conn ) { echo "Connection could not be established.<br />"; die( print_r( sqlsrv_errors(), true)); }
	
	
?>