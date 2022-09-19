<?php

namespace app\controllers;


use app\models\Customer;
use app\models\Event;
use app\models\General;
use app\models\Helpers\BasicHelper as BH;
use app\models\Helpers\LogHelper as Log;

use app\models\Konkurs; // Салям 01.11.2021

use Yii;

use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Settings;
use app\models\City;
use app\models\RfObl;
use app\models\GEO;
use app\models\Category;
use yii\helpers\Url;
use app\models\ES;

use app\models\R301;

use app\models\Location;
use app\models\Helpers\ConsoleHelper;

class KonkursController extends Controller
{

    public $user; 

    public function actionIndex()
    {
        
        $this->view->title = "Конкурс";

        //dd($id);die;

        // $accii_id = Yii::$app->request->post('Accii');

        // $id = $accii_id['accii_id'];

        $model = new Konkurs;

        $city = City::find()->orderBy(['city' => SORT_ASC])->all();

         if($model->load(Yii::$app->request->post()))
        {
            
            if(Yii::$app->request->post('action') == 'close')
                dd(Yii::$app->request->post());
                //return $this->redirect('index', ['model' => $model, 'city' => $city, 'id' => $id]);

        }    

        // $model->resources = Yii::$app->request->post('resources');

        // if (Yii::$app->request->post()) {
        //     dd($accii_id['accii_id']);die;
        // }

        // if($model->validate() && $model->save())
        //     {                
                
        //        dd(Yii::$app->request->post());die;       
        //     }

        return $this->render('index', ['model' => $model, 'city' => $city, 'id' => $id]);
    }

     public function actionThanks()
    {
        
        $this->view->title = "Спасибо!";

        if (Yii::$app->request->post()) {
            dd(Yii::$app->request->post());die;
        }

        return $this->render('thank'); //, ['model' => $model, 'now' => $currentDateTime]);
    }
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {

        Yii::$app->params['geo'] = new GEO();

        Yii::$app->params['user'] = (object)[
            'access_level' => 0,
            'guid' => '009e988e-82c4-11ea-8118-bbfd54cdd6d3',
        ];

       // dd(Yii::$app->controller->action->id); die;

        Yii::$app->params['general'] = General::find()->where(['id' => 1])->asArray()->one();
        Yii::$app->params['sitename'] = Yii::$app->params['general']['sitename'];

        if(!Yii::$app->user->isGuest)
        {

            $user = new \app\models\UserSession(['id' => Yii::$app->user->identity->id]);
            Yii::$app->params['user'] = $user->user;
            $this->user = $user->user;


            if(Yii::$app->params['user']->domain != $_SERVER['HTTP_HOST'])
                return $this->redirect('https://'. Yii::$app->params['user']->domain . $_SERVER['REQUEST_URI']);

            //dd(Yii::$app->controller->action->id);

            if(
                Yii::$app->controller->action->id != 'application' &&
                Yii::$app->controller->action->id != 'contact' &&
                Yii::$app->controller->action->id != 'logout'
            ) {

                if (!$user->user->application_status) {
                    Yii::$app->session->setFlash('alertPopup', ['status' => 'error', 'msg' => 'Для активации аккаунта, заполните личные данные!']);
                    return $this->redirect(['/personal/application', 'app']);
                }

                elseif ($user->user->email_status == Customer::EMAIL_STATUS_CONFIRM)
                    return $this->redirect(Url::to('/personal/contact'));

                elseif ($user->user->email_status == Customer::EMAIL_STATUS_REQUIRED)
                    return $this->redirect(Url::to('/personal/contact'));

            } else {
                if (

                    Yii::$app->controller->action->id != 'application' && !$user->user->application_status)
                {

                    if ($user->user->account_type == Customer::ACCOUNT_TYPE_SPECIALIST)
                        Yii::$app->session->setFlash('alertPopup', ['status' => 'worning', 'msg' => 'Для активации аккаунта, заполните личные данные!']);

                    if ($user->user->account_type == Customer::ACCOUNT_TYPE_CLINIC)
                        Yii::$app->session->setFlash('alertPopup', ['status' => 'worning', 'msg' => 'Для активации аккаунта, заполните данные организации!']);
                }

                elseif (
                    $user->user->email_status == Customer::EMAIL_STATUS_CONFIRM &&
                    Yii::$app->controller->action->id != 'application'
                ) {

                    if($resend = Yii::$app->session->hasFlash('alertPopup')) {
                        $resend = Yii::$app->session->getFlash('alertPopup');
                        Yii::$app->session->setFlash('alertPopup', $resend);
                    } else
                        Yii::$app->session->setFlash('alertPopup', ['status' => 'worning', 'msg' => 'Необходимо подтвердить e-mail!']);
                }

                elseif (
                    $user->user->email_status == Customer::EMAIL_STATUS_REQUIRED &&
                    Yii::$app->controller->action->id != 'application'
                )
                    Yii::$app->session->setFlash('alertPopup', ['status' => 'worning', 'msg' => 'Для работы в системе необходимо указать и подтвердить e-mail!']);

            }


        }

        $query = $_SERVER['REQUEST_URI'];
        $redirectList = R301::redirects();

        if(isset($redirectList[$query]))
            Yii::$app->response->redirect('https://' . $_SERVER['HTTP_HOST'] . $redirectList[$query], 301)->send();






        return parent::beforeAction($action);
    }
}
