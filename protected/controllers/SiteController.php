<?php

/**
 *
 */
Yii::import('application.models.Notis');
class SiteController extends Controller
{
    public $layout = 'column1';

    /**
     *
     */
    public function actionIndex()
    {
        $this->layout = 'home';
        $notisModel = new Notis;
        $allNotis = $notisModel->findAll();
        $this->render('index', array(
        'allNotis'=>$allNotis
    ));
    }

    /**
     * show the about page
     * @param
     * @return void
     */
    public function actionAbout()
    {
        $this->render('about');
    }


    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the action to handle external exceptions.
     * @return void.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        if (!defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH)
            throw new CHttpException(500, "This application requires that PHP was compiled with Blowfish support for crypt().");

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        //$this->redirect(Yii::app()->homeUrl);
        $this->redirect(array('/user/auth'));
    }

    /*
    * Get all the cities
    *
    */
    public function actionGetCities()
    {
        $accessToken = 'CAAFbW0cHhFQBAJLrYfAcjURDK0YP54Qf6iM3r7SijogQnnZBQdRiMxWbDTe3kBiboABkQ817LlBlFZA9b8E7tJHD7YNCJRndYQZCuZCeJphLGZC2ir9V3sDEfGocmTPRtMIEguCBc7rKxWQo8TgUyazfzAUqdtO8ZD';
        $countryList = "['VN','KH','LA','TH']";
        $url = 'https://graph.facebook.com/search?list=global&country_list=' . $countryList . '&type=adcity&access_token=' . $accessToken . '&q=' . $params['q'] . '&limit=25';
        $data = $this->getApiResponse($url);
        $i = 0;
        $res = array();
        if (!empty($data['data'])) {
            foreach ($data['data'] as $element) {
                $res[$i]['name'] = $element['name'];
                $res[$i]['key'] = $element['name'];
                $i++;
            }
        }
        return $res;
    }

    public function getApiResponse($url, $post = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($post !== null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

}
