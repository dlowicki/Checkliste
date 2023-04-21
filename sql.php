<?php
sqlsrv_configure('WarningsReturnAsErrors',0);
$serverName = "SQL2016\MATRIX42"; //serverName\instanceName
$connectionInfo = array( "Database"=>"M42Production", "UID"=>"sa", "PWD"=>"");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}

$sql = "SELECT TOP(20) * FROM Dbo.SPSUserClassBase";
$result = sqlsrv_query( $conn, $sql);
if($result === false) {
    die( print_r( sqlsrv_errors(), true) );
}

while( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ) {
    print_r($row);
}

/*
Erhalte UserID
SELECT ID, FirstName, LastName from dbo.SPSUserClassBase WHERE LastName = 'Lowicki'

Erhalte ObjectID (ObjectID = WPLID)
SELECT ObjectID,ID from dbo.ASMWorkplaceClassBase WHERE MainUser LIKE '87559D61-1FCA-EC11-9AB2-005056A19623'

Erhalte Assets von WorkplaceID
SELECT InventoryNumber, Name, serialnumber from dbo.SPSAssetClassBase WHERE Workplace LIKE '17B17CD1-12BC-ED11-A6B2-005056A19623'

*/


?>