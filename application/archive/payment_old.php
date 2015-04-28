<script type='text/javascript' src='/js/jquery.validate.js'></script>
<script type='text/javascript' src='/js/additional-methods.js'></script>
<script type='text/javascript' src='/js/payment.js'></script>

<h1>RedFax Signup</h1>
<div id="wrapper">
	<?php if(validation_errors() != NULL) { ?>
    <div id="alert-box" class="alert alert-warning alert-dismissable">
        <button type="button" id="hide_alert" class="close">&times;</button>
        <strong><?php echo validation_errors();?></strong>
    </div>
    <?php } ?>
    <?php if(isset($error) && !empty($error)) { ?> 
    <div id="alert-box" class="alert alert-warning alert-dismissable">
        <button type="button" id="hide_alert" class="close">&times;</button>
        <strong></strong>
        <?php echo $error ?>
    </div>
    <?php } ?>
    <form id="payment-form" action="<?php echo site_url('setup/payment'); ?>" style="margin: 0 auto;" method="post" accept-charset="utf-8">
        <div id="step_three" class="clear">
            <img id="setup-step-three" class="clear" style="margin: 0px 0px 0px -120px" src="/img/setup-step-three.png">
            <h3>Step Three: Payment</h3>
            <div id="step_three_left" class="step_left">
                 <table class="form_table">
                    <tr><td>First Name on Card:</td></tr>
                    <tr><td><input id="cc_first_name" class="form_input" type="text" name="cc_first_name" value="<?php if(isset($form_entry['cc_first_name'])){ echo $form_entry['cc_first_name']; }; ?>"></td></tr>
                    <tr><td>Last Name on Card:</td></tr>
                    <tr><td><input id="cc_last_name" class="form_input" type="text" name="cc_last_name" value="<?php if(isset($form_entry['cc_last_name'])){ echo $form_entry['cc_last_name']; }; ?>"></td></tr>
                    <tr><td>Credit Card Number:</td></tr>
                    <tr><td><input class="form_input" id="number" type="text" name="number"></td></tr>
                    <tr><td><img src="/img/cc-list.png"></td></tr>
                    <tr><td>Expiration Date:</td></tr>
                    <tr><td><select class="form_exp_month" id="cc_exp_month" name="cc_exp_month"></select>
                    <select class="form_exp_year" id="cc_exp_year" name="cc_exp_year"></select></td></tr>
                    <tr><td>CVV:</td></tr>
                    <tr><td><input class="form_input" type="text" name="cc_cvv"></td></tr>
                </table>
            </div>
            <div id="step_three_right" class="step_right">
                <table class="form_table">
                    <tr><td>Billing Address:</td></tr>
                    <tr><td><input id="cc_address1" class="form_input" type="text" name="cc_address1" value="<?php if(isset($form_entry['cc_address1'])){ echo $form_entry['cc_address1']; }; ?>"></td></tr>
                    <tr><td>Billing Address 2 (if applicable):</td></tr>
                    <tr><td><input id="cc_address2" class="form_input" type="text" name="cc_address2" value="<?php if(isset($form_entry['cc_address2'])){ echo $form_entry['cc_address2']; }; ?>"></td></tr>
                    <tr><td>City:</td></tr>
                    <tr><td><input id="cc_city" class="form_input" type="text" name="cc_city" value="<?php if(isset($form_entry['cc_city'])){ echo $form_entry['cc_city']; }; ?>"></td></tr>
                    <tr><td>State:</td></tr>
                    <tr><td><select class="form_select" id="cc_state" name="cc_state"></select></td></tr>
                    <tr><td>Zip:</td></tr>
                    <tr><td><input id="cc_zip" class="form_input" type="text" name="cc_zip" value="<?php if(isset($form_entry['cc_zip'])){ echo $form_entry['cc_zip']; }; ?>"></td></tr>
                    <tr><td>Country:</td></tr>
                    <tr><td><select class="form_select" id="cc_country" name="cc_country"></select></td></tr>
                    <tr><td style="font-size: 11px;">By clicking "Process Payment" you agree to the <a href="/setup/agreement" target="_blank">Customer Agreement</a></td></tr>
                </table>
            </div>
            <div class="clear"></div>
            <div id="submit_check_three" class="right"></div>
    		<div class="step_nav" style="margin: 35px 50px 0px -50px;">
        		<button class="next-button left" id="back_step_two" type="button">Go Back to Step 2</button>
                <button type="submit" class="next-button right" id="finish_activate">Process Payment</button>
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
    	<tr><td>Please wait while your while your transaction is processing.</td></tr>
    </table>
    <div style="margin-left: 33%;">
    <img  src="/img/ajax-loader-80x80.gif">
    </div>
</div>
