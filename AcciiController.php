<?php

namespace app\controllers;


use app\models\Customer;
use app\models\Event;
use app\models\General;
use app\models\Helpers\BasicHelper as BH;
use app\models\Helpers\LogHelper as Log;

use app\models\Accii; // Салям 01.11.2021
use app\models\Konkurs;

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

class AcciiController extends Controller
{

    public $user; 

    public function actionIndex()
    {
        
        $this->view->title = "Акции";

        $currentDateTime = strtotime(BH::td()); //date('d-m-Y H:i:s');        

        $query = Accii::find();

        $qudate = Accii::find();

        $sub = [];
        $sq = $qudate->all();
        
        foreach ($sq as $item)
        {
            
            if    ((strtotime($item->start_at) <= $currentDateTime) && is_null($item->finish_at) && ($item->published == 1)) 
                $sub[] = $item->id;
            elseif ((strtotime($item->finish_at) >= $currentDateTime) && is_null($item->start_at) && ($item->published == 1)) 
                $sub[] = $item->id;
            elseif((strtotime($item->start_at) <= $currentDateTime) && (strtotime($item->finish_at) >= $currentDateTime) && ($item->published == 1))
                $sub[] = $item->id;
            elseif(is_null($item->start_at) && is_null($item->finish_at) && ($item->published == 1))
                $sub[] = $item->id;
        }

        $query->andWhere(['in', 'id', $sub]);
          
        $query->orderBy(['id' => SORT_DESC]);

        $model = $query->all();     


        // dd($model);die;   

        return $this->render('index', ['model' => $model, 'now' => $currentDateTime]);
    }

    public function actionAcciiDetail($id)
    {
        
        $model = Accii::find()->where(['id' => $id])->one();

        $this->view->title = $model->title;

        Yii::$app->params['og_title']['content'] = $model->title;
        Yii::$app->params['og_description']['content'] = $model->text;
        Yii::$app->params['og_url']['content'] = 'https://a410.ru/site/accii-detail?id='.$model->id;
        Yii::$app->params['og_image']['content'] = 'https://files.a410.ru'.($model->image ? substr($model->image, 6) : '/service/no_photo.jpg');

        Yii::$app->params['route'] = 'accii';//.$model->title;
        Yii::$app->params['breadcrumbs'] = json_encode(['Акции', $model->title]);
        Yii::$app->params['breadcrumbsTitle'] =  $model->title;

        return $this->render('accii-ditail', ['model' => $model]);
    }


    public function actionKonkurs($id)
    {
        $city = City::find()->orderBy(['city' => SORT_ASC])->all();
        
        if(!empty(Yii::$app->request->post()))
        {

            // dd(Yii::$app->request->post());die;
            if (Yii::$app->request->post('g-recaptcha-response')) {
            
                $konkurs = Yii::$app->request->post();

                    $c = $konkurs['accii_id'];

                    if (!empty($c)) {
                        $newcount = Konkurs::find()->where(['accii_id' => $c])->count();

                        if ($newcount > 0 ) {
                            $uchas = Konkurs::find()->where(['accii_id' => $c])->all();
                            $val = [];
                            foreach ($uchas as $key => $value) {
                                $val [] = $value->bilet;
                            }

                            $count = max($val);
                        }else
                            $count = 0;
                    }
                    
                    $phone = $konkurs['mob_phone'];
                    
                    if (!empty($phone)) {
                        $q = Konkurs::find()->with('phone')->where(['phone' => $phone])->count();                        
                    }

                    if ($q == 0) 
                    {                      
                        $model = new Konkurs;
                        $model->accii_id = $konkurs['accii_id'];
                        $model->first_name = $konkurs['first_name'];
                        $model->second_name = $konkurs['second_name'];
                        $model->fathers_name =$konkurs['fathers_name'];
                        $model->phone = $konkurs['mob_phone'];
                        $model->email = $konkurs['email'];
                        $model->city = $konkurs['city'];
                        $model->clinic_name = $konkurs['clinic_name'];
                        $model->bilet = $count + 1 ;                        
                       
                       if ($model->validate() && $model->save()) {
                           $fio = $model->second_name." ".$model->first_name." ".$model->fathers_name;

                            Log::send(209, ['content' => $fio, 'email' => $model->email, 'phone' => $model->phone]);
                            Yii::$app->session->setFlash('success', 'Вы участвуете в конкурсе!'); 
                            return $this->redirect(['site/accii', 'id' => $model->id]); 
                        }else{
                            Yii::$app->session->setFlash('error', 'Что-то пошло не так!'); 
                            return $this->redirect(['site/accii', 'id' => $id]);
                        }
                    }
                    else
                    {
                         // dd(Yii::$app->request->post());die;
                        Yii::$app->session->setFlash('error', 'Вы уже участвуете в конкурсе!');
                        return $this->redirect(['site/accii', 'id' => $id]); 
                    }
            }    
           else{
                    Yii::$app->session->setFlash('error', 'Подтвердите что вы не робот!'); 
                    return $this->redirect(['site/accii', 'id' => $id]);
            }             
        } 
        
        return $this->render('_form', ['id' => $id]);
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
