<div class="main_heading">Login Here!</div>

<div class="content">

<?php echo Core::getErrors(); ?>

<form name="login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	
    <input type="hidden" name="back" value="<?php echo Core::getValue('back', ''); ?>" />
	<div class="form_block">
    <label>Email</label>
    <input type="text" name="email" value="<?php echo Core::getValue('email', ''); ?>" />
    </div>
    
    <div class="form_block">
    <label>Password</label>
    <input type="password" name="password" />
    </div>
    
    <div class="form_block">
    Forgot your password? <a href="<?php echo $this->link->getPageLink('forgot_password.php'); ?>">Retrive Here</a> or 
    Don't have account? <a href="<?php echo $this->link->getPageLink('registration.php'); ?>">Register Here</a>
    </div>
    
    <div class="form_block_submit">
    <input type="submit" name="user_login" class="button submit trans" value="Login Me!" />
    </div>
    
</form>

</div>



<script type="text/javascript">

var frmvalidator  = new Validator("login"); // form name

frmvalidator.addValidation("email","req","Please enter your email.");
frmvalidator.addValidation("password","req","Please enter your password.");

</script>