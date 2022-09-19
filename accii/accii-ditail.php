<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use yii\helpers\Html;

//$this->registerCssFile( '@web/css/serial.css' );
$this->registerJsFile( '@web/js/serial_check.js',  ['depends' => ['app\assets\AppAsset']]);

$css = <<<CSS

.content{padding-left: 0!important; padding-right: 0!important;}

.head-acii{ background-image: url(/images/style_a410tpl/siren.png); background-repeat: no-repeat;
    background-size: 100%; background-position: 70%;display: flex; justify-content: flex-start; display: flex; padding: 20px; padding-left: 21.5%;}

.head-acii h1 {font-size: 48px; text-align: left;}

.parent{ display: flex; justify-content: center;  /*display : block ;*/ padding: 50px; margin-bottom: 20px; text-align: center;}

.img-acc{width: 25%; height:  50%;display: block; margin-left: 20px;}

.img-acc img{ width: 100%; height: 100%; display: inline-block;}

.dates .date-but{background-color: #A4A4B8; color: #ffffff; border-color:#A4A4B8; border: 0 solid transparent; padding: 10px 20px; text-align: center;}

.detail-acc p {font-size: 18px; line-height: 20px; margin-bottom: 0 !important; text-align: left; margin-top: 10px;}

.detail-acc a {font-size: 18px; line-height: 20px; margin-bottom: 10px; text-align: justify; margin-top: 10px;}

.detail-acc {width: 35%; align-self: flex-start; margin-top: 10px;}

.detail-acc div {/*height: 80%;*/ text-align: center; }

.detail-acc ul {list-style: disc !important; text-align: left !important; padding: 20 !important;}

.detail-acc .take {display: flex; justify-content: flex-start; margin-top: 30px;}

.detail-acc .take a {color: #ffffff;  text-align: center; background-color: #9b2386;  border-color: #9b2386; border: 0 solid transparent; text-transform: uppercase; padding: 20px;}    

.bottom  {display: flex; margin-top: 70px;}

.share a img {width: 40px;}

.share p {text-align: center;}

.dates .date {margin-top: 11px;}

.footer-sosials {padding: 0!important;}

.spec-link{padding: 10px 44px;}

@media (max-width:1024px)
{
    .img-acc {width: 40%;}
    .content { padding: 0;  margin: 0;}
    .head-acii{padding: 0; margin-top: 95px; display: block; }
    .bottom{display: block; margin-top: 50px;}
    .head-acii h1 {text-align: center !important;}
    .share{margin-top: 50px;  text-align: left;}
    .share p { text-align: left;}
    /*.detail-acc div {text-align: left; }*/
    
}

@media (max-width:768px)
{
    
    .brand-slider .brands {width: 100%;}
    .head-acii{padding: 0px; margin-top: 95px; display: block; text-align: center;}
    .head-acii h1 {font-size: 28px; font-weight: 500; -webkit-box-shadow: none;}
    h1 {margin-top: 0; font-size: 26px;}
    .parent{display: block ;}
    .img-acc {background-size: 50% !important; background-position: 55% !important; width: 100%; margin-left:0; }
    .detail-acc{width: 100%; text-align: center;}
    /*.detail-acc div { display: block;margin-top: 20px; }*/
    .share p { text-align: center;}
    .detail-acc .take{display: block;}
    .detail-acc p{text-align: center;}
    .detail-acc a {margin-top: 5px;}

}

@media (max-width:425px)
{
    .detail-acc .take{display: block;}
    .head-acii{ background-size: 100% 100%}
    .parent{display: block !important; padding: 0;}
    .detail-acc {width: 100%;}
    .img-acc {width: 100%; padding: 0; display: none;}
    .share{margin-left: 0!important;}
}

@media (max-width:320px)
{
    .detail-acc .take a {padding: 0px;} 

}


CSS;



$js = <<<JS

$(".detail-acc > ul").style.list-style = 'disc';
$(".detail-acc > ul").style.textAlign = 'left';
$(".detail-acc > ul").style.padding = '20px';


JS;
$this->registerJs($js);

Yii::$app->params['og_title']['content'] = strip_tags($model->title);
Yii::$app->params['og_description']['content'] = strip_tags($model->text);
Yii::$app->params['og_url']['content'] = 'https://a410.ru/site/accii-detail?id='.$model->id;
Yii::$app->params['og_image']['content'] = 'https://files.a410.ru'.($model->image ? substr($model->image, 6) : '/service/no_photo.jpg');

$this->registerCss($css);

// dd($model);die;
?>

<div class="head-acii"><h1 style="margin-bottom: 0;"><?= $model->title ?></h1><p></p></div>
<div class="wraper">
    <div class="parent">
        <div class="detail-acc">            
            <p><?= $model->text ?></p>
            <?php if ($model->konkurs == 1): ?>
                <div class="take">
                    <?= Html::a('Заявка на участие в конкурсе', ['accii/konkurs', 'id'=> $model->id], ['class' => 'date-but spec-link', 'name'=>'submit']) ?>
                </div>
            <?php else: ?>  
                <?php if (!empty($model->url) && !empty($model->provid_at) && !empty($model->end_at)): ?>
                    <div class="take">
                       <a class="date-but" style="padding: 10px 55px;" href="<?=$model->url ?>" style="display: block;" ><?=$model->btn ?></a>
                    </div>

                <?php elseif (!empty($model->url)): ?>
                    <div class="take">
                       <a class="date-but" style="padding: 10px 44px;" href="<?=$model->url ?>" style="display: block;" ><?=$model->btn ?></a>
                    </div>
                <?php endif ?>  
            <?php endif ?>
            
            <div class="bottom">
                <?php if (!empty($model->provid_at) || !empty($model->end_at)): ?>
                     <?php if (!empty($model->provid_at) && !empty($model->end_at)): ?>
                        <div class="dates">
                           <p class="date-but">Дата проведения</p>                        

                                <p class="date" style="text-align: center;"><?= date('d.m.Y', strtotime($model->provid_at)) ?> - <?= date('d.m.Y',strtotime($model->end_at)) ?></p>
                        </div>  
                        <div class="share" style="margin-left: 50px;">
                            <div class="share-text"><p class="f-header">Поделиться акцией</p></div>
                            <a class="footer-sosials hv" href="https://vk.com/share.php?url=https%3A%2F%2Fa410.ru%2Fsite%2Faccii-detail%3Fid%3D<?=$model->id?>" target="_blank"><img src="/images/style_a410tpl/accii/vk.png" alt=""></a>

                            <a class="footer-sosials hv" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fa410.ru%2Fsite%2Faccii-detail%3Fid%3D<?=$model->id?>&amp;src=sdkpreparse" target="_blank"><img style = "margin-left: -10px; margin-right: -10px;" src="/images/style_a410tpl/accii/facebook.png" alt=""></a>

                            <a class="footer-sosials hv" href="https://t.me/share/url?url=https://a410.ru/site/accii-detail?id=<?=$model->id?>&text=<?=$model->title?>" target="_blank"><img src="/images/style_a410tpl/accii/telegram.png" alt=""></a>
                        </div>                

                        <?php elseif (!empty($model->provid_at) && empty($model->end_at)): ?>                          

                            <div class="dates">
                               <p class="date-but">Дата проведения</p>
                                    <p class="date" style="text-align: center;"><?= date('d.m.Y', strtotime($model->provid_at)) ?></p>
                            </div>  
                            <div class="share" style="margin-left: 50px;">
                                <p class="f-header">Поделится акцией</p>
                                <a class="footer-sosials hv" href="https://vk.com/share.php?url=https%3A%2F%2Fa410.ru%2Fsite%2Faccii-detail%3Fid%3D<?=$model->id?>" target="_blank"><img src="/images/style_a410tpl/accii/vk.png" alt=""></a>

                                <a class="footer-sosials hv" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fa410.ru%2Fsite%2Faccii-detail%3Fid%3D<?=$model->id?>&amp;src=sdkpreparse" target="_blank"><img src="/images/style_a410tpl/accii/facebook.png" alt=""></a>

                                <a class="footer-sosials hv" href="https://t.me/share/url?url=https://a410.ru/site/accii-detail?id=<?=$model->id?>&text=<?=$model->title?>" target="_blank"><img src="/images/style_a410tpl/accii/telegram.png" alt=""></a>
                            </div>               
                            
                        <?php elseif (empty($model->provid_at) && !empty($model->end_at)): ?>  
                            
                            <div class="dates">
                               <p class="date-but">Дата проведения</p>
                                    <p class="date" style="text-align: center;"><?= date('d.m.Y',strtotime($model->end_at)) ?></p>
                            </div>  
                            <div class="share" style="margin-left: 50px;">
                                <p class="f-header">Поделиться акцией</p>
                                <a class="footer-sosials hv" href="https://vk.com/share.php?url=https%3A%2F%2Fa410.ru%2Fsite%2Faccii-detail%3Fid%3D<?=$model->id?>" target="_blank"><img src="/images/style_a410tpl/accii/vk.png" alt=""></a>

                                <a class="footer-sosials hv" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fa410.ru%2Fsite%2Faccii-detail%3Fid%3D<?=$model->id?>&amp;src=sdkpreparse" target="_blank"><img src="/images/style_a410tpl/accii/facebook.png" alt=""></a>

                                <a class="footer-sosials hv" href="https://t.me/share/url?url=https://a410.ru/site/accii-detail?id=<?=$model->id?>&text=<?=$model->title?>" target="_blank"><img src="/images/style_a410tpl/accii/telegram.png" alt=""></a>
                            </div>
                        <?php endif ?>

                <?php else: ?> 
                        
                        <div class="share">
                            <p class="f-header">Поделиться акцией</p>
                            <a class="footer-sosials hv" href="https://vk.com/share.php?url=https%3A%2F%2Fa410.ru%2Fsite%2Faccii-detail%3Fid%3D<?=$model->id?>" target="_blank"><img src="/images/style_a410tpl/accii/vk.png" alt=""></a>

                            <a class="footer-sosials hv" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fa410.ru%2Fsite%2Faccii-detail%3Fid%3D<?=$model->id?>&amp;src=sdkpreparse" target="_blank"><img src="/images/style_a410tpl/accii/facebook.png" alt=""></a>

                            <a class="footer-sosials hv" href="https://t.me/share/url?url=https://a410.ru/site/accii-detail?id=<?=$model->id?>&text=<?=$model->title?>" target="_blank"><img src="/images/style_a410tpl/accii/telegram.png" alt=""></a>
                        </div>                  
                <?php endif ?>
                         
            </div>
        </div>
        <img class="img-acc" src="https://files.a410.ru<?= $model->image ? substr($model->image, 6) : `/service/no_photo.jpg`?>" alt="">

        <div class="img-wrap">
            
        </div>       
    </div>
</div>
    
