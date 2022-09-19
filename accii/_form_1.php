<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
//$this->registerCssFile( '@web/css/serial.css' );
$this->registerJsFile( '@web/js/serial_check.js',  ['depends' => ['app\assets\AppAsset']]);

// $this->view->title = "Акции";
$css = <<<CSS

.content{padding-left: 0!important; padding-right: 0!important;}

.head-acii{ background-image: url(/images/style_a410tpl/siren.png); background-repeat: no-repeat;
    background-size: 100%; background-position: 70%;display: flex; justify-content: flex-start; display: flex; padding: 20px; padding-left: 16%;}

.parent{ display : flex ; justify-content: center;padding: 20px; margin: 20px 0; text-align: center; position: relative;}

h1 { font-size: 32px; }
.head-acii h1 {font-size: 48px; text-align: left ;}

.img-acc{width: 20%; display: inline-block; }

.img-acc img{ width: 100%; height: 100%; display: inline-block;}

.detail-acc{text-align: left !important; margin-left: 100px;}

.detail-acc a{background-color: #9b2386; color: #ffffff; border-color:#9b2386; border: 0 solid transparent; padding: 8px 10px; text-align: center;}

.detail-acc p {font-size: 18px; line-height: 20px; margin-bottom: 10px; text-align: left; margin-top: 10px;}

.detail-acc a {font-size: 16px; line-height: 18px; margin-bottom: 10px; text-align: justify; text-transform: uppercase;}

.detail-acc {width: 45%; display: inline-block; padding: 20px; }

.detail-acc div { text-align: left;}

.bottom  {display: flex; justify-content: space-around; margin-top: 50px;}

.share a{border: 2px solid #337ab7; border-radius: 20px 20px 20px 20px; }

.share a:hover{color: #23527c; border: 2px solid #23527c; border-radius: 20px 20px 20px 20px;}

.with-but {margin-top: 90px;}
.with-det{ position: absolute; top: 4%; }

@media (max-width:1024px)
{
    .content { padding: 0;  margin: 0;}
    .head-acii{padding: 0; margin-top: 95px; display: block; }

    .head-acii h1 {text-align: center ;}
}

@media (max-width:768px)
{
    .head-acii{padding: 30px;}
    .head-acii h1 {font-size: 28px !important; font-weight: 500; -webkit-box-shadow: none;}
    .brand-slider .brands {width: 100%;}
    .head-acii{padding: 0; margin-top: 95px; display: block; text-align: center;}

    h1 {margin-top: 0; font-weight: 500; font-size: 26px !important;}
    .parent{display: block ; margin: 40px 0}
    .img-acc {width: 35%;}
    .detail-acc{ text-align: center; width: 100%; display: block; margin-left: 0;}

    .detail-acc div { display: block;margin-top: 40px; position: relative; text-align: center!important;}
    .detail-acc p {text-align: center; width: 100%;}
    .with-det h1{text-align: center !important;}
}

@media (max-width:425px)
{
    
    .head-acii{ background-size: 100% 100%}
    .parent{display: block !important; padding: 0;}
    
    .img-acc {width: 100%; padding: 0;}
}

CSS;

$this->registerCss($css);

 $cities = [];

 foreach ($city as $key => $value) {
     $cities [] = $value->city;
 }

?>
<div class="head-acii"><h1 style="margin-bottom: 0;">Конкурс</h1><p></p></div>
        <div class="parent">
            <div class="detail-acc">
                <div class="with-det">
                    <h1 style="margin-bottom: 5px; color: #000000; text-align: left;"></h1>
                    <p></p>
                </div>               

                <?php $form = ActiveForm::begin(); ?>


                <?= $form->field($model, 'accii_id')->hiddenInput(['value'=> $id])->label(false); ?>
    
                <div class="page-content">
                   <div class="header-group" style="margin-bottom:15px;">
                       
                       <div class="row">
                           <div class="col-xs-12">
                                <?= $form->field($model, 'first_name')->textInput(['placeholder' => "Имя", 'required' => 'required']) ?>
                                <?= $form->field($model, 'second_name')->textInput(['placeholder' => "Фамилия", 'required' => 'required']) ?>
                                <?= $form->field($model, 'fathers_name')->textInput(['placeholder' => "Отчество", 'required' => 'required']) ?>

                                 <?= $form->field($model, 'city_id')->label('Город')->dropDownList($cities, ['prompt' => '-- Select --', 'id' => 'city', 'class' => 'form-control city_id', 'required' => 'required']); ?>

                                <?= $form->field($model, 'phone', ['template' => "{label}\n{input}"])->widget(\yii\widgets\MaskedInput::className(), [
                                        'mask' => '+9 (999) 999-9999',
                                        'clientOptions' => [
                                            'clearIncomplete' => true,
                                            'removeMaskOnSubmit' => true,
                                            //'aria-required' => true,
                                        ],
                                    ])->textInput(['required' => 'required']); ?>                               

                                <?= $form->field($model, 'email')->input('email')->textInput(['required' => 'required']); ?>

                                <?= $form->field($model, 'clinic_name')->textInput(['placeholder' => "Название клиники (если есть)"]) ?>


                            </div>
                        </div>
                    </div>
                    <div class="with-but">
                        <?= Html::submitButton('Отправить', ['class' => 'date-but', 'name'=>'submit']) ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>        
   

