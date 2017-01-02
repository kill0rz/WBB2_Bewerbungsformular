<?php

/* Bewerbungsformular v1.0 by kill0rz (C) 2017 */
/* for WBB 2.3.6 */
/* kill0rz.com */

require './global.php';
require './lib/class_parse.php';
require './lib/class_parsecode.php';

if (!checkAdminPermissions("a_can_bewerbungsformular_edit")) {
	access_error(1);
}

$lang->load("ACP_BEWERBFRM,MISC,POSTINGS");

if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];
} else {
	$action = "info";
}

function get_next_page() {
	global $db, $n;
	$sql = "SELECT page FROM bb" . $n . "_bewerbungsformular_fields GROUP BY page ORDER BY page;";
	$result = $db->query($sql);
	$pagecount = 0;
	while ($row = $db->fetch_array($result)) {
		if ($pagecount + 1 == $row['page']) {
			$pagecount++;
		} else {
			break;
		}
	}
	return $pagecount + 1;
}

switch ($action) {
	case 'options':
		$count = 0;
		$bewerbungsformular_options_bit_var = '';
		$sql = "SELECT * FROM bb" . $n . "_bewerbungsformular_fields ORDER BY page ASC, fieldtype ASC, fieldname ASC;";
		$result = $db->query($sql);
		while ($row = $db->fetch_array($result)) {
			$rowclass = getone($count++, "firstrow", "secondrow");
			eval("\$bewerbungsformular_options_bit_var .= \"" . $tpl->get('bewerbungsformular_options_bit', 1) . "\";");
		}

		eval("\$tpl->output(\"" . $tpl->get('bewerbungsformular_options', 1) . "\");");
		break;

	default:
		eval("\$tpl->output(\"" . $tpl->get('bewerbungsformular_info', 1) . "\");");
		break;
}