<?php
$page_title = "Retrive Password";
$controller = 'ForgotPwdController';
$add_js = 'validation.js';
require(dirname(__FILE__).'/include/header.php');
?>

<div class="main_heading">Retrive Password!</div>

<div class="content_area">
<?php echo Core::getErrors(); ?>

<?php if($password_send){ ?>

<div class="msg"><?php echo 'Reset password link has been sent to your email id: '.$email; ?></div>

<?php } elseif($password_reset){ ?>

<div class="msg"><?php echo 'You have successfully reset your password. <a href="'.$link->getPageLink('login.php?email='.$email).'">Click here</a> to login.'; ?></div>

<?php } elseif($reset_mode) { ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" name="reset_password">
<input type="hidden" name="reset" value="<?php echo $reset; ?>" />

<div class="form_block">
<label>Email</label>
<input type="text" name="email" value="<?php echo $email; ?>" />
</div>

<div class="form_block">
<label>New Password</label>
<input name="n_password" id="n_password" type="password" value="" />
</div>

<div class="form_block">
<label>Re-type Password</label>
<input name="c_password" id="c_password" type="password" value="" />
</div>

<div class="form_block_submit">
<input type="submit" name="reset_password" class="button submit trans" value="Reset My Password" />
</div>

</form>

<script type="text/javascript">
var frmvalidator  = new Validator("reset_password"); // form name

frmvalidator.addValidation("email","req","Enter your email.");
frmvalidator.addValidation("email","email","Enter your valid email.");
frmvalidator.addValidation("n_password","req","Enter your new password.");
frmvalidator.addValidation("c_password","req","Please re-type your password.");
frmvalidator.addValidation("c_password","eqelmnt=n_password","Both passwords must be same.");
</script>

<?php } else { ?>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="forgot_password">

<div class="form_block">
<label>Email</label>
<input type="text" name="email" value="" />
</div>

<div class="form_block_submit">
<input type="submit" name="forgot_password" class="button submit trans" value="Retrive Password" />
</div>

</form>

<script type="text/javascript">
var frmvalidator  = new Validator("forgot_password"); // form name

frmvalidator.addValidation("email","req","Enter your email.");
frmvalidator.addValidation("email","email","Enter your valid email.");

</script>

<?php } ?>

</div>

<?php require(dirname(__FILE__).'/include/footer.php'); ?>