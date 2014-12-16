function User () {
    this.id = 0;
}

User.prototype.controller = "";
User.prototype.token = "";
User.prototype.id = 0;
User.prototype.username = "";
User.prototype.firstname = "";
User.prototype.lastname = "";
User.prototype.password = "";
User.prototype.email = "";
User.prototype.birthday = "";
User.prototype.errors = [];

User.prototype.setToken = function(token) {
    this.token = token;
};

User.prototype.setController = function(controller) {
    this.controller = controller;
};

User.prototype.setId = function(id) {
    if (isNumber(id)) this.id = id;
};

User.prototype.setUsername = function(username) {
    if (isStringValid(username, 3, 15)) this.username = username;
};

User.prototype.setFirstname = function(firstname) {
    if (isStringValid(firstname, 3, 30)) this.firstname = firstname;
};

User.prototype.setLastname = function(lastname) {
    if (isStringValid(lastname, 3, 30)) this.lastname = lastname;
};

User.prototype.setPassword = function(password) {
    if (isPasswordValid(password)) this.password = password;
};

User.prototype.setEmail = function(email) {
    if (isEmailValid(email)) this.email = email;
};

User.prototype.setBirthday = function(birthday) {
    if (isBirthday(birthday)) this.birthday = birthday;
};

User.prototype.getController = function() {
    return this.controller;
};

User.prototype.getToken = function() {
    return this.token;
};

User.prototype.getId = function() {
    return this.id;
};

User.prototype.getUsername = function() {
    return this.username;
};

User.prototype.getFirstname = function() {
    return this.firstname;
};

User.prototype.getLastname = function() {
    return this.lastname;
};

User.prototype.getPassword = function() {
    return this.password;
};

User.prototype.getEmail = function() {
    return this.email;
};

User.prototype.getBirthday = function() {
    return this.birthday;
};

User.prototype.prepare = function(){
    this.id = 0;
    this.username = '';
    this.firstname = '';
    this.lastname = '';
    this.password = '';
    this.email = '';
    this.birthday = '';
};

User.prototype.getErrors = function() {
    this.errors = [];
    if (this.controller == "") this.errors.push({name:"controller", msg:"Controller is not valid."});
    if (this.username == "") this.errors.push({name:"username", msg:"Username is not valid."});
    if (this.firstname == "") this.errors.push({name:"firstname", msg:"Firstname is not valid."});
    if (this.lastname == "") this.errors.push({name:"Lastname", msg:"Lastname is not valid."});
    if (this.password == "") this.errors.push({name:"password", msg:"Password is not valid."});
    if (this.email == "") this.errors.push({name:"email", msg:"Email is not valid."});
    if (this.birthday == "") this.errors.push({name:"birthday", msg:"Birthday is not valid."});
    return this.errors;
};

User.prototype.isValid = function() {
    return (this.getErrors().length == 0);
};

User.prototype.save = function(onSuccess, onError, onComplete){
    var url = '';
    var type = '';
    var data = {
        _username:this.getUsername(),
        _firstname:this.getFirstname(),
        _lastname:this.getLastname(),
        _password:this.getPassword(),
        _email:this.getEmail(),
        _birthday:this.getBirthday(),
        _csrf_token_register:this.getToken()
    };
    if (this.getId() > 0) {
        data['id'] = this.getId();
        url = this.getController()+'update';
        type = 'PUT';
    } else  {
        url = this.getController()+'create';
        type = 'POST';
    }
    callService(url, JSON.stringify(data), type, 'html', 'application/json; charset=utf-8', onSuccess, onError, onComplete);
};

User.prototype.destroy = function(onSuccess, onError, onComplete){
    var data = { _id:this.getId(), _csrf_token_detail:this.getToken() };
    callService(this.getController(), JSON.stringify(data), 'DELETE', 'html', 'application/json; charset=utf-8', onSuccess, onError, onComplete);
};

function callService(url, data, type, dataType, contentType, onSuccess, onError, onComplete){
    $.ajax({
        type: type,
        dataType: dataType,
        url: url,
        contentType: contentType,
        data : data,
        success: function (data, textStatus, jqXHR) {
            onSuccess(data, textStatus, jqXHR);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            onError(jqXHR, textStatus, errorThrown);
        },
        complete: function(){
            onComplete();
        }
    });
}