<?php
//sqlsrv_configure('WarningsReturnAsErrors',0);


/*
Erhalte UserID
SELECT ID, FirstName, LastName from dbo.SPSUserClassBase WHERE LastName = 'Lowicki'

Erhalte ObjectID (ObjectID = WPLID)
SELECT ObjectID,ID from dbo.ASMWorkplaceClassBase WHERE MainUser LIKE '87559D61-1FCA-EC11-9AB2-005056A19623'

Erhalte Assets von WorkplaceID
SELECT InventoryNumber, Name, serialnumber from dbo.SPSAssetClassBase WHERE Workplace LIKE '17B17CD1-12BC-ED11-A6B2-005056A19623'

*/


function getUserID($vorname, $nachname){
	$serverName = "SQL2016\MATRIX42"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"M42Production", "UID"=>"sa", "PWD"=>"");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	if( !$conn ) { echo "Connection could not be established.<br />"; die( print_r( sqlsrv_errors(), true)); }
	$result = sqlsrv_query( $conn, "SELECT ID from dbo.SPSUserClassBase WHERE LastName = '$nachname' AND FirstName = '$vorname'");
	if($result === false) {
		die( print_r( sqlsrv_errors(), true) );
		return false;
	}
	
	while( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ) {
		if($row['ID'] != null){
			return $row['ID'];
		}
	}
	return false;
}


function getAssetFromWorkplace($userID) {
	$serverName = "SQL2016\MATRIX42"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"M42Production", "UID"=>"sa", "PWD"=>"");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	if( !$conn ) { echo "Connection could not be established.<br />"; die( print_r( sqlsrv_errors(), true)); }
	$result = sqlsrv_query( $conn, "SELECT InventoryNumber, dbo.SPSStockKeepingUnitClassBase.Model from dbo.SPSAssetClassBase INNER JOIN dbo.SPSStockKeepingUnitClassBase ON dbo.SPSAssetClassBase.SKU = dbo.SPSStockKeepingUnitClassBase.ID WHERE dbo.SPSAssetClassBase.AssignedUser = '$userID'");
	if($result === false) { die( print_r( sqlsrv_errors(), true) ); return false; }
		
	$temp = array(); $r=0;	
	while( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ) {
		$temp[$r]['Name'] = $row['Model'];
		$temp[$r]['in'] = $row['InventoryNumber'];
		$r++;
		
	}
	return $temp;
}


?>