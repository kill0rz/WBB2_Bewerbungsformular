<?xml version="1.0" encoding="{$lang->items['LANG_GLOBAL_ENCODING']}"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$lang->items['LANG_GLOBAL_DIRECTION']}" lang="{$lang->items['LANG_GLOBAL_LANGCODE']}" xml:lang="{$lang->items['LANG_GLOBAL_LANGCODE']}">

<head>
	<title>$master_board_name | {$lang->items['LANG_BEWERBFRM_TITLE']}</title>
	$headinclude
</head>

<body>
	$header
	<table cellpadding="{$style['tableincellpadding']}" cellspacing="{$style['tableincellspacing']}" border="{$style['tableinborder']}" style="width:{$style['tableinwidth']}" class="tableinborder">
		<tr>
			<td class="tablea">
				<table cellpadding="0" cellspacing="0" border="0" style="width:100%">
					<tr class="tablea_fc">
						<td align="left">
							<span class="smallfont"><b><a href="index.php{$SID_ARG_1ST}">$master_board_name</a> &raquo; {$lang->items['LANG_BEWERBFRM_TITLE']}</b>
							</span>
						</td>
						<td align="right">
							<span class="smallfont">
								<b>$usercbar</b>
							</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<table cellpadding="4" cellspacing="1" border="0" style="width:95%" class="tableinborder">
				<tr class="tabletitle">
					<td colspan="4" align="center">
						<font size='5'><b>Willkommen beim Retter-Radio</b></font>
					</td>
				</tr>
				<tr>
					<td class="tablea" align="left" width="50%">
						<span class="smallfont">
							test
						</span>
					</td>
					<td class="tablea" align="center" width="50%">
						test2
					</td>
				</tr>
				<tr align="left">
					<td class="tablea" align="center" colspan="2">
						<span class="normalfont">
							<button type="button" onclick="location.href='./bewerbungsformular.php?page=1';">Weiter</button>
						</span>
					</td>
				</tr>
				<tr>
					<td class="tabletitle" align="right" colspan="4">
						<span class="smallfont"><b>Bewerbungsformular 1.0 &copy; 2017 by <a href='http://kill0rz.com/' target='_blank'>kill0rz</a></b></span>
					</td>
				</tr>
			</table>
		</tr>
	</table>
	$footer
