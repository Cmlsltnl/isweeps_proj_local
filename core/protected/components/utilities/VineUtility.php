<?php

/*
 * gstringer - 2013-10-02
 * This file is responsible for importing vine videos
 * into the youtoo admin based on a given keyword or tag.
 */

class VineUtility {

    private $session = null;
    private $id = null;
    private $username = null;
    public $message = null;
    public $connected = false;

    public function __construct() {
        $this->connected = $this->login(Yii::app()->params['vine']['username'], Yii::app()->params['vine']['password']);
    }

    public function login($username, $password) {
        $success = false;
        $url = Yii::app()->params['vine']['url'] . "/users/authenticate";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "username=$username&password=$password");
        curl_setopt($curl, CURLOPT_USERAGENT, "com.vine.iphone/1.0.3 (unknown, iPhone OS 6.1.0, iPhone, Scale/2.000000)");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = json_decode(curl_exec($curl));

        if (!empty($response)) {

            $this->session = $response->data->key;
            $this->id = strtok($response->data->key, '-');
            $this->username = $response->data->username;
            $success = $response->success;

            if ($success === false) {
                $this->message = $response->error;
            }
        }

        curl_close($curl);
        return $success;
    }

    public function getUserInfo() {
        $returnInfo = array();
        $returnInfo["username"] = $this->username;
        $returnInfo["userId"] = $this->id;
        $returnInfo["key"] = $this->session;
        $returnInfo["message"] = $this->message;
        return $returnInfo;
    }

    public function getTimeline($key, $userId, $categoryIdentifier, $numVideos) {
        //$url = '/timelines/users/'.$userId;
        //$url = '/timelines/popular';
        //$url = '/timelines/graph';
        $encodedcategoryIdentifier = '';
        $length = mb_strlen($categoryIdentifier);
        for($i=0;$i<$length;$i++){
            $encodedcategoryIdentifier.='%'.wordwrap(bin2hex(mb_substr($categoryIdentifier,$i,1)),2,'%',true);
        }
        $url = Yii::app()->params['vine']['url'] . "/timelines/tags/" . $encodedcategoryIdentifier . '?size=' . $numVideos;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "com.vine.iphone/1.0.3 (unknown, iPhone OS 6.1.0, iPhone, Scale/2.000000)");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8', 'vine-session-id: ' . $key));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        if (!$result) {
            $returnStuff = curl_error($ch);
        } else {
            $returnStuff = $result;
        }

        curl_close($ch);
        return $returnStuff;
    }

    public function get_vine_thumbnail($id) {

        $vine = file_get_contents("http://vine.co/v/{$id}");
        preg_match('/property="og:image" content="(.*?)"/', $vine, $matches);
        return ($matches[1]) ? $matches[1] : false;
    }

}

?>
