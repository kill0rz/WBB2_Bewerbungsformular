"use strict";

var addOption = function() {
	document.getElementById("fieldcontent").value += "\n<option></option>";
};

var toggleVisibility = function() {
	var e = document.getElementById("fieldtype");
	var currval = e.options[e.selectedIndex].value;
	if (currval === "1") {
		document.getElementById('dropdownoptionsbox').style.visibility = 'visible';
	} else {
		document.getElementById('dropdownoptionsbox').style.visibility = 'hidden';
	}
};
