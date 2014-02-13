<?php

$page_title = "My Account";
$controller = "MyAccountController.php";
$auth = true;
$add_js = 'gen_validation.js';

require(dirname(__FILE__).'/include/header.php');
?>



<?php if(!$edit) { ?>
<table border="0" cellspacing="0" cellpadding="0" class="normal_table grid">
  <tr>
    <td>First Name :</td>
    <td><?php echo l($user->firstname); ?></td>
  </tr>
  <tr>
    <td>Last Name :</td>
    <td><?php echo l($user->lastname); ?></td>
  </tr>
  <tr>
    <td>Email :</td>
    <td style="color:#06F;"><?php echo $user->email; ?></td>
  </tr>
  <tr>
    <td>Last Login :</td>
    <td><?php echo date('d M, Y', strtotime($user->last_login)); ?></td>
  </tr>
  <tr>
    <td colspan="2"><a href="<?php echo $link->self('action=edit'); ?>" class="button">Edit Profile</a>
    <a href="<?php echo $link->self('action=chgpsw'); ?>" class="button">Change Password</a></td>
  </tr>
</table>

<?php } else { 

	if($editpwd){
?>
<form name="changepsd" method="post" action="<?php echo $link->self(); ?>">
<input type="hidden" name="chgpsw" value="1" />
<table width="600" border="0" cellspacing="0" cellpadding="0" class="normal_table">
  <tr>
    <td width="150"><?php echo l(current_password); ?></td>
    <td><input type="password" name="c_password" value="" /></td>
  </tr>
  <tr>
    <td><?php echo l(new_password); ?></td>
    <td><input type="password" name="n_password" value="" /></td>
  </tr>
  <tr>
    <td><?php echo l(confirm_password); ?></td>
    <td><input type="password" name="r_password" value="" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="edit_password" value="<?php echo l(update); ?>" class="button" />
    <a href="<?php echo $link->self(); ?>" class="button"><?php echo l(cancel); ?></a>
    </td>
  </tr>
</table>

</form>

<script type="text/javascript">
var frmvalidator  = new Validator("changepsd"); // form name

frmvalidator.addValidation("c_password","req","Please enter your current password.");
frmvalidator.addValidation("n_password","req","Please enter your new password.");
frmvalidator.addValidation("r_password","req","Please enter new password once again.");
frmvalidator.addValidation("r_password","eqelmnt=n_password","Re-password must be same as new password.");
</script>


<?php } else { ?>
<form method="post" action="<?php echo $link->self(); ?>">
<input type="hidden" name="edit" value="1" />
<table width="400" border="0" cellspacing="0" cellpadding="0" class="normal_table">
  <tr>
    <td><?php echo l(FIRST_NAME); ?></td>
    <td><input type="text" name="firstname" value="<?php echo $user->firstname; ?>" /></td>
  </tr>
  <tr>
    <td><?php echo l(LAST_NAME); ?></td>
    <td><input type="text" name="lastname" value="<?php echo $user->lastname; ?>" /></td>
  </tr>
  <tr>
    <td><?php echo l(email); ?></td>
    <td><input type="text" name="email" value="<?php echo $user->email; ?>" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="edit_user" value="<?php echo l(update); ?>" class="button" /></td>
  </tr>
</table>
</form>
<?php }} ?>



<?php
require(dirname(__FILE__).'/include/footer.php');
?>