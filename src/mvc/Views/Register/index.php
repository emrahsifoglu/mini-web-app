<div class="container">
    <form id="form-register" class="form-horizontal" action='<?=WEB.'register/'?>' method="POST">
        <fieldset>
            <div id="legend">
                <legend class="">Register</legend>
            </div>

            <div class="control-group">
                <!-- Username -->
                <label class="control-label" for="username">Username</label>
                <div class="controls">
                    <input type="text" id="_username" name="_username" placeholder="Username" class="form-control form-register-control" maxlength="15" required autofocus>
                    <p class="help-block">Username can contain any letters or numbers, without spaces between 3 and 15</p>
                </div>
            </div>

            <div class="control-group">
                <!-- Username -->
                <label class="control-label" for="username">Firsname</label>
                <div class="controls">
                    <input type="text" id="_firstname" name="_firstname" placeholder="Firstname" class="form-control form-register-control" maxlength="30" required>
                    <p class="help-block">Firsname can contain any letters or numbers, without spaces between 3 and 30</p>
                </div>
            </div>

            <div class="control-group">
                <!-- Username -->
                <label class="control-label" for="username">Lastname</label>
                <div class="controls">
                    <input type="text" id="_lastname" name="_lastname" placeholder="Lastname" class="form-control form-register-control" maxlength="30" required>
                    <p class="help-block">Lastname can contain any letters or numbers, without spaces 3 and 30</p>
                </div>
            </div>

            <div class="control-group">
                <!-- E-mail -->
                <label class="control-label" for="email">E-mail</label>
                <div class="controls">
                    <input type="email" id="_email" name="_email" placeholder="Email" class="form-control form-register-control" required>
                    <p class="help-block">Please provide your E-mail</p>
                </div>
            </div>

            <div class="control-group">
                <!-- Birthday -->
                <label class="control-label" for="_birthday">Birthday</label>
                <div class="controls">
                    <input type="text" style="width: 100px;" class="span2 form-control form-register-control" data-date-format="dd-mm-yyyy" id="_birthday" name="_birthday" required>
                    <p class="help-block">Birthday should be in format like 01-01-1950</p>
                </div>
            </div>

            <div class="control-group">
                <!-- Password-->
                <label class="control-label" for="_password">Password</label>
                <div class="controls">
                    <input type="password" id="_password" name="_password" placeholder="Password" class="form-control form-register-control" maxlength="20" required>
                    <p class="help-block">Password should be between 6 an 20 characters without spaces</p>
                </div>
            </div>

            <div class="control-group">
                <!-- Password -->
                <label class="control-label" for="_password_confirm">Password (Confirm)</label>
                <div class="controls">
                    <input type="password" id="_password_confirm" name="_password_confirm" placeholder="Password (Confirm)" class="form-control form-register-control" maxlength="20" required>
                    <p class="help-block">Please confirm password</p>
                </div>
            </div>

            <div class="control-group">
                <!-- Button -->
                <div class="controls">
                    <button type="submit" class="btn btn-success">Register</button>
                </div>
            </div>
            <input type="hidden" id="_csrf_token_register" name="_csrf_token_register" value="<?=$data['csrf_token_register']?>">
        </fieldset>
    </form>
    <input type="hidden" id="route-email" value="<?=WEB.'email'?>">
</div>