<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CustomerReviews */

$this->title = 'Update Reviews: ';
$this->params['breadcrumbs'][] = ['label' => 'Customer Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>


            </div>
            <div class="panel-body">
                <?= Html::a('<i class="fa-th-list"></i><span> Manage Customer Reviews</span>', ['index'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                <?php if ($model->status == 0) { ?>
                    <?= Html::a('<i class="fa fa-check"></i><span> Approve</span>', ['approve', 'id' => $model->id], ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']) ?>
                <?php } else { ?>
                    <?= Html::a('<i class="fa fa-ban"></i><span> Disable</span>', ['disable', 'id' => $model->id], ['class' => 'btn btn-red btn-icon btn-icon-standalone']) ?>
                <?php }
                ?>
                <div class="panel-body"><div class="customer-reviews-create">
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
