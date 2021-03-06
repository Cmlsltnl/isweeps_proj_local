<?php

class WebUser extends CWebUser {

    // Store model to not repeat query.
    private $_model;

    public function getUsername() {
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->username;
    }

    public function getEmail() {
        return $this->getUsername();
    }

    public function getRole() {
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->role;
    }

    public function hasPermission($controller){
        $user = $this->loadUser(Yii::app()->user->id);
        $permission = eUserPermission::model()->findByAttributes(Array('user_id'=>$user->id,'controller'=>strtolower($controller)));        
        return !is_null($permission);
    }
    
    public function isSuperAdmin() {
        if($user = $this->loadUser(Yii::app()->user->id))
            return $user->role == 'super admin';
        return false;
    }

    public function isSiteAdmin() {
        if($user = $this->loadUser(Yii::app()->user->id))
            return $user->role == 'site admin';
        return false;
    }
    
    public function isProducerAdmin() {
        if($user = $this->loadUser(Yii::app()->user->id))
            return $user->role == 'producer admin';
        return false;
    }
    
    public function isAdmin() {

        if($this->isSuperAdmin() || $this->isSiteAdmin() || $this->isProducerAdmin()) {
            return true;
        }
        
        return false;
    }
    
    public function isUser() {
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->role == 'user';
    }

    // Load user model.
    protected function loadUser($id = null) {
        if ($this->_model === null) {
            if ($id !== null)
                $this->_model = User::model()->findByPk($id);
        }

        return $this->_model;
    }

}