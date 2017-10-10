<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PrivateLabelHowItWorksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'UpdateShowrooms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="private-label-how-it-works-index">

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>


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

					<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

					<?=
					GridView::widget([
					    'dataProvider' => $dataProvider,
					    'filterModel' => $searchModel,
					    'columns' => [
						    ['class' => 'yii\grid\SerialColumn'],
						//'id',
						//'id',
						'title',
						//'address:ntext',
						'email:email',
						    [
						    'attribute' => 'image',
						    'format' => 'raw',
						    'value' => function ($data) {
							    if (isset($data->image)) {
								    $img = '<img width="" src="' . Yii::$app->homeUrl . '../uploads/cms/showrooms/' . $data->id . '/small.' . $data->image . '?' . rand() . '"/>';
							    } else {
								    $img = '';
							    }
							    return $img;
						    },
						],
						// 'map',
						[
						    'attribute' => 'status',
						    'filter' => ['1' => 'Enable', '0' => 'Disable'],
						    'value' => function($data) {
							    return $data->status == 1 ? 'Enable' : 'Disable';
						    }
						],
						// 'CB',
						// 'UB',
						// 'DOC',
						// 'DOU',
						[
						    'class' => 'yii\grid\ActionColumn',
//                                    'contentOptions' => ['style' => 'width:100px;'],
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
								    $url = Url::to(['del', 'id' => $model->id]);
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

