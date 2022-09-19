<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
//$this->registerCssFile( '@web/css/serial.css' );
$this->registerJsFile( '@web/js/serial_check.js',  ['depends' => ['app\assets\AppAsset']]);

$form = ActiveForm::begin();
    
    echo $this->render('_form', ['model' => $model, 'city' => $city, 'id' => $id]);
    ActiveForm::end();

?>