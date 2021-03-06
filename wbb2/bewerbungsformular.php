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
	if (isset($_POST['sendfield']) && count($_POST['sendfield']) > 0) {
		foreach ($_POST['sendfield'] as $key => $value) {
			$hiddenfields .= "<input type='hidden' name='sendfield[{$key}]' value='{$value}' />\n";
		}
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

		// 1) check all params set in post
		$sql_query = "SELECT COUNT(ID) AS Anzahl FROM bb" . $n . "_bewerbungsformular_fields WHERE required='1';";
		$count_fields = $db->query_first($sql_query);
		// 2) send all data via mail
		$mailtext = "Hallo, \n\nes gibt eine neue Bewerbung!\n\n\nFolgende Angaben wurden gemacht:\n";
		$subject = "Neue Bewerbung im Forum!";
		foreach ($_POST['sendfield'] as $key => $value) {
			$mailtext .= $key . ": " . htmlspecialchars($value, ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1') . "\n";
		}

		//mail versenden
		$mime_boundary = "-----=" . md5(uniqid(mt_rand(), 1));

		$mail_header = "From:" . $adminmail . "<" . $adminmail . ">\n";
		$mail_header .= "Reply-To: " . $adminmail . "\n";

		$mail_header .= "MIME-Version: 1.0\r\n";
		$mail_header .= "Content-Type: multipart/mixed;\r\n";
		$mail_header .= " boundary=\"" . $mime_boundary . "\"\r\n";

		$mail_content = "This is a multi-part message in MIME format.\r\n\r\n";
		$mail_content .= "--" . $mime_boundary . "\r\n";
		$mail_content .= "Content-Type: text/html charset=\"iso-8859-1\"\r\n";
		$mail_content .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
		$mail_content .= $mailtext . "\r\n";

		// todo: die konfig muss irgendwo herkommen
		include './bewerbungsformular_config.php';
		if (mail($mail_to_me, $subject, $mail_content, $mail_header)) {
			$success = $lang->items["LANG_BEWERBFRM_INDEX_3"];
			eval("\$tpl->output(\"" . $tpl->get("bewerbungsformular_success") . "\");");
			die();
		} else {
			$error = $lang->items["LANG_BEWERBFRM_INDEX_2"];
			eval("\$tpl->output(\"" . $tpl->get("bewerbungsformular_error") . "\");");
			die();
		}
	} else {
		// Im Formular
		$sql_query = "SELECT * FROM bb" . $n . "_bewerbungsformular_fields WHERE page='" . $page . "'ORDER BY ID ASC;";
		$result = $db->query($sql_query);
		while ($row = $db->fetch_array($result)) {
			$id = intval($row['ID']);
			$tupelclass1 = getone($count++, "tablea", "tableb");
			$tupelclass2 = getone($count++, "tablea", "tableb");
			$fieldname = htmlspecialchars($row['fieldname'], ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1');

			if ($row['required'] == 1) {
				$required = "required";
			} else {
				$required = "";
			}

			switch (intval($row['fieldtype'])) {
				case 1:
					// Dropdown
					$dropdown_options = explode("\n", $row['fieldcontent']);
					$fieldcontent = "<select name='sendfield[{$id}]' {$required}>\n";
					foreach ($dropdown_options as $dropdown_option) {
						$fieldcontent .= "<option>" . htmlspecialchars(str_replace("\n", '', $dropdown_option), ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1') . "</option>\n";
					}
					$fieldcontent .= "</select>\n";
					break;

				case 2:
					// Number
					$fieldcontent = "<input type='number' value='" . intval($row['fieldcontent']) . "' name='sendfield[{$id}]' {$required}>\n";
					break;

				case 3:
					// Text
					$fieldcontent = "<input type='text' value='" . htmlspecialchars($row['fieldcontent'], ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1') . "' name='sendfield[{$id}]' {$required}>\n";
					break;

				case 4:
					// Textarea
					$fieldcontent = "<textarea name='sendfield[{$id}]' {$required}>" . htmlspecialchars($row['fieldcontent'], ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1') . "</textarea>\n";
					break;

				case 5:
					// Checkboxen
					$checkbox_options = explode("\n", $row['fieldcontent']);
					$fieldcontent = "<fieldset>\n";
					foreach ($checkbox_options as $checkbox_option) {
						$fieldcontent .= "<input name='sendfield[{$id}][]' type='checkbox' " . htmlspecialchars(str_replace("\n", '', $checkbox_option), ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1') . " value='" . htmlspecialchars(str_replace("\n", '', $checkbox_option), ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1') . "'> " . htmlspecialchars(str_replace("\n", '', $checkbox_option), ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1') . "<br />\n";
					}
					$fieldcontent .= "</fieldset>\n";
					break;

				case 6:
					// E-Mail
					$fieldcontent = "<input type='email' value='" . htmlspecialchars($row['fieldcontent'], ENT_NOQUOTES | ENT_HTML401, 'ISO-8859-1') . "' name='sendfield[{$id}]' {$required}>\n";
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