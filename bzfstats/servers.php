<?php
#include_once("db.php");

function FindServerInCurrent ( $host )
{
	$id = GetDBFieldForKey("host",$host,"current_servers","ID");
	if (!$id)
		return FALSE;
		
	return $id;
}

function AddServerToCurrent ( $host )
{
	SQLSet( "INSERT INTO current_servers (host) VALUES ('" . $host . "')");
	return FindServerInCurrent($host);
}

function ServerExists ( $id )
{
	$result = GetDBFieldForID ( $id, "current_servers", "last_heartbeat" );
	if (!$result)
		return FALSE;
	return TRUE;
}
?>