<?php

namespace frontend\controllers;

use yii;
use common\models\OrderMaster;
use common\models\UserAddress;
use common\models\OrderDetails;
use common\models\Product;
use common\models\Settings;
use common\models\Cart;
use common\models\OrderPromotions;
use yii\helpers\ArrayHelper;
use common\models\CreateYourOwn;

class CheckoutController extends \yii\web\Controller {

    public function init() {
        date_default_timezone_set('Asia/Kolkata');
    }

    public function actionCheckout() {

        if (isset(Yii::$app->user->identity->id)) {
            $address = UserAddress::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
            $country_codes = ArrayHelper::map(\common\models\CountryCode::find()->where(['status' => 1])->orderBy(['id' => SORT_ASC])->all(), 'id', 'country_code');
//			if (empty($model)) {
            $model = new UserAddress();
            if ($model->load(Yii::$app->request->post())) {
                if (isset(Yii::$app->request->post()['UserAddress']['billing'])) {
                    $bill_address = Yii::$app->request->post()['UserAddress']['billing'];
                    $this->orderbilling($bill_address);
                }
                Yii::$app->SetValues->Attributes($model);
                $model->user_id = Yii::$app->user->identity->id;
                $model->status = 1;
                if ($model->save()) {
                    $this->orderbilling($model->id);
                }
            }
            return $this->render('billing', ['model' => $model, 'addresses' => $address, 'country_codes' => $country_codes,]);
        } else {
            $this->redirect(array('site/login'));
        }
    }

    function orderbilling($bill_address) {
        $model1 = OrderMaster::find()->where(['order_id' => Yii::$app->session['orderid']])->one();
        $model1->bill_address_id = $bill_address;
        $model1->status = 2;
        $model1->save();
        if (isset(Yii::$app->request->post()['UserAddress']['check'])) {
            $model1->ship_address_id = $bill_address;
            $model1->status = 3;
            $model1->save();
            $this->redirect(array('checkout/confirm'));
        } else {
            $this->redirect(array('checkout/delivery'));
        }
    }

    public function actionDelivery() {
        if (isset(Yii::$app->user->identity->id)) {
            if (Yii::$app->session['orderid']) {
                $address = UserAddress::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
                $model = new UserAddress();
                $country_codes = ArrayHelper::map(\common\models\CountryCode::find()->where(['status' => 1])->orderBy(['id' => SORT_ASC])->all(), 'id', 'country_code');
                if ($model->load(Yii::$app->request->post())) {
                    if (Yii::$app->request->post()['Deliveryaddress']['billing'] != '') {
                        $bill_address = Yii::$app->request->post()['Deliveryaddress']['billing'];
                        $model1 = OrderMaster::find()->where(['order_id' => Yii::$app->session['orderid']])->one();
                        $model1->ship_address_id = $bill_address;
                        $model1->status = 3;
                        if ($model1->save()) {
                            $this->redirect(array('checkout/confirm'));
                        }
                    } else {
                        Yii::$app->SetValues->Attributes($model);
                        $model->user_id = Yii::$app->user->identity->id;
                        if ($model->save()) {

                            //$this->redirect(array('checkout/confirm'));
                            $this->redirect(array('checkout/confirm'));
                        }
                    }
                }
                return $this->render('delivery', ['model' => $model, 'addresses' => $address, 'country_codes' => $country_codes]);
            } else {
                $this->redirect(array('cart/mycart'));
            }
        } else {
            $this->redirect(array('site/login'));
        }
    }

    public function actionPromotion() {
        if (isset(Yii::$app->user->identity->id)) {
            $promotion = new \common\models\Promotions;
            $order_master = OrderMaster::find()->where(['order_id' => Yii::$app->session['orderid']])->one();
            $added_promotions = OrderPromotions::find()->where(['order_master_id' => $order_master->id])->all();
            if ($promotion->load(Yii::$app->request->post())) {
                return $this->redirect(array('checkout/checkout'));
            }

            return $this->render('promotions', ['promotion' => $promotion, 'added_promotions' => $added_promotions]);
        } else {
            $this->redirect(array('site/login'));
        }
    }

    /*
     * check the promotion code is already used or not
     */

    public function CodeUsed($code_exists) {
        $code_used_list = explode(',', $code_exists->code_used);
        if (($code_exists->code_usage == 1)) {
            if (!in_array(Yii::$app->user->identity->id, $code_used_list)) {
                $permision = 0;
            } else {
                $permision = 1;
            }
        } else {
            $permision = 0;
        }

        return $permision;
    }

    /*
     * check the promotion code validity
     */

    public function CheckDate($code_exists) {
        $date_from_user = date('Y-m-d');
        $start_ts = strtotime($code_exists->starting_date);
        $end_ts = strtotime($code_exists->expiry_date);
        $user_ts = strtotime($date_from_user);
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    /*
     * check the purchased product or user is in this promotion code
     */

    public function PromotionProduct($code_exists, $code) {
        $products = explode(',', $code_exists->product_id);
        $users = explode(',', $code_exists->user_id);
        $order_master = OrderMaster::find()->where(['order_id' => Yii::$app->session['orderid']])->one();
        $oreder_setails = OrderDetails::find()->where(['master_id' => $order_master->id])->all();
        $exist = 0;

        if ($code_exists->promotion_type == 1 || $code_exists->promotion_type == 3) {
            foreach ($oreder_setails as $value) {
                if (in_array($value->product_id, $products)) {
                    $exist = 1;
                }
            }
        }
        if ($code_exists->promotion_type == 2 || $code_exists->promotion_type == 3) {
            if (in_array($order_master->user_id, $users))
                $exist = 1;
        }
        return $exist;
    }

    /*
     * add this user used this code
     */

    public function AddUsed($code_exists, $order_master) {

        $code_exists->code_used = $code_exists->code_used . ',' . Yii::$app->user->identity->id;
        $code_exists->save();
    }

    public function actionConfirm() {
        if (isset(Yii::$app->user->identity->id)) {
            if (Yii::$app->session['orderid']) {
                $order_master = OrderMaster::find()->where(['order_id' => Yii::$app->session['orderid']])->one();
                $order_details = OrderDetails::find()->where(['order_id' => Yii::$app->session['orderid']])->all();
                $total_amt = $this->total($order_details);
                $shipping_limit = Settings::findOne('1')->value;
                $promotions = OrderPromotions::find()->where(['order_master_id' => $order_master->id])->sum('promotion_discount');

                return $this->render('confirm', ['order_details' => $order_details, 'subtotal' => $total_amt, 'shipping_limit' => $shipping_limit, 'order_master' => $order_master, 'promotions' => $promotions]);
            } else {
                $this->redirect(array('cart/mycart'));
            }
        } else {
            $this->redirect(array('site/login'));
        }
    }

    public function actionConfirm_order() {
        if (isset(Yii::$app->user->identity->id)) {
            if (Yii::$app->session['orderid']) {
                $model = OrderMaster::find()->where(['order_id' => Yii::$app->session['orderid']])->one();

                $model->status = 4;
                if ($model->save()) {
                    $this->stock_change();
                    return $this->redirect(['payment', 'id' => $model->order_id]);
//                    $this->sendMail(Yii::$app->session['orderid']);
//                    Yii::$app->session['orderid'] = '';
//                    $model1 = Cart::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
//                    $this->clearcart($model1);
//                    $this->redirect(array('site/index'));
                }
            } else {
                $this->redirect(array('cart/mycart'));
            }
        } else {
            $this->redirect(array('site/login'));
        }
    }

    function stock_change() {
        $order_details = OrderDetails::find()->where(['order_id' => Yii::$app->session['orderid']])->all();
        foreach ($order_details as $detail) {
            if ($detail->item_type == '0') {
                $product = Product::findOne($detail->product_id);
                $product->stock = ($product->stock - $detail->quantity);
                $product->save();
            }
        }
    }

    public function sendMail($orderid) {
        $mail = \common\models\User::findOne(Yii::$app->user->identity->id)->email;
        $message = Yii::$app->mailer->compose('confirm_mail', ['orderid' => $orderid]) // a view rendering result becomes the message body here
                ->setFrom('no-replay@coralperfumes.com')
                ->setTo($mail)
                ->setSubject('Order Confirm');
//       echo $message;exit;
        $message->send();
        return TRUE;
    }

    public function actionPayment($id) {

        return $this->redirect(['payment-success', 'id' => $id]);
    }

    /*
     * payment success function
     */

    public function actionPaymentSuccess($id) {
        $model = OrderMaster::find()->where(['order_id' => $id])->one();
        if (!empty($model)) {
            $this->sendMail($id);
            $model->payment_status = 1; /* payment success for 1 and 0 for fail */
            $model->save();
            Yii::$app->session['orderid'] = '';
            $model1 = Cart::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
            $this->clearcart($model1);
        }

        $this->redirect(array('site/index'));
    }

    function clearcart($models) {
        foreach ($models as $model) {
            $model->delete();
        }
    }

    public function total($cart) {
        $subtotal = '0';
        foreach ($cart as $cart_item) {
            if ($cart_item->item_type == 1) {
                $subtotal += ($cart_item->rate);
            } else {
                $product = Product::findOne($cart_item->product_id);

                if ($product->offer_price == '0' || $product->offer_price == '') {
                    $price = $product->price;
                } else {
                    $price = $product->offer_price;
                }
                $subtotal += ($price * $cart_item->quantity);
            }
        }
        return $subtotal;
    }

    public function total_continue($cart) {
        $subtotal = '0';
        foreach ($cart as $cart_item) {
            if ($cart_item->item_type == 1) {
                $subtotal += ($cart_item->amount * $cart_item->quantity);
            } else {
                $product = Product::findOne($cart_item->product_id);
                if ($product->stock > 0 && $product->stock_availability == '1') {
                    if ($product->offer_price == '0' || $product->offer_price == '') {
                        $price = $product->price;
                    } else {
                        $price = $product->offer_price;
                    }
                    if ($product->stock >= $cart_item->quantity) {
                        $quantity = $cart_item->quantity;
                    } else {
                        $quantity = $product->stock;
                    }
                    $subtotal += ($price * $quantity);
                }
            }
        }
        return $subtotal;
    }

    public function actionGetadress() {
        if (yii::$app->request->isAjax) {
            $id = Yii::$app->request->post()['id'];
            if (isset($id)) {
                $address = UserAddress::find()->where(['user_id' => Yii::$app->user->identity->id, 'id' => $id])->one();
                if ($address) {
                    echo json_encode(['rslt' => 'success', 'name' => $address->name, 'address' => $address->address, 'landmark' => $address->landmark, 'location' => $address->location, 'emirate' => $address->emirate, 'post_code' => $address->post_code, 'mobile_number' => $address->mobile_number, 'country_code' => $address->country_code]);
                } else {
                    echo json_encode(['rslt' => 'error', 'msg' => 'No details Found']);
                }
            } else {
                echo json_encode(['rslt' => 'error', 'msg' => 'address cannot be set']);
            }
        }
    }

    public function actionPromotionCheck() {
        if (Yii::$app->request->isAjax) {
            $code = $_POST['code'];
            $code_exists = \common\models\Promotions::find()->where(['promotion_code' => $code])->one();

            if (!empty($code_exists)) {

                $date_check = $this->CheckDate($code_exists);
                if ($date_check == 1) {
                    $used = $this->CodeUsed($code_exists);
                    if ($used == 0) {
                        $exist = $this->PromotionProduct($code_exists, $code);
                        if ($exist == 1) {
                            $order_master = OrderMaster::find()->where(['order_id' => Yii::$app->session['orderid']])->one();
                            $prev_promotion = OrderPromotions::find()->where(['order_master_id' => $order_master->id, 'promotion_id' => $code_exists->id])->exists();
                            if (!$prev_promotion) {
                                $amount_range = $this->AmountRange($code_exists, $order_master);
                                if ($amount_range == 0) {
                                    if ($code_exists->type == 1) {
                                        $promotion_discount = ($order_master->total_amount * $code_exists->price) / 100;
                                    } else {
                                        $promotion_discount = $code_exists->price;
                                    }

                                    $order_master->net_amount = $order_master->net_amount - $promotion_discount;
                                    $order_master->update();
                                    if ($code_exists->code_usage == 1) {
                                        $this->AddUsed($code_exists, $order_master);
                                    }
                                    $added_promotion = new OrderPromotions;
                                    $added_promotion->order_master_id = $order_master->id;
                                    $added_promotion->promotion_id = $code_exists->id;
                                    $added_promotion->promotion_discount = $promotion_discount;
                                    $added_promotion->save();
                                    $promotion_amount = OrderPromotions::find()->where(['order_master_id' => $order_master->id])->sum('promotion_discount');
                                    $arr_variable = array('con' => '1', 'code' => $code, 'total_amount' => $promotion_amount, 'amount' => $promotion_discount, 'discount_id' => $added_promotion->id);
                                    $data['result'] = $arr_variable;
                                    echo json_encode($data);
                                } else {
                                    $arr_variable = array('con' => '2', 'amount' => $code_exists->amount_range);
                                    $data['result'] = $arr_variable;
                                    echo json_encode($data);
                                }
                            } else {
                                echo '5';
                            }
                        } else {
                            echo '4';
                        }
                    } else {
                        echo '3';
                    }
                } else {
                    echo '2';
                }
            } else {
                echo '1';
            }
        }
    }

    public function AmountRange($code_exists, $order_master) {
        $amount_range = 0;
        if (isset($code_exists->amount_range) && $code_exists->amount_range != '') {
            if ($order_master->total_amount > $code_exists->amount_range)
                $amount_range = 0;
            else
                $amount_range = 1;
        }
        return $amount_range;
    }

    public function actionPromotionRemove() {
        if (Yii::$app->request->isAjax) {
            $promo_id = $_POST['id'];
            $order_promotion = OrderPromotions::findOne($promo_id);
            $promotion = \common\models\Promotions::findOne($order_promotion->promotion_id);
            $order_master = OrderMaster::findOne($order_promotion->order_master_id);
            $promotion_users = explode(',', $promotion->code_used);

            if (($key = array_search(Yii::$app->user->identity->id, $promotion_users)) !== false) {
                unset($promotion_users[$key]);
            }
            $promotion_users = implode(',', $promotion_users);
            $promotion->code_used = $promotion_users;
            $promotion->save();
            $order_master->net_amount = $order_master->net_amount + $order_promotion->promotion_discount;
            $order_master->save();
            $order_promotion->delete();
        }
    }

    public function actionContinue($id) {
        Yii::$app->session['orderid'] = $id;
        $items = OrderDetails::find()->where(['order_id' => Yii::$app->session['orderid']])->all();
        $subtotal = $this->total_continue($items);
        return $this->render('continue', ['order_id' => Yii::$app->session['orderid'], 'items' => $items, 'subtotal' => $subtotal]);
//        $this->redirect(array('checkout/checkout'));
    }

    public function actionRemoveOrder($id) {
        $cart = OrderDetails::findone($id);
        $order_id = $cart->order_id;
        if ($cart->delete()) {
            return $this->redirect(['continue', 'id' => $order_id]);
        } else {
            return $this->redirect(['continue', 'id' => $order_id]);
        }
    }

    public function actionUpdatecart() {
        if (yii::$app->request->isAjax) {
            $cart_id = Yii::$app->request->post()['cartid'];
            $qty = Yii::$app->request->post()['quantity'];
            if (isset($cart_id)) {
                $cart = OrderDetails::findone($cart_id);
                if ($cart->item_type == '1') {
                    $prdct = CreateYourOwn::findOne($cart->product_id);
                    $cart->quantity = $qty > 100 ? 100 : $qty;
                    $price = $prdct->tot_amount;
                    $total = $cart->quantity * $prdct->tot_amount;
                } else {
                    $product = Product::findOne($cart->product_id);
                    if ($qty == 0 || $qty == "") {
                        $qty = 1;
                    }
                    $cart->quantity = $qty > $product->stock ? $product->stock : $qty;
                    ///
                    if ($product->offer_price == '0' || $product->offer_price == '') {
                        $price = $product->price;
                    } else {
                        $price = $product->offer_price;
                    }
                    $total = $price * $cart->quantity;
                }
                $cart->amount = $price;
                $cart->rate = $total;
                if ($cart->save()) {
                    $cart_items = OrderDetails::find()->where(['order_id' => $cart->order_id])->all();
                    if (!empty($cart_items)) {
                        $subtotal = $this->total_continue($cart_items);
                    }
                    $this->updatemaster($cart->order_id, $subtotal);
                    echo json_encode(array('msg' => 'success', 'quantity' => $cart->quantity, 'total' => sprintf('%0.2f', $total), 'subtotal' => sprintf('%0.2f', $subtotal)));
                } else {
                    echo json_encode(array('msg' => 'error', 'content' => 'Cannot be Changed'));
                }
            } else {
                echo json_encode(array('msg' => 'error', 'content' => 'Id cannot be set'));
            }
        }
    }

    function updatemaster($order_id, $subtotal) {
        $ordermaster = OrderMaster::find()->where(['order_id' => $order_id])->one();
        $limit = Settings::findOne(1)->value;
        $net_amnt = $subtotal;
        if ($limit > $subtotal) {
            $extra = Settings::findOne(2)->value;
            $net_amnt = $extra + $subtotal;
        }
        $ordermaster->total_amount = $subtotal;
        $ordermaster->net_amount = $net_amnt;
        $ordermaster->save();
    }

    public function actionProceed() {
        $orderdetails = OrderDetails::find()->where(['order_id' => Yii::$app->session['orderid']])->all();
        foreach ($orderdetails as $details) {
            $this->updatedetails($details);
        }
        $subtotal = $this->total_continue($orderdetails);
        $this->updatemaster(Yii::$app->session['orderid'], $subtotal);
        $this->continuepromotion();
        $this->redirect(array('checkout/promotion'));
    }

    function continuepromotion() {
        $order = OrderMaster::find()->where(['order_id' => Yii::$app->session['orderid']])->one();
        $order_promotion = OrderPromotions::find()->where(['order_master_id' => $order->id])->all();
        foreach ($order_promotion as $ordrpromo) {
            $promotion = \common\models\Promotions::findOne($ordrpromo->promotion_id);
            $promotion_users = explode(',', $promotion->code_used);

            if (($key = array_search(Yii::$app->user->identity->id, $promotion_users)) !== false) {
                unset($promotion_users[$key]);
            }
            $promotion_users = implode(',', $promotion_users);
            $promotion->code_used = $promotion_users;
            $promotion->save();
            $ordrpromo->delete();
        }
    }

    function updatedetails($details) {
        $product = Product::findOne($details->product_id);
        if ($details->item_type != 1) {
            if ($product->stock == '0' || $product->stock_availability == '0') {
                $details->delete();
                return TRUE;
            } elseif ($product->stock > '0' && $product->stock < $details->quantity) {
                $quantity = $product->stock;
            } elseif ($product->stock >= $details->quantity) {
                $quantity = $details->quantity;
            }
            if ($product->offer_price == '0' || $product->offer_price == '') {
                $price = $product->price;
            } else {
                $price = $product->offer_price;
            }
        }
        if ($details->item_type == 1) {
            $details->rate = $details->amount * $details->quantity;
        } else {
            $details->quantity = $quantity;
            $details->amount = $price;
            $details->rate = ($price * $quantity);
        }
        $details->save();
    }

}
