<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employee Details</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<?php session_start();
include 'config/database.php';?>
<style>
.table-primary{background-color: #b8daff;}
.file {position: relative;overflow: hidden;cursor: pointer;}
.table{margin-top:15px;}
h4{color:#777;}
#file{cursor: pointer;}
</style>

<?php
$_SESSION["error_status"] ='0';
if(isset($_FILES['file']['name'])){
  //file validation
  $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  if($ext=='csv'){
  //Row and Column Validation
  $row = 1;
  $error_status =$flag =0 ;
  $handle = fopen($_FILES['file']['tmp_name'], "r");
    while ($data = fgetcsv($handle)) {
      $column = count($data);//column count
      $row++;//row count
      if($row>20)
      {
        $flag = 1;
        // 'Specified record limit exceeds!'; 
      }
      if($column!=5)
      {
        $flag = 1;
        // 'Specified column limit exceeds!'; 
      }
      if($flag==0){
        $emp_code = $data[0];
        $emp_name = $data[1];  
        $department = $data[2];
        $age = $data[3];
        $experience = $data[4];
        if(preg_match('/[^a-z_\-0-9]/i', $emp_code))
        {
          // 'Employee Code is Invalid Input-alphanumeric validation';
          $error_status =1;
        }
        if (preg_match("/[^A-Za-z]/", $emp_name))
        {
          //'Employee Name is Invalid Input-character validation';
          $error_status =1;
        }
        if (preg_match("/[^A-Za-z]/", $department))
        {
         // 'Department is Invalid Input-character validation';
          $error_status =1;
        }
        if(!is_numeric($experience))
        {
          // 'Experience is Invalid Input-numeric validation';
          $error_status =1;
        }
        if(!is_numeric($age))
        {
        //'Age is Invalid Input-numeric validation';
        $error_status =1;
        }
    }
    else {
      $_SESSION["file_validation"] = "1";
    }
  }

  if($error_status ==1)
  {
    $_SESSION["error_status"] = "1";
  }
  else if($flag==0){
    $_SESSION["error_status"] ="2";
    $fhandle = fopen($_FILES['file']['tmp_name'], "r");
      while ($listing = fgetcsv($fhandle)) {
      $emp_code = $listing[0];
      $emp_name = $listing[1];  
      $department = $listing[2];
      $age = $listing[3];
      $experience = $listing[4];
      $sql = "INSERT INTO employee_details(employee_code,employee_name,department,age,experience)
      VALUES ('$emp_code','$emp_name','$department',$age,$experience)";
        $conn->query($sql);
  }
  }
  }
  else{
    $_SESSION["file_status"] ="1";
  } 
} 
?>

<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">Employee Management</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php">Home</a></li>
    </ul>
  </div>
</nav>
  
<div class="container">
  <h3>Employee Details</h3>
  <p>You can add employee details by uploading csv files.</p>

  <?php if(isset($_SESSION["error_status"]) && ($_SESSION["error_status"]==1)){ ?>
  <div class="alert alert-danger alert-dismissible">
    <strong>Error!</strong> Invalid Data format.please check the file before uploading.
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } if(isset($_SESSION["error_status"]) && ($_SESSION["error_status"]==2)){ ?>
  <div class="alert alert-success alert-dismissible">
    <strong>File Uploaded Successfully.</strong>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div> 
  <?php }  if(isset($_SESSION["file_status"]) && ($_SESSION["file_status"]==1)){ ?>
  <div class="alert alert-danger alert-dismissible">
    <strong>Invalid File Format !</strong>Allowed file type CSV.
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div> 
  <?php }  if(isset($_SESSION["file_validation"]) && ($_SESSION["file_validation"]==1)){ ?>
  <div class="alert alert-danger alert-dismissible">
    <strong>Invalid File Data Format !</strong>Should contain minimum of 5 rows and maximum of 20 records.
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div> 
  <?php }?>
  <form id="myform" method="post" action="" enctype="multipart/form-data" id="myform">
  <div class='row'>
  <div class='col-md-4 col-xs-12 pull-right'>
  <div class='pull-right'>
  <div class="file btn btn-sm btn-primary">

		<input type="file" name="file"  id='file' >
	</div>
    <input type="submit" value="Upload" class="file btn btn-sm btn-success" />
  </div>  
  </div>  
  </div>  
</form>
<section>
<p>Data Format*</p>
<p>Employee Code:Alphanumeric values,Employee Name:Alphabetic values,Department:Alphabetic values,Age:Numeric values,Experience:Numeric values.</p>
</section>
<table class="table table-bordered table-striped">
    <thead>
      <tr  class="table-primary">
        <th>Employee Code</th>
        <th>Employee Name</th>
        <th>Department</th>
        <th>Age</th>
        <th>Experience in the Organization</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM employee_details";
    $employee = $conn->query($sql);
     foreach($employee as $emp){ ?>
      <tr>
        <td><?= $emp['employee_code']; ?></td>
        <td><?= $emp['employee_name']; ?></td>
        <td><?= $emp['department']; ?></td>
        <td><?= $emp['age']; ?></td>
        <td><?= $emp['experience']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
</table>
</div>
</body>

<script type="text/javascript">
$(document).ready(function () {
  setTimeout(function() { 
    $('.alert-dismissible').hide();
    '<?php unset($_SESSION['error_staus']);
           unset($_SESSION['file_status']);
           unset($_SESSION['file_validation']);
        ?>'
    }, 6000);
});
</script>
</html>

