<!--PHP login System by WEBDEVTRICK (https://webdevtrick.com) -->
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
<style>
i.icon-red {
    color: red !important;
}

.modal-body{
    max-height: 450px;
    overflow-y: auto;
}
</style>
<script>
$(document).ready(function() {
   $("#discount").change(function() {
	  var newTotal = totalBillAmount;
	   newTotal = newTotal - $("#discount").val();
    $("#totalDue").val(newTotal);
	});
	$("#transactionDate").datepicker({ 
        format: 'mm/dd/yyyy'
    });
	
	$('#serviceModal').on('hide.bs.modal', function (e) {
		$("#service_results").html("");
		})
	$('#productModal').on('hide.bs.modal', function (e) {
		$("#search_results").html("");
		})
	$('#savedModal').on('hidden.bs.modal', function () {
    clearTransaction();
});
	
});
	
	var totalBillAmount = 0;
	 	 $(function() {
        $("#searchForm").bind('submit',function() {
          var productVal = $('#searchProduct').val();
		  var user = "<?php echo $_SESSION['username']; ?>";
		  if(productVal==null){
			  productVal = "";
		  }
           $.post('getproducts.php',{product:productVal,user:user}, function(data){
             $("#search_results").html(data);
           });
           return false;
        });
      });
	  
	 
	function removeAll(){
	 $("#resultTbl").empty();
	 document.getElementById("searchProduct").value="";
	 document.getElementById("productId").value="";
	}
 
	function addItem(id,srp){
	
	
		var productTitle="";
		var user = "<?php echo $_SESSION['username']; ?>";

		var quantityVal = document.getElementById(id).value;
		
		if(quantityVal<1){
			$('#addQuantityModal').modal('show');
			return false;
		}else{
			$.ajax({
			url: "getproductdetails.php",
			type: "POST",
			dataType: 'JSON',
			data: {productId:id,user:user},
			success: function(result){
				fillTable(id,result);
			}
		});
		}

	}
	
	function saveTransaction(){
		
		var change = document.getElementById("change").value;
		var totalPaid = document.getElementById("amountPaid").value;
		var table = document.getElementById("billSummaryTbl");
		var rows = table.rows.length;
		var date = document.getElementById("transactionDate").value;
		var customerName = document.getElementById("customerName").value;
		var customerAddr = document.getElementById("customerAddr").value;
		var customerContact = document.getElementById("customerContact").value;
		
		if(rows==1){
			$('#itemsModal').modal('show');
			return false;
		}else if(totalPaid==null || totalPaid==''){
			$('#paidModal').modal('show');
			return false;
		}else if(change==null || change==''){
			$('#changeModal').modal('show');
			return false;
		}
		
		var user = "<?php echo $_SESSION['username']; ?>";
		
		var totalAmount = totalBillAmount;
		var change = document.getElementById("change").value;
		var discount = document.getElementById("discount").value;

		$.ajax({
        url: "addNewTransaction.php",
        type: "POST",
		dataType: 'JSON',
        data: {totalAmount:totalAmount,totalPaid:totalPaid,date:date,change:change,user:user,customerName:customerName,customerAddr:customerAddr,customerContact:customerContact,discount:discount},
        success: function(result){
			saveTransactionItems(result);
			
        }
    });
	
	
	}
	
	function saveTransactionItems(data){
		
		var table = document.getElementById("billSummaryTbl");
		var rowNum = table.rows.length;
		var transactionId = data.transactionId;
		var user = "<?php echo $_SESSION['username']; ?>";
 
        for (var i = 1 ; i < table.rows.length; i++) {
				var type = table.rows[i].cells[0].innerHTML;
				var productId = table.rows[i].cells[1].innerHTML;
				var quantity = table.rows[i].cells[4].innerHTML;
				var totalAmount = table.rows[i].cells[7].innerHTML;
				var totalDiscount = table.rows[i].cells[6].innerHTML;
				var renderId='';
				if(type=='R'){
					$.ajax({
						url: "addTransactionItems.php",
						type: "POST",
						dataType: 'JSON',
						async: false,
						data: {transactionId:transactionId,productId:productId,quantity:quantity,totalAmount:totalAmount, user:user},
						success: function(result){
						}
					});
				}else if(type=='S'){
					var row=i;
					//alert("service row:" +row);
					$.ajax({
						url: "addServiceItems.php",
						type: "POST",
						async: false,
						dataType: 'JSON',
						data: {transactionId:transactionId,serviceId:productId,totalExpense:totalDiscount,totalAmount:totalAmount, user:user},
						success: function(result){
							
							saveServiceItemDetail(row,result);
						}
					});
					
				}
				
 
         
        }
		
		$('#savedModal').modal('show');
		
	//	resetBill();
		//removeAll();
	}
	
	function saveServiceItemDetail(row,result){
		var table = document.getElementById("billSummaryTbl");
		var rowNum=row+1;
		//alert(rowNum);
		var renderId = result.renderId;
		var serviceId = result.serviceId;
		var user = "<?php echo $_SESSION['username']; ?>";
		
		for (var i = rowNum ; i < table.rows.length; i++) {
			
			var type = table.rows[i].cells[0].innerHTML;
			var fieldValue = table.rows[i].cells[2].innerHTML;
			var fieldArr = fieldValue.split(":");
			if(type==''){
				
				//alert(fieldArr);
				$.ajax({
						url: "addServiceDetails.php",
						type: "POST",
						dataType: 'JSON',
						data: {renderId:renderId,serviceId:serviceId,fieldArr:fieldArr,user:user},
						success: function(result){
							
						}
				});
			}else{
				break;
			}
		}
	}
	
	function fillTable(id,data)
	{
		
		var table = document.getElementById("billSummaryTbl");
		var rowNum = table.rows.length;
		var row = table.insertRow(table.rows.length);
		row.id = 'row'+rowNum;
		var type=row.insertCell(0);
		var productId = row.insertCell(1);
		var productName = row.insertCell(2);
		var image = row.insertCell(3);
		var quantity = row.insertCell(4);
		var srp = row.insertCell(5);
		var discount = row.insertCell(6);
		var total = row.insertCell(7);
		var remove = row.insertCell(8);
		
		type.innerHTML='R';
		if(data.image!='0'){
			image.innerHTML ='<img src=\'getimage.php?id='+id+'\' height=\'25\' />';
		}
		
		productId.innerHTML = id;
		productName.innerHTML = data.title;
		var quantityVal = document.getElementById(id).value;
		var srpVal = data.srp;
		quantity.innerHTML = quantityVal;
		srp.innerHTML = srpVal;
		var totalAmt;
		if(data.discount == null){
			discount.innerHTML = '---';
			totalAmt = quantityVal * srpVal;
		}else{
			discount.innerHTML = data.discount;
			totalAmt = (quantityVal * srpVal)-data.discount;
		}
		
		total.innerHTML = totalAmt;
		totalBillAmount += totalAmt;
		var div = document.getElementById('totalDue');
		div.value=totalBillAmount;
		remove.innerHTML = '<a class=\'link\' style=\'cursor:pointer;\' onclick=\'deleteRow(this,'+totalAmt+');\'><i class=\'material-icons icon-red\'>clear</i></a>';
	}
	
	function deleteRow(rowNum,amount){
		 
	   var row = rowNum.parentNode.parentNode;
	   var num = rowNum.parentNode.parentNode.rowIndex;
	  
	   row.parentNode.removeChild(row);

	   totalBillAmount -= amount;
	   var div = document.getElementById('totalDue');
	   div.value=totalBillAmount;
		
	   var table = document.getElementById("billSummaryTbl");
	   var rowLength = table.rows.length;
	   
	   if(rowLength>1){
		   for(var i=num;i<=rowLength;i++){
			   var type=table.rows[num].cells[0].innerHTML;
			   if(type==''){
				    table.deleteRow(num);
			   }else{
				   
			   }
		  
			}
	   }

		
		
	}
	
	function resetBill(){
	
	   var table = document.getElementById("billSummaryTbl");
	   var rowLength = table.rows.length;
	   
	   if(rowLength>1){
		   for(var i=1;i<rowLength;i++){
		   table.deleteRow(1);
	   }
	   }
	   
	   document.getElementById("change").value = null;
	   document.getElementById("amountPaid").value = null;
	   document.getElementById("customerAddr").value = null;
	   document.getElementById("customerContact").value = null;
	   document.getElementById("customerName").value = null;
	   document.getElementById("discount").value = null;
	   document.getElementById("transactionDate").value = null;
	   var div = document.getElementById('totalDue');
	   div.value='0.00';
	   
	   totalBillAmount = 0;
	}
	
	function minusQuantity(id){
		
		var input = document.getElementById(id);
		var count = parseInt(input.value) - 1;
		
		count = count < 1 ? 0 : count;
		document.getElementById(id).value=count;

	}
	
	function addQuantity(id,max){

		var input = document.getElementById(id);
		var count = parseInt(input.value) + 1;
		
		count = count >= max ? max : count;
		document.getElementById(id).value=count;

	}
	
	function calculateChange(){
		var amountPaid = document.getElementById("amountPaid").value;
		var totalDue = document.getElementById("totalDue").value;
		var change = document.getElementById("change");
		change.value = amountPaid - totalDue;
	}
	
	function addBillItem(){
		$('#addItemModal').modal('show');
	}
	
	function openSale(){
		$('#addItemModal').modal('hide');
		var selected = document.getElementById("saleType").value;
		if(selected=='Retail')
		{
			$('#productModal').modal('show');
		}else{
			$('#serviceModal').modal('show');
		}
	}
	
	function showForm()
	{
		var serviceId = document.getElementById("serviceList").value;
		var user = "<?php echo $_SESSION['username']; ?>";
		 $.post('getServiceFields.php',{serviceId:serviceId,user:user}, function(data){
             $("#service_results").html(data);
           });
		 
	}
	
	function addService(){

		var user = "<?php echo $_SESSION['username']; ?>";
		var serviceCost = document.getElementById("serviceCost").value;
		var serviceComm = document.getElementById("serviceComm").value;
		var serviceId = document.getElementById("serviceId").value;
		var serviceName = document.getElementById("serviceName").value;
		var data = {serviceCost:serviceCost, serviceComm:serviceComm, serviceId:serviceId,serviceName:serviceName};
		fillTableService(serviceId,data);
		var i=100;
		while(i!=99){
			var value= document.getElementById(i).value;
			var field = document.getElementById(i).name;
		
			if(value==null)
			{
				i=99;
			}else{
				
				var pair = {field:field,value:value};
				fillTableServiceDetails(serviceId,pair);
			}
			i++;
		}
		
		$("#service_results").html("");
	}
	
	function fillTableService(id,data)
	{
		
		var table = document.getElementById("billSummaryTbl");
		var rowNum = table.rows.length;
		var row = table.insertRow(table.rows.length);
		row.id = 'row'+rowNum;
		var type = row.insertCell(0);
		var productId = row.insertCell(1);
		var productName = row.insertCell(2);
		var details = row.insertCell(3);
		var quantity = row.insertCell(4);
		var srp = row.insertCell(5);
		var comm = row.insertCell(6);
		var total = row.insertCell(7);
		var remove = row.insertCell(8);
		
		details.innerHTML ='';
		type.innerHTML='S';
		
		productId.innerHTML = id;

        productName.innerHTML = data.serviceName;

		   
		var quantityVal = 1;
		var srpVal = data.serviceCost;
		quantity.innerHTML = quantityVal;
		srp.innerHTML = srpVal;
		var totalAmt;
		if(data.serviceComm == null){
			comm.innerHTML = '---';
			totalAmt = quantityVal * srpVal;
		}else{
			comm.innerHTML = data.serviceComm;
			totalAmt = quantityVal * srpVal;
		}
		
		total.innerHTML = totalAmt;
		totalBillAmount += totalAmt;
		var div = document.getElementById('totalDue');
		div.value=totalBillAmount;
		remove.innerHTML = '<a class=\'link\' style=\'cursor:pointer;\' onclick=\'deleteRow(this,'+totalAmt+');\'><i class=\'material-icons icon-red\'>clear</i></a>';
	
	}
	
	function fillTableServiceDetails(id,data)
	{	
		var table = document.getElementById("billSummaryTbl");
		var rowNum = table.rows.length;
		var row = table.insertRow(table.rows.length);
		row.id = 'row'+rowNum;
		var type = row.insertCell(0);
		var productId = row.insertCell(1);
		var productName = row.insertCell(2);
		var details = row.insertCell(3);
		var quantity = row.insertCell(4);
		var srp = row.insertCell(5);
		var comm = row.insertCell(6);
		var total = row.insertCell(7);
		var remove = row.insertCell(8);
		
		details.innerHTML ='';
		type.innerHTML='';
		
		productId.innerHTML = '';

        productName.innerHTML = data.field +':'+data.value;

		quantity.innerHTML ='';
		srp.innerHTML = '';

		comm.innerHTML = ''
		
		total.innerHTML = '';

		remove.innerHTML = '';
	
	}
	
	function clearTransaction(){
		resetBill();
		removeAll();
	}
	
	
</script>
<style></style>
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
            
          <!-- / .main-navbar -->
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Transaction</span>
                <h3 class="page-title">New Transaction</h3>
              </div>
            </div>
            <!-- End Page Header -->
			<form action="" method="post" id="searchForm">
			  <div class="row">
              <div class="col-lg-12 mb-4">
                <!-- Sliders & Progress Bars -->
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Bill Summary</h6>
                  </div>
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <!-- Progress Bars -->
					  <div class="form-row">
						 <div class="form-group col-md-6">
							<label for="customerName">Customer Name</label>
							<input type="text" class="form-control" id="customerName" name="customerName" placeholder="Customer Name">
						</div>
						<div class="form-group col-md-6">
							<label for="transactionDate">Date</label>
							  <input type="text" class="input-sm form-control" name="transactionDate" id="transactionDate" placeholder="Date (leave blank if date is today)">
						</div>
					  </div>
					  <div class="form-row">
						  <div class="form-group col-md-6">
							<label for="customerAddr">Customer Address</label>
							<input type="text" class="form-control" id="customerAddr" name="customerAddr" placeholder="Customer Address">
						  </div>
						  <div class="form-group col-md-6">
							<label for="customerContact">Contact No.</label>
							<input type="text" class="form-control" id="customerContact" name="customerContact" placeholder="Contact No.">
						  </div>
						</div>
					 
                      <div class="form-row">
					  <div class="form-group col-md-12">
                       <table class="table mb-0" id="billSummaryTbl">
					   <thead>
						<tr>
							<td>Type</td>
							<td>Product/Service ID</td>
							<td>Product/Service Name</td>
							<td></td>
							<td>Quantity</td>
							<td>SRP</td>
							<td>Discount/Commission</td>
							<td>Total</td>
							<td></td>
						</tr>
					   </thead>
                      <tbody>

                      </tbody>
                    </table>
					<br/>
						<div style="text-align:right;">
							<button type="button" class="btn btn-accent" onclick="addBillItem();" style="margin-left:10px;">+ Add Item</button>
						</div>
                      </div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6" id='total_amount'>
								<label for="totalDue">Total Amount Due</label>
								<input type="text" class="form-control" id="totalDue" name="totalDue" placeholder="₱" disabled="true">
							</div>
							<div class="form-group col-md-6">
								<label for="discount">Discount</label>
								<input type="text" class="form-control" id="discount" name="discount" placeholder="Discount">
							</div>
						</div>
					  <div class="form-row">
						<div class="form-group col-md-6">
								<label for="amountPaid">Amount Paid</label>
								<input type="text" class="form-control" id="amountPaid" name="amountPaid" placeholder="Amount Paid"></div>
					  </div>
					  <div class="form-row"><div class="form-group col-md-6">
					  <label for="change">Change</label>
						<div class="input-group mb-3">
                          <input type="text" class="form-control" placeholder="0.00" name="change" id="change">
                          <div class="input-group-append">
							<button class="btn btn-danger" type="button" onclick="calculateChange();">Calculate Change</button>
                          </div>
                        </div>
					  </div></div>
					   <div class="mb-2" style="text-align:right">
							
					  <input type="button" value="Save Transaction" name="save" class="btn btn-accent" onclick="saveTransaction();">
					   <input type="button" value="Reset" onclick="resetBill();" class="btn btn-white">
					  </div>
                    </li>
                  </ul>
				   </div>
                </div>
				</div>
				
				 </div>
        </main>
	  </div>
			<div class='modal fade' id='savedModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Transaction saved successfully.</p>
					</div>
						<div class='modal-footer'>
							
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
			</div>
			<div class='modal fade' id='changeModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Please calculate change to proceed.</p>
					</div>
						<div class='modal-footer'>
							
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
			</div>
			<div class='modal fade' id='paidModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Please input amount paid by customer.</p>
					</div>
						<div class='modal-footer'>
							
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
			</div>
			<div class='modal fade' id='itemsModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>There are no items in this transaction.</p>
					</div>
						<div class='modal-footer'>
							
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
			</div>
			<div class='modal fade' id='addQuantityModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Quantity of added product must be more than 0.</p>
					</div>
						<div class='modal-footer'>
							<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
						</div>
					</div>
				</div>
			</div>
			<div class='modal fade' id='addItemModal' role='dialog'>
				<div class='modal-dialog modal-lg'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <div class="form-group">
						  <label for="sel1">Type of Sale:</label>
						  <select class="form-control" id="saleType">
							<option>Retail</option>
							<option>Service</option>
						  </select>
						</div>
					  <div style="text-align:right;">
						<button type='button' class='btn btn-accent' data-dismiss='modal' onclick="openSale();">Select</button><button type='button' class='btn btn-default' data-dismiss='modal' style="margin-left:10px;">Cancel</button>
						</div>
					</div>
					</div>
				</div>
			</div>
			<div class='modal fade' id='productModal' role='dialog'>
				<div class='modal-dialog modal-lg'>
				  <div class='modal-content'>
					<div class='modal-body'>
							<div class="form-group">
								<label for="productName">Search Products</label>
									<input type="text" class="form-control" placeholder="Search for products" name="searchProduct" id="searchProduct">
							</div>
							<div class="form-group">
								<div style="text-align:right;">
											<button class="btn btn-primary" type="submit">Search</button>
											<button type='button' class='btn btn-default' onclick="removeAll();" data-dismiss='modal' style="margin-left:5px;">Cancel</button>
											</form>
								</div>
							</div>
							<div class="form-row"> <div class="card-body p-0 pb-3 text-center">
								<div id="search_results"></div>
								</div>		
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='modal fade' id='serviceModal' role='dialog'>
				<div class='modal-dialog modal-lg'>
				  <div class='modal-content'>
					<div class='modal-body'>
							<div class="form-group">
								<label for="serviceList">Select a Service</label>
								<form name="serviceForm" action="" method="POST">
								<?php
								$username = $_SESSION['username'];
								$sql = "SELECT s.serviceId, s.serviceName from services s where s.branchId=(select branchId from users where username='$username')";
								$result = mysqli_query($con,$sql);

								echo "<select id='serviceList' name='serviceList' class='form-control' required>";
								while($row = mysqli_fetch_array($result))
								{
									echo "<option value='" . $row['serviceId'] . "'>" . $row['serviceName'] . "</option>";
								}
								echo "</select>";
								?>
								
							</div>
							<div class="form-group">
								<div style="text-align:right;">
											<button class="btn btn-primary" type="button" onclick="showForm();">Select</button>
												<button class="btn btn-white" type="button" onclick="removeAll();" style="margin-left:5px;">Clear</button>
											<button type='button' class='btn btn-default' onclick="removeAll();" data-dismiss='modal' style="margin-left:5px;">Cancel</button>
											</form>
								</div>
							</div>
							<div id="service_results"></div>	
							</div>
						</div>
					</div>
				</div>
			</div>
	
    <?php include "scripts.inc"; ?>
	</div>

  </body>
</html>