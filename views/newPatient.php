<?php
require('../db.php');
include("../auth.php"); 

  
?>

<!DOCTYPE html>
<html class="no-js h-100" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>VetSales - Dashboard</title>
    <meta name="description" content="VetSales POS and Clinic Management System">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php include "imports.inc"; ?>
	<link rel="stylesheet" href="tabs/tabs.css">
	<style>
	.table td, .table th{
		border-top:0px !important; 
	}
	</style>

<?php

	$msg = "";
	if (isset($_POST['submit'])) {
		$username = $_SESSION['username'];
		$productName = $_POST['productName']; 
		$productBrand = $_POST['productBrand']; 
		$productDesc = $_POST['productDesc']; 
		$category = $_POST['categoryList']; 
		$quantity = $_POST['quantity'];
		$cost = $_POST['cost']; 	
		$srp = $_POST['srp']; 
		$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	   
	    $imgData = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $imageProperties = getimageSize($_FILES['image']['tmp_name']);
    
		
		$sql = "INSERT INTO products (image,dateAdded,brand,productDesc, productTitle,quantity,cost,srp,branchId,status,category) VALUES 
		('$imgData',sysdate(),'$productBrand','$productDesc', '$productName',$quantity,$cost,$srp,$branchId,1,$category)";
		if(mysqli_query($con, $sql)){
			echo "<script>
	$(document).ready(function(){
	$('#myModal').modal('show');
	});
	</script>

			<div class='modal fade' id='myModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Product added.</p>
					</div>
						<div class='modal-footer'>
							
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
			</div>";         
		}else{
			echo "<div>".mysqli_error($con)."</div>";
		}

	}
	
	//batch upload
	
	if(isset($_POST["import"])){
		
    $username = $_SESSION['username'];
	$sql = "SELECT branchId FROM users where username='$username'";
	if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	   
	   
    $filename=$_FILES["file"]["tmp_name"];  
	$isUploaded = true;
     if($_FILES["file"]["size"] > 0)
     {
        $file = fopen($filename, "r");
          while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
           {
             $sql = "INSERT into products (dateAdded,productTitle,quantity,cost,srp,category,status,branchId) 
                   values (sysdate(),".$getData[0].",".$getData[1].",".$getData[2].",".$getData[3].",".$getData[4].",1,$branchId)";
                   $result = mysqli_query($con, $sql);
				if(!isset($result))
				{
				 $isUploaded = false;
				}
       
           }
		   if($isUploaded){
			   echo "<script type=\"text/javascript\">
            alert(\"CSV File has been successfully Imported.\");
          </script>";
		   }
		   
      
           fclose($file);  
     }
  }   

 

?>

<script>
$(function() {
  var $tabButtonItem = $('#tab-button li'),
      $tabSelect = $('#tab-select'),
      $tabContents = $('.tab-contents'),
      activeClass = 'is-active';

  $tabButtonItem.first().addClass(activeClass);
  $tabContents.not(':first').hide();

  $tabButtonItem.find('a').on('click', function(e) {
    var target = $(this).attr('href');

    $tabButtonItem.removeClass(activeClass);
    $(this).parent().addClass(activeClass);
    $tabSelect.val(target);
    $tabContents.hide();
    $(target).show();
    e.preventDefault();
  });

  $tabSelect.on('change', function() {
    var target = $(this).val(),
        targetSelectNum = $(this).prop('selectedIndex');

    $tabButtonItem.removeClass(activeClass);
    $tabButtonItem.eq(targetSelectNum).addClass(activeClass);
    $tabContents.hide();
    $(target).show();
  });
});
</script>
  </head>
  <body class="h-100"><div class="form">
    <div class="container-fluid">
      <div class="row">
        <!-- Main Sidebar -->
        <?php include "menu.inc"; ?>
        <!-- End Main Sidebar -->
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
          <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar -->
            <?php include "mainnavbar.inc"; ?>
          <!-- / .main-navbar -->
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Veterinary</span>
                <h3 class="page-title">New Patient Record</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <!-- Default Light Table -->
            <div class="row">
              <div class="col">
                
                 <div class="tabs">
				  <div class="tab-button-outer">
					<ul id="tab-button">
					  <li><a href="#profileTab">Profile</a></li>
					  <li><a href="#tab02">Clinical Exams</a></li>
					  <li><a href="#tab03">Medical History</a></li>
					  <li><a href="#tab04">Food Diet History</a></li>
					</ul>
				  </div>
				  <div class="tab-select-outer">
					<select id="tab-select">
					  <option value="#profileTab">Profile</option>
					  <option value="#tab02">Clinical Exams</option>
					  <option value="#tab03">Medical History</option>
					  <option value="#tab04">Food Diet History</option>
					</select>
				  </div>

				  <div id="profileTab" class="tab-contents">
					  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <form>
                            <div class="form-group">
                              <label for="ownerName">Name of Owner</label>
                              <input type="text" class="form-control" id="ownerName" placeholder="Name of Owner"> </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feEmailAddress">Email</label>
                                <input type="email" class="form-control" id="feEmailAddress" placeholder="Email" value="sierra@example.com"> </div>
                              <div class="form-group col-md-6">
                                <label for="fePassword">Password</label>
                                <input type="password" class="form-control" id="fePassword" placeholder="Password"> </div>
                            </div>
                            <div class="form-group">
                              <label for="feInputAddress">Address</label>
                              <input type="text" class="form-control" id="feInputAddress" placeholder="1234 Main St"> </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feInputCity">City</label>
                                <input type="text" class="form-control" id="feInputCity"> </div>
                              <div class="form-group col-md-4">
                                <label for="feInputState">State</label>
                                <select id="feInputState" class="form-control">
                                  <option selected>Choose...</option>
                                  <option>...</option>
                                </select>
                              </div>
                              <div class="form-group col-md-2">
                                <label for="inputZip">Zip</label>
                                <input type="text" class="form-control" id="inputZip"> </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-12">
                                <label for="feDescription">Description</label>
                                <textarea class="form-control" name="feDescription" rows="5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio eaque, quidem, commodi soluta qui quae minima obcaecati quod dolorum sint alias, possimus illum assumenda eligendi cumque?</textarea>
                              </div>
                            </div>
                            <button type="submit" class="btn btn-accent">Update Account</button>
                          </form>
                        </div>
                      </div>
                    </li>
                  </ul>
                        </div>
                      </div>
                    </li>
                  </ul>
				  </div>
				  <div id="tab02" class="tab-contents">
					<h2>Tab 2</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius quos aliquam consequuntur, esse provident impedit minima porro! Laudantium laboriosam culpa quis fugiat ea, architecto velit ab, deserunt rem quibusdam voluptatum.</p>
				  </div>
				  <div id="tab03" class="tab-contents">
					<h2>Tab 3</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius quos aliquam consequuntur, esse provident impedit minima porro! Laudantium laboriosam culpa quis fugiat ea, architecto velit ab, deserunt rem quibusdam voluptatum.</p>
				  </div>
				  <div id="tab04" class="tab-contents">
					<h2>Tab 4</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius quos aliquam consequuntur, esse provident impedit minima porro! Laudantium laboriosam culpa quis fugiat ea, architecto velit ab, deserunt rem quibusdam voluptatum.</p>
				  </div>
				  <div id="tab05" class="tab-contents">
					<h2>Tab 5</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius quos aliquam consequuntur, esse provident impedit minima porro! Laudantium laboriosam culpa quis fugiat ea, architecto velit ab, deserunt rem quibusdam voluptatum.</p>
				  </div>
				</div>
               
              </div>
            </div>
            <!-- End Default Light Table -->
            <!-- Default Dark Table -->
            
            <!-- End Default Dark Table -->
          </div>
    <?php include "scripts.inc"; ?>
	</div>

  </body>
</html>