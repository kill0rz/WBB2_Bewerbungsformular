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

	case 'editfield':
		if (isset($_GET['fieldid']) && trim($_GET['fieldid']) != '') {
			// open field editor
			$fieldid = intval(trim($_GET['fieldid']));

			$bewerbungsformular_options_bit_var = '';
			$sql = "SELECT f.*,ft.typename FROM bb" . $n . "_bewerbungsformular_fields f JOIN bb" . $n . "_bewerbungsformular_fieldtypes ft ON ft.ID=f.fieldtype WHERE f.ID='" . mysqli_real_escape_string($db->link_id, $fieldid) . "' LIMIT 1;";
			$result = $db->query($sql);
			if ($db->num_rows($result) > 0) {
				$savefield_page_options = '';
				$savefield_fieldtype_options = '';

				while ($row = $db->fetch_array($result)) {
					// page
					for ($page = 1; $page <= get_next_page(); $page++) {
						if ($page == intval($row['page'])) {
							$savefield_page_options .= "<option value='{$page}' selected>{$page}</option>\n";
						} else {
							$savefield_page_options .= "<option value='{$page}'>{$page}</option>\n";
						}
					}

					// fieltype
					$sql2 = "SELECT * FROM bb" . $n . "_bewerbungsformular_fieldtypes;";
					$result2 = $db->query($sql2);
					while ($row2 = $db->fetch_array($result2)) {
						if ($row2['ID'] == $row['fieldtype']) {
							$savefield_fieldtype_options .= "<option value='{$row2["ID"]}' selected>{$row2['typename']}</option>\n";
						} else {
							$savefield_fieldtype_options .= "<option value='{$row2["ID"]}'>{$row2['typename']}</option>\n";
						}
					}

					eval("\$bewerbungsformular_editfield_bit .= \"" . $tpl->get('bewerbungsformular_editfield_bit', 1) . "\";");
				}

				eval("\$lang->items['LANG_ACP_BEWERBFRM_TPL_EDITFIELD_1'] = \"" . $lang->get4eval("LANG_ACP_BEWERBFRM_TPL_EDITFIELD_1") . "\";");

				eval("\$tpl->output(\"" . $tpl->get('bewerbungsformular_editfield', 1) . "\");");
			} else {
				// invalid field ID
				header("Location: bewerbungsformular_admin.php?action=options&sid={$session['hash']}");
			}
		} else {
			// params missing
			header("Location: bewerbungsformular_admin.php?action=options&sid={$session['hash']}");
		}
		break;

	case 'savefield':
		// check if all required fields are filled
		$error = '';
		if (!isset($_POST['savefield_page']) || trim($_POST['savefield_page']) == '') {
			$error .= $lang->items["LANG_ACP_BEWERBFRM_TPL_SAVEFIELD_ERROR_1"] . "<br />";
		}

		if (!isset($_POST['savefield_fieldname']) || trim($_POST['savefield_fieldname']) == '') {
			$error .= $lang->items["LANG_ACP_BEWERBFRM_TPL_SAVEFIELD_ERROR_2"] . "<br />";
		}

		if (!isset($_POST['savefield_fieldtype']) || trim($_POST['savefield_fieldtype']) == '') {
			$error .= $lang->items["LANG_ACP_BEWERBFRM_TPL_SAVEFIELD_ERROR_5"] . "<br />";
		}

		if (!isset($_POST['savefield_id']) || trim($_POST['savefield_id']) == '') {
			$error .= $lang->items["LANG_ACP_BEWERBFRM_TPL_SAVEFIELD_ERROR_4"] . "<br />";
		}

		if (!isset($_POST['formsub']) || trim($_POST['formsub']) != $lang->items['LANG_ACP_BEWERBFRM_TPL_EDITFIELD_5']) {
			$error .= $lang->items["LANG_ACP_BEWERBFRM_TPL_SAVEFIELD_ERROR_3"] . "<br />";
		}

		if ($error != '') {
			// Es gab einen Fehler
			eval("\$tpl->output(\"" . $tpl->get('bewerbungsformular_error', 1) . "\");");
		} else {
			// Speichern!
			$fieldcontent = (isset($_POST['savefield_fieldcontent']) && trim($_POST['savefield_fieldcontent']) != '') ? mysqli_real_escape_string($db->link_id, trim($_POST['savefield_fieldcontent'])) : '';
			$sql = "UPDATE bb" . $n . "_bewerbungsformular_fields SET fieldcontent='" . $fieldcontent . "', fieldname='" . mysqli_real_escape_string($db->link_id, trim($_POST['savefield_fieldname'])) . "', page='" . intval(trim($_POST['savefield_page'])) . "', fieldtype='" . intval(trim($_POST['savefield_fieldtype'])) . "' WHERE ID='" . intval(trim($_POST['savefield_id'])) . "';";
			$db->query($sql);

			header("Location: bewerbungsformular_admin.php?action=options&sid={$session['hash']}");
		}
		break;

	case 'delfield':
		$error = '';
		if (!isset($_GET['fieldid']) || trim($_GET['fieldid']) == '') {
			$error .= $lang->items["LANG_ACP_BEWERBFRM_TPL_SAVEFIELD_ERROR_4"] . "<br />";
		} else {
			$fieldid = intval(trim($_GET['fieldid']));
		}

		if ($error != '') {
			// Es gab einen Fehler
			eval("\$tpl->output(\"" . $tpl->get('bewerbungsformular_error', 1) . "\");");
		} else {
			if (isset($_POST['dodel']) && trim($_POST['dodel']) == 'true') {
				// go ahead and del it
				$sql = "DELETE FROM bb" . $n . "_bewerbungsformular_fields WHERE ID='" . mysqli_real_escape_string($db->link_id, $fieldid) . "'";
				$db->query($sql);

				header("Location: bewerbungsformular_admin.php?action=options&sid={$session['hash']}");

			} else {
				// ask user if field should really be deleted
				eval("\$lang->items['LANG_ACP_BEWERBFRM_TPL_DELFIELD_1'] = \"" . $lang->get4eval("LANG_ACP_BEWERBFRM_TPL_DELFIELD_1") . "\";");
				eval("\$tpl->output(\"" . $tpl->get('bewerbungsformular_delfield', 1) . "\");");
			}
		}
		break;

	default:
		eval("\$tpl->output(\"" . $tpl->get('bewerbungsformular_info', 1) . "\");");
		break;
}