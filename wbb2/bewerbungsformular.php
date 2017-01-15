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

function get_next_page() {
	global $db, $n;
	$sql = "SELECT page FROM bb" . $n . "_bewerbungsformular_fields GROUP BY page ORDER BY page ASC, ID ASC;";

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

$lang->load("BEWERBFRM");
$bewerbungsformular_options_db = $db->query_first("SELECT * FROM bb" . $n . "_bewerbungsformular_options;");
$count = 0;

// save data tmp
if ($page > 1) {
	$hiddenfields = '';
	foreach ($_POST['sendfield'] as $key => $value) {
		$hiddenfields .= "<input type='hidden' name='sendfield[{$key}]' value='{$value}' />\n";
	}
}

// check if Bewerbungsformular is online
if ($bewerbungsformular_options_db['isonline'] == 1) {
	if ($page == 0) {
		// Startseite
		$tupelclass1 = getone($count++, "tablea", "tableb");
		$tupelclass2 = getone($count++, "tablea", "tableb");
		$fieldname = $bewerbungsformular_options_db['startpage_left'];
		$fieldcontent = $bewerbungsformular_options_db['startpage_right'];
		eval("\$bewerbungsformular_field_bit .= \"" . $tpl->get('bewerbungsformular_field_bit') . "\";");
	} elseif ($page == get_next_page()) {
		// last page

		// 1) check all params set in cookie
		// 	anzahl felder == count($_SESSION['bewerbungsformular_savedata'])
		$sql_query = "SELECT COUNT(ID) AS Anzahl FROM bb" . $n . "_bewerbungsformular_fields;";
		$count_fields = $db->query_first($sql_query);
		if ($count_fields['Anzahl'] == count($_SESSION['bewerbungsformular_savedata'])) {
			echo "k";
		} else {
			echo $count_fields['Anzahl'] . " - " . count($_SESSION['bewerbungsformular_savedata']);
			print_r($_SESSION['bewerbungsformular_savedata']);
		}

		// 2) save all data to db, to file, mail etc.
	} else {
		// Im Formular
		$sql_query = "SELECT * FROM bb" . $n . "_bewerbungsformular_fields WHERE page='" . $page . "'ORDER BY ID ASC;";
		$result = $db->query($sql_query);
		while ($row = $db->fetch_array($result)) {
			$id = intval($row['ID']);
			$tupelclass1 = getone($count++, "tablea", "tableb");
			$tupelclass2 = getone($count++, "tablea", "tableb");
			$fieldname = htmlentities($row['fieldname']);

			switch (intval($row['fieldtype'])) {
				case 1:
					// Dropdown
					$dropdown_options = explode("\n", $row['fieldcontent']);
					$fieldcontent = "<select name='sendfield[{$id}]'>\n";
					foreach ($dropdown_options as $dropdown_option) {
						$fieldcontent .= "<option>" . htmlentities(str_replace("\n", '', $dropdown_option)) . "</option>\n";
					}
					$fieldcontent .= "</select>\n";
					break;

				case 2:
					// Number
					$fieldcontent = "<input type='number' value='" . intval($row['fieldcontent']) . "' name='sendfield[{$id}]'>\n";
					break;

				case 3:
					// Text
					$fieldcontent = "<input type='text' value='" . htmlentities($row['fieldcontent']) . "' name='sendfield[{$id}]'>\n";
					break;

				case 4:
					// Textarea
					$fieldcontent = "<textarea name='sendfield[{$id}]'>" . htmlentities($row['fieldcontent']) . "</textarea>\n";
					break;

				case 5:
					// Checkboxen
					$checkbox_options = explode("\n", $row['fieldcontent']);
					$fieldcontent = "<fieldset>\n";
					foreach ($checkbox_options as $checkbox_option) {
						$fieldcontent .= "<input name='sendfield[{$id}][]' type='checkbox' " . htmlentities(str_replace("\n", '', $checkbox_option)) . " value='" . htmlentities(str_replace("\n", '', $checkbox_option)) . "'> " . htmlentities(str_replace("\n", '', $checkbox_option)) . "<br />\n";
					}
					$fieldcontent .= "</fieldset>\n";
					break;

				default:
					# code...
					break;
			}
			eval("\$bewerbungsformular_field_bit .= \"" . $tpl->get('bewerbungsformular_field_bit') . "\";");
		}
	}
	$nextpage = $page + 1;
	eval("\$tpl->output(\"" . $tpl->get("bewerbungsformular") . "\");");
} else {
	// Fehlerdialog
	eval("\$tpl->output(\"" . $tpl->get("bewerbungsformular_isoffline") . "\");");

}