#!/user/bin/env node

let tab = [];
let tab1 = [];
let tab2 = [];
//console.log(process.argv.slice(2), tab1);
for(let i = 2; i < process.argv.length; i++){
	tab = tab.concat(process.argv[i].trim().split(/\w.+[\d\s]+/g)).sort();
	tab1 = tab1.concat(process.argv[i].split(/[\D\s/]+/)).sort();
	tab2 = tab2.concat(process.argv[i].split(/[\w\s/]+/)).sort();
}


/*
tab.sort(function (a, b) {
	if(strcmp(a, b) >= 0)
		return a;
	else
		return b;
});
*/



tab.forEach(function (element){
	console.log(element);
});
console.log(tab, tab1, tab2);
