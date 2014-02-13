<?php

// Developers functions
function getCountryList($all=false){
	$_lists = '<option value="0" title="Select Country">Select Country</option>';
	$full = '';
	
	if(!$all)
		$full = ' WHERE un_member = "yes"';
		
	$countries = Db::getInstance()->select('SELECT country_id, short_name, long_name FROM '.country.' '.$full);
	foreach($countries as $country){
		$_lists .= '<option value="'.$country['country_id'].'" title="'.$country['long_name'].'">'.$country['short_name'].'</option>';
	}
	return $_lists;
}

function getBreadCrumbs($showAccountLinks = true, $showPageTitle = true, $pre_links = array(), $additional_text = '')
{
	global $lang, $user_id, $page_title;
	
	$sep = '<span class="sep">&raquo;</span> ';
	$link = new Link();
	
	$_breadcrumbs = '<ul class="breadcrumbs">';
	$_breadcrumbs .= '<li><a href="'.$link->getPageLink('index.php').'">Home</a>'.$sep.'</li>';
	if($user_id && $showAccountLinks){
		$_breadcrumbs .= '<li><a href="'.$link->getPageLink('my_account.php').'">My Account</a>'.$sep.'</li>';
	}
	if(count($pre_links>0) && !empty($pre_links)){
		if(isset($pre_links[0])){
			foreach($pre_links as $link){
				$_breadcrumbs .= '<li><a href="'.$link->getPageLink($link['link']).'">'.$link['name'].'</a>'.$sep.'</li>';
			}
		} else {
			$_breadcrumbs .= '<li><a href="'.$link->getPageLink($pre_links['link']).'">'.$pre_links['name'].'</a>'.$sep.'</li>';
		}
	}
	if($showPageTitle)
		$_breadcrumbs .= '<li>'.$page_title.' '.$additional_text.'</li>';
	
	$_breadcrumbs .= '</ul>';
	return $_breadcrumbs;
}


?>