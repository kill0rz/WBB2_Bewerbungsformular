<?php

require './global.php';
require './acp/lib/config.inc.php';
require './acp/lib/class_parse.php';
require './acp/lib/options.inc.php';

if (isset($_REQUEST['page'])) {
	$page = intval($_REQUEST['page']);
} else {
	$page = 1;
}

$sql_query = "SELECT page FROM bb" . $n . "_bewerbungsformular_fields WHERE page='" . $page . "'ORDER BY ID ASC;";
$result = $db->query($sql_query);
while ($row = $db->fetch_array($result)) {
	$id = intval($row['ID']);
	$fieldcontent = htmlentities($row['fieldcontent']);
	eval("\$bewerbungsformular_field_bit .= \"" . $tpl->get('bewerbungsformular_field_bit', 1) . "\";");
}

eval("\$tpl->output(\"" . $tpl->get("bewerbungsformular") . "\");");