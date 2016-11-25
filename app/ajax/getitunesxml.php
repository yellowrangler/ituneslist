<?php

include_once ('../class/class.Log.php');
include_once ('../class/class.ErrorLog.php');
include_once ('../class/class.AccessLog.php');

//
// get xml itunes file
//
if (file_exists('../../data/itunesmusic.xml')) 
{
    // $xml = simplexml_load_file('../../data/test.xml');
    $xml = simplexml_load_file('../../data/itunesmusic.xml');
    if ($xml == false)
    {
        exit('Failed to get itunesmusic.xml.');
    }

    // print_r($xml);
} 
else 
{
    exit('Failed to open itunesmusic.xml.');
}

//
// get date time for this transaction
//
$datetime = date("Y-m-d H:i:s");

// print_r($_POST);
// die();

// get post values & set values for query
// $loginpasswd = $_POST["passwd"];
// $loginscreenname = $_POST["screenname"];
// $rc = 1;
// $msgtext = "";

//
// messaging
//
$returnArrayLog = new AccessLog("logs/");
// $returnArrayLog->writeLog("Client List request started" );

//------------------------------------------------------
// get admin member info
//------------------------------------------------------
// open connection to host
$DBhost = "localhost";
$DBschema = "itunesmusic";
$DBuser = "tarryc";
$DBpassword = "tarryc";

//
// connect to db
//
$dbConn = @mysql_connect($DBhost, $DBuser, $DBpassword);
if (!$dbConn) 
{
    $log = new ErrorLog("logs/");
    $dberr = mysql_error();
    $log->writeLog("DB error: $dberr - Error mysql connect. Unable to login.");

    $rv = "";
    exit($rv);
}

if (!mysql_select_db($DBschema, $dbConn)) 
{
    $log = new ErrorLog("logs/");
    $dberr = mysql_error();
    $log->writeLog("DB error: $dberr - Error selecting db Unable to login.");

    $rv = "";
    exit($rv);
}

$json = json_encode($xml);
$array = json_decode($json,TRUE);

// print_r($array);

print_r($json);

?>