<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\AdminPost;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stock Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-user-index">

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>


                </div>
                <div class="panel-body">
                    <div class="table-responsive" style="border: none">
                        <button class="btn btn-white" id="search-option" style="float: right;">
                            <i class="linecons-search"></i>
                            <span>Search</span>
                        </button>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'rowOptions' => function ($model, $index, $widget, $grid) {
                                $url = 'http://' . Yii::$app->getRequest()->serverName . Yii::$app->homeUrl . 'product/product/update?id=' . $model->id;
                                return ['data-id' => $model->id, 'onclick' => "window.location.href='{$url}'", 'onmouseover' => "this.style.backgroundColor='rgba(167, 167, 167, 0.52)',this.style.cursor='pointer'", 'onmouseout' => "this.style.backgroundColor=''"];
                            },
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'product_name',
                                'stock',
                                'price',
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".filters").slideToggle();
        $("#search-option").click(function () {
            $(".filters").slideToggle();
        });
    });
</script>


