var numbers = /^[0-9]+$/;
var alpha = /^[a-zA-Z\ ]*$/;

$(document).ready(function(){
	
});

function isNumber(value){
	if(value.match(numbers))
		return true;
	return false;
}

function isAlpha(value){
	if(value.match(alpha))
		return true;
	return false;
}

function makeNewApp(){
	var total = $('#total_apps').val();
	if(total>=3){
		alert("You have added maximum number of applications in your account.\nTo add a new application, you have to complete one of your existing application.");
		return false;
	}
		
	return true;
}

function confirmDelete(){
	return confirm("Are you sure to delete this item?");
}

function validateCreditCard(s) {
    // remove non-numerics
    var v = "0123456789";
    var w = "";
    for (i=0; i < s.length; i++) {
        x = s.charAt(i);
        if (v.indexOf(x,0) != -1)
        w += x;
    }
    // validate number
    j = w.length / 2;
    k = Math.floor(j);
    m = Math.ceil(j) - k;
    c = 0;
    for (i=0; i<k; i++) {
        a = w.charAt(i*2+m) * 2;
        c += a > 9 ? Math.floor(a/10 + a%10) : a;
    }
    for (i=0; i<k+m; i++) c += w.charAt(i*2+1-m) * 1;
    return (c%10 == 0);
}