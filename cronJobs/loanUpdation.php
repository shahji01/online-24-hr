<?php
$con = mysqli_connect("localhost","vis001","?-2_XAlGj-s2","vis_master");

// Check connection
$dbArray = array();
$sql="SELECT dbName FROM company";


  $result=mysqli_query($con,$sql);
  while ($row=mysqli_fetch_array($result))
  {
    $dbArray[] = $row['dbName'];
      echo $row['dbName']."<br>";

  }

  foreach($dbArray as $value):
      $con2 = mysqli_connect("localhost","vis001","?-2_XAlGj-s2",$value);
      $sql2="SELECT loan_request.emp_id,loan_request.id from loan_request INNER JOIN employee on employee.emp_id=loan_request.emp_id";
      $result2=mysqli_query($con2,$sql2);

       if(!$result2){echo mysqli_error($con2);}

      while ($row2=mysqli_fetch_array($result2))
      {

           $sql3="SELECT sum(loan_amount_paid) from payslip where loan_id = '".$row2['id']."' and emp_id='".$row2['emp_id']."'";
           $result3 = mysqli_query($con2,$sql3);
           if(!$result3){echo mysqli_error($con2);}
           $row3=mysqli_fetch_array($result3);

           $sql4="SELECT loan_amount from loan_request where id = '".$row2['id']."' and emp_id='".$row2['emp_id']."'";
           $result4 = mysqli_query($con2,$sql4);
           if(!$result4){echo mysqli_error($con2);}
           $row4=mysqli_fetch_array($result4);
           if($row3['sum(loan_amount_paid)'] >= $row4['loan_amount'])
           {
               $sql5 = "UPDATE loan_request SET loan_status='1' where id = '".$row2['id']."' and emp_id='".$row2['emp_id']."'";
               $result5 = mysqli_query($con2,$sql5);
           }
      }
  endforeach;
mysqli_close($con);

?>