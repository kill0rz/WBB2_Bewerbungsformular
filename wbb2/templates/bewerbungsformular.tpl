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
			<form action="./bewerbungsformular.php?page={$nextpage}" method="post" accept-charset="utf-8">
				$hiddenfields
				<table cellpadding="4" cellspacing="1" border="0" style="width:95%" class="tableinborder">
					<tr class="tabletitle">
						<td colspan="4" align="center">
							<font size='5'><b>{$bewerbungsformular_options_db['startpage_title']}</b></font>
						</td>
					</tr>
					{$bewerbungsformular_field_bit}
					<tr align="left">
						<td class="tablea" align="center" colspan="2">
							<span class="normalfont">
								<input type="submit" value="{$lang->items['LANG_BEWERBFRM_WEITER']}" />
							</span>
						</td>
					</tr>
					<tr>
						<td class="tabletitle" align="right" colspan="4">
							<span class="smallfont"><b>{$lang->items['LANG_BEWERBFRM_COPYRIGHT']}</b></span>
						</td>
					</tr>
				</table>
			</form>
		</tr>
	</table>
	$footer
