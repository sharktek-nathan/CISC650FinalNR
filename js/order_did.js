$(document).ready(function() {
	
        // Initializes popover functionality for non-mobile devices
//        if (  !window.matchMedia('(max-width: 768px)').matches  )
//        {
            $('#available_numbers').popover({ trigger: "manual", html: true, placement: 'right' });
//        }
    
	//hide alerts
	$("#hide_alert").click(function(){
            $("#alert-box").hide();
	});			
	
	// Pre-populate the state dropdown
	for(var i = 0; i < usStates.length; i++){
            $('#state').append('<option value="' + usStates[i]['abbreviation'] +'">' + usStates[i]['name'] + '</option>');
	}
	
	// On selection of a state:
        $('#state').on('change', function() { 

            // Hide other steps
            $('.city_hide').hide();  
            $('.available_numbers_hide').hide();
            $('#available_numbers').popover('hide');
            $('#did_search_loader').hide();
            $('#step_one_complete').hide();
            $('#confirm_button').hide();

            // Prevent user from not selecting a value   
            if($(this).val() === "") {
                $('.area_hide').hide();
                return false;
            }

            // Set the first option in the area_code dropdown, populate it with lookup results, then show it
            $('#area_code').html('<option value="">Select an area code</option>');       
            var state_code = $(this).val();
            for(var i = 0; i < usAreaCodes.length; i++){
                if(usAreaCodes[i]['state'] == state_code) {
                    $('#area_code').append('<option value="' + usAreaCodes[i]['area_code'] +'">' + usAreaCodes[i]['area_code'] + '</option>');
                }
            } 
            $('.area_hide').show();                                        
        });

        // On selection of an area code:
        $('#area_code').on('change', function() {        

            // Hide other steps
            $('.city_hide').hide();
            $('.available_numbers_hide').hide();
            $('#available_numbers').popover('hide');
            $('#step_one_complete').hide();
            $('#confirm_button').hide();

            // Prevent user from not selecting a value   
            if($(this).val() === "") {            
                $('.city_hide').hide();
                $('#city_search_loader').hide();
                $('#did_search_loader').hide();
                return false;
            }

            // Show the loading gif
            $('#city_search_loader').fadeIn("fast");                  

            // Set the first option in the city dropdown, populate it with lookup results, then show it and hide the loading gif
            $('#city').html('<option value="">Select a city</option>');  
            var area_code = $(this).val();
            $.getJSON( "/app/setup/city_search/", { area: area_code } )
                .done(function( data ) {
                    did_search = data;   

                    // Populate the city dropdown with the results found for the selected area code and show it, then hide the loading gif
                    $.each(data, function(key, val) {
                        $('#city').append('<option value="' + key +'">' + val + '</option>');
                    });
                    $('.city_hide').show();
                    $('#city_search_loader').hide(); 
            });
        });        

        // On selection of a city:
        $('#city').on('change', function() {  

            // Hide other steps       
            $('.available_numbers_hide').hide();
            $('#available_numbers').popover('hide');
            $('#step_one_complete').hide();
            $('#confirm_button').hide();
                        

            // Prevent user from not selecting a value   
            if($(this).val() === "") {
                $('.available_numbers_hide').hide();
                return false;
            }
            
            var selected_city = $(this).val();
            var selected_state = $('#state').val();

            // Show the loading gif
        $('#did_search_loader').fadeIn("fast"); 
        
        // Perform an available numbers lookup        
        $.getJSON( "/app/setup/did_search/", { city: selected_city, state: selected_state } )
            .done(function( data ) {
                did_search = data;
        
                // If no numbers were found, populate available_numbers with the 'Available in 24 Hours*' message
                if(did_search.length === 0) {
                    $('#available_numbers').html('<option value="unv_00000000000">Available in 24 Hours*</option>');                    
                    $('.available_numbers_hide').show();
                    
                    // Prevents the popover from being incorrectly positioned
                    setTimeout(function(){
                        $('#available_numbers').popover('show');
                    }, 500);                   
                    
                } 
                // Populate the available_numbers dropdown with the results found for the selected city and show it, then hide the loading gif
                else {
                    $('#available_numbers').html('<option value="">Select a number</option>');
                    $.each(data, function(key, val) {
                        $('#available_numbers').append('<option value="' + key +'">' + val + '</option>');
                    });
                    $('.available_numbers_hide').show(); 
                }
                $('#did_search_loader').hide(); 
            });
        });

        // On selection of a number:
        $('#available_numbers').on('change', function() { 

            // Check if user has 'Select a fax number' selected in the available_numbers dropdown
            var newDID = $('#available_numbers').val();

            // If not, proceed normally
            if(newDID.length === 15) {

                // Set value of the hidden 'did' field in the form
                $('#did').val(newDID);

                // Set value of the 'Your Selection' element, and show it along with the 'Continue Registration' button
                var formatted_did = newDID.substr(5, 3) + '.' + newDID.substr(8, 3) + '.' + newDID.substr(11, 4);                                               
                $('#step_one_complete_label').text(formatted_did);
                $('#step_one_complete').show();
                $('#confirm_button').show();
            }  

            // If yes, hide the 'Your Selection' element and 'Continue Registration' button 
            else{            
                $('#step_one_complete').hide();
                $('#confirm_button').hide();
            }
        });                 
	
	$("#did-form").validate({		
            submitHandler: function(form) {
                    form.submit();
            },
            rules: {
              did: {required: true,minlength: 15,maxlength: 15,}	
            }
	});
	
});

var usStates = [
	{ name: 'Select A State', abbreviation: '-'},
    { name: 'Alabama', abbreviation: 'AL'},
    { name: 'Alaska', abbreviation: 'AK'},
    { name: 'Arizona', abbreviation: 'AZ'},
    { name: 'Arkansas', abbreviation: 'AR'},
    { name: 'California', abbreviation: 'CA'},
    { name: 'Colorado', abbreviation: 'CO'},
    { name: 'Connecticut', abbreviation: 'CT'},
    { name: 'Delaware', abbreviation: 'DE'},
    { name: 'Florida', abbreviation: 'FL'},
    { name: 'Georgia', abbreviation: 'GA'},
    { name: 'Hawaii', abbreviation: 'HI'},
    { name: 'Idaho', abbreviation: 'ID'},
    { name: 'Illinois', abbreviation: 'IL'},
    { name: 'Indiana', abbreviation: 'IN'},
    { name: 'Iowa', abbreviation: 'IA'},
    { name: 'Kansas', abbreviation: 'KS'},
    { name: 'Kentucky', abbreviation: 'KY'},
    { name: 'Louisiana', abbreviation: 'LA'},
    { name: 'Maine', abbreviation: 'ME'},
    { name: 'Maryland', abbreviation: 'MD'},
    { name: 'Massachusetts', abbreviation: 'MA'},
    { name: 'Michigan', abbreviation: 'MI'},
    { name: 'Minnesota', abbreviation: 'MN'},
    { name: 'Mississippi', abbreviation: 'MS'},
    { name: 'Missouri', abbreviation: 'MO'},
    { name: 'Montana', abbreviation: 'MT'},
    { name: 'Nebraska', abbreviation: 'NE'},
    { name: 'Nevada', abbreviation: 'NV'},
    { name: 'New Hampshire', abbreviation: 'NH'},
    { name: 'New Jersey', abbreviation: 'NJ'},
    { name: 'New Mexico', abbreviation: 'NM'},
    { name: 'New York', abbreviation: 'NY'},
    { name: 'North Carolina', abbreviation: 'NC'},
    { name: 'North Dakota', abbreviation: 'ND'},
    { name: 'Ohio', abbreviation: 'OH'},
    { name: 'Oklahoma', abbreviation: 'OK'},
    { name: 'Oregon', abbreviation: 'OR'},
    { name: 'Pennsylvania', abbreviation: 'PA'},
    { name: 'Rhode Island', abbreviation: 'RI'},
    { name: 'South Carolina', abbreviation: 'SC'},
    { name: 'South Dakota', abbreviation: 'SD'},
    { name: 'Tennessee', abbreviation: 'TN'},
    { name: 'Texas', abbreviation: 'TX'},
    { name: 'Utah', abbreviation: 'UT'},
    { name: 'Vermont', abbreviation: 'VT'},
    { name: 'Virginia', abbreviation: 'VA'},
    { name: 'Washington', abbreviation: 'WA'},
    { name: 'West Virginia', abbreviation: 'WV'},
    { name: 'Wisconsin', abbreviation: 'WI'},
    { name: 'Wyoming', abbreviation: 'WY' }
];

var usAreaCodes = [
	{ area_code: '907', state: 'AK'},
	{ area_code: '205', state: 'AL'},
	{ area_code: '251', state: 'AL'},
	{ area_code: '256', state: 'AL'},
	{ area_code: '334', state: 'AL'},
	{ area_code: '938', state: 'AL'},
	{ area_code: '479', state: 'AR'},
	{ area_code: '501', state: 'AR'},
	{ area_code: '870', state: 'AR'},
	{ area_code: '684', state: 'AS'},
	{ area_code: '480', state: 'AZ'},
	{ area_code: '520', state: 'AZ'},
	{ area_code: '602', state: 'AZ'},
	{ area_code: '623', state: 'AZ'},
	{ area_code: '928', state: 'AZ'},
	{ area_code: '209', state: 'CA'},
	{ area_code: '213', state: 'CA'},
	{ area_code: '310', state: 'CA'},
	{ area_code: '323', state: 'CA'},
	{ area_code: '408', state: 'CA'},
	{ area_code: '415', state: 'CA'},
	{ area_code: '424', state: 'CA'},
	{ area_code: '442', state: 'CA'},
	{ area_code: '510', state: 'CA'},
	{ area_code: '530', state: 'CA'},
	{ area_code: '559', state: 'CA'},
	{ area_code: '562', state: 'CA'},
	{ area_code: '619', state: 'CA'},
	{ area_code: '626', state: 'CA'},
	{ area_code: '650', state: 'CA'},
	{ area_code: '657', state: 'CA'},
	{ area_code: '661', state: 'CA'},
	{ area_code: '669', state: 'CA'},
	{ area_code: '707', state: 'CA'},
	{ area_code: '714', state: 'CA'},
	{ area_code: '747', state: 'CA'},
	{ area_code: '760', state: 'CA'},
	{ area_code: '805', state: 'CA'},
	{ area_code: '818', state: 'CA'},
	{ area_code: '831', state: 'CA'},
	{ area_code: '858', state: 'CA'},
	{ area_code: '909', state: 'CA'},
	{ area_code: '916', state: 'CA'},
	{ area_code: '925', state: 'CA'},
	{ area_code: '949', state: 'CA'},
	{ area_code: '951', state: 'CA'},
	{ area_code: '303', state: 'CO'},
	{ area_code: '719', state: 'CO'},
	{ area_code: '720', state: 'CO'},
	{ area_code: '970', state: 'CO'},
	{ area_code: '203', state: 'CT'},
	{ area_code: '475', state: 'CT'},
	{ area_code: '860', state: 'CT'},
	{ area_code: '202', state: 'DC'},
	{ area_code: '302', state: 'DE'},
	{ area_code: '239', state: 'FL'},
	{ area_code: '305', state: 'FL'},
	{ area_code: '321', state: 'FL'},
	{ area_code: '352', state: 'FL'},
	{ area_code: '386', state: 'FL'},
	{ area_code: '407', state: 'FL'},
	{ area_code: '561', state: 'FL'},
	{ area_code: '727', state: 'FL'},
	{ area_code: '754', state: 'FL'},
	{ area_code: '772', state: 'FL'},
	{ area_code: '786', state: 'FL'},
	{ area_code: '813', state: 'FL'},
	{ area_code: '850', state: 'FL'},
	{ area_code: '863', state: 'FL'},
	{ area_code: '904', state: 'FL'},
	{ area_code: '941', state: 'FL'},
	{ area_code: '954', state: 'FL'},
	{ area_code: '229', state: 'GA'},
	{ area_code: '404', state: 'GA'},
	{ area_code: '470', state: 'GA'},
	{ area_code: '478', state: 'GA'},
	{ area_code: '678', state: 'GA'},
	{ area_code: '706', state: 'GA'},
	{ area_code: '762', state: 'GA'},
	{ area_code: '770', state: 'GA'},
	{ area_code: '912', state: 'GA'},
	{ area_code: '671', state: 'GU'},
	{ area_code: '808', state: 'HI'},
	{ area_code: '319', state: 'IA'},
	{ area_code: '515', state: 'IA'},
	{ area_code: '563', state: 'IA'},
	{ area_code: '641', state: 'IA'},
	{ area_code: '712', state: 'IA'},
	{ area_code: '208', state: 'ID'},
	{ area_code: '217', state: 'IL'},
	{ area_code: '224', state: 'IL'},
	{ area_code: '309', state: 'IL'},
	{ area_code: '312', state: 'IL'},
	{ area_code: '331', state: 'IL'},
	{ area_code: '618', state: 'IL'},
	{ area_code: '630', state: 'IL'},
	{ area_code: '708', state: 'IL'},
	{ area_code: '773', state: 'IL'},
	{ area_code: '779', state: 'IL'},
	{ area_code: '815', state: 'IL'},
	{ area_code: '847', state: 'IL'},
	{ area_code: '872', state: 'IL'},
	{ area_code: '219', state: 'IN'},
	{ area_code: '260', state: 'IN'},
	{ area_code: '317', state: 'IN'},
	{ area_code: '574', state: 'IN'},
	{ area_code: '765', state: 'IN'},
	{ area_code: '812', state: 'IN'},
	{ area_code: '316', state: 'KS'},
	{ area_code: '620', state: 'KS'},
	{ area_code: '785', state: 'KS'},
	{ area_code: '913', state: 'KS'},
	{ area_code: '270', state: 'KY'},
	{ area_code: '502', state: 'KY'},
	{ area_code: '606', state: 'KY'},
	{ area_code: '859', state: 'KY'},
	{ area_code: '225', state: 'LA'},
	{ area_code: '318', state: 'LA'},
	{ area_code: '337', state: 'LA'},
	{ area_code: '504', state: 'LA'},
	{ area_code: '985', state: 'LA'},
	{ area_code: '339', state: 'MA'},
	{ area_code: '351', state: 'MA'},
	{ area_code: '413', state: 'MA'},
	{ area_code: '508', state: 'MA'},
	{ area_code: '617', state: 'MA'},
	{ area_code: '774', state: 'MA'},
	{ area_code: '781', state: 'MA'},
	{ area_code: '857', state: 'MA'},
	{ area_code: '978', state: 'MA'},
	{ area_code: '240', state: 'MD'},
	{ area_code: '301', state: 'MD'},
	{ area_code: '410', state: 'MD'},
	{ area_code: '443', state: 'MD'},
	{ area_code: '667', state: 'MD'},
	{ area_code: '207', state: 'ME'},
	{ area_code: '231', state: 'MI'},
	{ area_code: '248', state: 'MI'},
	{ area_code: '269', state: 'MI'},
	{ area_code: '313', state: 'MI'},
	{ area_code: '517', state: 'MI'},
	{ area_code: '586', state: 'MI'},
	{ area_code: '616', state: 'MI'},
	{ area_code: '734', state: 'MI'},
	{ area_code: '810', state: 'MI'},
	{ area_code: '906', state: 'MI'},
	{ area_code: '947', state: 'MI'},
	{ area_code: '989', state: 'MI'},
	{ area_code: '218', state: 'MN'},
	{ area_code: '320', state: 'MN'},
	{ area_code: '507', state: 'MN'},
	{ area_code: '612', state: 'MN'},
	{ area_code: '651', state: 'MN'},
	{ area_code: '763', state: 'MN'},
	{ area_code: '952', state: 'MN'},
	{ area_code: '314', state: 'MO'},
	{ area_code: '417', state: 'MO'},
	{ area_code: '573', state: 'MO'},
	{ area_code: '636', state: 'MO'},
	{ area_code: '660', state: 'MO'},
	{ area_code: '816', state: 'MO'},
	{ area_code: '228', state: 'MS'},
	{ area_code: '601', state: 'MS'},
	{ area_code: '662', state: 'MS'},
	{ area_code: '769', state: 'MS'},
	{ area_code: '406', state: 'MT'},
	{ area_code: '252', state: 'NC'},
	{ area_code: '336', state: 'NC'},
	{ area_code: '704', state: 'NC'},
	{ area_code: '828', state: 'NC'},
	{ area_code: '910', state: 'NC'},
	{ area_code: '919', state: 'NC'},
	{ area_code: '980', state: 'NC'},
	{ area_code: '984', state: 'NC'},
	{ area_code: '701', state: 'ND'},
	{ area_code: '308', state: 'NE'},
	{ area_code: '402', state: 'NE'},
	{ area_code: '531', state: 'NE'},
	{ area_code: '603', state: 'NH'},
	{ area_code: '201', state: 'NJ'},
	{ area_code: '551', state: 'NJ'},
	{ area_code: '609', state: 'NJ'},
	{ area_code: '732', state: 'NJ'},
	{ area_code: '848', state: 'NJ'},
	{ area_code: '856', state: 'NJ'},
	{ area_code: '862', state: 'NJ'},
	{ area_code: '908', state: 'NJ'},
	{ area_code: '973', state: 'NJ'},
	{ area_code: '505', state: 'NM'},
	{ area_code: '575', state: 'NM'},
	{ area_code: '670', state: 'NN'},
	{ area_code: '702', state: 'NV'},
	{ area_code: '725', state: 'NV'},
	{ area_code: '775', state: 'NV'},
	{ area_code: '212', state: 'NY'},
	{ area_code: '315', state: 'NY'},
	{ area_code: '347', state: 'NY'},
	{ area_code: '516', state: 'NY'},
	{ area_code: '518', state: 'NY'},
	{ area_code: '585', state: 'NY'},
	{ area_code: '607', state: 'NY'},
	{ area_code: '631', state: 'NY'},
	{ area_code: '646', state: 'NY'},
	{ area_code: '716', state: 'NY'},
	{ area_code: '718', state: 'NY'},
	{ area_code: '845', state: 'NY'},
	{ area_code: '914', state: 'NY'},
	{ area_code: '917', state: 'NY'},
	{ area_code: '929', state: 'NY'},
	{ area_code: '216', state: 'OH'},
	{ area_code: '234', state: 'OH'},
	{ area_code: '330', state: 'OH'},
	{ area_code: '419', state: 'OH'},
	{ area_code: '440', state: 'OH'},
	{ area_code: '513', state: 'OH'},
	{ area_code: '567', state: 'OH'},
	{ area_code: '614', state: 'OH'},
	{ area_code: '740', state: 'OH'},
	{ area_code: '937', state: 'OH'},
	{ area_code: '405', state: 'OK'},
	{ area_code: '539', state: 'OK'},
	{ area_code: '580', state: 'OK'},
	{ area_code: '918', state: 'OK'},
	{ area_code: '458', state: 'OR'},
	{ area_code: '503', state: 'OR'},
	{ area_code: '541', state: 'OR'},
	{ area_code: '971', state: 'OR'},
	{ area_code: '215', state: 'PA'},
	{ area_code: '267', state: 'PA'},
	{ area_code: '272', state: 'PA'},
	{ area_code: '412', state: 'PA'},
	{ area_code: '484', state: 'PA'},
	{ area_code: '570', state: 'PA'},
	{ area_code: '610', state: 'PA'},
	{ area_code: '717', state: 'PA'},
	{ area_code: '724', state: 'PA'},
	{ area_code: '814', state: 'PA'},
	{ area_code: '878', state: 'PA'},
	{ area_code: '787', state: 'PR'},
	{ area_code: '939', state: 'PR'},
	{ area_code: '401', state: 'RI'},
	{ area_code: '803', state: 'SC'},
	{ area_code: '843', state: 'SC'},
	{ area_code: '864', state: 'SC'},
	{ area_code: '605', state: 'SD'},
	{ area_code: '423', state: 'TN'},
	{ area_code: '615', state: 'TN'},
	{ area_code: '731', state: 'TN'},
	{ area_code: '865', state: 'TN'},
	{ area_code: '901', state: 'TN'},
	{ area_code: '931', state: 'TN'},
	{ area_code: '210', state: 'TX'},
	{ area_code: '214', state: 'TX'},
	{ area_code: '254', state: 'TX'},
	{ area_code: '281', state: 'TX'},
	{ area_code: '325', state: 'TX'},
	{ area_code: '346', state: 'TX'},
	{ area_code: '361', state: 'TX'},
	{ area_code: '409', state: 'TX'},
	{ area_code: '430', state: 'TX'},
	{ area_code: '432', state: 'TX'},
	{ area_code: '469', state: 'TX'},
	{ area_code: '512', state: 'TX'},
	{ area_code: '682', state: 'TX'},
	{ area_code: '713', state: 'TX'},
	{ area_code: '737', state: 'TX'},
	{ area_code: '806', state: 'TX'},
	{ area_code: '817', state: 'TX'},
	{ area_code: '830', state: 'TX'},
	{ area_code: '832', state: 'TX'},
	{ area_code: '903', state: 'TX'},
	{ area_code: '915', state: 'TX'},
	{ area_code: '936', state: 'TX'},
	{ area_code: '940', state: 'TX'},
	{ area_code: '956', state: 'TX'},
	{ area_code: '972', state: 'TX'},
	{ area_code: '979', state: 'TX'},
	{ area_code: '385', state: 'UT'},
	{ area_code: '435', state: 'UT'},
	{ area_code: '801', state: 'UT'},
	{ area_code: '276', state: 'VA'},
	{ area_code: '434', state: 'VA'},
	{ area_code: '540', state: 'VA'},
	{ area_code: '571', state: 'VA'},
	{ area_code: '703', state: 'VA'},
	{ area_code: '757', state: 'VA'},
	{ area_code: '804', state: 'VA'},
	{ area_code: '340', state: 'VI'},
	{ area_code: '802', state: 'VT'},
	{ area_code: '206', state: 'WA'},
	{ area_code: '253', state: 'WA'},
	{ area_code: '360', state: 'WA'},
	{ area_code: '425', state: 'WA'},
	{ area_code: '509', state: 'WA'},
	{ area_code: '262', state: 'WI'},
	{ area_code: '414', state: 'WI'},
	{ area_code: '534', state: 'WI'},
	{ area_code: '608', state: 'WI'},
	{ area_code: '715', state: 'WI'},
	{ area_code: '920', state: 'WI'},
	{ area_code: '304', state: 'WV'},
	{ area_code: '681', state: 'WV'},
	{ area_code: '307', state: 'WY'}
];
