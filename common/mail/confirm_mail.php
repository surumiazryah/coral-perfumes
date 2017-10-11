<?php

use yii\helpers\Html;
use common\models\OrderDetails;
use common\models\OrderMaster;
use common\models\Product;
use common\models\Fregrance;
use common\models\Settings;

$order_products = OrderDetails::find()->where(['order_id' => $orderid])->all();
$order_master = OrderMaster::find()->where(['order_id' => $orderid])->one();
?>

<html>

    <head>
        <title>Confirm Order</title>
        <link href="<?= Yii::$app->homeUrl; ?>css/email.css" rel="stylesheet">
    </head>

    <body>
        <div class="mail-body" style="margin: auto;width: 50%;border: 1px solid #9e9e9e;">
            <div class="content" style="margin-left: 40px;">
                <h2 style="text-align: center;">ORDER SUCCESSFULLY CONFIRMED</h2>
                <p style="text-align: center;">Your order is successfully Confirmed. Thanks For Purchasing From Us. Your Order Id : <h3><?= $orderid ?></h3></p>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 hidden-xs my-order-list">

                <div class="orders-box">
                    <?php
                    foreach ($order_products as $order_product) {
                        $product_detail = Product::find()->where(['id' => $order_product->product_id])->one();
                        ?>
                        <div class="ordered-pro-dtls">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <p><?= $product_detail->product_name ?></p>
                                <?php $product_type = Fregrance::findOne($product_detail->product_type); ?>
                                <p class="cart-pro-subheading"><?= $product_type->name;?></p>
                                </a>
                            </div>
                            <?php
                            if ($product_detail->offer_price == '0' || $product_detail->offer_price == '') {
                                $price = $product_detail->price;
                            } else {
                                $price = $product_detail->offer_price;
                            }
                            ?>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 price">AED  <?= $price; ?></div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 price">Quantity : <?= $order_product->quantity; ?></div>
                        </div>
                    <?php }
                    ?>
                    <div class="pro-order-detail">
                        
                        <?php
                        $shipping_limit = Settings::findOne('1')->value;
                        $shipextra = Settings::findOne('2')->value;
                        $ship_charge = $order_master->total_amount <= $shipping_limit ? sprintf('%0.2f', $shipextra) : 0.00
                        ?>
                        <p class="shipping">Shipping Charge <?= $ship_charge ?> </p>
                        <p class="order-total">Order Total: AED <?= sprintf('%0.2f', $order_master->net_amount) ?></p>
                    </div>
                </div>
            </div>
        </div>



    </body>
</html>