$(document).ready(function() {

	//hide alerts
	$("#hide_alert").click(function(){
		$("#alert-box").hide();
	});
	
	/* --------  Initialize Content --------  */
	
	//pre-populate state dropdowns
	for(var i = 0; i < usStates.length; i++){
		$('#cc_state').append('<option value="' + usStates[i]['abbreviation'] +'">' + usStates[i]['name'] + '</option>');
	}
	
	//pre-populate country dropdowns
	for(var i = 0; i < countryCodes.length; i++){
		$('#cc_country').append('<option value="' + countryCodes[i]['abbreviation'] +'">' + countryCodes[i]['name'] + '</option>');
	}
	
	//pre-populate country dropdowns
	for(var i = 0; i < expMonths.length; i++){
		$('#cc_exp_month').append('<option value="' + expMonths[i]['value'] +'">' + expMonths[i]['month'] + '</option>');
	}
	
	//pre-populate country dropdowns
	for(var i = 0; i < expYears.length; i++){
		$('#cc_exp_year').append('<option value="' + expYears[i]['value'] +'">' + expYears[i]['year'] + '</option>');
	}
	
	$('#back_step_two').on('click', function() { 
	
		
		$.ajax({
			url: "/setup/set_session/",
			type:"POST",
			data: { 
				last_step : 2
			 },
			success: function(data, textStatus, jqXHR) {
				window.location.replace("/setup/register");
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert(textStatus);
			}
		});
		
		
	});
	
	
	$("#payment-form").validate({
		errorClass: "invalid",
		validClass: "valid",
		ignore: ".ignore",
		wrapper: "tr",
		success: function(label) {
			label.hide()
		},
		onfocusout: function(element) { $(element).valid(); },
		invalidHandler: function(event, validator) {
			var errors = validator.numberOfInvalids();
				if (errors) {
					var message = errors == 1
					? 'You missed 1 field. Please go back and fix the error.'
					: 'You missed ' + errors + ' fields.  Please go back and fix the errors.';
					$("#submit_check_three").html(message);
					$("#submit_check_three").show();
				} else {
					$("#submit_check_three").hide();
				}
			},
		submitHandler: function(form) {
			$('#submit-load').show();
			
			$.ajax({
				url: "/setup/set_session/",
				type:"POST",
				data: { 
					cc_first_name : $('#cc_first_name').val(),
					cc_last_name : $('#cc_last_name').val(),
					cc_address1 : $('#cc_address1').val(),
					cc_address2 : $('#cc_address2').val(),
					cc_city : $('#cc_city').val(),
					cc_zip : $('#cc_zip').val(),
				 },
				success: function(data, textStatus, jqXHR) {
					$('#finish_activate').hide();
					form.submit();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(textStatus);
				}
			});
		},
	  	rules: {
		  cc_first_name: {
			required: true,
      		minlength: 2
		  },
		  cc_last_name: {
			required: true,
      		minlength: 1
		  },
		  number: {
			required: true,
      		creditcard: true
		  },
		  cc_cvv: {
			required: true,
			digits: true,
      		rangelength: [3, 4]
		  },
		  cc_address1: {
			required: true,
			minlength: 2
		  },
		  cc_city: {
			required: true,
			minlength: 2
		  },
		  cc_zip: {
			required: true,
			minlength: 5,
			digits: true,
			maxlength: 5
		  }
  		}
	});
});


var expMonths = [
	{ month: 'Month', value: '-'},
	{ month: 'January', value: '01'},
	{ month: 'February', value: '02'},
	{ month: 'March', value: '03'},
	{ month: 'April', value: '04'},
	{ month: 'May', value: '05'},
	{ month: 'June', value: '06'},
	{ month: 'July', value: '07'},
	{ month: 'August', value: '08'},
	{ month: 'September', value: '09'},
	{ month: 'October', value: '10'},
	{ month: 'November', value: '11'},
	{ month: 'December', value: '12'},
];

var expYears = [
	{ year: 'Year', value: '-'},
	{ year: '2014', value: '2014'},
	{ year: '2015', value: '2015'},
	{ year: '2016', value: '2016'},
	{ year: '2017', value: '2017'},
	{ year: '2018', value: '2018'},
	{ year: '2019', value: '2019'},
	{ year: '2020', value: '2020'},
	{ year: '2021', value: '2021'},
	{ year: '2022', value: '2022'},
	{ year: '2023', value: '2023'},
	{ year: '2024', value: '2024'},
];

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


var countryCodes = [
	{ name: 'United States of America', abbreviation: 'USA'},
	{ name: 'Afghanistan', abbreviation: 'AFG'},
	{ name: 'Aland Islands', abbreviation: 'ALA'},
	{ name: 'Albania', abbreviation: 'ALB'},
	{ name: 'Algeria', abbreviation: 'DZA'},
	{ name: 'American Samoa', abbreviation: 'ASM'},
	{ name: 'Andorra', abbreviation: 'AND'},
	{ name: 'Angola', abbreviation: 'AGO'},
	{ name: 'Anguilla', abbreviation: 'AIA'},
	{ name: 'Antarctica', abbreviation: 'ATA'},
	{ name: 'Antigua and Barbuda', abbreviation: 'ATG'},
	{ name: 'Argentina', abbreviation: 'ARG'},
	{ name: 'Armenia', abbreviation: 'ARM'},
	{ name: 'Aruba', abbreviation: 'ABW'},
	{ name: 'Australia', abbreviation: 'AUS'},
	{ name: 'Austria', abbreviation: 'AUT'},
	{ name: 'Azerbaijan', abbreviation: 'AZE'},
	{ name: 'Bahamas', abbreviation: 'BHS'},
	{ name: 'Bahrain', abbreviation: 'BHR'},
	{ name: 'Bangladesh', abbreviation: 'BGD'},
	{ name: 'Barbados', abbreviation: 'BRB'},
	{ name: 'Belarus', abbreviation: 'BLR'},
	{ name: 'Belgium', abbreviation: 'BEL'},
	{ name: 'Belize', abbreviation: 'BLZ'},
	{ name: 'Benin', abbreviation: 'BEN'},
	{ name: 'Bermuda', abbreviation: 'BMU'},
	{ name: 'Bhutan', abbreviation: 'BTN'},
	{ name: 'Bolivia', abbreviation: 'BOL'},
	{ name: 'Bosnia and Herzegovina', abbreviation: 'BIH'},
	{ name: 'Botswana', abbreviation: 'BWA'},
	{ name: 'Bouvet Island', abbreviation: 'BVT'},
	{ name: 'Brazil', abbreviation: 'BRA'},
	{ name: 'British Virgin Islands', abbreviation: 'VGB'},
	{ name: 'British Indian Ocean Territory', abbreviation: 'IOT'},
	{ name: 'Brunei Darussalam', abbreviation: 'BRN'},
	{ name: 'Bulgaria', abbreviation: 'BGR'},
	{ name: 'Burkina Faso', abbreviation: 'BFA'},
	{ name: 'Burundi', abbreviation: 'BDI'},
	{ name: 'Cambodia', abbreviation: 'KHM'},
	{ name: 'Cameroon', abbreviation: 'CMR'},
	{ name: 'Canada', abbreviation: 'CAN'},
	{ name: 'Cape Verde', abbreviation: 'CPV'},
	{ name: 'Cayman Islands', abbreviation: 'CYM'},
	{ name: 'Central African Republic', abbreviation: 'CAF'},
	{ name: 'Chad', abbreviation: 'TCD'},
	{ name: 'Chile', abbreviation: 'CHL'},
	{ name: 'China', abbreviation: 'CHN'},
	{ name: 'Hong Kong, Special Administrative Region of China', abbreviation: 'HKG'},
	{ name: 'Macao, Special Administrative Region of China', abbreviation: 'MAC'},
	{ name: 'Christmas Island', abbreviation: 'CXR'},
	{ name: 'Cocos (Keeling) Islands', abbreviation: 'CCK'},
	{ name: 'Colombia', abbreviation: 'COL'},
	{ name: 'Comoros', abbreviation: 'COM'},
	{ name: 'Congo (Brazzaville)', abbreviation: 'COG'},
	{ name: 'Congo, Democratic Republic of the', abbreviation: 'COD'},
	{ name: 'Cook Islands', abbreviation: 'COK'},
	{ name: 'Costa Rica', abbreviation: 'CRI'},
	{ name: 'Côte d\'Ivoire', abbreviation: 'CIV'},
	{ name: 'Croatia', abbreviation: 'HRV'},
	{ name: 'Cuba', abbreviation: 'CUB'},
	{ name: 'Cyprus', abbreviation: 'CYP'},
	{ name: 'Czech Republic', abbreviation: 'CZE'},
	{ name: 'Denmark', abbreviation: 'DNK'},
	{ name: 'Djibouti', abbreviation: 'DJI'},
	{ name: 'Dominica', abbreviation: 'DMA'},
	{ name: 'Dominican Republic', abbreviation: 'DOM'},
	{ name: 'Ecuador', abbreviation: 'ECU'},
	{ name: 'Egypt', abbreviation: 'EGY'},
	{ name: 'El Salvador', abbreviation: 'SLV'},
	{ name: 'Equatorial Guinea', abbreviation: 'GNQ'},
	{ name: 'Eritrea', abbreviation: 'ERI'},
	{ name: 'Estonia', abbreviation: 'EST'},
	{ name: 'Ethiopia', abbreviation: 'ETH'},
	{ name: 'Falkland Islands (Malvinas)', abbreviation: 'FLK'},
	{ name: 'Faroe Islands', abbreviation: 'FRO'},
	{ name: 'Fiji', abbreviation: 'FJI'},
	{ name: 'Finland', abbreviation: 'FIN'},
	{ name: 'France', abbreviation: 'FRA'},
	{ name: 'French Guiana', abbreviation: 'GUF'},
	{ name: 'French Polynesia', abbreviation: 'PYF'},
	{ name: 'French Southern Territories', abbreviation: 'ATF'},
	{ name: 'Gabon', abbreviation: 'GAB'},
	{ name: 'Gambia', abbreviation: 'GMB'},
	{ name: 'Georgia', abbreviation: 'GEO'},
	{ name: 'Germany', abbreviation: 'DEU'},
	{ name: 'Ghana', abbreviation: 'GHA'},
	{ name: 'Gibraltar', abbreviation: 'GIB'},
	{ name: 'Greece', abbreviation: 'GRC'},
	{ name: 'Greenland', abbreviation: 'GRL'},
	{ name: 'Grenada', abbreviation: 'GRD'},
	{ name: 'Guadeloupe', abbreviation: 'GLP'},
	{ name: 'Guam', abbreviation: 'GUM'},
	{ name: 'Guatemala', abbreviation: 'GTM'},
	{ name: 'Guernsey', abbreviation: 'GGY'},
	{ name: 'Guinea', abbreviation: 'GIN'},
	{ name: 'Guinea-Bissau', abbreviation: 'GNB'},
	{ name: 'Guyana', abbreviation: 'GUY'},
	{ name: 'Haiti', abbreviation: 'HTI'},
	{ name: 'Heard Island and Mcdonald Islands', abbreviation: 'HMD'},
	{ name: 'Holy See (Vatican City State)', abbreviation: 'VAT'},
	{ name: 'Honduras', abbreviation: 'HND'},
	{ name: 'Hungary', abbreviation: 'HUN'},
	{ name: 'Iceland', abbreviation: 'ISL'},
	{ name: 'India', abbreviation: 'IND'},
	{ name: 'Indonesia', abbreviation: 'IDN'},
	{ name: 'Iran, Islamic Republic of', abbreviation: 'IRN'},
	{ name: 'Iraq', abbreviation: 'IRQ'},
	{ name: 'Ireland', abbreviation: 'IRL'},
	{ name: 'Isle of Man', abbreviation: 'IMN'},
	{ name: 'Israel', abbreviation: 'ISR'},
	{ name: 'Italy', abbreviation: 'ITA'},
	{ name: 'Jamaica', abbreviation: 'JAM'},
	{ name: 'Japan', abbreviation: 'JPN'},
	{ name: 'Jersey', abbreviation: 'JEY'},
	{ name: 'Jordan', abbreviation: 'JOR'},
	{ name: 'Kazakhstan', abbreviation: 'KAZ'},
	{ name: 'Kenya', abbreviation: 'KEN'},
	{ name: 'Kiribati', abbreviation: 'KIR'},
	{ name: 'Korea, Democratic People\'s Republic of', abbreviation: 'PRK'},
	{ name: 'Korea, Republic of', abbreviation: 'KOR'},
	{ name: 'Kuwait', abbreviation: 'KWT'},
	{ name: 'Kyrgyzstan', abbreviation: 'KGZ'},
	{ name: 'Lao PDR', abbreviation: 'LAO'},
	{ name: 'Latvia', abbreviation: 'LVA'},
	{ name: 'Lebanon', abbreviation: 'LBN'},
	{ name: 'Lesotho', abbreviation: 'LSO'},
	{ name: 'Liberia', abbreviation: 'LBR'},
	{ name: 'Libya', abbreviation: 'LBY'},
	{ name: 'Liechtenstein', abbreviation: 'LIE'},
	{ name: 'Lithuania', abbreviation: 'LTU'},
	{ name: 'Luxembourg', abbreviation: 'LUX'},
	{ name: 'Macedonia, Republic of', abbreviation: 'MKD'},
	{ name: 'Madagascar', abbreviation: 'MDG'},
	{ name: 'Malawi', abbreviation: 'MWI'},
	{ name: 'Malaysia', abbreviation: 'MYS'},
	{ name: 'Maldives', abbreviation: 'MDV'},
	{ name: 'Mali', abbreviation: 'MLI'},
	{ name: 'Malta', abbreviation: 'MLT'},
	{ name: 'Marshall Islands', abbreviation: 'MHL'},
	{ name: 'Martinique', abbreviation: 'MTQ'},
	{ name: 'Mauritania', abbreviation: 'MRT'},
	{ name: 'Mauritius', abbreviation: 'MUS'},
	{ name: 'Mayotte', abbreviation: 'MYT'},
	{ name: 'Mexico', abbreviation: 'MEX'},
	{ name: 'Micronesia, Federated States of', abbreviation: 'FSM'},
	{ name: 'Moldova', abbreviation: 'MDA'},
	{ name: 'Monaco', abbreviation: 'MCO'},
	{ name: 'Mongolia', abbreviation: 'MNG'},
	{ name: 'Montenegro', abbreviation: 'MNE'},
	{ name: 'Montserrat', abbreviation: 'MSR'},
	{ name: 'Morocco', abbreviation: 'MAR'},
	{ name: 'Mozambique', abbreviation: 'MOZ'},
	{ name: 'Myanmar', abbreviation: 'MMR'},
	{ name: 'Namibia', abbreviation: 'NAM'},
	{ name: 'Nauru', abbreviation: 'NRU'},
	{ name: 'Nepal', abbreviation: 'NPL'},
	{ name: 'Netherlands', abbreviation: 'NLD'},
	{ name: 'Netherlands Antilles', abbreviation: 'ANT'},
	{ name: 'New Caledonia', abbreviation: 'NCL'},
	{ name: 'New Zealand', abbreviation: 'NZL'},
	{ name: 'Nicaragua', abbreviation: 'NIC'},
	{ name: 'Niger', abbreviation: 'NER'},
	{ name: 'Nigeria', abbreviation: 'NGA'},
	{ name: 'Niue', abbreviation: 'NIU'},
	{ name: 'Norfolk Island', abbreviation: 'NFK'},
	{ name: 'Northern Mariana Islands', abbreviation: 'MNP'},
	{ name: 'Norway', abbreviation: 'NOR'},
	{ name: 'Oman', abbreviation: 'OMN'},
	{ name: 'Pakistan', abbreviation: 'PAK'},
	{ name: 'Palau', abbreviation: 'PLW'},
	{ name: 'Palestinian Territory, Occupied', abbreviation: 'PSE'},
	{ name: 'Panama', abbreviation: 'PAN'},
	{ name: 'Papua New Guinea', abbreviation: 'PNG'},
	{ name: 'Paraguay', abbreviation: 'PRY'},
	{ name: 'Peru', abbreviation: 'PER'},
	{ name: 'Philippines', abbreviation: 'PHL'},
	{ name: 'Pitcairn', abbreviation: 'PCN'},
	{ name: 'Poland', abbreviation: 'POL'},
	{ name: 'Portugal', abbreviation: 'PRT'},
	{ name: 'Puerto Rico', abbreviation: 'PRI'},
	{ name: 'Qatar', abbreviation: 'QAT'},
	{ name: 'Réunion', abbreviation: 'REU'},
	{ name: 'Romania', abbreviation: 'ROU'},
	{ name: 'Russian Federation', abbreviation: 'RUS'},
	{ name: 'Rwanda', abbreviation: 'RWA'},
	{ name: 'Saint-Barthélemy', abbreviation: 'BLM'},
	{ name: 'Saint Helena', abbreviation: 'SHN'},
	{ name: 'Saint Kitts and Nevis', abbreviation: 'KNA'},
	{ name: 'Saint Lucia', abbreviation: 'LCA'},
	{ name: 'Saint-Martin (French part)', abbreviation: 'MAF'},
	{ name: 'Saint Pierre and Miquelon', abbreviation: 'SPM'},
	{ name: 'Saint Vincent and Grenadines', abbreviation: 'VCT'},
	{ name: 'Samoa', abbreviation: 'WSM'},
	{ name: 'San Marino', abbreviation: 'SMR'},
	{ name: 'Sao Tome and Principe', abbreviation: 'STP'},
	{ name: 'Saudi Arabia', abbreviation: 'SAU'},
	{ name: 'Senegal', abbreviation: 'SEN'},
	{ name: 'Serbia', abbreviation: 'SRB'},
	{ name: 'Seychelles', abbreviation: 'SYC'},
	{ name: 'Sierra Leone', abbreviation: 'SLE'},
	{ name: 'Singapore', abbreviation: 'SGP'},
	{ name: 'Slovakia', abbreviation: 'SVK'},
	{ name: 'Slovenia', abbreviation: 'SVN'},
	{ name: 'Solomon Islands', abbreviation: 'SLB'},
	{ name: 'Somalia', abbreviation: 'SOM'},
	{ name: 'South Africa', abbreviation: 'ZAF'},
	{ name: 'South Georgia and the South Sandwich Islands', abbreviation: 'SGS'},
	{ name: 'South Sudan', abbreviation: 'SSD'},
	{ name: 'Spain', abbreviation: 'ESP'},
	{ name: 'Sri Lanka', abbreviation: 'LKA'},
	{ name: 'Sudan', abbreviation: 'SDN'},
	{ name: 'Suriname *', abbreviation: 'SUR'},
	{ name: 'Svalbard and Jan Mayen Islands', abbreviation: 'SJM'},
	{ name: 'Swaziland', abbreviation: 'SWZ'},
	{ name: 'Sweden', abbreviation: 'SWE'},
	{ name: 'Switzerland', abbreviation: 'CHE'},
	{ name: 'Syrian Arab Republic (Syria)', abbreviation: 'SYR'},
	{ name: 'Taiwan, Republic of China', abbreviation: 'TWN'},
	{ name: 'Tajikistan', abbreviation: 'TJK'},
	{ name: 'Tanzania *, United Republic of', abbreviation: 'TZA'},
	{ name: 'Thailand', abbreviation: 'THA'},
	{ name: 'Timor-Leste', abbreviation: 'TLS'},
	{ name: 'Togo', abbreviation: 'TGO'},
	{ name: 'Tokelau', abbreviation: 'TKL'},
	{ name: 'Tonga', abbreviation: 'TON'},
	{ name: 'Trinidad and Tobago', abbreviation: 'TTO'},
	{ name: 'Tunisia', abbreviation: 'TUN'},
	{ name: 'Turkey', abbreviation: 'TUR'},
	{ name: 'Turkmenistan', abbreviation: 'TKM'},
	{ name: 'Turks and Caicos Islands', abbreviation: 'TCA'},
	{ name: 'Tuvalu', abbreviation: 'TUV'},
	{ name: 'Uganda', abbreviation: 'UGA'},
	{ name: 'Ukraine', abbreviation: 'UKR'},
	{ name: 'United Arab Emirates', abbreviation: 'ARE'},
	{ name: 'United Kingdom', abbreviation: 'GBR'},
	{ name: 'United States Minor Outlying Islands', abbreviation: 'UMI'},
	{ name: 'Uruguay', abbreviation: 'URY'},
	{ name: 'Uzbekistan', abbreviation: 'UZB'},
	{ name: 'Vanuatu', abbreviation: 'VUT'},
	{ name: 'Venezuela (Bolivarian Republic of)', abbreviation: 'VEN'},
	{ name: 'Viet Nam', abbreviation: 'VNM'},
	{ name: 'Virgin Islands, US', abbreviation: 'VIR'},
	{ name: 'Wallis and Futuna Islands', abbreviation: 'WLF'},
	{ name: 'Western Sahara', abbreviation: 'ESH'},
	{ name: 'Yemen', abbreviation: 'YEM'},
	{ name: 'Zambia', abbreviation: 'ZMB'},
	{ name: 'Zimbabwe', abbreviation: 'ZWE'}
];
