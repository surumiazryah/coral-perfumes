<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-master-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['order-report'],
                'method' => 'get',
    ]);
    $model->createdFrom = $from;
    $model->createdTo = $to;
    ?>

    <div class="col-md-4">
        <?=
        $form->field($model, 'createdFrom')->widget(\yii\jui\DatePicker::classname(), [
            //'language' => 'ru',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control']
        ])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'createdTo')->widget(\yii\jui\DatePicker::classname(), [
            //'language' => 'ru',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control']
        ])
        ?>
    </div>

    <div class="col-md-4" style="margin-top: 23px;">
        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-success']) ?>
            <?php // Html::resetButton('Reset', ['class' => 'btn btn-default', 'style' => 'background-color: #e6e6e6;'])   ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
