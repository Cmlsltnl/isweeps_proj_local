<?php

class TwitterUtility {

    public static function getApplicationAccessToken() {
        $access_token = base64_encode(Yii::app()->twitter->consumer_key . ':' . Yii::app()->twitter->consumer_secret);
        $url = 'https://api.twitter.com/oauth2/token';
        $query = http_build_query(Array('grant_type' => 'client_credentials'));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . $access_token,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
        ));
        $result = json_decode(curl_exec($ch));
        return $result->access_token;
    }

    //TODO: collapse getUsernameFromID and getAvatarFromID into getUserdataFromID
    //TODO: make this one function username/id lookup
    public static function getIDFromUsername($username) {
        $access_token = self::getApplicationAccessToken();
        $url = 'https://api.twitter.com/1.1/users/lookup.json';
        $query = http_build_query(Array(
            'screen_name' => $username,
        ));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $access_token,
        ));
        $response = curl_exec($ch);
        list($headers, $results) = explode("\r\n\r\n", $response, 2);
        $headers = explode("\r\n", $headers);
        foreach ($headers as $header) {
            list($k, $v) = explode(':', $header);
            $headers[$k] = $v;
        }
        $results = json_decode($results);

        return $results;
    }

    public static function getUsernameFromID($id) {
        $twitterCache = eTwitterCache::model()->findByPK($id);

        if (is_null($twitterCache) || $twitterCache->user_name == 'Twitter User' || $twitterCache->name == '') {
            $access_token = self::getApplicationAccessToken();
            $url = 'https://api.twitter.com/1.1/users/lookup.json';
            $query = http_build_query(Array(
                'user_id' => $id,
            ));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $access_token,
            ));
            $response = curl_exec($ch);
            list($headers, $results) = explode("\r\n\r\n", $response, 2);
            $results = json_decode($results);

            /*
              $headers = explode("\r\n", $headers);
              array_shift($headers);
              $headersParsed = array();
              foreach ($headers as $header) {
              $exp = explode(':', $header);
              if($exp) {
              $headersParsed[$exp[0]] = $exp[1];
              }
              } */

            if (!empty($results->errors)) {
                $results = Array(new stdClass());
                $results[0]->name = ''; // greg - this was missing so I added blank for now
                $results[0]->screen_name = 'Twitter User';
                $results[0]->profile_image_url_https = '/webassets/images/you/profile-avitar.png';
            }

            $twitterCache = (is_null($twitterCache)) ? new eTwitterCache : $twitterCache;
            $twitterCache->id = $id;
            $twitterCache->name = $results[0]->name;
            $twitterCache->user_name = $results[0]->screen_name;
            $twitterCache->user_avatar = $results[0]->profile_image_url_https;
            $twitterCache->save();
        }
        return $twitterCache->user_name;
    }

    public static function getNamesFromID($id) {

        $twitterCache = eTwitterCache::model()->findByPK($id);

        if (is_null($twitterCache) || $twitterCache->user_name == 'Twitter User' || $twitterCache->name == '') {

            $access_token = self::getApplicationAccessToken();
            $url = 'https://api.twitter.com/1.1/users/lookup.json';
            $query = http_build_query(Array(
                'user_id' => $id,
            ));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $access_token,
            ));
            $response = curl_exec($ch);
            list($headers, $results) = explode("\r\n\r\n", $response, 2);
            $results = json_decode($results);
            if (!empty($results->errors)) {
                $results = Array(new stdClass());
                $results[0]->screen_name = 'Twitter User';
                $results[0]->profile_image_url_https = '/webassets/images/you/profile-avitar.png';
            }

            $twitterCache = (is_null($twitterCache)) ? new eTwitterCache : $twitterCache;
            $twitterCache->id = $id;
            $twitterCache->name = $results[0]->name;
            $twitterCache->user_name = $results[0]->screen_name;
            $twitterCache->user_avatar = $results[0]->profile_image_url_https;
            $twitterCache->save();
        }
        return array('name' => $twitterCache->name, 'username' => $twitterCache->user_name);
    }

    public static function getAvatarFromID($id) {
        $twitterCache = eTwitterCache::model()->findByPK($id);
        if (is_null($twitterCache) || $twitterCache->user_name == 'Twitter User' || $twitterCache->name == '') {
            $access_token = self::getApplicationAccessToken();
            $url = 'https://api.twitter.com/1.1/users/lookup.json';
            $query = http_build_query(Array(
                'user_id' => $id,
            ));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $access_token,
            ));
            $response = curl_exec($ch);
            list($headers, $results) = explode("\r\n\r\n", $response, 2);
            $headers = explode("\r\n", $headers);
            foreach ($headers as $header) {
                list($k, $v) = array_pad(explode(':', $header), 4, '');
                $headers[$k] = $v;
            }
            $results = json_decode($results);
            if (!empty($results->errors)) {
                $results = Array(new stdClass());
                $results[0]->screen_name = 'Twitter User';
                $results[0]->profile_image_url_https = '/webassets/images/you/profile-avitar.png';
            }
            $twitterCache = (is_null($twitterCache)) ? new eTwitterCache : $twitterCache;
            $twitterCache->id = $id;
            $twitterCache->name = isset($results[0]->name) ? $results[0]->name : 'N/A';
            $twitterCache->user_name = $results[0]->screen_name;
            $twitterCache->user_avatar = $results[0]->profile_image_url_https;
            $twitterCache->save();
        }
        return $twitterCache->user_avatar;
    }

    public static function getUserInfoFromID($id) {

        $twitterCache = eTwitterCache::model()->findByPK($id);

        if (is_null($twitterCache) || $twitterCache->user_name == 'Twitter User' || $twitterCache->name == '') {
            $access_token = self::getApplicationAccessToken();
            $url = 'https://api.twitter.com/1.1/users/lookup.json';
            $query = http_build_query(Array(
                'user_id' => $id,
            ));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $access_token,
            ));
            $response = curl_exec($ch);
            list($headers, $results) = explode("\r\n\r\n", $response, 2);
            $results = json_decode($results);
            if (!empty($results->errors)) {
                $results = Array(new stdClass());
                $results[0]->screen_name = 'Twitter User';
                $results[0]->profile_image_url_https = '/webassets/images/you/profile-avitar.png';
            }

            $twitterCache = (is_null($twitterCache)) ? new eTwitterCache : $twitterCache;
            $twitterCache->id = $id;
            $twitterCache->name = $results[0]->name;
            $twitterCache->user_name = $results[0]->screen_name;
            $twitterCache->user_avatar = $results[0]->profile_image_url_https;
            $twitterCache->save();
        }
        return $twitterCache;
    }

    public static function search($terms, $max_id = null, $minResults = 100) {
        $access_token = self::getApplicationAccessToken();
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $query = http_build_query(Array(
            'q' => $terms,
            'count' => $minResults,
            //'rpp' => $minResults,
            'max_id' => $max_id,
        ));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $access_token,
        ));
        $response = curl_exec($ch);
        list($headers, $results) = explode("\r\n\r\n", $response);
        $results = json_decode($results);

        /*
         * HTTP/1.1 200 OK
          cache-control: no-cache, no-store, must-revalidate, pre-check=0, post-check=0
          content-length: 64695
          content-type: application/json;charset=utf-8
          date: Thu, 29 May 2014 22:11:33 GMT
          expires: Tue, 31 Mar 1981 05:00:00 GMT
          last-modified: Thu, 29 May 2014 22:11:33 GMT
          pragma: no-cache
          server: tfe
          set-cookie: guest_id=v1%3A140140149367376175; Domain=.twitter.com; Path=/; Expires=Sat, 28-May-2016 22:11:33 UTC
          status: 200 OK
          strict-transport-security: max-age=631138519
          x-access-level: read
          x-content-type-options: nosniff
          x-frame-options: SAMEORIGIN
          x-rate-limit-limit: 450
          x-rate-limit-remaining: 431
          x-rate-limit-reset: 1401401901
          x-transaction: ebd121e1a3cfed6e
          x-xss-protection: 1; mode=block
         */

        $headers = explode("\r\n", $headers);
        array_shift($headers);
        $headersParsed = array();
        foreach ($headers as $header) {
            $exp = explode(':', $header);
            if ($exp) {
                $headersParsed[$exp[0]] = $exp[1];
            }
        }

        $results->rate_limit_remaining = (integer) $headersParsed['x-rate-limit-remaining'];
        $results->rate_limit_reset = (integer) $headersParsed['x-rate-limit-reset'];
        return $results;
    }

    public static function openStream($customTracks = false) {
        $polls = ePoll::model()->with('pollAnswers')->current()->findAll();
        foreach ($polls as $poll) {
            foreach ($poll->pollAnswers as $pollAnswer) {
                $track[] = $pollAnswer['hashtag'];
            }
        }
        $questions = eQuestion::model()->ticker()->current()->findAll();
        foreach ($questions as $question) {
            $track[] = $question->hashtag;
        }
        if ($customTracks && is_array($customTracks)) {
            foreach ($customTracks as $customTrack) {
                $track[] = $customTrack;
            }
        }
        if (empty($track)) {
            return false;
        }
        ProcessUtility::killProcess('twitterstream');
        $client = Yii::app()->params['client'];
        $track = array_map('escapeshellarg', $track);
        $track = implode(' ', $track);
        ProcessUtility::startProcess("twitterstream {$track}");
        return ProcessUtility::findProcess('twitterstream');
    }

    public static function openVoting() {
        if (self::openStream()) {
            ProcessUtility::killProcess('twitterscrapevotes');
            ProcessUtility::startProcess('twitterscrapevotes');
            return ProcessUtility::findProcess('twitterscrapevotes');
        }
        return false;
    }

    public static function closeVoting() {
        ProcessUtility::killProcess('twitterstream');
        ProcessUtility::killProcess('twitterscrapevotes');
        return true;
    }

    public static function tweetAs($uID = false, $text) {//var_dump($text);exit;
        if (!$uID) {
            return false;
        }
        if (is_numeric($uID)) {
            $user = eUserTwitter::model()->findByAttributes(Array('user_id' => $uID));
            if (is_null($user)) {
                return false;
            }
            $oauth_token = $user->oauth_token;
            $oauth_token_secret = $user->oauth_token_secret;
        } else {
            switch ($uID) {
                case 'client':
                    $oauth_token = Yii::app()->twitter->adminAccessToken;
                    $oauth_token_secret = Yii::app()->twitter->adminTokenSecret;
                    break;
                default:
                    break;
            }
        }
        $twitter = Yii::app()->twitter->getTwitterTokened($oauth_token, $oauth_token_secret);
        $result = $twitter->post("statuses/update", Array('status' => $text)); //var_dump($result);exit;
        return $result;
    }

    public static function getLanguages($update = false) {
        $twitterCache = Yii::app()->cache->get('twitterLanguages');
        if ($update != false || empty($twitterCache)) {
            $access_token = self::getApplicationAccessToken();
            $url = 'https://api.twitter.com/1.1/help/languages.json';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $access_token,
            ));
            $response = curl_exec($ch);


            $results = json_decode($response);

            Yii::app()->cache->set('twitterLanguages', $results, 7200);
            return $results;
        } else {
            return $twitterCache;
        }
    }

    public static function parseTweet($tweet, $q, $terms = '') {
        set_time_limit(60);
        $questions = Utility::resultToKeyValue(eQuestion::model()->ticker()->current()->findAll(), 'id', 'question');
        $questionsBr = array();
        foreach ($questions as $key => $value) {
            $questionsBr[$key] = $value;

            if (Yii::app()->params['ticker']['breakingTweets'] === true) {
                $questionsBr[$key . 'b'] = 'Breaking - ' . $value;
            }
        }

        $ticker = eTicker::model()->findByAttributes(Array('source' => 'twitter', 'source_content_id' => $tweet->id_str));

        if (is_null($ticker) || isset($ticker->question_id) == "") {
            $dropDownDisabled = '';
        } else {
            $dropDownDisabled = 'disabled';
            if ($ticker->is_breaking == 1)
                $ticker->question_id = $ticker->question_id . 'b';
        }


        if (Yii::app()->twitter->advancedFilters) {
            $clean = LanguageUtility::filter($tweet->text);
            $screenNameClean = LanguageUtility::filter($tweet->user->screen_name);
            $nameClean = LanguageUtility::filter($tweet->user->name);
            $bioClean = LanguageUtility::filter($tweet->user->description);
        } else {
            $pass = $filter = true;
            $clean = array('result' => $pass, 'filter' => $filter);
            $screenNameClean = array('result' => $pass, 'filter' => $filter);
            $nameClean = array('result' => $pass, 'filter' => $filter);
            $bioClean = array('result' => $pass, 'filter' => $filter);
        }
        if (!empty($tweet->user->entities->description->urls)) {
            foreach ($tweet->user->entities->description->urls as $url) {
                $tweet->text = str_replace($url->url, $url->expanded_url, $tweet->text);
            }
        }

        /*
          foreach ($tweet->entities->media as $media) {
          $tweet->text = str_replace($media->url, $media->expanded_url, $tweet->text);
          } */
        /*
          $parsed = Array(
          'avatar' => $tweet->user->profile_image_url_https,
          'username' => $tweet->user->screen_name,
          'name' => $tweet->user->name,
          'userid' => $tweet->user->id,
          'followers' => number_format($tweet->user->followers_count, 0, '.', ','),
          'following' => number_format($tweet->user->friends_count, 0, '.', ','),
          'questions' => CHtml::dropDownList('question', $ticker->question_id, $questionsBr, Array('prompt' => 'Choose a question', 'class' => 'storeSearch', 'disabled' => $dropDownDisabled)),
          'timestamp' => strtotime($tweet->created_at),
          'date' => date('Y/m/d H:i:s', strtotime($tweet->created_at)),
          'content' => Utility::makeLinksFromText($tweet->text),
          'id' => $tweet->id_str,
          'source' => 'twitter',
          'hashtag' => $response,
          'language' => $tweet->lang,
          'accountDescription' => $tweet->user->description,
          'accountLanguage' => $tweet->user->lang,
          'accountLink' => "http://www.twitter.com/" . $tweet->user->screen_name,
          'clean' => Array(
          'pattern' => $clean['filter']->pattern,
          'pass' => $clean['result'],
          ),
          'accountClean' => Array(
          'pattern' => Array(
          $screenNameClean['filter']->pattern,
          $nameClean['filter']->pattern,
          $bioClean['filter']->pattern,
          ),
          'pass' => ($screenNameClean['result'] && $nameClean['result'] && $bioClean['result']),
          ),
          'media' => (sizeof($tweet->entities->media) > 0) ? true : false,
          'verified' => $tweet->user->verified,
          'place' => (!empty($tweet->place->full_name)) ? $tweet->place->full_name . ', ' . $tweet->place->country_code : '',
          'hasLocation' => (!empty($tweet->place->name)) ? true : false,
          'tweetCoordinates' => (!empty($tweet->coordinates->coordinates)) ? $tweet->coordinates->coordinates : '',
          'placeCoordinates' => (!empty($tweet->place->bounding_box->coordinates)) ? $tweet->place->bounding_box->coordinates : '',
          //'tweet'=>$tweet,
          ); */


        $parsed = Array(
            'avatar' => $tweet->user->profile_image_url_https,
            'username' => $tweet->user->screen_name,
            'name' => $tweet->user->name,
            'userid' => $tweet->user->id,
            'followers' => number_format($tweet->user->followers_count, 0, '.', ','),
            'following' => number_format($tweet->user->friends_count, 0, '.', ','),
            'questions' => CHtml::dropDownList('question', (!is_null($ticker) ? $ticker->question_id : 0), $questionsBr, Array('prompt' => 'Choose a question', 'class' => 'storeSearch', 'disabled' => $dropDownDisabled)),
            'timestamp' => strtotime($tweet->created_at),
            'date' => date('Y/m/d H:i:s', strtotime($tweet->created_at)),
            'content' => Utility::makeLinksFromText($tweet->text),
            'id' => $tweet->id_str,
            'source' => 'twitter',
            'hashtag' => 'gregfixthis', //$response,
            'language' => $tweet->lang,
            'accountDescription' => $tweet->user->description,
            'accountLanguage' => $tweet->user->lang,
            'accountLink' => "http://www.twitter.com/" . $tweet->user->screen_name,
            'clean' => Array(
                //'pattern' => $clean['filter']->pattern,
                'pass' => $clean['result'],
            ),
            'accountClean' => Array(
                /* 'pattern' => Array(
                  $screenNameClean['filter']->pattern,
                  $nameClean['filter']->pattern,
                  $bioClean['filter']->pattern,
                  ), */
                'pass' => ($screenNameClean['result'] && $nameClean['result'] && $bioClean['result']),
            ),
            'media' => false, //(sizeof($tweet->entities->media) > 0) ? true : false,
            'verified' => $tweet->user->verified,
            'place' => 'n/a', //(!empty($tweet->place->full_name)) ? $tweet->place->full_name . ', ' . $tweet->place->country_code : '',
            'hasLocation' => (!empty($tweet->place->name)) ? true : false,
            'tweetCoordinates' => (!empty($tweet->coordinates->coordinates)) ? $tweet->coordinates->coordinates : '',
            'placeCoordinates' => (!empty($tweet->place->bounding_box->coordinates)) ? $tweet->place->bounding_box->coordinates : '',
                //'tweet'=>$tweet,
        );

        if (0 == count(eTickerSearchPull::model()->findAllByAttributes(array('source_content_id' => $parsed['id'], 'question_id' => $q)))) {
            $tickerSearchPull = new eTickerSearchPull();
            $tickerSearchPull->user_id = Yii::app()->user->getId();
            $tickerSearchPull->question_id = $q;
            $tickerSearchPull->ticker = $parsed['content'];
            $tickerSearchPull->source = 'twitter';
            $tickerSearchPull->source_content_id = $parsed['id'];
            $tickerSearchPull->source_user_id = $parsed['userid'];
            $tickerSearchPull->source_date = $parsed['date'];
            $tickerSearchPull->username = $parsed['username'];
            $tickerSearchPull->name = $parsed['name'];
            $tickerSearchPull->avatar = $parsed['avatar'];
            $tickerSearchPull->followers = $parsed['followers'];
            $tickerSearchPull->following = $parsed['following'];
            $tickerSearchPull->hashtag = $terms;
            $tickerSearchPull->save();
        }

        //print_r($parsed); exit;
        return $parsed;
    }

    public static function isConnected($userID) {
        $twitter = eUserTwitter::model()->findByAttributes(Array('user_id' => $userID));
        return(!is_null($twitter));
    }

    public static function renderCardMetaTags($controller) {
        $card = $controller->id . '.' . $controller->getAction()->id;
        switch ($card) {
            case 'video.play':
                $video = eVideo::model()->findByAttributes($controller->getActionParams());
                $tags = array(
                    'twitter:card' => 'player',
                    'twitter:title' => $video->title,
                    'twitter:description' => $video->description,
                    'twitter:description' => !empty(Yii::app()->params['custom_params']['twitter_share_text']) ? Yii::app()->params['custom_params']['twitter_share_text'] : $video->description,
                    'twitter:player' => 'https://' . $_SERVER['HTTP_HOST'] . '/twittercard/' . $video->view_key,
                    'twitter:player:width' => '360',
                    'twitter:player:height' => '200',
                    'twitter:image' => 'https://' . $_SERVER['HTTP_HOST'] . '/uservideos/' . $video->filename . '.png',
                    'twitter:player:stream' => 'https://' . $_SERVER['HTTP_HOST'] . '/uservideos/' . $video->filename . Yii::app()->params['video']['postExt'],
                    'twitter:player:stream:content_type' => 'video/mp4',
                );
                break;
            case 'image.view':
                $image = eImage::model()->findByAttributes($controller->getActionParams());
                $docroot = !empty($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : Yii::app()->params['docroot'];
                if ($link = realpath($docroot . Yii::app()->params['paths']['image'] . "/{$image->filename}")) {
                    list($width, $height, $type) = getimagesize($link);
                    $tags = array(
                        'twitter:card' => 'photo',
                        'twitter:title' => $image->title,
                        'twitter:image' => 'https://' . $_SERVER['HTTP_HOST'] . '/userimages/' . $image->filename,
                        'twitter:image:type' => image_type_to_mime_type($type),
                        'twitter:image:width' => $width,
                        'twitter:image:height' => $height,
                    );
                }
                break;
            default:
                break;
        }
        if (isset($tags) && $tags && is_array($tags)) {
            foreach ($tags as $property => $data) {
                Yii::app()->clientScript->registerMetaTag($data, null, null, array('property' => $property));
            }
        }
        return true;
    }

    public static function getTimeline($uID, $numberOfTweets) {
        $access_token = self::getApplicationAccessToken();
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $query = http_build_query(Array(
            'user_id' => $uID,
            'count' => $numberOfTweets,
        ));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $access_token,
        ));
        $response = curl_exec($ch);
        list($headers, $results) = explode("\r\n\r\n", $response, 2);
        $headers = explode("\r\n", $headers);
        foreach ($headers as $header) {
            list($k, $v) = explode(':', $header);
            $headers[$k] = $v;
        }
        $results = json_decode($results);
        $results->rate_limit_remaining = (integer) $headers['x-rate-limit-remaining'];
        $results->rate_limit_reset = (integer) $headers['x-rate-limit-reset'];
        return $results;
    }

    public static function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
        $connection = new TwitterOAuth(Yii::app()->twitter->consumer_key, Yii::app()->twitter->consumer_secret, $oauth_token, $oauth_token_secret);
        return $connection;
    }

    public static function getGamingAccountMentions($id = NULL) {
        $connection = self::getConnectionWithAccessToken(Yii::app()->twitter->gamingOauthToken, Yii::app()->twitter->gamingOauthTokenSecret);
        $lastRecord = eTwitterMention::model()->lastRecord()->find();
        $youtoosandbox = eUser::model()->findByAttributes(array('username' => 'youtootechsupport@youtootech.com'));
        $twitterUserName = self::getUsernameFromID($lastRecord->twitter_user_id);
        $params = array();

        if (!is_null($lastRecord)) {
            $params = array(
                // get tweets greater than the last id processed
                'since_id' => $lastRecord->tweet_id,
            );
        }

        $mentions = $connection->get("statuses/mentions_timeline", $params);//var_dump($mentions);exit;

        if (empty($mentions)) {
            return NULL;
        }

        if (isset($mentions->errors[0]->message) == 'Rate limit exceeded') {
            return $mentions->errors[0]->message;
        }

        // remove objects that do not have a valid hashtag answer
        foreach ($mentions as $mention => $m) {
            // make sure user is connected to twitter via our site

            $attrs = array('twitter_user_id' => $m->user->id);
            $twitterAccountExists = eUserTwitter::model()->findByAttributes($attrs);

            if (!is_null($twitterAccountExists)) {
                // see if tweet has already been processed
                $attrs = array('tweet_id' => $m->id);
                $tweetStored = eTwitterMention::model()->findByAttributes($attrs);
                if(!self::hasValidHashtag($m->text) || !is_null($tweetStored)) {
                    unset($mentions[$mention]);
                } else {
                    // process payment here

                    $eTwitterMention = new eTwitterMention();
                    $eTwitterMention->user_id = $twitterAccountExists->user_id;
                    $eTwitterMention->tweet_id = $m->id;
                    $eTwitterMention->twitter_user_id = $m->user->id;
                    $eTwitterMention->tweet = $m->text;
                    $eTwitterMention->created_on = new CDbExpression('NOW()');

                    if ($eTwitterMention->save()) {
                        self::tweetingGamingVotes($id, $m->text, $eTwitterMention->id);
                        //unset($mentions[$mention]);
                    }
                }
            } else if (is_null($twitterAccountExists)) {

                    $eTwitterMention = new eTwitterMention();
                    $eTwitterMention->user_id = 0;
                    $eTwitterMention->tweet_id = $m->id;
                    $eTwitterMention->twitter_user_id = $m->user->id;
                    $eTwitterMention->tweet = $m->text;
                    $eTwitterMention->created_on = new CDbExpression('NOW()');

                $unregisteredUser = $mentions[$mention]->user->screen_name;
                $tweetResponseNotSigned = '@' . $unregisteredUser . ' tweeted at ' . date('Y-m-d H:i:s') . ', Click http://ctt.ec/ffbQ5+ to join, connect and play.';

                if ($eTwitterMention->save()) {
                    self::tweetAs($youtoosandbox->id, $tweetResponseNotSigned);
                }
                unset($mentions[$mention]);
            }
        }
        return $mentions;
    }

    public static function tweetingGamingVotes($id = NULL, $tweet, $tweet_id) {

        if ($id == NULL) {
            $game = eGameChoice::model()->multiple()->isActive()->with('gameChoiceAnswers')->find();
        } else {
            $game = eGameChoice::model()->multiple()->with('gameChoiceAnswers')->findByPk((int) $id);
        }
        //$user_id = Yii::app()->user->getId();
        $twRecord = eTwitterMention::model()->findByPk($tweet_id);
        $youtoosandbox = eUser::model()->findByAttributes(array('username' => 'youtootechsupport@youtootech.com'));
        $user = eUser::model()->findByAttributes(array('id' => $twRecord->user_id));
        $cardRecord = eUserLocation::model()->findByAttributes(array('user_id' => $twRecord->user_id));
        $cardonfile = !empty($cardRecord->card) ? $cardRecord->card : 'XXXX';

        $twitterUserName = self::getUsernameFromID($twRecord->twitter_user_id);
        $tweetResponseApproveYet = '';
        $tweetResponseApprove = '';
        $gameAnswers = eGameChoiceAnswer::model()->findAllByAttributes(array('game_choice_id' => $game->id));

        $twituser = eUserTwitter::model()->findByAttributes(array('user_id' => $twRecord->user_id));
        $flag = false;
        //$authpay = $twituser->authorize_pay;

        preg_match_all('/(#|@)([a-zA-Z0-9]{1,16})/', $tweet, $matches, PREG_SET_ORDER);

        if (isset($matches[0]) && count($matches[0]) > 0 && $twituser->authorize_pay == 1) {
            foreach ($gameAnswers as $gameAnswer) {
                if (strtolower($matches[1][2]) == strtolower($gameAnswer->label)) {

                    $transaction = new eGamingTransaction();

                    $transaction->user_id = $user->id;
                    $transaction->processor = 'twitterpay';
                    $transaction->game_choice_id = $game->id;
                    $transaction->response = 'TweetPay';
                    $transaction->request = 'TwitterRequest';
                    $transaction->description = 'This is for Demo Twitter Pay';
                    $transaction->country = 'USA';
                    $transaction->operator = 'Youtoo';
                    $transaction->price = 5;
                    $transaction->created_on = new CDbExpression('NOW()');

                    if ($transaction->save()) {
                        $gameChoiceResponse = new eGameChoiceResponse();

                        $gameChoiceResponse->user_id = $user->id;
                        $gameChoiceResponse->source = 'GameTweet';
                        $gameChoiceResponse->game_choice_id = $game->id;
                        $gameChoiceResponse->game_choice_answer_id = $gameAnswer->id;
                        //$gameChoiceResponse->sms_id = $tweet_id;
                        $gameChoiceResponse->transaction_id = $transaction->id;

                        if ($gameChoiceResponse->save()) {

                            $credittransaction = new eCreditTransaction;
                            $credittransaction->game_type = "game_choice";
                            $credittransaction->user_id = $user->id;
                            $credittransaction->game_id = $gameChoiceResponse->game_choice_id;
                            $credittransaction->trans_id = $gameChoiceResponse->id;
                            $credittransaction->type = "earned";
                            $credittransaction->credits = $gameAnswer->point_value;

                            $credittransaction->save();
                        }
                    }
                    $credits = eCreditBalance::getTotalUserBalance($user->id);
                    $tweetResponseApproveYet = "@" . $twitterUserName . " Thank You! We're about to charge your card " . substr($cardonfile,-4) . ' at ' . date('H:i:s');
                    $tweetResponseApprove = '@' . $twitterUserName . ' Card ' . substr($cardonfile,-4) . ' charged $5. Credits '.$credits['credits_total'].' Redeem: https://initechgames.youtoo.com/redeemtw/'.rand(0,999);

                    self::tweetAs($youtoosandbox->id, $tweetResponseApproveYet);
                    self::tweetAs($youtoosandbox->id, $tweetResponseApprove);
                    $flag = true;
                }
            }
        }
        if ($flag == false) {
            $tweetResponseError = "@" . $twitterUserName . " Sorry. Unable to charge card " . substr($cardonfile,-4) . ". Tweet not submitted. Please authorize. " . date('H:i:s');

            self::tweetAs($youtoosandbox->id, $tweetResponseError);
        }
    }

    public static function hasValidHashtag($tweet) {

        preg_match_all('/(#|@)([a-zA-Z0-9]{1,16})/', $tweet, $matches, PREG_SET_ORDER);

        if (isset($matches[0]) && count($matches[0]) > 0) {
            // we only take the first answer

            $arr = array('#a', '#b', '#c', '#d', '@a', '@b', '@c', '@d', '#A', '#B', '#C', '#D', '@A', '@B', '@C', '@D');

            if (strtolower($matches[0][0]) == '@youtoosandbox') {
                foreach ($matches as $m) {
                    if (in_array($m[0], $arr)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

}

?>
