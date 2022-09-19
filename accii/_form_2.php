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

    .alert {
    top: 45px;
    position: absolute;
    width: 100%;
    margin-top: 50px;
    }
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

    .alert {margin-top: 50px;}
}

@media (max-width:425px)
{
    
    .head-acii{ background-size: 100% 100%}
    .parent{display: block !important; padding: 0;}
    
    .img-acc {width: 100%; padding: 0;}

    .alert {margin-top: 50px;}
}

CSS;

$this->registerCss($css);


 // $cities = [];

 // foreach ($city as $key => $value) {
 //     $cities [$key] = $value->city;
 // }

$space = '';


if (!empty(Yii::$app->request->get())) {
    $data = Yii::$app->request->get('data');
    //dd($data);die;
}
// dd('tyt');die;

$this->registerJsFile("https://www.google.com/recaptcha/api.js", ['position' => $this::POS_END, 'async'=>'async', 'defer'=>'defer']);

?>
<div class="head-acii"><h1 style="margin-bottom: 0;">Конкурс</h1><p></p></div>
        <div class="parent">
            <div class="detail-acc">
                <div class="with-det">
                    <h1 style="margin-bottom: 5px; color: #000000; text-align: left;"></h1>
                    <p></p>
                </div>               

               <?= Html::beginForm(['accii/konkurs', 'id' => 'konkurs'], 'post', ['enctype' => 'multipart/form-data', /*'novalidate' => 'novalidate', */'class' => 'needs-validation']) ?>

                    <?= Html::input('hidden', 'accii_id', $id) ?>

                    <?= Html::input('hidden', Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>                                     

                    
                            <div>

                                <label class="control-label" for ="first_name">Имя <span style="color: red;">*</span></label>
                                <?php if ($data['first_name']): ?>

                                    <?= Html::input('text', 'first_name', $data['first_name'],['label' => 'Имя *', 'class' => 'form-control is-invalid', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'first_name']) ?>

                                <?php else: ?>

                                    <?= Html::input('text', 'first_name', $space,['label' => 'Имя *', 'class' => 'form-control is-invalid', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'first_name']) ?>

                                <?php endif ?>        
                                    
                                   
                                <label class="control-label" for ="second_name">Фамилия <span style="color: red;">*</span></label>

                                <?php if ($data['second_name']): ?>

                                   <?= Html::input('text', 'second_name', $data['second_name'],['label' => 'Фамилия *', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'second_name']) ?>

                                <?php else: ?>

                                    <?= Html::input('text', 'second_name', $space,['label' => 'Фамилия *', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'second_name']) ?>

                                <?php endif ?>  
                                
                                <label class="control-label" for ="fathers_name">Отчество <span style="color: red;">*</span></label>

                                <?php if ($data['fathers_name']): ?>

                                  <?= Html::input('text', 'fathers_name', $data['fathers_name'],['label' => 'Отчество', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'fathers_name']) ?>

                                <?php else: ?>

                                   <?= Html::input('text', 'fathers_name', $space,['label' => 'Отчество', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'fathers_name']) ?>

                                <?php endif ?>
                                
                            </div>
                       
                            <div>
                                <label class="control-label" for ="city">Ваш город <span style="color: red;">*</span></label>
                                <? //= Html::dropDownList('city', '' , $cities,['label' => 'Ваш город *', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'city']) ?>
                                <?php if ($data['city']): ?>

                                  <?= Html::input('text', 'city', $data['city'],['label' => 'Ваш город', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'city']) ?>

                                <?php else: ?>

                                   <?= Html::input('text', 'city', $space,['label' => 'Ваш город', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'city']) ?>

                                <?php endif ?>


                            </div>                          
                    
                            <div>  
                                <label class="control-label" for ="email">e-mail <span style="color: red;">*</span></label>

                                <?php if ($data['email']): ?>

                                  <?= Html::input('email', 'email', $data['email'],['label' => 'e-mail *', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'email']) ?>  

                                <?php else: ?>

                                   <?= Html::input('email', 'email', $space,['label' => 'e-mail *', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'email']) ?>  

                                <?php endif ?>
                                    
                            </div>          
                      
                            <div>
                                <label class="control-label" for ="mob_phone">Телефон <span style="color: red;">*</span></label>

                                <?php if ($data['mob_phone']): ?>

                                  <?= Html::input('tel', 'mob_phone',  $data['mob_phone'],['label' => 'Телефон *', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'mob_phone', 'pattern' => '^((\+7|7|8)+([0-9]){10})$','placeholder' => '7(999)-999-9999']) ?>

                                <?php else: ?>

                                   <?= Html::input('tel', 'mob_phone',  $space,['label' => 'Телефон *', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'mob_phone', 'pattern' => '^((\+7|7|8)+([0-9]){10})$','placeholder' => '7(999)-999-9999']) ?>

                                <?php endif ?>

                                                                                                 
                            </div>
                            <div>
                                <label class="control-label" for ="clinic_name">Название клиники (если вы делаете закупку на определенную клинику)</label>

                                <?php if ($data['clinic_name']): ?>

                                  <?= Html::input('text', 'clinic_name', $data['clinic_name'] ,['label' => 'Название клиники (если вы делаете закупку на определенную клинику)', 'class' => 'form-control', 'aria-invalid' => 'true', 'id' => 'clinic_name']) ?>

                                <?php else: ?>

                                   <?= Html::input('text', 'clinic_name', $space,['label' => 'Название клиники (если вы делаете закупку на определенную клинику)', 'class' => 'form-control', 'aria-invalid' => 'true', 'id' => 'clinic_name']) ?>

                                <?php endif ?>
                                
                            </div>
                            <div class="capcha" style="padding-top: 20px">
                              <!-- <div class="g-recaptcha" data-sitekey="6LclS8QUAAAAAIbxdZa2jb9GQLsT97O3BfE_Rje9"></div> -->

                              <?= \himiklab\yii2\recaptcha\ReCaptcha::widget([
                                    'name' => 'reCaptcha',
                                    'siteKey' => '6LclS8QUAAAAAIbxdZa2jb9GQLsT97O3BfE_Rje9',
                                    //'action' => Yii::$app->request->referrer, 
                                    'widgetOptions' => ['class' => 'g-recaptcha'/*, 'required' => 'required'*/],
                                ]) ?>
                              <? //= \himiklab\yii2\recaptcha\ReCaptcha::widget (['name' => 'reCaptcha', ]) ?>
                          </div>
                      
                   
                      <div style="text-align: center;padding-top: 15px">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success', 'id' => 'btn_send']) ?>
                      </div>
                   
                    <?= Html::endForm() ?> 
        </div>        
   

