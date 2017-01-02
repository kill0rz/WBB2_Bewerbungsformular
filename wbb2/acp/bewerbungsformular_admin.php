<?php

/* Bewerbungsformular v1.0 by kill0rz (C) 2017 */
/* for WBB 2.3.6 /*
/* kill0rz.com */

require './global.php';
require './lib/class_parse.php';
require './lib/class_parsecode.php';
require './wm2018_gameids.php';

if (!checkAdminPermissions("a_can_bewerbungsformular_edit")) {
	access_error(1);
}

$lang->load("ACP_BEWERBFRM,MISC,POSTINGS");

if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];
} else {
	$action = "info";
}

switch ($action) {
	case 'info':
		eval("\$tpl->output(\"" . $tpl->get('bewerbungsformular_info', 1) . "\");");
		break;

	default:
		# code...
		break;
}