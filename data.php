<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
/*
|---------------------------------------------------------------
| LOAD THE GLOBAL FUNCTIONS
|---------------------------------------------------------------
*/
require_once('common.php');

/*
|---------------------------------------------------------------
| INSTANTIATE THE DATABASE CLASS
|---------------------------------------------------------------
*/
$db = new Database();

/*
|---------------------------------------------------------------
| INSTANTIATE THE ERROR CLASS
|---------------------------------------------------------------
*/
$error = new Error();

//print_r($_REQUEST);
//echo "</br>";
if ($_REQUEST["day"] != ""){$GBL_day=$_REQUEST["day"];}else{$GBL_day=date("d");}
if ($_REQUEST["month"] != ""){$GBL_month=$_REQUEST["month"]+1;}else{$GBL_month=date("m");}
if ($_REQUEST["year"] != ""){$GBL_year=$_REQUEST["year"];}else{$GBL_year=date("Y");}

$itemLimit= 25;

$sql = "SELECT Count(*) as DailyCount FROM `security` WHERE extract(YEAR from time_stamp) = '$GBL_year' AND extract(MONTH from time_stamp) = '$GBL_month' AND extract(DAY from time_stamp) = '$GBL_day' AND file_type = '1'";
//print($sql);
//echo "</br>";
foreach ($db->query($sql) as $row)
{
    $totalRows = $row['DailyCount'];
}

//echo "Date: $date</br>";
echo "Total Rows: $totalRows</br>";
$data = "<table border='0'><thead><tr><th>Time</th><th>Event</th><th></th><th></th><th>changed_pixels</th><th>width</th><th>height</th><th>x</th><th>y</th></tr></thead><tbody>";



//$sql = "SELECT * from Drink order by Date";
//$sql = "SELECT * from Security order by date LIMIT $preLimit,$postLimit";

//SELECT 
//a.*, 
//b.filename as movie_filename
//FROM `security` a
//LEFT JOIN `security` b ON a.event_number = b.event_number AND b.file_type = '8' AND a.event_time_stamp = b.event_time_stamp
//WHERE extract(YEAR from a.time_stamp) = '2017' 
//AND extract(MONTH from a.time_stamp) = '2' 
//AND extract(DAY from a.time_stamp) = '23' 
//AND (a.file_type = '1')
//ORDER BY a.event_time_stamp

$sql = "SELECT a.*, b.filename as movie_filename FROM `security` a LEFT JOIN `security` b ON a.event_number = b.event_number AND b.file_type = '8' AND a.event_time_stamp = b.event_time_stamp WHERE extract(YEAR from a.time_stamp) = '$GBL_year' AND extract(MONTH from a.time_stamp) = '$GBL_month' AND extract(DAY from a.time_stamp) = '$GBL_day' AND a.file_type = '1' ORDER BY a.event_time_stamp";

$sumOunces = 0;
$maxOunces = 0;
$sumItems = 0;
$minDate = date("Y/m/d");
$maxDate = date("1990/01/01");
$dateRow = 1;
$dateCheck = "";

foreach ($db->query($sql) as $row)
{
//  $sumOunces+= $row['Ounces'];
//if ($dateCheck != $row['Date']){
//        if ($dateRow == 0){
//                $dateRow = 1;
//        }else{
//                $dateRow = 0;}
//}
//
  $data.="<tr class='motionRow'>";
//        if ($dateRow == 0){
//                $data.=" class='evenRow'";
//        }else{
//                $data.=" class='oddRow'";
  $data.="<td class='data_time_stamp'>" . substr($row['time_stamp'],11) . 
"</td><td class='data_event_number'>" . $row['event_number']. 
"</td><td class='image_link'><a href='#' data-target='" . substr($row['filename'],8)."'>JPG</a></td><td class='image_movie'><a href='#' data-target='" . substr($row['movie_filename'],8)."'>SWF</a></td><td class='data_changed_pixels'>" .$row['changed_pixels']. 
"</td><td class='data_motion_width'>" . $row['motion_width']. 
"</td><td class='data_motion_height'>" . $row['motion_height']. 
"</td><td class='data_motion_x'>" . $row['motion_x']. 
"</td><td class='data_motion_y'>" . $row['motion_y'];

//if ($row['Date'] < $minDate)
//{
//$minDate=$row['Date'];}
//if ($row['Date'] > $maxDate)
//{
//$maxDate=$row['Date'];}
//
  $data.="</td></tr>";
//  $sumItems++;
//$dateCheck = $row['Date'];
  }

$data.="</tbody></table>";
echo $data;
?>

