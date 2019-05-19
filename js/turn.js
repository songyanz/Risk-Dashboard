function Turnto(page, tag)
{
	var i = 1;
	var el;
	for (var i = 1; i < 24; i++) {
		if (i == page) {
			document.getElementById(tag + i).style.display = 'block';
		}else {
			document.getElementById(tag + i).style.display = 'none';
		}
	}
}