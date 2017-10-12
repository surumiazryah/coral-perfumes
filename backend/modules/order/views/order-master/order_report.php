<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\User;
use kartik\daterange\DateRangePicker;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Order Wise Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-master-index">

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>


                </div>
                <div class="panel-body">
                    <div class="row" style="margin-left: 0px;border-bottom: 2px solid rgba(39, 41, 42, 0.46);">
                        <div class="col-md-6">

                            <?= $this->render('_search_order', ['model' => $searchModel, 'from' => $from, 'to' => $to]) ?>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group ">
                                <table cellspacing="0" class="table table-small-font table-bordered table-striped" style="">
                                    <tr>
                                        <th>Total Sale</th>
                                        <th>Total Amount</th>
                                        <th>Amount Net Amount</th>
                                    </tr>
                                    <?php
                                    $sale_total = $dataProvider->getTotalCount();
                                    $amount_total = common\models\OrderMaster::getAmountTotal($from, $to, 'total_amount');
                                    $net_amount_total = common\models\OrderMaster::getAmountTotal($from, $to, 'net_amount');
                                    ?>
                                    <tr>
                                        <td><?= $sale_total ?></td>
                                        <td><?= $amount_total ?></td>
                                        <td><?= $net_amount_total ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'pager' => [
                            'firstPageLabel' => 'First',
                            'lastPageLabel' => 'Last',
                        ],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'order_id',
                            [
                                'attribute' => 'user_id',
                                'label' => 'User Name',
                                'value' => function ($data) {
                                    if (isset($data->user_id)) {
                                        return User::findOne($data->user_id)->first_name;
                                    } else {
                                        return '';
                                    }
                                },
                            ],
                            'total_amount',
                            'net_amount',
                            'order_date',
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>