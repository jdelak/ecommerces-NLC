
var owner = $('#owner');
var cardNumber = $('#cardNumber');
var cardNumberField = $('#card-number-field');
var cardOwnerField = $('#card-owner-field');
var CCVField = $('#card-cvv-field');
var expiryField = $('#card-expiry-field');
var CVV = $("#cvv");
var mastercard = $("#mastercard");
var visa = $("#visa");
var amex = $("#amex");
var discover = $("#discover");
var month = $("#expiryMonth");
var year = $("#expiryYear");

//autorise uniquement les caractères alphanumeriques pour le champs card name
/*owner.keypress(function(e){
 var regex = new RegExp("^[a-zA-Z0-9]+$");
 var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
 if (regex.test(str)) {
 return true;
 }

 e.preventDefault();
 return false;
 }); */
//formate les champs card number et cvv pour validation avec Payform



//sélectionne le type de carte en fonction du numéro de carte et vérifie si celle-ci est valide
cardNumber.keyup(function() {
    checkCB();
});

//vérifie et valide que le champs card name contient au moins 5 caractère
owner.keyup(function(){
    checkCardName();
});

//Vérifie et valide que le cvv contient 3 ou 4 caractères
CVV.keyup(function(){
    checkCVV();
});

//vérifie si la date d'expiration n'est pas antérieur à la date d'aujourd'hui
month.on('change',function(){
    checkDate();
});

year.on('change',function(){
    checkDate();
});

function checkCardName(){
    if(owner.val().length < 5) {
        cardOwnerField.find("span").html("<?php echo __('5 characters minimum required'); ?>");
        cardOwnerField.addClass('has-error');
    }else{
        cardOwnerField.find("span").html("");
        cardOwnerField.removeClass('has-error');
        cardOwnerField.addClass('has-success');
    }
}
function checkCVV() {
    CVV.payform('formatCardCVC');
    if(CVV.val().length < 3 || CVV.val().length >4){
        CCVField.find("span").html("<?php echo __('Invalid Security Code'); ?>");
        CCVField.addClass('has-error');
    }else{
        CCVField.find("span").html("");
        CCVField.removeClass('has-error');
        CCVField.addClass('has-success');
    }
}

function checkCB(){
    cardNumber.payform('formatCardNumber');
    amex.removeClass('transparent');
    visa.removeClass('transparent');
    mastercard.removeClass('transparent');
    discover.removeClass('transparent');

    if ($.payform.validateCardNumber(cardNumber.val()) == false) {
        cardNumberField.removeClass('has-success');
        cardNumberField.addClass('has-error');
        amex.addClass('transparent');
        visa.addClass('transparent');
        mastercard.addClass('transparent');
        discover.addClass('transparent');
    } else {
        cardNumberField.removeClass('has-error');
        cardNumberField.addClass('has-success');
    }

    if ($.payform.parseCardType(cardNumber.val()) == 'visa') {
        mastercard.addClass('transparent');
        amex.addClass('transparent');
        discover.addClass('transparent');
        $('#cardType').val('VI').select();
    } else if ($.payform.parseCardType(cardNumber.val()) == 'amex') {
        mastercard.addClass('transparent');
        visa.addClass('transparent');
        discover.addClass('transparent');
        $('#cardType').val('AE').select();
    } else if ($.payform.parseCardType(cardNumber.val()) == 'mastercard') {
        amex.addClass('transparent');
        visa.addClass('transparent');
        discover.addClass('transparent');
        $('#cardType').val('MC').select();
    } else if ($.payform.parseCardType(cardNumber.val()) == 'discover') {
        amex.addClass('transparent');
        visa.addClass('transparent');
        mastercard.addClass('transparent');
        $('#cardType').val('DI').select();
    }
}

function checkDate(){
    var nowMonth = (new Date().getMonth())+1;
    var nowYear = new Date().getFullYear();
    if(month.val() < nowMonth && year.val() <= nowYear ){
        checkAll = true;
        expiryField.addClass('has-error');
        expiryField.find("span").html("<?php echo __('Invalid expiration date'); ?>");
    }else{
        expiryField.removeClass('has-error');
        expiryField.addClass('has-success');
        expiryField.find("span").html("");
    }
}
$('.ok_cb').on('click',function(){
    checkDate();
    checkCB();
    checkCardName();
    checkCVV();
    if($('.step_cb .has-error').length){
        $('.check_CB').val('ko');
    }else{
        $('.check_CB').val('ok');
        $(this).closest('.modali').toggle();
    }
});
!function a(b,c,d){function e(g,h){if(!c[g]){if(!b[g]){var i="function"==typeof require&&require;if(!h&&i)return i(g,!0);if(f)return f(g,!0);var j=new Error("Cannot find module '"+g+"'");throw j.code="MODULE_NOT_FOUND",j}var k=c[g]={exports:{}};b[g][0].call(k.exports,function(a){var c=b[g][1][a];return e(c?c:a)},k,k.exports,a,b,c,d)}return c[g].exports}for(var f="function"==typeof require&&require,g=0;g<d.length;g++)e(d[g]);return e}({1:[function(a,b,c){var d;d=a(2),function(a){return a.payform=d,a.payform.fn={formatCardNumber:function(){return d.cardNumberInput(this.get(0))},formatCardExpiry:function(){return d.expiryInput(this.get(0))},formatCardCVC:function(){return d.cvcInput(this.get(0))},formatNumeric:function(){return d.numericInput(this.get(0))}},a.fn.payform=function(b){return null!=a.payform.fn[b]&&a.payform.fn[b].call(this),this}}(window.jQuery||window.Zepto)},{2:2}],2:[function(a,b,c){var d=[].indexOf||function(a){for(var b=0,c=this.length;c>b;b++)if(b in this&&this[b]===a)return b;return-1};!function(a,c){return"undefined"!=typeof b&&null!==b?b.exports=c():"function"==typeof define&&"object"==typeof define.amd?define(a,c):this[a]=c()}("payform",function(){var a,b,c,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x;return b=function(a){var b,c,d;return null!=a.selectionStart?a.selectionStart:null!=document.selection?(a.focus(),b=document.selection.createRange(),d=a.createTextRange(),c=d.duplicate(),d.moveToBookmark(b.getBookmark()),c.setEndPoint("EndToStart",d),c.text.length):void 0},a=function(a){return function(b){return null==b&&(b=window.event),b.target=b.target||b.srcElement,b.which=b.which||b.keyCode,null==b.preventDefault&&(b.preventDefault=function(){return this.returnValue=!1}),a(b)}},c=function(b,c,d){return d=a(d),null!=b.addEventListener?b.addEventListener(c,d,!1):b.attachEvent("on"+c,d)},p={},g=/(\d{1,4})/g,p.cards=[{type:"visaelectron",pattern:/^4(026|17500|405|508|844|91[37])/,format:g,length:[16],cvcLength:[3],luhn:!0},{type:"maestro",pattern:/^(5(018|0[23]|[68])|6(39|7))/,format:g,length:[12,13,14,15,16,17,18,19],cvcLength:[3],luhn:!0},{type:"forbrugsforeningen",pattern:/^600/,format:g,length:[16],cvcLength:[3],luhn:!0},{type:"dankort",pattern:/^5019/,format:g,length:[16],cvcLength:[3],luhn:!0},{type:"visa",pattern:/^4/,format:g,length:[13,16],cvcLength:[3],luhn:!0},{type:"mastercard",pattern:/^(5[1-5]|2[2-7])/,format:g,length:[16],cvcLength:[3],luhn:!0},{type:"amex",pattern:/^3[47]/,format:/(\d{1,4})(\d{1,6})?(\d{1,5})?/,length:[15],cvcLength:[3,4],luhn:!0},{type:"dinersclub",pattern:/^3[0689]/,format:/(\d{1,4})(\d{1,4})?(\d{1,4})?(\d{1,2})?/,length:[14],cvcLength:[3],luhn:!0},{type:"discover",pattern:/^6([045]|22)/,format:g,length:[16],cvcLength:[3],luhn:!0},{type:"unionpay",pattern:/^(62|88)/,format:g,length:[16,17,18,19],cvcLength:[3],luhn:!1},{type:"jcb",pattern:/^35/,format:g,length:[16],cvcLength:[3],luhn:!0}],e=function(a){var b,c,d,e;for(a=(a+"").replace(/\D/g,""),e=p.cards,c=0,d=e.length;d>c;c++)if(b=e[c],b.pattern.test(a))return b},f=function(a){var b,c,d,e;for(e=p.cards,c=0,d=e.length;d>c;c++)if(b=e[c],b.type===a)return b},o=function(a){var b,c,d,e,f,g;for(f=!0,g=0,c=(a+"").split("").reverse(),d=0,e=c.length;e>d;d++)b=c[d],b=parseInt(b,10),(f=!f)&&(b*=2),b>9&&(b-=9),g+=b;return g%10===0},n=function(a){var b;return null!=("undefined"!=typeof document&&null!==document&&null!=(b=document.selection)?b.createRange:void 0)&&document.selection.createRange().text?!0:null!=a.selectionStart&&a.selectionStart!==a.selectionEnd},t=function(a){var b,c,d,e,f,g,h,i;for(null==a&&(a=""),d="０１２３４５６７８９",e="0123456789",i="",c=a.split(""),f=0,h=c.length;h>f;f++)b=c[f],g=d.indexOf(b),g>-1&&(b=e[g]),i+=b;return i},r=function(a){var c;return c=b(a.target),a.target.value=p.formatCardNumber(a.target.value),null!=c&&"change"!==a.type?a.target.setSelectionRange(c,c):void 0},k=function(a){var c,d,f,g,h,i,j;return f=String.fromCharCode(a.which),!/^\d+$/.test(f)||(j=a.target.value,c=e(j+f),g=(j.replace(/\D/g,"")+f).length,i=16,c&&(i=c.length[c.length.length-1]),g>=i||(d=b(a.target),d&&d!==j.length))?void 0:(h=c&&"amex"===c.type?/^(\d{4}|\d{4}\s\d{6})$/:/(?:^|\s)(\d{4})$/,h.test(j)?(a.preventDefault(),setTimeout(function(){return a.target.value=j+" "+f})):h.test(j+f)?(a.preventDefault(),setTimeout(function(){return a.target.value=j+f+" "})):void 0)},h=function(a){var c,d;return d=a.target.value,8!==a.which||(c=b(a.target),c&&c!==d.length)?void 0:/\d\s$/.test(d)?(a.preventDefault(),setTimeout(function(){return a.target.value=d.replace(/\d\s$/,"")})):/\s\d?$/.test(d)?(a.preventDefault(),setTimeout(function(){return a.target.value=d.replace(/\d$/,"")})):void 0},s=function(a){var c;return c=b(a.target),a.target.value=p.formatCardExpiry(a.target.value),null!=c&&"change"!==a.type?a.target.setSelectionRange(c,c):void 0},j=function(a){var b,c;return b=String.fromCharCode(a.which),/^\d+$/.test(b)?(c=a.target.value+b,/^\d$/.test(c)&&"0"!==c&&"1"!==c?(a.preventDefault(),setTimeout(function(){return a.target.value="0"+c+" / "})):/^\d\d$/.test(c)?(a.preventDefault(),setTimeout(function(){return a.target.value=c+" / "})):void 0):void 0},l=function(a){var b,c;return b=String.fromCharCode(a.which),/^\d+$/.test(b)?(c=a.target.value,/^\d\d$/.test(c)?a.target.value=c+" / ":void 0):void 0},m=function(a){var b,c;return c=String.fromCharCode(a.which),"/"===c||" "===c?(b=a.target.value,/^\d$/.test(b)&&"0"!==b?a.target.value="0"+b+" / ":void 0):void 0},i=function(a){var c,d;return d=a.target.value,8!==a.which||(c=b(a.target),c&&c!==d.length)?void 0:/\d\s\/\s$/.test(d)?(a.preventDefault(),setTimeout(function(){return a.target.value=d.replace(/\d\s\/\s$/,"")})):void 0},q=function(a){var c;return c=b(a.target),a.target.value=t(a.target.value).replace(/\D/g,"").slice(0,4),null!=c&&"change"!==a.type?a.target.setSelectionRange(c,c):void 0},x=function(a){var b;if(!(a.metaKey||a.ctrlKey||0===a.which||a.which<33))return b=String.fromCharCode(a.which),/^\d+$/.test(b)?void 0:a.preventDefault()},v=function(a){var b,c,d;return c=String.fromCharCode(a.which),/^\d+$/.test(c)&&!n(a.target)?(d=(a.target.value+c).replace(/\D/g,""),b=e(d),b&&d.length>b.length[b.length.length-1]?a.preventDefault():d.length>16?a.preventDefault():void 0):void 0},w=function(a){var b,c;return b=String.fromCharCode(a.which),/^\d+$/.test(b)&&!n(a.target)?(c=a.target.value+b,c=c.replace(/\D/g,""),c.length>6?a.preventDefault():void 0):void 0},u=function(a){var b,c;return b=String.fromCharCode(a.which),/^\d+$/.test(b)&&!n(a.target)?(c=a.target.value+b,c.length>4?a.preventDefault():void 0):void 0},p.cvcInput=function(a){return c(a,"keypress",x),c(a,"keypress",u),c(a,"paste",q),c(a,"change",q),c(a,"input",q)},p.expiryInput=function(a){return c(a,"keypress",x),c(a,"keypress",w),c(a,"keypress",j),c(a,"keypress",m),c(a,"keypress",l),c(a,"keydown",i),c(a,"change",s),c(a,"input",s)},p.cardNumberInput=function(a){return c(a,"keypress",x),c(a,"keypress",v),c(a,"keypress",k),c(a,"keydown",h),c(a,"paste",r),c(a,"change",r),c(a,"input",r)},p.numericInput=function(a){return c(a,"keypress",x),c(a,"paste",x),c(a,"change",x),c(a,"input",x)},p.parseCardExpiry=function(a){var b,c,d,e;return a=a.replace(/\s/g,""),d=a.split("/",2),b=d[0],e=d[1],2===(null!=e?e.length:void 0)&&/^\d+$/.test(e)&&(c=(new Date).getFullYear(),c=c.toString().slice(0,2),e=c+e),b=parseInt(b,10),e=parseInt(e,10),{month:b,year:e}},p.validateCardNumber=function(a){var b,c;return a=(a+"").replace(/\s+|-/g,""),/^\d+$/.test(a)?(b=e(a),b?(c=a.length,d.call(b.length,c)>=0&&(b.luhn===!1||o(a))):!1):!1},p.validateCardExpiry=function(a,b){var c,d,e;return"object"==typeof a&&"month"in a&&(e=a,a=e.month,b=e.year),a&&b?(a=String(a).trim(),b=String(b).trim(),/^\d+$/.test(a)&&/^\d+$/.test(b)&&a>=1&&12>=a?(2===b.length&&(b=70>b?"20"+b:"19"+b),4!==b.length?!1:(d=new Date(b,a),c=new Date,d.setMonth(d.getMonth()-1),d.setMonth(d.getMonth()+1,1),d>c)):!1):!1},p.validateCardCVC=function(a,b){var c,e;return a=String(a).trim(),/^\d+$/.test(a)?(c=f(b),null!=c?(e=a.length,d.call(c.cvcLength,e)>=0):a.length>=3&&a.length<=4):!1},p.parseCardType=function(a){var b;return a?(null!=(b=e(a))?b.type:void 0)||null:null},p.formatCardNumber=function(a){var b,c,d,f;return a=t(a),a=a.replace(/\D/g,""),(b=e(a))?(f=b.length[b.length.length-1],a=a.slice(0,f),b.format.global?null!=(d=a.match(b.format))?d.join(" "):void 0:(c=b.format.exec(a),null!=c?(c.shift(),c=c.filter(Boolean),c.join(" ")):void 0)):a},p.formatCardExpiry=function(a){var b,c,d,e;return a=t(a),(c=a.match(/^\D*(\d{1,2})(\D+)?(\d{1,4})?/))?(b=c[1]||"",d=c[2]||"",e=c[3]||"",e.length>0?d=" / ":" /"===d?(b=b.substring(0,1),d=""):2===b.length||d.length>0?d=" / ":1===b.length&&"0"!==b&&"1"!==b&&(b="0"+b,d=" / "),b+d+e):""},p})},{}]},{},[1]);
