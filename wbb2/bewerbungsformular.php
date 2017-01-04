<?php

require './global.php';
require './acp/lib/config.inc.php';
require './acp/lib/class_parse.php';
require './acp/lib/options.inc.php';

eval("\$tpl->output(\"" . $tpl->get("bewerbungsformular") . "\");");