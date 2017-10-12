<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\AdminPost;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<style>
    .table>thead:first-child>tr:first-child>th {
        width: 0px;
        white-space: nowrap;
    }
    .purchase-clickable-row:hover{
        cursor: pointer;
    }
    .sales-clickable-row:hover{
        cursor: pointer;
    }
    .row-style{
        margin-left: 0px;
        margin-right: 0px;
    }
</style>
<!--<div class="row">

    <div class="col-sm-9">

        <div class="col-sm-4">

            <div class="xe-widget xe-counter-block">
                <div class="xe-upper">

                    <div class="xe-icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="xe-label">
                        <strong class="num"><?= sprintf('%0.2f', 0) ?></strong>
                        <span>Total Sales Amount</span>
                    </div>

                </div>
                <div class="xe-lower">
                    <div class="border"></div>
                    <strong><?= date("d-M-Y") ?></strong>
                </div>
            </div>

        </div>

        <div class="col-sm-4">

            <div class="xe-widget xe-counter-block xe-counter-block-purple">
                <div class="xe-upper">

                    <div class="xe-icon">
                        <i class="fa fa-briefcase"></i>
                    </div>
                    <div class="xe-label">
                        <strong class="num"><?= sprintf('%0.2f', 0) ?></strong>
                        <span>Total Purchase Amount</span>
                    </div>

                </div>
                <div class="xe-lower">
                    <div class="border"></div>
                    <strong><?= date("d-M-Y") ?></strong>
                </div>
            </div>

        </div>

        <div class="col-sm-4">

            <div class="xe-widget xe-counter-block xe-counter-block-blue">
                <div class="xe-upper">

                    <div class="xe-icon">
                        <i class="fa fa-credit-card"></i>
                    </div>
                    <div class="xe-label">
                        <strong class="num"><?= sprintf('%0.2f', 0) ?></strong>
                        <span>Total Expense</span>
                    </div>

                </div>
                <div class="xe-lower">
                    <div class="border"></div>

                    <strong><?= date('d-M-Y') ?></strong>
                </div>
            </div>

        </div>

        <div class="col-sm-4" style="float: right;">

            <div class="xe-widget xe-counter-block xe-counter-block-orange">
                <div class="xe-upper">

                    <div class="xe-icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="xe-label">
                        <strong class="num"><?= sprintf('%0.2f', 0) ?></strong>
                        <span>Total Sales Debt</span>
                    </div>

                </div>
                <div class="xe-lower">
                    <div class="border"></div>
                    <strong><?= date("d-M-Y") ?></strong>
                </div>
            </div>

        </div>

        <div class="col-sm-4">

            <div class="xe-widget xe-counter-block xe-counter-block-red">
                <div class="xe-upper">

                    <div class="xe-icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <div class="xe-label">
                        <strong class="num"><?= sprintf('%0.2f', 0) ?></strong>
                        <span>Total Sales Amount Received</span>
                    </div>

                </div>
                <div class="xe-lower">
                    <div class="border"></div>
                    <strong><?= date("d-M-Y") ?></strong>
                </div>
            </div>

        </div>

        <div class="col-sm-4">

            <div class="xe-widget xe-counter-block xe-counter-block-yellow">
                <div class="xe-upper">

                    <div class="xe-icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <div class="xe-label">
                        <strong class="num"><?= sprintf('%0.2f', 0) ?></strong>
                        <span>Total Purchase Amount Paid</span>
                    </div>

                </div>
                <div class="xe-lower">
                    <div class="border"></div>
                    <strong><?= date("d-M-Y") ?></strong>
                </div>
            </div>

        </div>
    </div>
    <div class="col-sm-3">
        <div class="col-sm-12">

            <div class="xe-widget xe-vertical-counter xe-vertical-counter-danger" style="height: 267px;">
                <div class="xe-icon">
                    <i class="fa fa-briefcase"></i>
                </div>

                <div class="xe-label">
                    <strong class="num"><?= sprintf('%0.2f', 0) ?></strong>
                    <span>Purchase Debt</span>
                </div>
            </div>

        </div>
    </div>

</div>-->

<div class="row row-style">
    <div class="col-sm-6">

        <div class="panel panel-default" style="height: 350px;">
            <div class="panel-heading">
                Recent Orders
            </div>
            <div  style="min-height: 210px;">
                <table class="table">
                    <thead>
                        <tr style="text-align: center;">
                            <th width="">Invoice Number</th>
                            <th width="">Date</th>
                            <th width="">Customer</th>
                            <th width="">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="col-sm-6">

        <div class="panel panel-default" style="height: 350px;">
            <div class="panel-heading">
                Stock Report
            </div>
            <div  style="min-height: 210px;">
                <table class="table">
                    <thead>
                        <tr style="text-align: center;">
                            <th width="">Product Name</th>
                            <th width="">Available Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($products)) {
                            foreach ($products as $value) {
                                ?>
                                <tr>
                                    <td><?= $value->product_name ?></td>
                                    <td>
                                        <?php
                                        if ($value->stock == 0) {
                                            echo 'No stock';
                                        } else {
                                            echo $value->stock;
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!--            <div>
            <?php // Html::a('<i class="fa-share"></i><span> View More</span>', ['product/product/stock-report'], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right', 'style' => 'margin-top: 8px;float:right;']) ?>
                        </div>-->
        </div>

    </div>
</div>

<script>
    jQuery(document).ready(function ($) {
        $(".purchase-clickable-row").click(function () {
            var current_row_id = $(this).attr('id').match(/\d+/);
            window.location = '<?= Yii::$app->homeUrl; ?>sales/purchase-invoice-details/view?id=' + current_row_id;
        });

        $(".sales-clickable-row").click(function () {
            var current_row_id = $(this).attr('id').match(/\d+/);
            window.location = '<?= Yii::$app->homeUrl; ?>sales/sales-invoice-details/view?id=' + current_row_id;
        });
    });
</script>

