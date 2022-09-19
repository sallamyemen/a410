<?php

namespace app\controllers;

use app\models\Customer;
use app\models\Event;
use app\models\General;
use app\models\Helpers\BasicHelper as BH;
use app\models\Helpers\LogHelper as Log;

use app\models\Accii; // Салям 01.11.2021

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

//use app\models\Helpers\BasicHelper as BH; //Салям

class SiteController extends Controller
{
    public $user;
    
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

//                if (!$user->user->application_status) {
//                    return $this->redirect(Url::to('/personal/application'), [
//                        'data-method' => 'POST',
//                        'data-params' => [
//                            'csrf_param' => \Yii::$app->request->csrfParam,
//                            'csrf_token' => \Yii::$app->request->csrfToken,
//                            'reason' => ['status' => 'error', 'msg' => 'Для активации аккаунта, заполните личные данные!']
//                        ],
//                    ]);
//                }

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

    public function actionTest()
    {
        dd(ConsoleHelper::send('console/find-specialist', ['phone' => 79036230683]));
    }


    public function actionVerifyEmail($token)
    {
        $token = trim(strip_tags($token));


        if(isset($_SESSION['join']['hash']) && $_SESSION['join']['hash'] == $token) {
            $_SESSION['join']['confirmed'] = true;
            return $this->redirect('/join/customer-search');
        }

        else {
            $model = Customer::findOne(['email_verify_hash' => $token]);

            if(empty($model)) {
                Yii::$app->session->setFlash('error', 'Неверный токен!');
                Yii::$app->session->setFlash('alertPopup', ['status' => 'error', 'msg' => 'Неверный токен!']);
                return $this->goHome();
            }


            if($model->email_verify_hash == $token) {
                $model->email_verify_code = null;
                $model->email_verify_hash = null;
                $model->email = $model->new_email;

                if($model->save()) {
                    Log::send(802, [], new \app\models\UserSession(['id' => $model->user_id]));
                    Yii::$app->session->setFlash('alertPopup', ['status' => 'success', 'msg' => 'Ваш e-mail подтвержден!']);
                    return $this->redirect('/personal/account');
                }

            }
        }

        Yii::$app->session->setFlash('alertPopup', ['status' => 'error', 'msg' => 'Неверный токен!']);
        return $this->goHome();

    }


    public function actionIndex()
    {



        $this->meta(BH::shortLocCode(Yii::$app->params['general']['meta_title']), Yii::$app->params['general']['meta_description'], Yii::$app->params['general']['meta_keyords']);
        
        $categories = Category::find()->where(['alias' => 'product'])->one();
        $model = $categories->children()->where(['depth' => 2])->andWhere(['published' => 1])->andWhere(['content_type' => 'CTL'])->andWhere(['!=','tag', 1])->with('routs')->orderBy('lft')->all();

        $events = Event::find()->limit(3)->andWhere(['>=', 'dt_finish', BH::td()])->andWhere(['published' => 1])->orderBy(['dt_start' => SORT_ASC])->all();

        $nws = Category::find()->where(['alias' => 'news'])->one();
        $news = $nws->children()->andWhere(['published' => 1])->orderBy(['publication_start_at' => SORT_DESC])->limit(3)->all();

        

        return $this->render('index', compact('model', 'events', 'news'));
    }
    
    public function actionImage($name)
    {
        if(Yii::$app->user->isGuest)
            throw new \yii\web\HttpException(404 ,'Страница не найдена');

        $model = Customer::findOne(['user_id' => Yii::$app->user->identity->id]);

        $hash = md5('doc_' . $model->id);
        $fileHash = explode('_', $name);


        
        if($hash == $fileHash[0])
        {
            $this->layout = false;
            header('Content-type: image/jpeg');
            header('Cache-Control: max-age=43200');
            header('Pragma: no-cache');
            header("Cache-Control: no-cache, must-revalidate");
            echo file_get_contents('/var/www/a410ru74/data/www/a410_platform/customers/profiles/' . $model->global_id . '/docs/' . $name);
        }
        else
            throw new \yii\web\HttpException(404 ,'Страница не найдена');
    }

    public function actionPpic($name)
    {
        $model = Customer::findOne(['user_id' => Yii::$app->user->identity->id]);

        $this->layout = false;
        header('Content-type: image/jpeg');
        //header('Cache-Control: max-age=43200');
        //header('Pragma: no-cache');
        //header("Cache-Control: no-cache, must-revalidate");
        echo file_get_contents('/var/www/a410ru74/data/www/a410_platform/customers/profiles/' . $model->global_id . '/' .$name);
    }
        
    public function shortLocCode($text)
    {
        $geo = new GEO();
        $text = preg_replace("/{city:1}/", $geo->v_location, $text);
        $text = preg_replace("/{city:0}/", $geo->location, $text);

        return $text;
    }

    public function actionAccii()
    {
        
        
        
        $currentDateTime = strtotime(BH::td()); //date('d-m-Y H:i:s');
        //dd($currentDateTime);

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

        //dd($model);die;

        return $this->render('accii', ['model' => $model, 'now' => $currentDateTime]);
    }

    public function actionAcciiDetail($id)
    {
        
        $model = Accii::find()->where(['id' => $id])->one();


        Yii::$app->params['og_title']['content'] = $model->title;
        Yii::$app->params['og_description']['content'] = $model->text;
        Yii::$app->params['og_url']['content'] = 'https://a410.ru/site/accii-detail?id='.$model->id;
        Yii::$app->params['og_image']['content'] = 'https://files.a410.ru'.($model->image ? substr($model->image, 6) : '/service/no_photo.jpg');

        // Yii::$app->params['route'] = 'site/accii';
        // Yii::$app->params['breadcrumbs'] = json_encode(['Акции', $model->title]);
        // Yii::$app->params['breadcrumbsTitle'] =  $model->start_at;

        return $this->render('accii-ditail', ['model' => $model]);
    }


    public function actionSerial()
    {
        return $this->render('serial');
    }

    public function actionSerialCheck()
    {
        if(!Yii::$app->request->isAjax)
            throw new \yii\web\HttpException(404, 'Страница не найдена!');

        $post = trim(strip_tags(Yii::$app->request->post('serial')));

        if(empty($post))
            return json_encode(['status' => 'error', 'msg' => 'Ничего не введено!']);

        $serv = 'http://80.251.133.194:8085/Copy_UT_A410/hs/Sharing_Site/check?series='.$post;

        $result = file_get_contents($serv);

        if($result != 'false')
        {
            return json_encode(['status' => 'ok', 'msg' => '<i class="fas fa-check"></i> <b>'.$result.'</b><br></br>Cерия <b>'.$post.'</b> продавалась в нашей сети', 'answer' => 'yes']);
        }

        else
            return json_encode(['status' => 'ok', 'msg' => '<i class="fas fa-ban"></i> Cерия <b>'.$post.'</b> не продавалась', 'answer' => 'no']);

        return json_encode(['status' => 'error', 'msg' => '<i class="fas fa-exclamation-triangle"></i> Ошибка связи! Пожадлуйста, попробуйте позже']);
    }

    public function actionSearch()
    {
        $this->layout = false;

        if(Yii::$app->request->post('searchRow') && strlen(Yii::$app->request->post('searchRow')) > 2) {
            $sr = trim(strip_tags(Yii::$app->request->post('searchRow')));
            $es = new ES(['key' => $sr]);
            $model = $es->search();
            return $this->render('search', compact('model'));
        }
    }

    public function actionFilial()
    {
        $this->layout = false;

        $model = City::find()->with('location')->orderBy('city')->where(['has_filial' => 1])->all();

        return $this->render('filial', compact('model'));

    }

    public function actionCities()
    {
        $this->layout = false;
        $search_row = Yii::$app->request->getBodyParam('id_reg');

        $model = Location::getRegAddress($search_row);

        return $this->render('cities', ['model'=>$model]);
    }

    public function meta($t, $d, $k, $inclSitename = false)
    {

       //dd(Yii::$app->params);die;

       $url     = Yii::$app->request->url;

       $sitename = Yii::$app->params['sitename'] . ' ' . Yii::$app->params['geo']->in_city;

        $t ? $t = $t . ' - ' : '';

        if(!$inclSitename)
            Yii::$app->view->title = $t . $sitename;
        else
            Yii::$app->view->title = $t . 'A410';


        \Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $d,
        ]);
        \Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => $k,
        ]);

        \Yii::$app->view->registerMetaTag(
            ['property' => 'og:locale', 'content' => 'ru_RU']
        );

        \Yii::$app->view->registerMetaTag(
            ['property' => 'og:site_name', 'content' => $sitename]
        );

        
        if ($url){
            \Yii::$app->view->registerMetaTag(
                ['property' => 'og:title', 'content' => 'Формула успеха']
            );

            \Yii::$app->view->registerMetaTag(
                ['property' => 'og:title', 'content' => Yii::$app->view->title]
            );

            \Yii::$app->view->registerMetaTag(
                ['property' => 'og:description', 'content' => 'Группа компаний А410 занимается продажей и распространением медицинских препаратов, для эстетической медицины и косметики']
            );

            \Yii::$app->view->registerMetaTag(
                ['property' => 'og:description', 'content' => $d]
            );
        }
        else{
            \Yii::$app->view->registerMetaTag(
                ['property' => 'og:title', 'content' => Yii::$app->view->title]
            );

            \Yii::$app->view->registerMetaTag(
                ['property' => 'og:description', 'content' => $d]
            );
        }


    }


}
