<?php

$page_title = "Template Demo Page"; // title of the page.
$controller = "MyAccountController.php"; // controller of the page which handle post back functionalities. File will stored in include/controller directory.
$auth = false; // if set to true, that means that this page needed login before use.
$add_js = 'test.js'; // to add addition js files. It can be in array also. Path will automatically assigned.
$add_css = array('test.css', 'abc.css'); // to add addition css files. It can be in array also. Path will automatically assigned.

require(dirname(__FILE__).'/include/header.php');

?>



<h1>Created by: Alauddin Ansari</h1>



<?php
require(dirname(__FILE__).'/include/footer.php');
?>