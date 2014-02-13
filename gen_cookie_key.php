<?php

require(dirname(__FILE__).'/include/init.php');


echo md5(_BASE_URL_._SITE_NAME_);

echo '<br /><br />Paste this code to config.inc.php in _COOKIE_KEY_';

?>