	<div class="form-group">

		<div class="col-xs-6">
			<label for="name"><h4>Name</h4></label>
			<input type="text" class="form-control" name="name requiredField" id="name" placeholder="Name" value="<?php echo $employeeData->value('emp_name') ?>" >
		</div>
	</div>
	<div class="form-group">

		<div class="col-xs-6">
			<label for="p_email"><h4>Personal Email</h4></label>
			<input type="email" class="form-control requiredField" name="p_email" id="p_email" placeholder="Personal Email" value="<?php echo $employeeData->value('professional_email') ?>">
		</div>
	</div>

	<div class="form-group">

		<div class="col-xs-6">
			<label for="cnic"><h4>Cnic</h4></label>
			<input type="text" class="form-control requiredField" name="cnic" id="cnic" placeholder="Cnic" value="<?php echo $employeeData->value('emp_cnic') ?>" >
		</div>
	</div>

	<div class="form-group">
		<div class="col-xs-6">
			<label for="phone"><h4>Phone</h4></label>
			<input type="number" class="form-control requiredField" name="phone" id="phone" placeholder="enter mobile number" value="<?php echo $employeeData->value('emp_contact_no') ?>" >
		</div>
	</div>
	<div class="form-group">

		<div class="col-xs-6">
			<label for="email"><h4>Dob</h4></label>
			<input type="date" class="form-control" name="dob" id="dob" placeholder="DOB" value="<?php echo $employeeData->value('emp_date_of_birth') ?>">
		</div>
	</div>
 
	<div class="form-group">
		<div class="col-xs-12">
			<br>
			<button type="submit" class="btn btn-lg btn-success" id="btn_check" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Loading...">Save</button>
			<button class="btn btn-lg" id="reset_btn" type="button"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
		</div>
	</div>
