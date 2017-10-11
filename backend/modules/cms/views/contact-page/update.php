<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ContactPage */

$this->title = 'Update Contact Page';
$this->params['breadcrumbs'][] = ['label' => 'Contact Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
        <div class="col-md-12">

                <div class="panel panel-default">
                        <div class="panel-heading">
                                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
				<div  style="float: right"><?= Html::a('<span>Map Locations</span>', ['map-locations/index'], ['class' => 'btn btn-success']) ?></div>



                        </div>
                        <div class="panel-body">
                                <div class="panel-body"><div class="contact-page-create">
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
