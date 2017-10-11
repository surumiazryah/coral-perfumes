<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\services\DirectionsWayPoint;
use dosamigos\google\maps\services\TravelMode;
use dosamigos\google\maps\overlays\PolylineOptions;
use dosamigos\google\maps\services\DirectionsRenderer;
use dosamigos\google\maps\services\DirectionsService;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\services\DirectionsRequest;
use dosamigos\google\maps\overlays\Polygon;
use dosamigos\google\maps\layers\BicyclingLayer;

if (isset($meta_title) && $meta_title != '')
	$this->title = $meta_title;
else
	$this->title = 'coral perfumes';
$i = 0;
foreach ($map_locations as $maps) {
	$coord = new LatLng(['lat' => $maps->latitude, 'lng' => $maps->longitude]);
	if ($i == 0) {
		$map = new Map([
		    'center' => $coord,
		    'zoom' => 3,
		    'width' => '100%',
		    'height' => '350'
		]);
	}
	$marker = new Marker([
	    'position' => $coord,
	    'title' => $maps->title,
	]);
	$marker->attachInfoWindow(
		new InfoWindow([
	    'content' => $maps->content
		])
	);
	$map->addOverlay($marker);
	$i++;
}


$bikeLayer = new BicyclingLayer(['map' => $map->getName()]);

// Append its resulting script
$map->appendScript($bikeLayer->getJs());

// Display the map -finally :)
?>
<div class="pad-20 hide-xs"></div>

<div class="container">
        <div class="breadcrumb">
                <span class="current-page">Contact us</span>
                <ol class="path">
                        <li><?= Html::a('Home', ['/site/index'], ['class' => '']) ?></li>
                        <li class="active">Contact us</li>
                </ol>
        </div>
</div>
<div id="contact-page">
        <div class="g-map">
		<?php // $contact_data->map;  ?>
		<?= $map->display(); ?>

	</div>

        <div class="contact-info-box">
                <div class="contact-addresses col-lg-6 col-md-12 col-sm-12 col-xs-12 white-smoke pad-0">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 box-1 colxs-widthfull box-min-height">
                                <h6>Accounts/Administration</h6>
				<?= $contact_data->accounts_info; ?>
                                <!--				<ul>
                                                                        <li>Phone: 907-821-1234</li>
                                                                        <li>Email: office@coralperfumes.com</li>
                                                                </ul>-->
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 box-2 colxs-widthfull box-min-height">
                                <h6>Contract Manufacturing</h6>
				<?= $contact_data->administration_info; ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 box-3 colxs-widthfull box-min-height">
                                <h6>Product Inquiry</h6>
				<?= $contact_data->marketing_info; ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 box-4 colxs-widthfull box-min-height">
                                <h6>Coral Aroma</h6>
				<?= $contact_data->business_info; ?>
                        </div>
                </div>
                <div class="head-office-address col-lg-6 col-md-12 col-sm-12 col-xs-12 dark-lit-blue">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 box-4 colxs-widthfull box-min-height">
                                <!--<h6>Marketing</h6>-->
				<?= $contact_data->address_1; ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 box-4 colxs-widthfull box-min-height">
                                <!--<h6>Marketing</h6>-->
				<?= $contact_data->address_2; ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 box-4 colxs-widthfull box-min-height">
                                <!--<h6>Marketing</h6>-->
				<?= $contact_data->address_3; ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 box-4 colxs-widthfull box-min-height">
                                <!--<h6>Marketing</h6>-->
				<?= $contact_data->address_4; ?>
                        </div>
                </div>
        </div>
	<div class="link2showroom">
		<div class="container">
			<div class="col-md-12">
				<p class=""><?= $contact_data->shoroom_content; ?></p>
				<!--<a href="showrooms.php"></a>-->
				<?= Html::a('<button class="link-btn link-btn-large icon-right">Showrooms<i class="fa fa-space-shuttle"></i></button>', ['/site/showrooms'], ['class' => '']) ?>
			</div>
		</div>
	</div>

        <div class="container">
                <div class="contact-form-box">
                        <div class="col-md-12 text-center">
                                <h3>Get in touch with us</h3>
                                <p><?= $contact_data->content; ?></p>			</div>
                        <br class="hidden-lg hidden-md hidden-sm"/>
			<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-feild-box margin-auto xs-pad-0">
                                <div class="col-lg-10 col-md-8 col-sm-8 col-xs-12 cntnt-center">

                                        <div class="row">
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group1">
							<?= $form->field($model, 'first_name')->textInput(['placeholder' => 'First Name'])->label('First Name*') ?>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group1">
							<?= $form->field($model, 'last_name')->textInput(['placeholder' => 'Last Name'])->label('Last Name*') ?>
                                                </div>
                                        </div>
                                        <div class="row">
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group1">
							<?= $form->field($model, 'email')->textInput(['placeholder' => 'yourname@domain.com'])->label('E-Mail Address*') ?>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group1">
							<?= $form->field($model, 'mobile_no')->textInput(['placeholder' => '555 555 5555', 'data-format' => '+1 (ddd) ddd-dddd', 'style' => ''])->label('Mobile Number*') ?>
                                                </div>
                                        </div>
                                        <div class="row">
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group1">
							<?= $form->field($model, 'country')->textInput(['placeholder' => 'Country'])->label('Country*'); ?>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group1">
							<?php $form->field($model, 'reason')->dropDownList(['General Questions' => 'General Questions', 'Some Reason' => 'Some Reason', 'Technical' => 'Technical', 'Help' => 'Help'], ['class' => 'select'])->label('Reason for Contact*'); ?>
							<?= $form->field($model, 'reason')->textArea(['rows' => 2])->label('Reason for Contact*'); ?>
                                                </div>
                                        </div>
                                        <div class="row">
                                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group1">
                                                        <div class="g-recaptcha" id="g-recaptcha"></div>
                                                </div>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12" style="text-align: center;float: none;margin: 0 auto;left: 0px;right: 0px;clear: both;">
						<?= Html::submitButton('submit', ['class' => 'green2']) ?>
                                        </div>

                                </div>
                        </div>
			<?php ActiveForm::end(); ?>
                </div>
        </div>
        <div class="pad-20"></div>
        <script>
		$(document).ready(function () {
			$('#contact-form').on('submit', function (e) {
				var res = grecaptcha.getResponse();
				if (res == "" || res == undefined || res.length == 0)
				{
					e.preventDefault();
					if ($("#g-recaptcha").next(".validation").length == 0) // only add if not added
					{
						$("#g-recaptcha").after("<div class='validation' style='color:#c54040;text-align: center;font-size: 13px;margin-bottom: 14px;'>Please verify that you are not a robot</div>");
					}
				}
			});
		});
        </script>
</div>

