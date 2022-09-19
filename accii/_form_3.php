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

$js = <<<JS

InvalidInputHelper(document.getElementById("mob_phone"), {
    defaultText: "Введен неверный номер телефона!",
    emptyText: "Введен неверный номер телефона!",
    invalidText: function (input) {
        return 'Введен неверный номер телефона';
    }
});

JS;

$this->registerJs($js);
 $cities = [];

 foreach ($city as $key => $value) {
     $cities [] = $value->city;
 }

$space = '';

?>
<div class="head-acii"><h1 style="margin-bottom: 0;">Конкурс</h1><p></p></div>
        <div class="parent">
            <div class="detail-acc">
                <div class="with-det">
                    <h1 style="margin-bottom: 5px; color: #000000; text-align: left;"></h1>
                    <p></p>
                </div>               

               <?= Html::beginForm(['konkurs', 'id' => 'konkurs'], 'post', ['enctype' => 'multipart/form-data']) ?>

                    <?= Html::input('hidden', 'accii_id', $id) ?>

                    <?= Html::input('hidden', Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>                                     

                    <div>
                        <?php if(!empty($user->fio)) :?>
                            <div>
                                <label class="control-label" for ="first_name">ФИО</label>
                                <?= Html::input('text', 'fio', trim($user->fio),['label' => 'ФИО', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'fio']) ?>
                                
                            </div>
                        <?php else :?>
                            <div>
                                <label class="control-label" for ="fio">ФИО</label>
                                <?= Html::input('text', 'fio', $space ,['label' => 'ФИО', 'class' => 'form-control', 'required' => 'required', 'id' => 'fio']) ?>
                            </div>
                        <?php endif ?>    
                    </div>  
                    <div>
                        <?php
                            if(!empty($user->city_id)){
                                $city = City::find()->where(['id' => $user->city_id])->one();
                            }

                            ?>
                            <?php if(!empty($user->city_id)) :?>
                                <div>
                                    <label class="control-label" for ="city">Ваш город</label>
                                    <?= Html::input('text', 'city', $city->city,['label' => 'Ваш город', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'city']) ?>
                                </div>
                            <?php else :?>
                               <div>
                                <label class="control-label" for ="city">Ваш город</label>
                                <?= Html::input('text', 'city', $space, ['label' => 'Ваш город', 'class' => 'form-control', 'required' => 'required', 'id' => 'city']) ?>
                               </div>
                            <?php endif;?>
                    </div>
                    <div>                         
                        <?php if(!empty($user->email)) :?>
                            <div>  
                                <label class="control-label" for ="email">e-mail</label>
                                <?= Html::input('email', 'email', $user->email,['label' => 'e-mail', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'email']) ?>      
                            </div>
                        <?php else : ?>
                            <div>
                                <label class="control-label" for ="email">e-mail</label>
                                <?= Html::input('email', 'email', $space, ['label' => 'e-mail', 'class' => 'form-control', 'required' => 'required', 'id' => 'email']) ?>                                
                            </div>
                        <?php endif;?>
                      </div>
                      <div>                          
                        <?php if(!empty($user->mobile_phone)) :?>
                            <div>
                                <label class="control-label" for ="mob_phone">Телефон</label>
                                <?= Html::input('tel', 'mob_phone', $user->mobile_phone,['label' => 'Телефон', 'class' => 'form-control', 'required' => 'required', 'aria-invalid' => 'true', 'id' => 'mob_phone', 'pattern' => '[0-9]{11}','placeholder' => '7(999)-999-9999']) ?>                                                                 
                            </div>
                        <?php else : ?>
                            <div>
                                <label class="control-label" for ="mob_phone">Телефон</label>
                                <?= Html::input('tel', 'mob_phone', $space, ['label' => 'Телефон', 'class' => 'form-control', 'required' => 'required', 'id' => 'mob_phone', 'pattern' => '[0-9]{11}', 'placeholder' => '7(999)-999-9999'])?>
                            </div>
                        <?php endif;?>
                      </div>
                      
                   
                      <div style="text-align: center;padding-top: 15px">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'id' => 'btn_send']) ?>
                      </div>
                   
                    <?= Html::endForm() ?> 
        </div>        
   

