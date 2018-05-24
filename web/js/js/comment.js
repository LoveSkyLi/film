
// $('#index').click(function () {
//     window.location.href = "index.html"
// });
// $('#dynamic').click(function () {
//     window.location.href = "dynamic.html"
// });
// $('#vip').click(function () {
//     window.location.href = "vip.html"
// });
// $('#my').click(function () {
//     window.location.href = "my.html"
// 
// alert(123)

// var  url = 'https://debug.oa.feeyan.com/movie-mp/api/'
var url =''
a = getCookie('usersession1');
alert(123)
localStorage["a"] = a
    var a1 = localStorage["a"]
    
    // alert('00')
    function getCookie(name) {
        var arr = document.cookie.replace(/\s/g, "").split(';');
        for (var i = 0; i < arr.length; i++) {
            var tempArr = arr[i].split('=');
            if (tempArr[0] == name) {
            return decodeURIComponent(tempArr[1]);
            }
        }
        return '';
    }
 function getUrlVars(){  
    var vars = [], hash;  
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');  
    for(var i = 0; i < hashes.length; i++)  
    {  
        hash = hashes[i].split('=');  
        vars.push(hash[0]);  
        vars[hash[0]] = hash[1];  
    }  
    return vars;  
}  
  
function getUrlVar(name){  
    return getUrlVars()[name];  
}