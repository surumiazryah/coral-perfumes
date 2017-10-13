<?php

use yii\helpers\Html;
use common\models\Product;
//use common\models\Settings;
use common\models\Brand;

$this->title = 'Shopping Cart';
?>
<style>
    .th{
        color: #0e0e0e;
    }
    h6{
        font-size: 13px;
    }
</style>
<div class="pad-20 hide-xs"></div>

<div class="container">
    <div class="breadcrumb">
        <span class="current-page">Shopping cart</span>
        <ol class="path">
            <li><?= Html::a('<span>Home</span>', ['/site/index'], ['class' => '']) ?></li>
            <li class="active">Cart</li>
        </ol>
    </div>
</div>

<div id="cart-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 hidden-xs">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center"><p class="th">Product</p></th>
                            <th class="text-center"><p class="th">Price</p></th>
                            <th class="text-center"><p class="th">Quantity</p></th>
                            <th class="text-center"><p class="th">Total</p></th>
                            <th class="text-center"><p class="th">Remove</p></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($carts as $cart) {
                            ?>
                            <tr>
                                <?php
                                if ($cart->item_type == 1) {
                                    $product = \common\models\CreateYourOwn::findOne($cart->product_id);
                                    $bottles = common\models\Bottle::findOne($product->bottle);
                                    $product_image = Yii::$app->basePath . '/../uploads/create_your_own/bottle/' . $bottles->id . '/small.' . $bottles->bottle_img;
                                    if (file_exists($product_image)) {
                                        $image = Yii::$app->homeUrl . 'uploads/create_your_own/bottle/' . $bottles->id . '/small.' . $bottles->bottle_img;
                                    }
                                } else {
                                    $product = Product::findOne($cart->product_id);
                                    $product_image = Yii::$app->basePath . '/../uploads/product/' . $product->id . '/profile/' . $product->canonical_name . '.' . $product->profile;
                                    if (file_exists($product_image)) {
                                        $image = Yii::$app->homeUrl . 'uploads/product/' . $product->id . '/profile/' . $product->canonical_name . '_thumb.' . $product->profile;
                                    } else {
                                        $image = Yii::$app->homeUrl . 'uploads/product/profile_thumb.png';
                                    }
                                }
                                ?>
                                <td class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class="media">
                                        <a class="thumbnail pull-left col-lg-5 col-md-5 col-sm-12 col-xs-12" href="#"> <img class="media-object" src="<?= $image ?>"> </a>
                                        <div class="media-body">
                                            <h6 class="product-heading"><a href="#">
                                                    <?= $cart->item_type == 1 ? 'Custom Perfume' : $product->product_name; ?>
                                                    <?php
                                                    $label1 = '';
                                                    $label2 = '';
                                                    if (isset($product->label_1)) {
                                                        $label1 = $product->label_1;
                                                    }
                                                    if (isset($product->label_2)) {
                                                        $label2 = $product->label_2;
                                                    }
                                                    ?>
                                                </a></h6>
                                            <h6 class="brand-name"><a href="#"><?= $cart->item_type == 1 ? $label1 . ' , ' . $label2 : Brand::findOne($product->brand)->brand; ?></a></h6>
                                            <!--<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>-->
                                        </div>
                                    </div>
                                </td>
                                <?php
                                if ($cart->item_type == 1) {
                                    $price = $product->tot_amount;
                                } else {
                                    if ($product->offer_price == '0' || $product->offer_price == '') {
                                        $price = $product->price;
                                    } else {
                                        $price = $product->offer_price;
                                    }
                                }
                                ?>
                                <?php // $price = ($product->offer_price == '0' ? $product->price : $product->offer_price);     ?>
                                <td class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center price">AED <span class="price_<?= $cart->id ?>"><?= sprintf('%0.2f', $price); ?></span></td>
                                <td class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align: center">
                                    <div class="input-group number-spinner">
                                        <span class="input-group-btn data-dwn">
                                            <button class="btn btn-default btn-info cart_quantity cart_quantity-minus" id="<?= $cart->id; ?>" data-dir="dwn"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                        </span>
                                        <?php
                                        if ($cart->item_type == 1) {
                                            $max = 'Max=100';
                                        } else {
                                            $max = 'Max="' . $product->stock . '"';
                                        }
                                        ?>
                                        <input type='number'  name='cart_quantity' class="form-control text-center quantity" id="quantity_<?= $cart->id; ?>" value="<?= $cart->quantity ?>" min="1" <?= $max ?>>
                                        <span class="input-group-btn data-up">
                                            <button class="btn btn-default btn-info cart_quantity cart_quantity-plus" id="<?= $cart->id; ?>" data-dir="up"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <?php $total = $price * $cart->quantity; ?>
                                <td class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center price total_<?= $cart->id ?>">AED <?= sprintf('%0.2f', $total) ?></td>
                                <td class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
                                    <?= Html::a('<button type="button" class="btn-remove"><i class="fa fa-trash-o" aria-hidden="true"></i></button>', ['cart/cart_remove?id=' . $cart->id], ['class' => 'button']) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <div class="clearfix"></div>
                <div class="lit-blue sub-total">
                    <div class="col-md-12"><h4>Subtotal:<span class="price subtotal">AED <?= $subtotal?></span></h4></div>
                    <div class="col-md-12"><span class="message">Shipping, taxes, and discounts will be calculated at checkout.</span></div>
                    <br/>
                    <div class="col-md-12">
                        <!--<a  href="our-products.php"><button class="start-shopping">Continue shopping</button></a>-->
                        <?= Html::a('<button class="start-shopping">Continue shopping</button>', ['site/index'], ['class' => '']) ?>
                        <?= Html::a('<button class="green2">check out</button>', ['cart/checkout'], ['class' => 'button']) ?>
                        <!--<a  href="checkout.php"> <button class="green2">check out</button></a>-->
                    </div>
                </div>
            </div>

            <div class=" hidden-lg hidden-md hidden-sm col-xs-12 mob-car-list">
                <?php
                foreach ($carts as $cart) {
                    ?>
                    <div class="media">
                        <?php
                        if ($cart->item_type == 1) {
                            $product = \common\models\CreateYourOwn::findOne($cart->product_id);
                            $bottles = common\models\Bottle::findOne($product->bottle);
                            $product_image = Yii::$app->basePath . '/../uploads/create_your_own/bottle/' . $bottles->id . '/small.' . $bottles->bottle_img;
                            if (file_exists($product_image)) {
                                $image = Yii::$app->homeUrl . 'uploads/create_your_own/bottle/' . $bottles->id . '/small.' . $bottles->bottle_img;
                            }
                        } else {
                            $product = Product::findOne($cart->product_id);
                            $product_image = Yii::$app->basePath . '/../uploads/product/' . $product->id . '/profile/' . $product->canonical_name . '.' . $product->profile;
                            if (file_exists($product_image)) {
                                $image = Yii::$app->homeUrl . 'uploads/product/' . $product->id . '/profile/' . $product->canonical_name . '_thumb.' . $product->profile;
                            } else {
                                $image = Yii::$app->homeUrl . 'uploads/product/profile_thumb.png';
                            }
                        }
                        ?>
                        <div class="track">
                            <?= Html::a('<button title="Remove From Cart" class="remove-cart"><i class="fa fa-times" aria-hidden="true"></i></button>', ['cart/cart_remove?id=' . $cart->id], ['class' => 'button']) ?>

                            <!--<button class="add-cart green2">add to cart</button>-->
                        </div>
                        <a class="thumbnail pull-left col-xs-4" href="#"> <img class="media-object" src="<?= $image ?>"> </a>
                        <div class="col-xs-7">
                            <h4 class="product-heading"><a href="#"><?= $cart->item_type == 1 ? $product->label_1 : $product->product_name; ?></a></h4>
                            <h5 class="brand-name"><a href="#"><?= $cart->item_type == 1 ? $product->label_2 : Brand::findOne($product->brand)->brand; ?></a></h5>
                            <!--<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>-->
                            <div class="col-xs-4 pad-0">
                                <p><strong>Price</strong></p>
                            </div>
                            <div class="col-xs-7">
                                <?php
                                if ($cart->item_type == 1) {
                                    $price = $product->tot_amount;
                                } else {
                                    if ($product->offer_price == '0' || $product->offer_price == '') {
                                        $price = $product->price;
                                    } else {
                                        $price = $product->offer_price;
                                    }
                                }
                                ?>
                                <p class="text-center">AED <?= sprintf('%0.2f', $price); ?></p>
                            </div>
                            <div class="col-xs-4 pad-0">
                                <p><strong>Quantity</strong></p>
                            </div>
                            <div class="col-xs-7 text-center">
                                <select min="0" max="5" id="quantity2_<?= $cart->id; ?>" class="quantity" name="quantity">
                                    <?php
                                    $stocks = $cart->item_type == 1 ? 100 : $product->stock;
                                    for ($i = '1'; $i <= $stocks; $i++) {
                                        ?>
                                        <option  <?php if ($i == $cart->quantity) { ?>selected="selected"<?php } ?>value="<?= $i ?>"><?= $i ?></option>
                                    <?php }
                                    ?>


                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-xs-12 item-total">
                            <div class="col-xs-4 pad-0 heading">
                                <p><strong>Item Total</strong></p>
                            </div>
                            <div class="col-xs-7 amount">
                                <?php $total = $price * $cart->quantity; ?>
                                <p class="text-right total_<?= $cart->id ?>">AED <?= sprintf('%0.2f', $total)?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="lit-blue mob-sub-total sub-total">
                    <div class="col-md-12"><h4>Subtotal:<span class="price subtotal">AED <?= $subtotal ?></span></h4></div>
                    <div class="col-md-12"><span class="message">Shipping, taxes, and discounts will be calculated at checkout.</span></div>
                    <br/>
                </div>
            </div>

            <div class="lit-blue mob-checkout-buttons sub-total hidden-lg hidden-md hidden-sm">
                <div class="col-md-12">
                    <?= Html::a('<button class="green2">Continue shopping</button>', ['site/index'], ['class' => 'button']) ?>
                    <?= Html::a('<button class="green2">check out</button>', ['cart/proceed'], ['class' => 'button']) ?>
                    <!--<a  href="checkout.php"> <button class="green2">check out</button></a>-->
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<div class="pad-30 hidden-xs"></div>
<script>
    $(document).ready(function () {

        $('.cart_quantity-plus').on('click', function () {
            var id = $(this).attr('id');
            var max = $('#quantity_' + id).attr('max');
            var num = parseInt($('#quantity_' + id).val());
            var add = parseInt(num + 1);
            if (add <= max) {
                $('#quantity_' + id).val(add);
            }
        });
        $('.cart_quantity-minus').on('click', function () {
            var id = $(this).attr('id');
            var num = parseInt($('#quantity_' + id).val());
            var add = parseInt(num - 1);
            if (add >= 1) {
                $('#quantity_' + id).val(add);
            }
        });
    });
</script>
