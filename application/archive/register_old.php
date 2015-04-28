<script type='text/javascript' src='/js/jquery.validate.js'></script>
<script type='text/javascript' src='/js/additional-methods.js'></script>
<script type='text/javascript' src='/js/register.js'></script>
<link rel="stylesheet" type="text/css" href="/css/setup.css">
<script>
$(document).ready(function() {
		//show next button if DID is in session

	var last_step = <?php if(isset($form_entry['last_step'])){ echo $form_entry['last_step']; } else { echo 1; }; ?>;
	switch (last_step) {
		case 1:
			show_step1();
			break;
		case 2:
			show_step2();
			break;
		case 3:
			window.location.replace("/setup/payment");
			break;
	} 
});

function VeriSign() { 
window.open('https://trustsealinfo.websecurity.norton.com/splash?form_file=fdf/splash.fdf&dn=WWW.REDFAX.COM&lang=en','VeriSign Secured page', 'width=550, height=400') 
}

function show_step1(type) {
	
	if (typeof(type) === "undefined") { type = 0; }
	
	//hides
	$('#step_two').hide();

	//shows
	$('#step_one').show();
	
	if(type == 0) {
		//shows
		$('#step_one_right').show();
		$('#finish_step_one').show();
		
		//hides
		$('#step_one_port').hide();
		$('#finish_step_one_port').hide();
		
	} else {
		//shows
		$('#step_one_port').show();
		$('#finish_step_one_port').show();
		
		//hides
		$('#step_one_right').hide();
		$('#finish_step_one').hide();
	}
	
	//show next button if DID is in session
	<?php if(isset($form_entry['did']) && !empty($form_entry['did'])) { 
		echo '$("#step_one_complete").show();';
	} else { 
		echo '$("#step_one_complete").hide();';
	} ?>


}

function show_step2() {
	//hides
	$('#step_one').hide();
		
	//shows
	$('#step_two').show();
}

</script>

<h1>RedFax Signup</h1>
<div id="wrapper">
	<?php if(validation_errors() != NULL) { ?>
    <div id="alert-box" class="alert alert-warning alert-dismissable">
        <button type="button" id="hide_alert" class="close">&times;</button>
        <strong><?php echo validation_errors();?></strong>
    </div>
    <?php } ?>
	
		<div id="step_one">
            <img id="setup-step-one" style="margin: 0px 0px 0px -120px" src="/img/setup-step-one.png">
            <div id="step_one_left" class="step_left">
                <h3>Do you have a fax number?</h3>
                <input type="radio" name="number_status_radio" value="0" checked>I need a fax number<br>
                <input type="radio" name="number_status_radio" value="1">I already have a number<br>
            </div>
            
            <div id="step_one_right" class="step_right">
                <form id="did-form" method="post" accept-charset="utf-8">
                	<input type="hidden" id="select_did" name="select_did" value="<?php if(isset($form_entry['did'])){ echo $form_entry['did']; }; ?>" />
                	<h3>Choose a new number</h3>
                    <table class="form_table">
                        <tr><td>Select a State</td></tr>
                        <tr><td><select class="form_select" id="state" name="state"></select></td></tr>
                        <tr class="area_hide"><td>Select an Area Code</td></tr>
                        <tr class="area_hide"><td><select class="form_select" id="area_code"></select></td></tr>
                        <tr id="did_search_loader"><td><img id="did_search_loader" src="/img/ajax-loader-80x80.gif"></td></tr>
                        <tr class="city_hide"><td>Select a City</td></tr>
                        <tr class="city_hide"><td><select class="form_select" id="city"></select></td></tr>
                        <tr class="available_numbers_hide"><td>Select a Number</td></tr>
                        <tr class="available_numbers_hide"><td><select class="form_select" id="available_numbers" name="available_numbers"></select></td></tr>
                        <tr><td><div id="step_one_complete" class="right">Your Selection: <label id="step_one_complete_label">
                        <?php 
                            if(isset($form_entry['did'])){ 
                                if(substr($form_entry['did'], 0, 4) == "gen_") {
                                    echo "Port Your Own Number";
                                } else {
                                    echo substr($form_entry['did'], 5, 3) . '.' . substr($form_entry['did'], 8, 3) . '.' . substr($form_entry['did'], 11, 4); 
                                }
                            }
                        ?>
                        </label></div></td></tr>
                    </table>
                    <div class="clear"></div>
                    <div id="submit_did_check" class="right"></div>
                    <div class="step_nav" style="margin: 35px 50px 0px -50px;">
                        <button class="next-button right" type="submit">Continue to Step 2</button>
        			</div>
                </form>
            </div>
            <div id="step_one_port" class="step_right">
            	<form id="port-form" method="post" accept-charset="utf-8">
                    <h3>Port your current number</h3>
                    <table  style="font-size: 13px;">
                        <tr><td>Porting numbers typically takes 5-15 days to complete, but don't worry--we will assign you a temporary number until your port is complete.</td></tr>
                        <tr><td>Please fill out the following information, we will contact you shortly:</td></tr>
                        <tr><td>Porting Number<br>
                        	<input id="current_number" class="form_input" type="text" name="current_number" value="<?php if(isset($form_entry['current_number'])){ echo $form_entry['current_number']; }; ?>"></td></tr>
                        <tr><td>Number Provider (if known)<br>
                        	<input id="current_provider" class="form_input ignore" type="text" name="current_provider" value="<?php if(isset($form_entry['current_provider'])){ echo $form_entry['current_provider']; }; ?>"></td></tr>
                        <tr><td>Contact Phone Number<br>
                        	<input id="contact_phone" class="form_input" type="text" name="contact_phone" value="<?php if(isset($form_entry['contact_phone'])){ echo $form_entry['contact_phone']; }; ?>"></td></tr>
                    </table>
                    <div class="clear"></div>
                    <div id="submit_port_check" class="right"></div>
                    <div class="step_nav" style="margin: 35px 50px 0px -50px;">
                        <button class="next-button right" type="submit">Continue to Step 2</button>
                    </div>
                </form>
           </div>
        </div>
        <form id="register-form" style="margin: 0 auto;" method="post" accept-charset="utf-8">
			<input type="hidden" id="did" name="did" value="<?php if(isset($form_entry['did'])){ echo $form_entry['did']; }; ?>" />
			<div id="step_two" class="clear">
				<img id="setup-step-two" style="margin: 0px 0px 0px -120px" class="clear" src="/img/setup-step-two.png">
              	<h3>Step Two: Account Information</h3>
              	<div id="step_two_left" class="step_left">
					<table class="form_table">
                      <tr><td>Email:</td></tr>
                      <tr><td><input id="email" class="form_input" type="text" name="email" value="<?php if(isset($form_entry['email'])){ echo $form_entry['email']; }; ?>"></td></tr>
                      <tr><td>Password:</td></tr>
                      <tr><td><input id="new_password" class="form_input" type="password"  name="new_password" value="<?php if(isset($form_entry['new_password'])){ echo $form_entry['new_password']; }; ?>"/></td></tr>
                      <tr><td>Confirm Password:</td></tr>
                      <tr><td><input class="form_input" type="password" name="confirm_password" value="<?php if(isset($form_entry['new_password'])){ echo $form_entry['new_password']; }; ?>"/></td></tr>
                      <tr><td>Promo Code (if applicable):</td></tr>
                      <tr><td><input id="promo" class="form_input ignore" type="text" name="promo" value="<?php if(isset($form_entry['promo'])){ echo $form_entry['promo']; }; ?>"/></td></tr>
                  </table>
              </div>
              <div id="step_two_right" class="step_right">
                  <table class="form_table">
                      <tr><td>First Name:</td></tr>
                      <tr><td><input id="first_name" class="form_input" type="text" name="first_name" value="<?php if(isset($form_entry['first_name'])){ echo $form_entry['first_name']; }; ?>"/></td></tr>
                      <tr><td>Last Name:</td></tr>
                      <tr><td><input  id="last_name" class="form_input" type="text" name="last_name" value="<?php if(isset($form_entry['last_name'])){ echo $form_entry['last_name']; }; ?>"/></td></tr>
                      <tr><td>Company:</td></tr>
                      <tr><td><input id="company" class="form_input" type="text" name="company" value="<?php if(isset($form_entry['company'])){ echo $form_entry['company']; }; ?>"/></td></tr>
                      <tr><td>Choose Your Billing Cycle</td></tr>
                      <tr><td><select class="form_select ignore" name="pricing_id" id="pricing_id">
                      			<option value="11">600 Pages 1 Month at $4.99</option>
                      		  		<option value="19">600 Pages 1 Year at $39.99</option>
									<option value="24">1200 Pages 1 Month at $9.95</option>
				  					<option value="23">1200 Pages 1 Year at $79.95</option>
                              </select>
                     </td></tr>
                  </table>
              </div>
            <div class="clear"></div>
            <div id="submit_check_two" class="right"></div>
            <div class="step_nav" style="margin: 35px 50px 0px -50px;">
                <button class="next-button left" id="back_step_one" type="button">Go Back to Step 1</button>
                <button class="next-button right" id="finish_step_two" type="submit">Continue to Step 3</button>
			</div>
		</div>
    </form>
</div>
<div class="badge-wrapper">
    <a href="javascript:VeriSign();"><img src="/img/verisign-small.png" alt="VeriSign by Norton" style="padding: 10px; float:right;"></a>
    <img src="/img/bbb.png" alt="Better Business Bureau" style="padding: 10px; float:right;">
    <img src="/img/money-back-small.png" alt="45 Monday Back Guarentee Badge" style="padding: 10px; float:right;">
</div>

<div id="processing" style="display:none;">
	<table>
    	<tr><td>Please wait while your while account is created.</td></tr>
    </table>
    <div style="margin-left: 33%;">
    <img  src="/img/ajax-loader-80x80.gif">
    </div>
</div>
