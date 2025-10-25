<?php
$con = mysqli_connect("localhost","inno-sfr-01","inno-sfr-01","demomasterdb");

// Check connection
$dbArray = array();
$sql="SELECT * from users where acc_type='user'";

$result=mysqli_query($con,$sql);
while ($row=mysqli_fetch_array($result))
{
    $startTimeStamp = strtotime($row['created_at']);
    $endTimeStamp = strtotime(date('Y-m-d'));

    $timeDiff = abs($endTimeStamp - $startTimeStamp);
    $numberDays = $timeDiff/86400;  // 86400 seconds in one day
    $numberDays = intval($numberDays);

    if($numberDays >= 90):
        $sql5 = "UPDATE users SET password_status='1' where emr_no = '".$row['emr_no']."'";
        $result5 = mysqli_query($con,$sql5);
    endif;
}


mysqli_close($con);

?>