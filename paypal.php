<?php
require(dirname(__FILE__).'/include/init.php');

$currency = 'CAD';
$setAmount = Core::getValue('amount', 59.99);
//$setAmount = 1;	
$link = new Link();
?>

<?php
/*  PHP Paypal IPN Integration Class Demonstration File
*  4.16.2005 - Micah Carrick, email@micahcarrick.com
*
*  This file demonstrates the usage of paypal.class.php, a class designed  
*  to aid in the interfacing between your website, paypal, and the instant
*  payment notification (IPN) interface.  This single file serves as 4 
*  virtual pages depending on the "action" varialble passed in the URL. It's
*  the processing page which processes form data being submitted to paypal, it
*  is the page paypal returns a user to upon success, it's the page paypal
*  returns a user to upon canceling an order, and finally, it's the page that
*  handles the IPN request from Paypal.
*
*  I tried to comment this file, aswell as the acutall class file, as well as
*  I possibly could.  Please email me with questions, comments, and suggestions.
*  See the header of paypal.class.php for additional resources and information.
*/

// Setup class
$p = new Paypal;             // initiate an instance of the class
$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
//$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url



$getUser = new User($user_id);
//print_r($getUser);
$secret_key = base64_encode(session_id().$getUser->user_id);

$app_id = Core::getValue('app_id', 0);

// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'process';  

switch ($_GET['action']) {
	case 'process':      // Process and order...
		
		// There should be no output at this point.  To process the POST data,
		// the submit_paypal_post() function will output all the HTML tags which
		// contains a FORM which is submited instantaneously using the BODY onload
		// attribute.  In other words, don't echo or printf anything when you're
		// going to be calling the submit_paypal_post() function.
		
		// This is where you would have your form validation  and all that jazz.
		// You would take your POST vars and load them into the class like below,
		// only using the POST values instead of constant string expressions.
		
		// For example, after ensureing all the POST variables from your custom
		// order form are valid, you might have:
		//
		
		$_SESSION[_COOKIE_KEY_]['paypal']['app_id'] = $app_id;
		$_SESSION[_COOKIE_KEY_]['paypal']['user_id'] = $getUser->user_id;
		
		$p->add_field('business', 'wonder_1322222006_biz@yahoo.co.in');
		$p->add_field('user_id', $getUser->user_id);
		$p->add_field('full_name', $getUser->fullname);
		$p->add_field('email', $getUser->email);
		$p->add_field('app_id', $app_id);
		$p->add_field('return', $link->self('action=success&key='.$secret_key));
		$p->add_field('cancel_return', $link->self('action=cancel&key='.$app_id));
		$p->add_field('currency_code', $currency);
		
		$p->add_field('notify_url', $link->self('action=ipn&key='.$secret_key));
		$p->add_field('item_name', 'Make New Auction');
		
		$p->add_field('amount', $setAmount);
		
		/*echo '<pre>';
		print_r($p);
		exit;*/
		$p->submit_paypal_post(); // submit the fields to paypal
		//$p->dump_fields();      // for debugging, output a table of all the fields
	break;
	
	case 'success':      // Order was successful...
		
		// This is where you would probably want to thank the user for their order
		// or what have you.  The order information at this point is in POST 
		// variables.  However, you don't want to "process" the order until you
		// get validation from the IPN.  That's where you would have the code to
		// email an admin, update the database with payment status, activate a
		// membership, etc.  
		
		/** QUERY TO UPDATE THE PAYMENT STATUS **/
		$app_id = (int)$_SESSION[_COOKIE_KEY_]['paypal']['app_id'];
		
		$amount_paid = $_REQUEST['mc_gross'];
		$pay_status = $_REQUEST['payment_status'];
		
		$key = $_GET['key'];
		if($key==$secret_key){
			$now = date('Y-m-d H:i:s');
			
			Db::getInstance()->autoExecute(_PAYMENT_, array('mode' => 'Paypal', 'app_id' => $app_id, 'currency'=>$currency, 'amount' => $amount_paid, 'status'=>$pay_status, 'payment_date'=>$now), 'INSERT');
			$pay_id = Db::getInstance()->insertID();
			
			
			echo '<script type="text/javascript">window.location.href="'.$link->getPageLink('make_new_app.php?payment=true&status='.$pay_status.'&pay_id='.$pay_id.'&key='.$secret_key.'&app_id='.$app_id).'";</script>';
            //Core::redirect('make_new_app.php?payment=true&status='.$pay_status.'&pay_id='.$pay_id.'&key='.$secret_key.'&app_id='.$app_id);
		}
		
	break;
	
	case 'cancel':       // Order was canceled...
		
		/** QUERY TO UPDATE THE PAYMENT STATUS **/
		
		Core::redirect($link->getPageLink('my_application.php?app_status=payment_cancelled&value='.Core::getValue('key')));
		
	break;
	case 'ipn':          // Paypal is calling page for IPN validation...
		
		// It's important to remember that paypal calling this script.  There
		// is no output here.  This is where you validate the IPN data and if it's
		// valid, update your database to signify that the user has payed.  If
		// you try and use an echo or printf function here it's not going to do you
		// a bit of good.  This is on the "backend".  That is why, by default, the
		// class logs all IPN data to a text file.
		
		if ($p->validate_ipn()) {
		
		// Payment has been recieved and IPN is verified.  This is where you
		// update your database to activate or process the order, or setup
		// the database with the user's order details, email an administrator,
		// etc.  You can access a slew of information via the ipn_data() array.
		
		// Check the paypal documentation for specifics on what information
		// is available in the IPN POST variables.  Basically, all the POST vars
		// which paypal sends, which we send back for validation, are now stored
		// in the ipn_data() array.
		
		
		}
	break;
}     



?>
