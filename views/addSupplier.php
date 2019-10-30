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
	
div.mid{
  width:50px;
  text-align:center;

}
select{
    width:100%;
    height:100px;
}
	</style>



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

function addBankRow()
{
	var table = document.getElementById("bankTbl");
		var rowNum = table.rows.length;
		var row = table.insertRow(table.rows.length);
		
		var bankName = row.insertCell(0);
		var accountName = row.insertCell(1)
		var accountNo = row.insertCell(2);
		var accountType = row.insertCell(3);
		
		bankName.innerHTML = '<input type=\'text\' class=\'form-control\'>';
		accountName.innerHTML = '<input type=\'text\' class=\'form-control\'>';
		accountNo.innerHTML = '<input type=\'text\' class=\'form-control\'>';
		accountType.innerHTML = '<select name=\'acctTypeList\' class=\'form-control\'>'+
		'<option value=\'Savings\'>Savings</option><option value=\'Current\'>Current</option></select>';
}

function add(){
	 $('#first option:selected').appendTo('#second');
}


$('.remove').click(function(){
    $('#second option:selected').appendTo('#first');
});
$('.add-all').click(function(){
    $('#first option').appendTo('#second'); 
});
$('.remove-all').click(function(){
    $('#second option').appendTo('#first'); 
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
                <span class="text-uppercase page-subtitle">Suppliers</span>
                <h3 class="page-title">Add Supplier</h3>
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
					  <li><a href="#tab02">Products</a></li>
					</ul>
				  </div>
				  <div class="tab-select-outer">
					<select id="tab-select">
					  <option value="#profileTab">Profile</option>
					  <option value="#tab02">Products</option>
					</select>
				  </div>
				  <div id="profileTab" class="tab-contents">
					<ul class="list-group list-group-flush">
						<li class="list-group-item p-3">
							<div class="row">
								<div class="col">
									<form>
										<div class="form-row">
											 <div class="form-group col-md-6">
										  <label for="supplierName">Supplier Name</label>
										  <input type="text" class="form-control" id="supplierName" placeholder="Supplier Name"> </div>
										   <div class="form-group col-md-6">
										  <label for="companyName">Company Name</label>
										  <input type="text" class="form-control" id="companyName" placeholder="Company Name"> 
										</div>
										</div>
										<div class="form-group">
										  <label for="address1">Address 1</label>
										  <input type="text" class="form-control" id="address1" placeholder="Address 1"> </div>
										<div class="form-row">
										  <div class="form-group col-md-6">
											<label for="city">City</label>
											<input type="text" class="form-control" id="city"> </div>
										  <div class="form-group col-md-4">
											<label for="region">Region</label>
											<select id="region" class="form-control">
											  <option selected>Choose...</option>
											  <option>...</option>
											</select>
										  </div>
										  <div class="form-group col-md-2">
											<label for="inputZip">Zip</label>
											<input type="text" class="form-control" id="inputZip"> </div>
										</div>
										<div class="form-row">                    
										  <div class="form-group col-md-6">
											<label for="telNo">Telephone No.</label>
											<input type="text" class="form-control" id="telNo" placeholder="Telephone No."> </div>
										<div class="form-group col-md-6">
											<label for="mobileNo">Mobile No.</label>
											<input type="text" class="form-control" id="mobileNo" placeholder="Mobile No."> </div>								
										</div>
										<div class="form-row">
											<div class="form-group col-md-6">
											<label for="companyEmail">Email Address</label>
											<input type="email" class="form-control" id="companyEmail" placeholder="Email Address"> </div>
											<div class="form-group col-md-6">
											<label for="faxNo">Fax No.</label>
											<input type="text" class="form-control" id="faxNo" placeholder="Fax No."> </div>
										</div>
										<div class="form-group">
										<label for="bankTbl">Bank Details</label>
										<table class="table mb-0" id="bankTbl" style="border-collapse: collapse;border: 1px solid black;border-color:#e1e5eb;">
										   <thead>
											<tr>
												<td>Bank Name</td>
												<td>Account Name</td>
												<td>Account No.</td>
												<td>Account Type</td>
											</tr>
										   </thead>
										  <tbody>

										  </tbody>
										</table><br/>
										<div style="text-align:right;">
										 <button type="button" class="btn btn-accent" onclick="addBankRow();" style="margin-left:10px;">+ Add Bank Details</button>
										</div>
										</div>
										<div class="form-row">
										  <div class="form-group col-md-12">
											<label for="supplierDesc">Description/Notes</label>
											<textarea class="form-control" name="supplierDesc" rows="5"></textarea>
										  </div>
										</div>
										<div style="text-align:right">
										<button type="submit" class="btn btn-accent">Save Changes</button>
										</div>
									</form>
								</div>
							</div>
						</li>
					</ul>
				  </div>
				  <div id="tab02" class="tab-contents">
					<ul class="list-group list-group-flush">
						<li class="list-group-item p-3">
							<div class="row">
								<div class="col">
    <select id="first" multiple="true">
        <option value="something@something.com"> something@something.com </option>
        <option value="something@something.com"> something@something.com </option> 
        <option value="something@something.com"> something@something.com </option> 
        <option value="something@something.com"> something@something.com </option> 
        <option value="something@something.com"> something@something.com </option>        
    </select></div><div class="col">
<div class="mid">
    <button type="button" onclick="add();"> > </button>
    <button class='remove'> < </button>
    <button class='add-all'> >>> </button>
    <button class='remove-all'> <<< </button>
</div></div><div class="col">
<div class="end">
    <select id="second" multiple="true">
    </select>
</div>
<div style="clear:both;"></div></div>
								
							</div>
						</li>
					</ul>
					
					
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