<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {
    
    public function __construct()
	{
		// On controller load, call model
        parent::__construct();

		$this->load->model('ion_auth_model');
		$this->load->model('setup_model');
		$this->load->library('blesta_api');
		$this->load->library('blesta_response');
		$this->load->library('bcrypt');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->lang->load('auth');
	}
	
	public function agreement() {
		$this->load->view('templates/header');
		$this->load->view('setup/agreement');
		$this->load->view('templates/footer');	
	}
		
	public function privacy() {
		$this->load->view('templates/header');
		$this->load->view('setup/privacy');
		$this->load->view('templates/footer');	
	}
	
	public function view_session() {
		print_r($this->session->userdata('form_entry'));
	}
	
	public function set_session() {
		$data = $this->session->userdata('form_entry');
		
		foreach($_POST as $key => $val)  
		{  
			$data[$key] = $this->input->post($key);  
		}
		
		$this->session->set_userdata('form_entry', $data);
	}
	
	
	public function did_search(){
		
		if($this->input->get('area')) 
		{
			$areacode = $this->input->get('area');
		} else {
			echo "area code not passed";
			die;
		}
		
		$dbconn = mysqli_connect("localhost", "root", "Sharktek1", "SharktekInv");

		// Check connection
		if (mysqli_connect_errno()) {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		//select data from database
		$result = mysqli_query($dbconn, "SELECT * FROM did_inventory WHERE available = 1 AND did LIKE " . "'1" . $areacode . "%'");
		
		$formatted_array = array();
		//assign data to array for comparison
		while ($row = mysqli_fetch_assoc($result)) {
			json_encode($formatted_array[$row['rate_city']][] = 'inv_' . $row['did']);
		}
		
		$id = "f8f1963234e3b908333631bcaabe98ef";
		$secret = "5c046068b99c302aaf977e0b8f945e77";
		$client = new soapclient('https://portal.bulkvs.com/api?wsdl');
		$response = $client->DnSearchAreaCode($id, $secret, $areacode);
		
		$array = json_decode(json_encode($response), true);
		
		foreach($array as $entry => $entry_item){
			$formatted_city = ucwords(strtolower($entry_item['city']));
			json_encode($formatted_array[$formatted_city][] = 'blk_' . $entry_item['dn']);
		}
		
		unset($formatted_array['O']);
		
		//array_shift($formatted_array);
		echo json_encode($formatted_array);
	}

	public function register()
	{   
		//redirect if guest is already user
		if ($this->ion_auth->logged_in()) 
		{ 
			$this->session->set_flashdata('danger', 'You cannot access the setup page, you are logged in.');
			redirect('dashboard','refresh');	
		}
		
		//validate form input
		$this->form_validation->set_rules('did', 'Fax Number', 'required|min_length[15]|max_length[15]|');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('new_password', 'Password', 'required');
		$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required|matches[new_password]');
		$this->form_validation->set_rules('first_name', 'Last Name', 'required|xss_clean|max_length[30]');
		$this->form_validation->set_rules('last_name', 'First Name', 'required|xss_clean|max_length[30]');
		$this->form_validation->set_rules('promo', 'Promotional Code', 'xss_clean|max_length[30]');
		$this->form_validation->set_rules('pricing_id', 'Billing Cycle', 'required');

			
		//if submitted process data
		if ($this->form_validation->run() === TRUE) {
						
			//prepare data for user create
			$did = $this->input->post('did');
			$email = $this->input->post('email');
			$new_password = $this->input->post('new_password');
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			
			//check if user has company, else use first + last name
			if($this->input->post('company')) {
				$company = $this->input->post('company');
			} else {
				$company = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
			}

			//send LNP Ticket Email to staff if form is correctly filed out
			if($this->session->userdata('form_entry')) {
				$form_entry_data = $this->session->userdata('form_entry');
				if(substr($form_entry_data['did'],0,4) == "gen_" && $form_entry_data['contact_phone'] && $form_entry_data['current_number']) {

					$message = "<h3>New LNP Request</h3>
					Name: $first_name $last_name <br>
					Email: $email <br>
					Contact Phone: " . $form_entry_data['contact_phone'] . " <br><br>
					Current Provider: " . $form_entry_data['current_provider'] ."<br>
					Number To Be Ported: " . $form_entry_data['current_number'] . "<br>";
					
					//email user with notification
					$this->load->library('email');
					$config = array (
								  'mailtype' => 'html',
								  'charset'  => 'utf-8',
								  'wordwrap' => TRUE,
								  'priority' => '1'
					);
					$this->email->initialize($config);
					$this->email->from('notify@sharktek.net', 'RedFax Team');
					$this->email->to('info@sharktek.net'); 		
					$this->email->subject('New LNP Ticket');
					$this->email->message($message);
					//$this->email->attach($outputFile);
					
					$this->email->send();
				}
			}
			
			//check if user has promo
			if($this->input->post('promo')) {
				if($this->input->post('promo') == "sharktek@2014") {
					$pricing_id = 21;
				} else {
					$pricing_id = $this->input->post('pricing_id');
				}
			} else {
				$pricing_id = $this->input->post('pricing_id');
			}
			
			//check if user exists in blesta, if not create one
			$blesta_client_id = $this->new_blesta_user($email, $new_password, $first_name, $last_name, $company);
			
			//check if user is already signed up for RedFax service (package_group_id = 11)
			$service_exists = $this->blesta_service_check($blesta_client_id);
			
			//redirect user based on whether service exists
			if($service_exists) {
				$service_id = $service_exists['existing_service_id'];
				$service_status = $service_exists['existing_service_status'];
				
				switch ($service_status) {
					case "active":
							$this->session->set_flashdata('message', 'You already have a paid account associated with this email, please login.');
							redirect('auth/login','refresh');	
						break;
					case "pending":
						//get invoice_id & pass service_id/invoice_id
						$invoice_id = $this->blesta_service_invoice($service_id);
						break;
					case "canceled":
						//generate a new service instance & get service_id/invoice_id
						$invoice_id = $this->new_blesta_service($blesta_client_id, $pricing_id);
						break;
				}
			} else {
				//generate a new service instance & get service_id/invoice_id
				$invoice_id = $this->new_blesta_service($blesta_client_id, $pricing_id);
			}
			
			//if customer is using promo, create user in red fax & proces DID then redirect
			if($pricing_id == 21) {
				//Setup User in RedFax
				$userid = $this->new_redfax_user($email, $new_password, $first_name, $last_name, $blesta_client_id, $company);
				
				//Purchase DID or mark as unavailable, then updates to realtime
				$this->new_fax_number($userid, $did);
	
				/*****************************ALL GOOD: REDIRECT*****************************/
				redirect('dashboard', 'refresh');
			}
			
			//else, set session data for payment processing
			$form_entry_data = $this->session->userdata('form_entry');
			$form_entry_data['invoice_id'] = $invoice_id;
			$form_entry_data['blesta_client_id'] = $blesta_client_id;
			$form_entry_data['company'] = $company;
			$this->session->set_userdata('form_entry', $form_entry_data);

			redirect('setup/payment', 'refresh');
		}
		
		$data['form_entry'] = $this->session->userdata('form_entry');
		$this->load->view('templates/header_old');
		$this->load->view('setup/register', $data);
		$this->load->view('templates/footer_old');			
	}
	
	
	public function payment() {
		//redirect if guest is already user
		if ($this->ion_auth->logged_in()) 
		{ 
			$this->session->set_flashdata('error', 'You cannot access the setup page, you are logged in.');
			redirect('dashboard','refresh');	
		}
		
		//verify session data was passed correctly
		if($this->session->userdata('form_entry')) {
			$form_entry_data = $this->session->userdata('form_entry');
		} else {
			$data = $this->session->userdata('form_entry');
			$data['last_step'] = 2;  
			$this->session->set_userdata('form_entry', $data);
			redirect('setup/register', 'refresh');
		}
		
		if($first_name = $form_entry_data['first_name']) {
		} else {
			$data = $this->session->userdata('form_entry');
			$data['last_step'] = 2;  
			$this->session->set_userdata('form_entry', $data);
			redirect('setup/register', 'refresh');
		}
		
		if($last_name = $form_entry_data['last_name']) {
		} else {
			$data = $this->session->userdata('form_entry');
			$data['last_step'] = 2;  
			$this->session->set_userdata('form_entry', $data);
			redirect('setup/register', 'refresh');
		}
		
		if($email = $form_entry_data['email']) {
		} else {
			$data = $this->session->userdata('form_entry');
			$data['last_step'] = 2;  
			$this->session->set_userdata('form_entry', $data);
			redirect('setup/register', 'refresh');
		}
		
		if($new_password = $form_entry_data['new_password']) {
		} else {
			$data = $this->session->userdata('form_entry');
			$data['last_step'] = 2;  
			$this->session->set_userdata('form_entry', $data);
			redirect('setup/register', 'refresh');
		}
		
		if($company = $form_entry_data['company']) {
		} else {
			$data = $this->session->userdata('form_entry');
			$data['last_step'] = 2;  
			$this->session->set_userdata('form_entry', $data);
			redirect('setup/register', 'refresh');
		}
		
		if($did = $form_entry_data['did']) {
		} else {
			$data = $this->session->userdata('form_entry');
			$data['last_step'] = 2;  
			$this->session->set_userdata('form_entry', $data);
			redirect('setup/register', 'refresh');
		}
		
		if($blesta_client_id = $form_entry_data['blesta_client_id']) {
		} else {
			$data = $this->session->userdata('form_entry');
			$data['last_step'] = 2;  
			$this->session->set_userdata('form_entry', $data);
			redirect('setup/register', 'refresh');
		}
		
		if($invoice_id = $form_entry_data['invoice_id']) {
		} else {
			$data = $this->session->userdata('form_entry');
			$data['last_step'] = 2;  
			$this->session->set_userdata('form_entry', $data);
			redirect('setup/register', 'refresh');
		}
		
		//validate form input
		$this->form_validation->set_rules('cc_first_name', 'First Name on Card', 'trim|required|xss_clean|min_length[2]|max_length[20]|alpha');
		$this->form_validation->set_rules('cc_last_name', 'Last Name on Card', 'trim|required|xss_clean|min_length[2]|max_length[20]|alpha');
		$this->form_validation->set_rules('number', 'Credit Card Number', 'trim|required|xss_clean|min_length[15]|max_length[19]');
		$this->form_validation->set_rules('cc_exp_month', 'Credit Card Expiration Month', 'required|xss_clean|min_length[2]|max_length[2]');
		$this->form_validation->set_rules('cc_exp_year', 'Credit Card Expiration Year', 'required|xss_clean|min_length[4]|max_length[4]');
		$this->form_validation->set_rules('cc_cvv', 'Credit Card CVV', 'required|xss_clean|min_length[3]|max_length[10]');
		$this->form_validation->set_rules('cc_address1', 'Billing Address 1', 'trim|required|xss_clean|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('cc_address2', 'Billing Address 2', 'trim|xss_clean|min_length[3]|max_length[25]');
		$this->form_validation->set_rules('cc_city', 'Credit Card City', 'trim|required|xss_clean|min_length[2]|max_length[55]');
		$this->form_validation->set_rules('cc_state', 'Credit Card State', 'trim|required|xss_clean|min_length[2]|max_length[2]');
		$this->form_validation->set_rules('cc_zip', 'Credit Card Zip Code', 'trim|required|xss_clean|min_length[5]|max_length[5]');
		$this->form_validation->set_rules('cc_country', 'Credit Card Country', 'trim|required|xss_clean|min_length[3]|max_length[3]');
			
		//if submitted process data
		if ($this->form_validation->run() === TRUE) {
				
			//attempt to pay
			$payment_status = $this->attempt_payment($blesta_client_id, $invoice_id);
			
			if($payment_status['transaction_status'] !== "approved") {
				$this->session->set_flashdata('error', 'Your credit card transaction was not approved. Please check the data below and try again.');
				redirect('setup/payment','refresh');
			}
			
			//Setup AutoDebit if Payment was approved
			$this->set_auto_debit($blesta_client_id);
			
			//Setup User in RedFax
			$userid = $this->new_redfax_user($email, $new_password, $first_name, $last_name, $blesta_client_id, $company);
			
			//Purchase DID or mark as unavailable, then updates to realtime
			$this->new_fax_number($userid, $did);

                        
                        //check if a user referral code exists in the session data
                        if($this->session->userdata('referrer_code')){ 
                            $data['referrer_id'] = $this->session->userdata('referrer_code'); 
                            $data['completed_users_id'] = $userid;

                            //check if new user had a standing invite
                            if($this->setup_model->get_invites($email)){
                                $invite_record = $this->setup_model->invites($email);
                                $data['completed_invite_id'] = $invite_record[0]['invite_id'];
                            }
                            $this->setup_model->insert_completed_invites($data);
                        }

                        //check if an affiliate referral code exists in the session data
                        else if($this->session->userdata('affiliate_code')){ 
                            $data['affiliate_id'] = $this->session->userdata('affiliate_code'); 
                            $data['completed_users_id'] = $userid;
                            $this->setup_model->insert_completed_invites($data);
                        }

//                        //referral system failed
//                        else{
//                            $this->session->set_flashdata('error', 'There was a problem processing the referral');
//                        }

			/*****************************ALL GOOD: REDIRECT*****************************/
			//flashdata -> confirmation page with transaction ID for reciept
			$this->session->set_flashdata('transaction_id', $payment_status['transaction_id']);
			redirect('dashboard/confirmation', 'refresh');
		}
		
		$data['form_entry'] = $this->session->userdata('form_entry');
		$data['error'] = $this->session->flashdata('error');
		$this->load->view('templates/header_old');
		$this->load->view('setup/payment', $data);
		$this->load->view('templates/footer_old');
	
	}

        
	private function new_blesta_user($email, $new_password, $first_name, $last_name, $company) {

		//prep blesta search data
		$blesta_search_data = array(
			'query' => $email
		);
		
		//check if email already exists in belsta
		if($this->clients_getSearchCount($blesta_search_data)) {
			$client_search_row = $this->clients_search($blesta_search_data);
			$blesta_client_id = $client_search_row[0]->id;
		} else {
			//assign data to array to create blesta client
			$blesta_data = array (
				'vars' => array (
					'username' => $email,
					'new_password' => $new_password,
					'confirm_password' => $new_password,
					'client_group_id' => 5,
					'first_name' => $first_name,
					'last_name' => $last_name,
					'company' => $company,
					'email' => $email,
					'settings' => array (
						'username_type' => 'email'
					)
				)
			);
		
			//create user in Blesta, redirect if fail
			$blesta_client_id = $this->clients_create($blesta_data);
		}
		
		return $blesta_client_id;
	}
	
	// - Checks to see if blesta user already has redfax service
	private function blesta_service_check($blesta_client_id){
		$data = array(
			'client_id' => $blesta_client_id,
			'status' => 'all'	
		);
		
		$service_exists = $this->services_getList($data);
		return $service_exists;
	}
	
	private function blesta_service_invoice($service_id) {
		$data = array(
			'service_id' => $service_id,
			'status' => 'active',
		);
		
		$invoice_id = $this->invoices_getAllWithService($data);
		return $invoice_id;
	}
	
	// - Adds a service to the user, returns the total cost 
	private function new_blesta_service($blesta_client_id, $pricing_id) {
		
		if($pricing_id == 21) {
			$status = "active";
		} else {
			$status = "pending";
		}
		
		//prep service data
		$service_data = array(
			'vars' => array(
				'package_group_id' => "11",
				'pricing_id' => $pricing_id,
				'client_id' => $blesta_client_id,
				'module_row_id' => "5",
				'status' => $status
			)
		);
		
		//add service to blesta account
		$service_id = $this->services_add($service_data);
		
		//prep invoice data
		$invoice_data = array(
			'client_id' => $blesta_client_id,
			'service_ids' => array('service_ids' => $service_id),
			'currency' => "USD",
			'due_date' => date("c"),
		);
		
		//create initial invoice
		$invoice_id = $this->invoices_createFromServices($invoice_data);
		
		$getDelivery_data = array(
			'invoice_id'  => $invoice_id
		);
		
		if($delivery_id = $this->invoices_getDelivery($getDelivery_data)) {
			$deleteDelivery_data = array(
				'invoice_delivery_id'  => $delivery_id
			);
			$this->invoices_deleteDelivery($deleteDelivery_data);
		}

		return $invoice_id;
	}
	
	
	private function attempt_payment($blesta_client_id, $invoice_id) {
		
		//retrieve amount of invoice
		$invoice_amount = $this->invoices_getTotal(array('invoice_id' => $invoice_id));
		
		//combine cc_exp_month & cc_exp_year in yyyymm format
		$cc_exp =  $this->input->post('cc_exp_year') . $this->input->post('cc_exp_month');
		
		//check if address 2 is NULL
		if(($this->input->post('cc_address2'))) {
			$address2 = $this->input->post('cc_address2');
		} else {
			$address2 = NULL;
		}
		
		//prep payment data
		$payment_data = array(
			'client_id' => $blesta_client_id,
			'type' => 'cc',
			'amount' => $invoice_amount,
			'currency' => 'USD',
			'account_info' => array(
						'first_name' => $this->input->post('cc_first_name'),
						'last_name' => $this->input->post('cc_last_name'),
						'card_number' => $this->input->post('number'),
						'card_exp' => $cc_exp,
						'card_security_code' => $this->input->post('cc_cvv'),
						'address1' => $this->input->post('cc_address1'),
						'address2' => $address2,
						'city' => $this->input->post('cc_city'),
						'state' => $this->input->post('cc_state'),
						'country' => $this->input->post('cc_country'),
						'zip' => $this->input->post('cc_zip'),
						),
			'options' => array (
							'invoices' => array ( $invoice_id => $invoice_amount ),
						)
		);
		
		$status = $this->payments_processPayment($payment_data);
		return $status;
	}
	
	private function set_auto_debit($blesta_client_id) {
		
		//check if address 2 is NULL
		if(($this->input->post('cc_address2'))) {
			$address2 = $this->input->post('cc_address2');
		} else {
			$address2 = NULL;
		}
		
		//retrieve contact_id by querying API
		$contact_id = $this->clients_get(array('client_id' => $blesta_client_id));

		//combine cc_exp_month & cc_exp_year in yyyymm format
		$cc_exp =  $this->input->post('cc_exp_year') . $this->input->post('cc_exp_month');
	
		$addCc_data = array(
			'vars' => array(
				'contact_id' => $contact_id,
				'first_name' => $this->input->post('cc_first_name'),
				'last_name' => $this->input->post('cc_last_name'),
				'address1' => $this->input->post('cc_address1'),
				'address2' =>  $address2,
				'city' => $this->input->post('cc_city'),
				'state' => $this->input->post('cc_state'),
				'zip' => $this->input->post('cc_zip'),
				'country' => $this->input->post('cc_country'),
				'number' => $this->input->post('number'),
				'expiration' => $cc_exp,
				'security_code' => $this->input->post('cc_cvv'),
			)
		);
		
		$account_id = $this->accounts_addCc($addCc_data);
		
		$addDebitAccount_data = array(
			'client_id' => (int)$blesta_client_id,
			'vars' => array(
				'account_id' => (int)$account_id,
				'type' => "cc",
			),
		);
		
		//has no return value
		$this->clients_addDebitAccount($addDebitAccount_data);
		
		$setSetting_data = array(
			'client_id' => $blesta_client_id,
			'key' => 'autodebit',
			'value' => 'true',
		);
		
		//has no return value
		$this->clients_setSetting($setSetting_data);
	
	}
	
	// - Create a new user, group, (associates them), and product
	private function new_redfax_user($email, $new_password, $first_name, $last_name, $blesta_client_id, $company) {
		//assign data to array for redfax customer data
		$redfax_client_data = array(
			'ip_address' => $this->input->ip_address(),
			'username' => $email,
			'password' => $this->bcrypt->hash($new_password),
			'email' => $email,
			'created_on' => time(),
			'active' => 1,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'blesta_client_id' => $blesta_client_id
		);
		
		
		//create user in SharkTrack, assigned the user id or redirect
		$userid = $this->setup_model->redfax_clients_create($redfax_client_data);
		if($userid == FALSE) {
			$this->session->set_flashdata('error', 'Unable to create client in RedFaxDB');
			redirect('setup/payment','refresh');	
		}
		
		//create group in SharkTrack, assign the group id or redirect
		$groupid = $this->setup_model->redfax_groups_create($company);
		if($groupid == FALSE) {
			$this->session->set_flashdata('error', 'Unable to create group in RedFaxDB');
			redirect('setup/payment','refresh');		
		}	
		
		//assign previous query returns to array
		$users_groups_data = array(
			'user_id' => $userid,
			'group_id' => $groupid
		);
		
		//associate the user to the group in SharkTrack, if fail redirect
		$return = $this->setup_model->redfax_users_groups_create($users_groups_data);
		if($return == FALSE) {
			$this->session->set_flashdata('error', 'Unable to associate user to group in RedFaxDB');
			redirect('setup/payment','refresh');		
		}	
			
		//assign data to array to create product
		$product_data = array(
			'product_name' => 'RedFax - ' . $company,
			'active' => 1,
			'groupid' => $groupid
		);
		
		//create product in SharkTrack, if fail redirect
		$return2 = $this->setup_model->redfax_products_create($product_data);
		if($return2 == FALSE) {
			$this->session->set_flashdata('error', 'Unable to create product in RedFaxDB');
			redirect('setup/payment','refresh');		
		}
		
		return $userid;
	}
	
	// - Orders a number from bulkvs or sets new number available to 0 in SharkInv
	// - Inserts a record of this new fax number in redfax.fax_did
	// - Updates all from redfax.fax_did to realtime
	private function new_fax_number($userid, $did) {
		
		//auto login user after processing
		//we put this here as it is used to get the groupid from IonAuth
		$session_row = $this->ion_auth->user($userid)->row();
		$this->ion_auth->set_session($session_row);
		
		$prefix = substr($did,0,4);
		$eleven_digit_did = substr($did,4,11);
		$ten_digit_did = substr($did,5,10);
		$comments = $session_row->username;
		
		//if did is from BulkVS, purchase it. Else mark as unavailable in SharkInv
		if($prefix == "blk_") {
			
			//order DID, pass $comments for logging
			$order_status = $this->order_bullkvs($eleven_digit_did, $comments);
			
			//if order failed, redirect to dashboard
			if($order_status === FALSE) {
				$this->session->set_flashdata('error', 'Your fax number was chosen by another user before completion!<br>
				Please find a new number or call 239.443.4444 for support.');
				redirect('account/order_did', 'refresh');
			}
			
			//store in SharktekInventory
			$this->update_inventory($eleven_digit_did, $comments);
			
		} else if($prefix == "inv_"){
			
			//store in SharktekInventory
			$this->set_unavailable($eleven_digit_did, $comments);
			
		} else if($prefix == "gen_") {
			$eleven_digit_did = $this->random_inventory();
			$ten_digit_did = substr($eleven_digit_did,1,10);
			$this->set_unavailable($eleven_digit_did, $comments);
		} else {
			$this->session->set_flashdata('error', 'Your fax number was not correctly passed, please select a new did!<br>
			Please find a new number or call 239.443.4444 for support.');
			redirect('account/order_did', 'refresh');
		}
		
		//get groupid
		$groupid = $this->ion_auth->get_users_groups()->row()->id;
		
		//get product data
		$product_data = $this->setup_model->get_product_id($groupid);
		
		$fax_did_data = array(
			'fax_did'  => $ten_digit_did,
			'productid' => $product_data
		);
		
		//it worked! insert data, update ATL forwards
		$this->setup_model->insert_fax_did($fax_did_data);
		$this->update_atl_dids();
	}
	
	//sends request for an order to bulkvs
	private function order_bullkvs($did, $username){
		
		//API Data
		$id = "f8f1963234e3b908333631bcaabe98ef";
		$secret = "5c046068b99c302aaf977e0b8f945e77";
		$dn = $did;
		$trunkgroup = 'SIPX';
		$cnamlookup = 'Enabled';
		$lidb = '';
		$wsdl = 'https://portal.bulkvs.com/api?wsdl';
		
	
		for ($i = 1; $i <= 3; $i++) {
			//initialize soap client
			$client = new SoapClient($wsdl,
				array('trace' => 1,
				'exceptions'=> 1,
				'connection_timeout'=> 15
			));
			
			//make api call
			$response = $client->DnOrder($id, $secret, $dn, $trunkgroup, $cnamlookup, $lidb);
			
			//log request
			$myfile = fopen("/var/redfax/debug.txt", "a+") or die("Unable to open file!");
			$time = date("F j, Y, g:i a");   
			fwrite($myfile, "SETUP $i -- $time: " . print_r($response, TRUE) . "\n");
			fclose($myfile);
			
			//check if request was successful
			if(property_exists($response, 'entry')) {
				return TRUE;
			}
		}
		
		//if user has not already been redirect, DID purchase failed
		return FALSE;
	}
	
	
	private function set_unavailable($inventory_did, $comments){

		$dbconn = mysqli_connect("localhost","root","Sharktek1","SharktekInv");
	
		// Check connection
		if (mysqli_connect_errno()) {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		$sql = "UPDATE did_inventory SET available = 0, comments = " . $this->db->escape($comments) . " WHERE did = " . $inventory_did;
		
		//select data from database
		$result = mysqli_query($dbconn, $sql);
		
		if(mysqli_affected_rows($dbconn) < 1) {
			$this->session->set_flashdata('error', 'Unable to update SharktekInv setting available = 0');
			redirect('setup/payment','refresh');
		}
	}
	
	private function update_inventory($inventory_did, $comments){
		
		$dbconn = mysqli_connect("localhost","root","Sharktek1","SharktekInv");
	
		// Check connection
		if (mysqli_connect_errno()) {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		//select data from database
		$result = mysqli_query($dbconn,
		"INSERT INTO did_inventory (did, available, trunk_group, used_by, is_vanity, comments) VALUES ($inventory_did , 0, 'SIPX','redfax', 0,  " . $this->db->escape($comments) . " )");
		
		if(mysqli_affected_rows($dbconn) < 1) {
			$this->session->set_flashdata('error', 'Unable to update SharktekInv, inserting new record!');
			redirect('setup/payment','refresh');
		}
	}
	
	private function random_inventory(){
		
		$dbconn = mysqli_connect("localhost","root","Sharktek1","SharktekInv");
	
		// Check connection
		if (mysqli_connect_errno()) {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		//find available did in database
		$result = mysqli_query($dbconn,"SELECT * FROM did_inventory WHERE available = 1");
		
		if($result->num_rows > 0) {
			$row = mysqli_fetch_assoc($result);
			return $row['did'];
		} else {
			$this->session->set_flashdata('error', 'There are no available fax numbers in our temporary pool.');
			redirect('setup/payment','refresh');
		}
	}
	
	private function update_atl_dids(){
		
		$dbconn = mysqli_connect("localhost","root","Sharktek1","redfax");
	
		// Check connection
		if (mysqli_connect_errno()) {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		//select data from database
		$result = mysqli_query($dbconn,"SELECT * FROM fax_did");
	
		//assign data to array for comparison
		$arr = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$arr['fax_did'][] = urlencode($row['fax_did']);
		}
		
		//set POST variables
		$url = 'http://sipxatl.sharktek.net/redfax/atl_update_dids.php';
		
		//initialize var
		$fields_string = '';
		$fields_string = http_build_query($arr);

		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST, count($arr));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		
		//execute post
		$result = curl_exec($ch);
		
		//close connection
		curl_close($ch);
	}

	
	
	private function clients_getSearchCount($data) {
		$return = $this->blesta_api->get("clients", "getSearchCount", $data);
		$response = $this->blesta_response->response($return);
		if(isset($response) && !empty($response)) {
			return $response;
			//log request
			$myfile = fopen("/var/redfax/debug.txt", "a+") or die("Unable to open file!");
			$time = date("F j, Y, g:i a");   
			fwrite($myfile, "SETUP $i -- $time: " . print_r($response, TRUE) . "\n");
			fclose($myfile);
		} else {
			return FALSE;
		}
	}
	
	private function clients_search($data) {
		$return = $this->blesta_api->get("clients", "search", $data);
		$response = $this->blesta_response->response($return);
		if(isset($response) && !empty($response)) {
			return $response;
		} else {
			return FALSE;
		}
	}
	
	private function clients_create($data) {
		$return = $this->blesta_api->get("clients", "create", $data);
		$response = $this->blesta_response->response($return);
		if(isset($response) && !empty($response)) {
			return $response->id;	
		} else {
			$this->session->set_flashdata('error', 'Unable to create client in Blesta');
			redirect('setup/register','refresh');		
		}
	}
	
	private function services_getList($data) {
		$return = $this->blesta_api->get("services", "getList", $data);
		$response = $this->blesta_response->response($return);
		if(isset($response) && !empty($response)) {
			foreach($response as $service) {
				if($service->package_group_id == 11) {
					$service_array = array(
						'existing_service_id' => $service->id,
						'existing_service_status' => $service->status
					);
					return $service_array;
				} else {
					return FALSE;
				}
			}
		} else {
			return FALSE;
		}
	}
	
	private function services_add($data) {
		$return = $this->blesta_api->get("services", "add", $data);
		$response = $this->blesta_response->response($return);
		if(!isset($response) || empty($response)) {
			$this->session->set_flashdata('error', 'Unable to add service to client in Blesta');
			redirect('setup/payment','refresh');	
		} else {
			return $response;
		}
	}
	
	private function invoices_getAllWithService($data) {
		$return = $this->blesta_api->get("invoices", "getAllWithService", $data);
		$response = $this->blesta_response->response($return);
		if(isset($response) && !empty($response)) {
			return $response[0]->id;
		} else {
			return FALSE;
		}
	}
	
	private function invoices_createFromServices($data) {
		$return = $this->blesta_api->get("invoices", "createFromServices", $data);
		$response = $this->blesta_response->response($return);
		if(!isset($response) || empty($response)) {
			$this->session->set_flashdata('error', 'Unable to create invoice from service in Blesta');
			redirect('setup/payment','refresh');	
		} else {
			return $response;
		}
	}
	
	private function invoices_getDelivery($data) {
		$return = $this->blesta_api->get("invoices", "getDelivery", $data);
		$response = $this->blesta_response->response($return);
		if(isset($response[0]->id) && !empty($response[0]->id)) {
			return $response[0]->id;
		} else {
			return FALSE;
		}
	}
	
	private function invoices_deleteDelivery($data) {
		$return = $this->blesta_api->get("invoices", "deleteDelivery", $data);
		$response = $this->blesta_response->response($return);
	}
	
	private function invoices_getTotal($data) {
		$return = $this->blesta_api->get("invoices", "getTotal", $data);
		$response = $this->blesta_response->response($return);
		if(!isset($response) || empty($response)) {
			$this->session->set_flashdata('error', 'Unable to get the total of the invoice in Blesta');
			redirect('setup/payment','refresh');	
		} else {
			return $response;
		}
	}
	
	private function payments_processPayment($data) {
		$return = $this->blesta_api->get("payments", "processPayment", $data);
		$response = $this->blesta_response->response($return);
		if(!isset($response) || empty($response)) {
			$this->session->set_flashdata('error', 'Transaction was unable to be completed.');
			redirect('setup/payment','refresh');	
		} else {
			$transaction_array = array(
				'transaction_status' => $response->status,
				'transaction_id' => $response->id
			);
			return $transaction_array;
		}
	}
	
	private function clients_get($data) {
		$return = $this->blesta_api->get("clients", "get", $data);
		$response = $this->blesta_response->response($return);
		if(!isset($response) || empty($response)) {
			$this->session->set_flashdata('error', 'Could not retrieve client by client_id.');
			redirect('setup/payment','refresh');	
		} else {
			return $response->contact_id;
		}
	}
	
	private function accounts_addCc($data) {
		$return = $this->blesta_api->get("accounts", "addCc", $data);
		$response = $this->blesta_response->response($return);
		if(!isset($response) || empty($response)) {
			$this->session->set_flashdata('error', 'Credit card account could not be created.');
			redirect('setup/payment','refresh');	
		} else {
			return $response;
		}
	}
	
	private function clients_addDebitAccount($data) {
		$return = $this->blesta_api->get("clients", "addDebitAccount", $data);
		$response = $this->blesta_response->response($return);
	}
		
	private function clients_setSetting($data) {
		$return = $this->blesta_api->get("clients", "setSetting", $data);
		$response = $this->blesta_response->response($return);
	}
}