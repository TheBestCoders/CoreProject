<?php

$page_title = "Registration";
$controller = "AuthController";
$add_js = 'validation.js';

require(dirname(__FILE__).'/include/header.php');

?>

<div class="main_heading">Get connected with the world!</div>

<div class="content_area">
<?php echo Core::getErrors(); ?>

<form name="registration" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="back" value="<?php echo Core::getValue('back', ''); ?>" />
<div class="registration_form">

<div class="form_block add_next">
<label>First Name</label>
<input type="text" name="firstname" />
</div>

<div class="form_block no_next">
<label>Last Name</label>
<input type="text" name="lastname" />
</div>

<div class="form_block">
<label>Email</label>
<input type="text" name="email" />
</div>

<div class="form_block add_next">
<label>Password</label>
<input type="password" name="password" />
</div>

<div class="form_block no_next">
<label>Confirm Password</label>
<input type="password" name="con_password" />
</div>

<div class="form_block add_next">
<label>City/State</label>
<input type="text" name="city" />
</div>

<div class="form_block no_next">
<label>Country</label>
<select name="country">
<?php echo getCountryList(); ?>
</select>
</div>

<div class="form_block_submit">
<input type="submit" name="user_registration" class="button submit trans" value="Register Me!" /> or <a href="<?php echo $link->getPageLink('login.php'); ?>">Login Here</a>
</div>


</div>
</form>


</div>


<script type="text/javascript">
var frmvalidator  = new Validator("registration"); // form name

frmvalidator.addValidation("firstname","req","Please enter your first name.");
frmvalidator.addValidation("lastname","req","Please enter your last name.");
frmvalidator.addValidation("email","req","Please enter your email.");
frmvalidator.addValidation("email","email","Please enter a valid email.");
frmvalidator.addValidation("password","req","Please enter your password.");
frmvalidator.addValidation("con_password","req","Please enter your confirm password.");
frmvalidator.addValidation("con_password","eqelmnt=password","Confirm password must be same as password.");
frmvalidator.addValidation("city","req","Please enter your city/state.");
frmvalidator.addValidation("country","dontselect=0","Please select your country.");
//frmvalidator.addValidation("captcha","req","Please enter captcha text.");
</script>


<?php require(dirname(__FILE__).'/include/footer.php'); ?>