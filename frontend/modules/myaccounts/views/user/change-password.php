<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="pad-20 hide-xs"></div>

<div class="container">
        <div class="breadcrumb">
                <span class="current-page">My account</span>
                <ol class="path">
                        <li><?= Html::a('<span>Home</span>', ['/site/index'], ['class' => '']) ?></li>
                        <li><?= Html::a('<span>My account</span>', ['/myaccounts/user/index'], ['class' => '']) ?></li>
                        <li class="active">Change Password</li>
                </ol>
        </div>
</div>

<div id="our-product" class="my-account">
        <div class="container">
                <?= Yii::$app->controller->renderPartial('_leftside_menu'); ?>
                <div class="settings">
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 right-box" style="padding: 50px 15px;">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 my-account-cntnt align-center">
                                        <div class="form-feild-box">
                                                <?php
                                                $form = ActiveForm::begin(
                                                                [
                                                                    'id' => 'change-password',
                                                                    'method' => 'post',
                                                                    'options' => [
                                                                        'class' => 'login-form fade-in-effect changepassword-form'
                                                                    ]
                                                                ]
                                                );
                                                ?>
                                                <?php if (!empty(Yii::$app->session->getFlash('error'))) { ?>
                                                        <div class="error-summary">
                                                                <?= Yii::$app->session->getFlash('error'); ?>
                                                        </div>
                                                <?php } ?>
                                                <?php if (!empty(Yii::$app->session->getFlash('success'))): ?>
                                                        <div class="alert alert-success" role="alert">
                                                                <button type="button" class="close" data-dismiss="alert">
                                                                        <span aria-hidden="true">&times;</span>
                                                                        <span class="sr-only">Close</span>
                                                                </button>
                                                                <?= Yii::$app->session->getFlash('success') ?>
                                                        </div>
                                                <?php endif; ?>
                                                <div class="form-group col-md-12">
                                                        <label for="pwd">Old Password*</label>
                                                        <input type="password" name="old-password" class="form-control" required="" placeholder="********" id="change-old-password">
                                                </div>
                                                <div class="form-group col-md-12">
                                                        <label for="pwd">New Password*</label>
                                                        <input type="password" class="form-control" name="new-password" required="" placeholder="********" id="change-new-password">
                                                </div>
                                                <div class="form-group col-md-12">
                                                        <label for="pwd">Confirm Password*</label>
                                                        <input type="password" class="form-control" required="" name="confirm-password" placeholder="********" id="change-confirm-password">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 save">
                                                        <?= Html::submitButton('Save Changes', ['class' => 'green2']) ?>
                                                </div>
                                                <?php ActiveForm::end(); ?>

                                        </div>
                                </div>
                        </div>
                </div>
        </div>

</div>
</div>

<div class="pad-20"></div>



<script>

        $(document).ready(function () {

                $('#change-old-password').on('blur', function () {
                        var old_pwd = $('#change-old-password').val();
                        $.ajax({
                                type: 'POST',
                                cache: false,
                                data: {old_pwd: old_pwd},
                                url: homeUrl + 'myaccounts/user/check-password',
                                success: function (data) {
                                        if (data == 0) {
                                                if (!$(".help-block3")[0]) {
                                                        $("#change-old-password").after('<div class="help-block3" style="color:#a94442"> Incorrect Password.</div>');
                                                } else {
                                                        $('.help-block3').empty();
                                                        $('.help-block3').append('Incorrect Password.');
                                                }
                                        } else {
                                                $('.help-block3').empty();
                                        }
                                }
                        });
                });



                $('#change-new-password').on('blur', function () {
                        var pass_length = PasswordLength();
                        if (pass_length == 0) {
                                if (!$(".help-block1")[0]) {
                                        $("#change-new-password").after('<div class="help-block1" style="color:#a94442"> Password should contain at least 6 characters.</div>');
                                } else {
                                        $('.help-block1').empty();
                                        $('.help-block1').append('Password should contain at least 6 characters.');
                                }
                        } else {
                                $('.help-block1').empty();
                        }
                });
                $('#change-confirm-password').on('blur', function () {
                        var confirm = ConfirmPassword();
                        if (confirm == 0) {
                                if (!$(".help-block2")[0]) {
                                        $("#change-confirm-password").after('<div class="help-block2" style="color:#a94442"> Password mismatch.</div>');
                                } else {
                                        $('.help-block2').empty();
                                        $('.help-block2').append('Password mismatch.');
                                }
                        } else {
                                $('.help-block2').empty();
                        }
                });


                $(document).on('submit', '#change-password', function (e) {
                        var pass_length = PasswordLength();
                        var confirm = ConfirmPassword();
                        var old_pwd = $('#change-old-password').val();
                        if (pass_length == 1 && confirm == 1) {
                                $.ajax({
                                        url: homeUrl + 'myaccounts/user/check-password',
                                        'async': false,
                                        'type': "POST",
                                        'global': false,
                                        data: {old_pwd: old_pwd},

                                })
                                        .done(function (data) {
                                                if (data == 1) {
                                                        return true;

                                                } else {
                                                        e.preventDefault();
                                                        return false;
                                                }
                                        });
                                return true;
                        } else {
                                e.preventDefault();
                        }
                });




                function PasswordLength() {
                        var length = $('#change-new-password').val().length;
                        if (length) {
                                if (length >= 6)
                                        return 1;
                                else
                                        return 0;
                        }
                }

                function ConfirmPassword() {
                        var password = $('#change-new-password').val();
                        var confirm_password = $('#change-confirm-password').val();
                        if (confirm_password !== password) {
                                return 0;
                        } else {
                                return 1;
                        }
                }


        });

</script>