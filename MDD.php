<?php
  // Standard inclusions
  include("../pChart/class/pData.class.php");
  include("../pChart/class/pDraw.class.php");
  include("../pChart/class/pSurface.class.php");
  include("../pChart/class/pImage.class.php");
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
//$error = new Error();


if ($_REQUEST["type"] != ""){$type=$_REQUEST["type"];}else{die("Type not Defined");}
if ($_REQUEST["year"] != ""){$year=$_REQUEST["year"];}else{die("Year not Defined");}
if ($_REQUEST["mult"] != ""){$mult=$_REQUEST["mult"];}else{$mult = "2.5";}
if ($_REQUEST["cont"] != ""){$cont=$_REQUEST["cont"];}else{$cont = "0";}


  // Dataset definition
  $myData = new pData;

 /* Create the pChart object */
 $myPicture = new pImage(300,400);

 /* Create a solid background */
 $Settings = array("R"=>91, "G"=>91, "B"=>91, "Dash"=>0, "DashR"=>199, "DashG"=>237, "DashB"=>111);
 $myPicture->drawFilledRectangle(0,0,300,400,$Settings);

 /* Do a gradient overlay */
 $Settings = array("StartR"=>31, "StartG"=>31, "StartB"=>31, "EndR"=>61, "EndG"=>61, "EndB"=>61, "Alpha"=>50);
 $myPicture->drawGradientArea(0,0,300,400,DIRECTION_VERTICAL,$Settings);
 $myPicture->drawGradientArea(0,0,300,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,299,399,array("R"=>0,"G"=>0,"B"=>0));

 /* Write the picture title */
 $myPicture->setFontProperties(array("FontName"=>"../fonts/tahoma.ttf","FontSize"=>9));

if ($type==1){
 $myPicture->drawText(10,13,"Sum of changed_pixels - $year",array("R"=>255,"G"=>255,"B"=>255));
}elseif ($type==2){
 $myPicture->drawText(10,13,"Event Count - $year",array("R"=>255,"G"=>255,"B"=>255));
}else{
 $myPicture->drawText(10,13,"Avg of chanaged_pixels - $year",array("R"=>255,"G"=>255,"B"=>255));
}
 /* Define the charting area */
 $myPicture->setGraphArea(20,40,290,390);
 $myPicture->drawFilledRectangle(20,40,290,390,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>20));

 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1));

 /* Create the surface object */
 $mySurface = new pSurface($myPicture);

 /* Set the grid size */
 $mySurface->setGrid(11,30);

 /* Write the axis labels */
 $myPicture->setFontProperties(array("FontName"=>"../fonts/tahoma.ttf","FontSize"=>6));
 $mySurface->writeXLabels(array("Labels"=>array("J","F","M","A","M","J","J","A","S","O","N","D"),"R"=>255,"G"=>255,"B"=>255,"Alpha"=>100));
 $mySurface->writeYLabels(array("Labels"=>array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31),"R"=>255,"G"=>255,"B"=>255,"Alpha"=>100));

 /* Add random values */
// for($i=0; $i<=50; $i++) { $mySurface->addPoint(rand(0,20),rand(0,20),rand(0,100)); }

if ($type==1){
$sql = "
SELECT Distinct DATE(event_time_stamp),
EXTRACT(Month from event_time_stamp) as Month,
EXTRACT(Day from event_time_stamp) as Day,
sum(changed_pixels)/100 as sum,
avg(changed_pixels) as changed_pixel_avg,
count(changed_pixels) as event_count 
from security
WHERE file_type='1' AND (EXTRACT(Year from event_time_stamp)='$year')
group by DATE(event_time_stamp)
order by event_time_stamp";
}elseif ($type==2){
$sql = "
SELECT Distinct DATE(event_time_stamp),
EXTRACT(Month from event_time_stamp) as Month,
EXTRACT(Day from event_time_stamp) as Day,
sum(changed_pixels) as changed_pixel_sum,
avg(changed_pixels) as changed_pixel_avg,
count(changed_pixels) as sum from security
WHERE file_type='1' AND (EXTRACT(Year from event_time_stamp)='$year')
group by DATE(event_time_stamp)
order by event_time_stamp";
}else{
$sql = "
SELECT Distinct DATE(event_time_stamp),
EXTRACT(Month from event_time_stamp) as Month,
EXTRACT(Day from event_time_stamp) as Day,
sum(changed_pixels) as changed_pixel_sum,
avg(changed_pixels)/100 as sum,
count(changed_pixels) as event_count from security
WHERE file_type='1' AND (EXTRACT(Year from event_time_stamp)='$year')
group by DATE(event_time_stamp)
order by event_time_stamp";
}
//echo "$sql </br>";

 if ($db->row_count($sql) != 0)
 {
        $totalArray="";$typeArray="";
        foreach ($db->query($sql) as $row)
        {
                $mySurface->addPoint(($row['Month']-1),($row['Day']-1),($row['sum']*$mult));
        }
 }

 /* Compute the missing points */
if ($cont > 0){
 $mySurface->computeMissing();
}
/* Draw the contour with a threshold of 50 */
// $mySurface->drawContour(70,array("R"=>0,"G"=>0,"B"=>0));
 /* Draw the surface chart */
 $mySurface->drawSurface(array("Border"=>TRUE,"Surrounding"=>25));

 /* Render the picture (choose the best way) */
// $myPicture->autoOutput("pictures/example.surface.png");
 $myPicture->stroke("$type.$year.surface.png");
 ?>

