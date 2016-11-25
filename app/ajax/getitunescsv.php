<?php

include_once ('../class/class.Log.php');
include_once ('../class/class.ErrorLog.php');
include_once ('../class/class.AccessLog.php');

//
//  set global values
//

// mysql_real_escape_string
// Name,Total Time,Artist,Album,Track Number,Track Count,Year,Genre
$msgtext = "";
$musicfieldnbr = 9;
$importfiledirectory = "../../data/";
$importfile = "iTunesMusic.csv";
$truncate = false; 

$songIdx = 0;
$totaltimeIdx = 1;
$artistIdx = 2;
$albumIdx = 3;
$trackIdx = 4;
$tracksIdx = 5;
$yearIdx = 6;
$genreIdx = 7;
$filelocationIdx = 8;

$song = " ";
$totaltime = " ";
$artist = " ";
$album = " ";
$track = 0;
$tracks = 0;
$year = " ";
$genre = " ";
$fileLocation = "";

$nbrInserted = 0;

//
// post/get input
//
if( isset($_POST['location']) )
{
    $location = $_POST['location']; 
}
else
{
    if( isset($_GET['location']) )
    {
        $location = $_GET['location']; 
    }
    else
    {
        $msgtext = "Invalid post/get. No location!";
        exit($msgtext);
    }
        
}

if( isset($_POST['filename']) )
{
    $importfile = $_POST['filename']; 
}
else
{
    if( isset($_GET['filename']) )
    {
        $importfile = $_GET['filename']; 
    }   
}

$truncateMsg = "No";
if( isset($_POST['truncate']) )
{
    $truncate = true; 
    $truncateMsg = "Yes";
}
else
{
    if( isset($_GET['truncate']) )
    {
        $truncate = true; 
        $truncateMsg = "Yes";
    }   
}

//
// show file name passed in
//
$msgtext = "<br /><br />Input Parameters: Location: $location Truncate: $truncateMsg importfile: $importfile";
$msgtext;

// print_r($_POST);
// die()

//
// get date time for this transaction
//
$datetime = date("Y-m-d H:i:s");

// // create time stamp versions for insert to mysql
$enterdateTS = date("Y-m-d H:i:s", strtotime($datetime));

// print_r($_POST);
// die();

//
// messaging
//
// $returnArrayLog = new AccessLog("logs/");
// $returnArrayLog->writeLog("Add member request started" );

//------------------------------------------------------
// get ddd access
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
    $log->writeLog("DB error: $dberr - Error mysql connect. Unable to add itunes music for location $location.");

    $rv = "";
    exit($rv);
}

if (!mysql_select_db($DBschema, $dbConn)) 
{
    $log = new ErrorLog("logs/");
    $dberr = mysql_error();
    $log->writeLog("DB error: $dberr - Error selecting db Unable to add itunes music for location $location.");

    $rv = "";
    exit($rv);
}


if ($truncate == true)
{
    // 
    // do truncate if asked
    // 
    $sql = "TRUNCATE musictbl";

    $sql_result = @mysql_query($sql, $dbConn);
    if (!$sql_result)
    {
        $log = new ErrorLog("logs/");
        $sqlerr = mysql_error();
        $log->writeLog("SQL error: $sqlerr - Error doing truncate to db Unable to add itunes music for location $location.");
        $log->writeLog("SQL: $sql");

        $status = -260;
        $msg = $msg . "SQL error: $sqlerr <br /> Error doing truncate to db Unable to add itunes music for location $location.<br />SQL: $sql";
        exit($msg);
    } 

    echo "<br />Table Truncated<br />";
}
    

//---------------------------------------------------------------
// read csv file
//---------------------------------------------------------------
$fullyqualifiedimportfilename = $importfiledirectory . $importfile;
if (!file_exists ($fullyqualifiedimportfilename) )
{
    $msgtext = "File $fullyqualifiedimportfilename does not exist!";
    exit($msgtext);
}


$filerow = 0;
$handle = fopen("$fullyqualifiedimportfilename", "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $filerow++;

    $num = count($data);
    // $msgtext = $msgtext . "<p> $num fields in line $filerow<br /></p>\n";

    // 
    // first row is header
    //
    if ($filerow == 1)
    {
        continue;
    }

    if ($num != $musicfieldnbr)
    {
        $msgtext = $msgtext . "<p> Invalid column count: $num <br /></p>\n";
        exit($msgtext);
    }

    //
    // build column variables from row
    //
    $song = mysql_real_escape_string(empty($data[$songIdx]) ? " " : $data[$songIdx]);
    $totaltime = empty($data[$totaltimeIdx]) ? ' ' : $data[$totaltimeIdx];
    $artist = mysql_real_escape_string(empty($data[$artistIdx]) ? ' ' : $data[$artistIdx]);
    $album = mysql_real_escape_string(empty($data[$albumIdx]) ? ' ' : $data[$albumIdx]);
    $track = empty($data[$trackIdx]) ? 0 : $data[$trackIdx];
    $tracks = empty($data[$tracksIdx]) ? 0 : $data[$tracksIdx];
    $year = empty($data[$yearIdx]) ? ' ' : $data[$yearIdx];
    $genre = empty($data[$genreIdx]) ? ' ' : $data[$genreIdx];
    $filelocation = mysql_real_escape_string(empty($data[$filelocationIdx]) ? ' ' : $data[$filelocationIdx]);
    
    // 
    // do insert
    // 
    $sql = "INSERT INTO musictbl 
        (artist, album, song, track, tracks, totaltime, genre, filelocation, location, importdate) 
        VALUES ('$artist', '$album', '$song', $track, $tracks, '$totaltime', '$genre', '$filelocation', '$location', '$enterdateTS')";
        
    // debug
    // $msg = $msg .  "sql insert:$sql<br/>";

    $sql_result_insert = @mysql_query($sql, $dbConn);
    if (!$sql_result_insert)
    {
        $log = new ErrorLog("logs/");
        $sqlerr = mysql_error();
        $log->writeLog("SQL error: $sqlerr - Error doing insert to db Unable to add itunes music for location $location.");
        $log->writeLog("SQL: $sql");

        $status = -260;
        $msg = $msg . "SQL error: $sqlerr <br /> Error doing insert to db Unable to add itunes music for location $location.<br />SQL: $sql";
        exit($msg);
    }

    $nbrInserted = $nbrInserted + 1;

    if ($nbrInserted > 1)
    {
        echo ", $nbrInserted";
    }
    else
    {
        echo "<br />Number Inserted = $nbrInserted";
    }
 
    $testNbr = $nbrInserted % 10;
    if ($testNbr == 0)
    {
        echo "<br />Number Inserted = $nbrInserted";
    }


} // end of while 

//
// close import file
//
fclose($handle);

// 
// close db connection
// 
mysql_close($dbConn);

//
// final message 
//
$msgtext = $msgtext . "<br />Totals: Rows read: $filerow. Number Inserted: $nbrInserted.";

//
// pass back info
//

exit($msgtext);
?>
