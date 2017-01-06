<?php

require './global.php';
require './acp/lib/config.inc.php';
require './acp/lib/class_parse.php';
require './acp/lib/options.inc.php';

if (isset($_REQUEST['page'])) {
	$page = intval($_REQUEST['page']);
} else {
	$page = 0;
}

$lang->load("BEWERBFRM");
$bewerbungsformular_options_db = $db->query_first("SELECT * FROM bb" . $n . "_bewerbungsformular_options;");
$count = 0;

if ($page == 0) {
	// Startseite
	$rowclass = getone($count++, "firstrow", "secondrow");
	eval("\$bewerbungsformular_field_bit .= \"" . $tpl->get('bewerbungsformular_field_bit') . "\";");
} else {
	// Im Formular
	$sql_query = "SELECT page FROM bb" . $n . "_bewerbungsformular_fields WHERE page='" . $page . "'ORDER BY ID ASC;";
	$result = $db->query($sql_query);
	while ($row = $db->fetch_array($result)) {
		$id = intval($row['ID']);
		$rowclass = getone($count++, "firstrow", "secondrow");
		$fieldcontent = htmlentities($row['fieldcontent']);
		$fieldname = htmlentities($row['fieldname']);
		eval("\$bewerbungsformular_field_bit .= \"" . $tpl->get('bewerbungsformular_field_bit') . "\";");
	}
}
eval("\$tpl->output(\"" . $tpl->get("bewerbungsformular") . "\");");