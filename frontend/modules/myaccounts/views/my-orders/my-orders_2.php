<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use common\models\OrderDetails;
use common\models\Product;
use common\models\Fregrance;
use common\models\Settings;

$order_products = OrderDetails::find()->where(['order_id' => $model->order_id])->all();
?>

<div class="col-lg-8 col-md-8 col-sm-12 hidden-xs my-order-list">

    <div class="orders-box">
        <div class="track">
            <button class="product-id"><?= $model->order_id ?></button>
            <?php if ($model->payment_status != '1') { ?>
                <?= Html::a('<i class="" aria-hidden="true"></i>Continue', ['/checkout/continue', 'id' => $model->order_id], ['class' => 'track-btn btn-success'])
                ?>
            <?php } ?>
            <?= Html::a('<i class="fa fa-ban" aria-hidden="true"></i>Cancel', ['/myaccounts/my-orders/cancel-order', 'id' => $model->order_id], ['class' => 'track-btn']) ?>
        </div>
        <?php
        foreach ($order_products as $order_product) {
            if ($order_product->item_type == 1) {
                $product_detail = \common\models\CreateYourOwn::findOne($order_product->product_id);
                $bottles = common\models\Bottle::findOne($product_detail->bottle);
                $product_image = Yii::$app->basePath . '/../uploads/create_your_own/bottle/' . $bottles->id . '/small.' . $bottles->bottle_img;
                if (file_exists($product_image)) {
                    $image = Yii::$app->homeUrl . 'uploads/create_your_own/bottle/' . $bottles->id . '/small.' . $bottles->bottle_img;
                }
            } else {
                $product_detail = Product::find()->where(['id' => $order_product->product_id])->one();
                $product_image = Yii::$app->basePath . '/../uploads/product/' . $product_detail->id . '/profile/' . $product_detail->canonical_name . '.' . $product_detail->profile;
                if (file_exists($product_image)) {
                    $image = Yii::$app->homeUrl . 'uploads/product/' . $product_detail->id . '/profile/' . $product_detail->canonical_name . '_thumb.' . $product_detail->profile;
                } else {
                    $image = Yii::$app->homeUrl . 'uploads/product/profile_thumb.png';
                }
            }
            ?>
            <div class="ordered-pro-dtls">
                <div class="pro-img-box col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <img src="<?= $image ?>" height="100%" alt="1" />
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <?php $name = $order_product->item_type == 1 ? 'Custom Perfume' : $order_product->item_type; ?>
                    <?= Html::a('<p class="cart-pro-heading">' . $name . '</p>', ['#'], ['target' => '_blank']) ?>


                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 price">Quantity : <?= $order_product->quantity; ?></div>



            </div>

        <?php
    }
    ?>


    <div style="clear:both"></div>
