<?php

class AdminDashboardController extends Controller {


    public $user;
    public $notification;
    public $layout = '//layouts/admin';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(

            array('allow',
                'actions' => array(
                    'index',
                ),
                'expression' => '(Yii::app()->user->isAdmin())',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Anything required on every page should be loaded here
     * and should also be made a class member.
     */
    function init() {
        parent::init();
        Yii::app()->setComponents(array('errorHandler' => array('errorAction' => 'admin/error',)));
        $this->user = ClientUtility::getUser();
        $this->notification = eNotification::model()->orderDesc()->findAllByAttributes(array('user_id' => Yii::app()->user->id));
    }

    /**
     *
     *
     * DASHBOARD ACTIONS
     * This section contains everything required for the dashboard section of the admin
     *
     *
     */
    public function actionIndex($startDate = null, $endDate = null) {
        $startDate = DateTimeUtility::getStartDate($startDate);
        $endDate = is_null($endDate) ? date("Y-m-d") : $endDate;
        $settings = eAppSetting::model()->dashboard()->active()->findAll();
        foreach ($settings as $k => $v) {
            $pageSettings[$v->attribute] = $v->value;
        }
        $this->render('index', array(
            'settings' => $pageSettings,
            'questionCount' => ePoll::model()->count(),
            'voteCount' => ePollResponse::model()->count(),
            'videoCount' => eVideo::model()->processed()->count(),
            'pendingCount' => eVideo::model()->processed()->new()->count(),
            'imageCount' => eImage::model()->count(),
            'pendingImageCount' => eImage::model()->new()->count(),
            'tickerRunningCount' => eTicker::model()->accepted()->with('tickerRuns')->hasRuns()->count(),
            'tickerFailCount' => eTicker::model()->denied()->count(),
            'tickerNewCount' => eTicker::model()->new()->count(),
            'startDate' => $startDate,
            'endDate' => $endDate,
        ));
    }
}