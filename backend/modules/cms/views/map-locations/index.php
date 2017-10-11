<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MapLocationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contact Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demo-index">

        <div class="row">
                <div class="col-md-12">
                        <div class="page-title">

                                <div class="title-env">
                                        <h3 class="title" style="float: left;
					    font-size: 13px;
					    text-transform: uppercase;
					    font-weight: bold;
					    color: #0c0c0c;"><?= Html::encode($this->title) ?></h3>

                                </div>
				<div  style="float: right"><?= Html::a('<span>Contact Page</span>', ['contact-page/update?id=1'], ['class' => 'btn btn-success']) ?></div>
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

					<?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

					<?=
					GridView::widget([
					    'dataProvider' => $dataProvider,
					    'filterModel' => $searchModel,
					    'columns' => [
						    ['class' => 'yii\grid\SerialColumn'],
						'title',
						'content',
						'latitude',
						'longitude',
						    [
						    'attribute' => 'status',
						    'filter' => ['1' => 'Enable', '0' => 'Disable'],
						    'value' => function($data) {
							    return $data->status == 1 ? 'Enable' : 'Disable';
						    }
						],
						    [
						    'class' => 'yii\grid\ActionColumn',
						    'header' => 'Actions',
						    'template' => '{update}{delete}',
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
							    if ($action === 'delete') {
								    $url = Url::to(['delete', 'id' => $model->id]);
								    return $url;
							    }
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


<script>
	$(document).ready(function () {
		$(".filters").slideToggle();
		$("#search-option").click(function () {
			$(".filters").slideToggle();
		});
	});
</script>

