var patterns = {
    year     : /^(19|20)\d{2}$/,
    str      : "^[a-zA-Z0-9_.öçşiğüÖÇŞİĞÜ-]{min,max}$",
    password : /^[a-zA-Z0-9]{6,20}$/,
    email    : /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
    birthday : /^([0-9]{2})\)?[-. ]?([0-9]{2})[-. ]?([0-9]{4})$/

};

function updateMinMax(pattern_str, min, max){
    var mapObj = { min:min, max:max };
    return pattern_str.replace(/min|max/gi, function(matched) {
        return mapObj[matched];
    });
}

function isStringValid(name, min, max) {
    return new RegExp(updateMinMax(patterns.str, min, max)).test(name);
}

function isEmailValid(email){
    return patterns.email.test(email);
}

function isPasswordValid(password){
    return patterns.password.test(password);
}

function isBirthday(birthday){
    return patterns.birthday.test(birthday);
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function eventPreventer(eventType, el){
    el.on(eventType, function(event){
        event.preventDefault();
    });
}

function BootstrapDialogShow(type, title, message, buttons){
    return new BootstrapDialog({
        type: type,
        title: title,
        message: message,
        buttons: buttons
    });
}