
<div class="container">
    <div class="login-form-wrap">
        <div class="login-form">
            <div class="row">
                <div class="col-12">
                <?php echo Form::open(); ?>

                    <div class="form-group">
                        <?php echo Form::label(___('Username'), 'username') ?>
                        <?php echo Form::input('username', null, array('class' => 'form-control')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo Form::label(___('Password'), 'password') ?>
                        <?php echo Form::password('password', null, array('class' => 'form-control')); ?>
                    </div>
                    <input type="hidden" name="mode" value="login" />
                    <?php echo Form::button('login', '<span class="icon-signin"></span> '.___('Sign in'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>

                <?php echo Form::close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php \Ember\Controller_Theme::append_css('inline_css') ?>
    <style type="text/css">
        .login-form-wrap {
            text-align: center;
        }
        .login-form {
            margin: 100px auto;
            text-align: left;
            max-width: 300px;
        }
        /* Mobile Landscape Size to Tablet Portrait (devices and browsers) */
        @media only screen and (min-width: 480px) and (max-width: 767px) {
            .login-form {
                margin: 0 auto;
            }
        }
        @media only screen and (max-width: 479px) {
            .login-form {
                margin: 0 auto;
            }
        }
    </style>
<?php \Ember\Controller_Theme::end() ?>