<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CurrencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Currencies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="currency-index">
    <div class="row">
        <div class="col-md-12">
            <div class="page-title">

                <div class="title-env">
                    <h1 class="title"><?= Html::encode($this->title) ?></h1>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body"><div class="demo-create">

                        <?=
                        $this->render('_form', [
                            'model' => $model,
                        ])
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-body table-responsive">
                    <button class="btn btn-white" id="search-option" style="float: right;">
                        <i class="linecons-search"></i>
                        <span>Search</span>
                    </button>
                    <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
//                                'id',
                                'currency_name',
                                'currency_symbol',
//                                'currency_value',
//                                'CB',
                                // 'UB',
                                // 'DOC',
                                // 'DOU',
                                [
                                    'attribute' => 'status',
                                    'filter' => ['1' => 'Enable', '0' => 'Disable'],
                                    'value' => function($data) {
                                        return $data->status == 1 ? 'Enable' : 'Disable';
                                    }
                                ],
//                                ['class' => 'yii\grid\ActionColumn',
//                                    'template' => '{update}{delete}'],
                                [
                                    'class' => 'yii\grid\ActionColumn',
//                                    'contentOptions' => ['style' => 'width:100px;'],
                                    'header' => 'Actions',
                                    'template' => '{update}',
                                    'buttons' => [
                                        'update' => function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                                        'title' => Yii::t('app', 'update'),
                                                        'class' => '',
                                            ]);
                                        },
                                        'delete' => function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                                        'title' => Yii::t('app', 'delete'),
                                                        'class' => '',
                                                        'data' => [
                                                            'confirm' => 'Are you sure you want to delete this item?',
                                                        ],
                                            ]);
                                        },
                                    ],
                                    'urlCreator' => function ($action, $model) {
                                        if ($action === 'update') {
                                            $url = Url::to(['index', 'id' => $model->id]);
                                            return $url;
                                        }
//                                        if ($action === 'delete') {
//                                            $url = Url::to(['del', 'id' => $model->id]);
//                                            return $url;
//                                        }
                                    }
                                ],
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

