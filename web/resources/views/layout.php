<?php
    $isUserLoggedIn = Security::isUserLoggedIn();
    function isGuest($isUserLoggedIn){
        return ($isUserLoggedIn) ? 'none' : 'block';
    }
    function isAuthorized($isUserLoggedIn){
        return ($isUserLoggedIn) ? 'block' : 'none';
    }
    function isTokenGenerated($isUserLoggedIn){
        return ($isUserLoggedIn) ? 0 : Security::generateCSRFToken('csrf_token_login');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?=BOWER?>bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?=BOWER?>bootstrap/dist/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="<?=BOWER?>bootstrap3-dialog-master/dist/css/bootstrap-dialog.min.css">
        <?php
                $css = [];
                $css[] = STYLES.'layout.css';
                $css = array_merge($css, $styles);
                if (isset($css) && !empty($css)){
                    foreach($css as $style) {
                        echo '<link rel="stylesheet" href="'.$style.'">'.PHP_EOL;
                    }
                }
        ?>
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="<?=BOWER?>jquery/dist/jquery.min.js"></script>
        <script src="<?=BOWER?>bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?=BOWER?>bootstrap3-dialog-master/dist/js/bootstrap-dialog.min.js"></script>
        <?php
                $js = [];
                $js[] = SCRIPTS.'helper.js';
                $js[] = SCRIPTS.'app.js';
                $js = array_merge($js, $scripts);
                if (isset($js) && !empty($js)){
                    foreach($js as $script) {
                        echo '<script type="text/javascript" src="'.$script.'"></script>'.PHP_EOL;
                    }
                }
        ?>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header" style="display: ">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?=WEB.'home'?>">Mini Web App</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="navbar-collapse collapse" id="navbar-main">
                    <ul class="nav navbar-nav navbar-right">
                        <li id="li-home"><a href="<?=WEB.'home'?>">Home</a></li>
                        <li class="guest" id="li-register" style="display: <?=isGuest($isUserLoggedIn)?>"><a href="<?=WEB.'register'?>">Register</a></li>
                        <li class="authorized" id="li-detail" style="display: <?=isAuthorized($isUserLoggedIn)?>"><a href="<?=WEB.'detail'?>">Detail</a></li>
                        <li class="authorized" id="li-logout" style="display: <?=isAuthorized($isUserLoggedIn)?>"><a href="<?=WEB.'logout'?>">Logout</a></li>
                        <li class="guest" id="li-login" style="display: <?=isGuest($isUserLoggedIn)?>"><a href="#">Login</a></li>
                        <li class="guest" id="li-form" style="display: none">
                            <form id="form-login" class="navbar-form form-signin" role="form" method="post" action="<?=WEB.'login'?>">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="_username" name="_username" placeholder="Username" maxlength="15" required autofocus>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="_password" name="_password" placeholder="Password" maxlength="20" required>
                                </div>
                                <input type="hidden" id="_csrf_token_login" name="_csrf_token_login" value="<?=isTokenGenerated($isUserLoggedIn)?>">
                                <button style="display: none" type="submit"></button>
                             </form>
                        </li>
                    </ul>
                </div>
            </div><!-- /.container-fluid -->
        </nav>
        <?php echo $content; ?>
        <input type="hidden" id="route-logout" value="<?=WEB.'logout'?>">
        <nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
            <div class="container" style="text-align: center">
                ...
            </div>
        </nav>
    </body>
</html>