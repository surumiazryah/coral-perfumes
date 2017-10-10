<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CmsOthers */

$this->title = 'Update: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Cms Others', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
        <div class="col-md-12">

                <div class="panel panel-default">
                        <div class="panel-heading">
                                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>


                        </div>
                        <div class="panel-body">
				<?= Html::a('<i class="fa-th-list"></i><span> Manage Others</span>', ['index'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                <div class="panel-body"><div class="cms-others-create">
						<?=
						$this->render('_form', [
						    'model' => $model,
						])
						?>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
</div>
