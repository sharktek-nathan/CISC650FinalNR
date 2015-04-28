<script type='text/javascript' src='/js/jquery.validate.js'></script>
<script type='text/javascript' src='/js/additional-methods.js'></script>
<script type='text/javascript' src='/js/order_did.js'></script>
<script>
$(document).ready(function() {

});

</script>

<h1>Order Fax Number</h1>
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
	<form id="did-form" action="<?php echo site_url('account/order_did'); ?>" style="margin: 0 auto;" method="post" accept-charset="utf-8">
		<input type="hidden" id="did" name="did" value="" />
        
        <div id="step_one">
            
            <div id="step_one_right">
            	<h3>Choose a new number</h3>
                <table class="form_table">
                    <tr><td>Select a State</td></tr>
                    <tr><td><select class="form_select ignore" id="state" name="state"></select></td></tr>
                    <tr class="area_hide"><td>Select an Area Code</td></tr>
                    <tr class="area_hide"><td><select class="form_select ignore" id="area_code"></select></td></tr>
                    <tr id="did_search_loader"><td><img id="did_search_loader" src="/img/ajax-loader-80x80.gif"></td></tr>
                    <tr class="city_hide"><td>Select a City</td></tr>
                    <tr class="city_hide"><td><select class="form_select ignore" id="city"></select></td></tr>
                    <tr class="available_numbers_hide"><td>Select a Number</td></tr>
                    <tr class="available_numbers_hide"><td><select class="form_select ignore" id="available_numbers"></select></td></tr>
                </table>
            </div>
            
            <div class="clear"></div>
            <div id="submit_check_one" class="right"></div>
    		<div class="step_nav" style="margin: 35px 50px 0px -50px;">
        		<button type="submit" class="next-button right" id="select_did">Select Fax Number</button>
                <img style="z-index: 999; float: right; margin-top: 50px; clear:both;" id="submit-load" src="/img/ajax-loader-80x80.gif">
        	</div>
        </div>
   </form>
</div>