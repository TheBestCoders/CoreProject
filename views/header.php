<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="<?php echo $meta_keyword; ?>" />
	<meta name="description" content="<?php echo $meta_description; ?>" />
	<title><?php echo $meta_title; ?></title>
	<script type="text/javascript">
	var base_url = '<?php echo $base_url; ?>';
	</script>
	
<?php
	foreach($css_files as $css) echo '<link rel="stylesheet" type="text/css" href="'.$css.'" />';
	foreach($js_files as $js) echo '<script type="text/javascript" src="'.$js.'"></script>';
?>
	
</head>
<body>

<div class="main_container">

<div class="header">
<div class="logo"><a href="<?php echo $base_url; ?>">Hello World</a></div>
<div class="header_right">
	<div class="user_info">
	<?php if($this->session->user_id): ?>
		<a href="<?php echo $this->link->getPageLink('logout.php'); ?>">Logout Me</a>
	<?php else: ?>
		<a href="<?php echo $this->link->getPageLink('login.php'); ?>">Login Here</a> |
		<a href="<?php echo $this->link->getPageLink('registration.php'); ?>">Register Yourself</a>
	<?php endif; ?>
	</div>

	<div class="language">
		Languages: 
		<a href="<?php echo $this->link->self('lang=en'); ?>" <?php echo ($lang_id == 1 ? 'class="active"' : ''); ?>>En</a>
		<a href="<?php echo $this->link->self('lang=fr'); ?>" <?php echo ($lang_id == 2 ? 'class="active"' : ''); ?>>Fr</a>
	</div>
</div>
<div class="clear">&nbsp;</div>
</div><!-- header ends here -->

<!-- jquery menu -->
<div class="coremenu" id="coremenuid">
<ul>
<li><a href="index.php">HOME</a></li>
<li><a href="#">ABOUT US</a>
	<ul>
	<li><a href="#">BRIEF ABOUT US</a></li>
	<li><a href="#">COMMITMENT</a></li>
	</ul>
</li>
<li><a href="#">PRODUCTS</a>
	<ul>
	<li><a href="#">PRODUCT1</a></li>
	<li><a href="#">PRODUCT2</a>
		<ul>
		<li><a href="#">Product 2.1</a></li>
		<li><a href="#">Product 2.2</a></li>
		</ul>
	</li>
	<li><a href="#">PRODUCT3</a></li>
	</ul>
</li>
</ul>
</div>
<!-- jquery menu ends here -->



<div class="clear white_space">&nbsp;</div>

<?php //echo getBreadCrumbs(); ?>

<div class="clear white_space">&nbsp;</div>
<!-- content area -->
<div class="content_area">

<div class="left_container width100">

<?php echo Core::getErrors(); ?>