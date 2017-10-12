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


<div class="row row-style">
	<div class="col-sm-12">

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
							<th width="">Price</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!empty($products)) {
							foreach ($products as $value) {
								?>
								<tr>
									<td><?= $value->product_name ?></td>
									<td><?= $value->stock ?></td>
									<td><?= $value->price ?></td>
								</tr>
								<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
			<div>
				<?= Html::a('<i class="fa-share"></i><span> View More</span>', ['product/product/stock-report'], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right', 'style' => 'margin-top: 8px;float:right;']) ?>
			</div>
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

