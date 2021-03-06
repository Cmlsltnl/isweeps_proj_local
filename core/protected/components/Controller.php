<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public function init() {
        $RE_MOBILE = '/(nokia|iphone|iPad|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220)/i';
        if (empty(Yii::app()->session['mobile'])) {
            Yii::app()->session['mobile'] = isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']) || preg_match($RE_MOBILE, $_SERVER['HTTP_USER_AGENT']);
        }
        if ($this->isMobile() && Yii::app()->params['useMobileTheme']) {
            Yii::app()->theme = 'mobile';
            $this->layout = null;
        }
    }

    protected function isMobile() {
        return empty(Yii::app()->session['mobile'])?false:true;
    }

    protected function afterAction($action) {
        if (!Yii::app()->user->isGuest) {
            AuditUtility::save($this, $_REQUEST);
        }
    }

    public function getOverrideViewPath($viewName)
    {
        if(($module=$this->getModule())===null)
            $module=Yii::app();
        if(file_exists(Yii::getPathOfAlias('client') . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->getId() . DIRECTORY_SEPARATOR . $viewName . '.php')){
            return Yii::getPathOfAlias('client') . DIRECTORY_SEPARATOR . 'views'. DIRECTORY_SEPARATOR .$this->getId();
        } else {
            return Yii::getPathOfAlias('core').DIRECTORY_SEPARATOR.'views'. DIRECTORY_SEPARATOR .$this->getId();
        }
    }

    public function getViewFile($viewName) {
        if (($theme = Yii::app()->getTheme()) !== null && ($viewFile = $theme->getViewFile($this, $viewName)) !== false)
            return $viewFile;
        $moduleViewPath = $basePath = Yii::app()->getOverrideViewPath($viewName);
        if (($module = $this->getModule()) !== null)
            $moduleViewPath = $module->getViewPath();
        return $this->resolveViewFile($viewName, $this->getOverrideViewPath($viewName), $basePath, $moduleViewPath);
    }

    public function resolveViewFile($viewName, $viewPath, $basePath, $moduleViewPath = null) {
        if (empty($viewName))
            return false;

        if ($moduleViewPath === null)
            $moduleViewPath = $basePath;

        if (($renderer = Yii::app()->getViewRenderer()) !== null)
            $extension = $renderer->fileExtension;
        else
            $extension = '.php';
        if ($viewName[0] === '/') {
            if (strncmp($viewName, '//', 2) === 0) {
                $viewFile = $basePath . $viewName;
                $viewFile = Yii::app()->getLayoutPath() . $viewName;
            } else {
                $viewFile = $moduleViewPath . $viewName;
            }
        } elseif (strpos($viewName, '.'))
            $viewFile = Yii::getPathOfAlias($viewName);
        else
            $viewFile = $viewPath . DIRECTORY_SEPARATOR . $viewName;

        if (is_file($viewFile . $extension))
            return Yii::app()->findLocalizedFile($viewFile . $extension);
        elseif ($extension !== '.php' && is_file($viewFile . '.php'))
            return Yii::app()->findLocalizedFile($viewFile . '.php');
        else
            return false;
    }

}