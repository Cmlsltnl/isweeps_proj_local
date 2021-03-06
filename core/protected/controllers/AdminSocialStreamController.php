<?php

class AdminSocialStreamController extends Controller {


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
                'actions'=>array(
                    'ajaxStartStream',
                    'ajaxStopStream',
                    'ajaxReadStream',
                    'index',
                    ),
                'expression'=>'(Yii::app()->user->isAdmin())',
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
     * SOCIAL STREAM ACTIONS
     * This section contains everything required for the social stream section of the admin
     *
     *
     */
    public function actionIndex(){
        $questions = eQuestion::model()->ticker()->current()->findAll();
        $this->render('index',Array('questions'=>$questions));
    }

    public function actionAjaxStartStream(){
        foreach($_POST as $k=>$v){
            $$k = $v;
        }
        if(empty($track)){
            echo 'Track cannot be empty!';
            return;
        }
        $tracks = explode(' ', $track);
        echo TwitterUtility::openStream($tracks);
    }

    public function actionAjaxStopStream(){
        echo TwitterUtility::killProcess('twitterstream');
    }

    public function actionAjaxReadStream(){
        $results['error'] = '';
        
        foreach($_POST as $k=>$v){
            $$k = $v;
        }
        $questions = eQuestion::model()->ticker()->current()->findAll();
        foreach($questions as $question){
            $responses[] = $question->hashtag;
        }
        
        $tracks = explode(' ', $track);
        foreach($tracks as $customTrack){
            $responses[] = $customTrack;
        }
        
        if(!isset($position) || !is_numeric($position)) {
            $position = -20000;
        }

        $file = Yii::app()->twitter->streamFile;
        $fp = fopen($file, 'r');
        if(!$fp){
            //die('can\'t open stream file!');
            $results['error'] = 'can\'t open stream file!';
            echo json_encode($results);
            exit;
        }
        
        $results['tweets'] = array();
        fseek($fp,$position,($position < 0) ? SEEK_END : SEEK_SET);
        while(!feof($fp) && sizeof($results['tweets']) <= 50){
            $buffer = stream_get_line($fp,10000,"\r\n");
            if($position < 0){
                $buffer = stream_get_line($fp,10000,"\r\n");
            }
            $tweet = json_decode($buffer);
            if(is_object($tweet)){
                foreach($responses as $response){
                    if(stripos($tweet->text,$response)){
                        $results['tweets'][] = TwitterUtility::parseTweet($tweet);
                    }
                }
            }
        }
        
        
        if(count($results['tweets']) == 0) {
            $results['error'] = 'Zero tweets returned. Twitter limit most likely exceeded!';
        }
        
        $results['beginPos'] = $position;
        $results['endPos'] = ftell($fp);
        fclose($fp);
        echo json_encode($results);
    }


}