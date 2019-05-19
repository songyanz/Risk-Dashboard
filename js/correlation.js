function is(){
	//var m1=document.tradeSellForm.a.selectedIndex;
//var m2=document.tradeSellForm.b.selectedIndex;
//if(m1==m2){ document.tradeSellForm.b.options.remove(m2);}  

	var m1 = document.corr.sectora.value;

	var obj_b = document.corr.sectorb;
	for(var i = 0; i < obj_b.options.length; i++) {
		if(obj_b.options[i].value == m1) {
			obj_b.options.remove(i);
			return;
		}
	}
}