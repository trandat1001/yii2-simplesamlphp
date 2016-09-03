<?php
namespace SimpleSamlPhp\Actions;

use Yii;
use yii\base\Action;

/**
 * Use this action to login a user.
 */
class LoginAction extends Action {

    /**
     * The component name which is you register Simplesamlphp instance in your config/main.php.
     */
    public $simplesamlphpComponentName = 'simplesamlphp';

    /**
     * Where the user is redirected after logout.
     */
    public $redirectAfterLoginTo = array('/');

    /**
     * Simplesamlphp component instance.
     */
    private $simplesamlphpInstance = null;

    /**
     * Init LoginAction.
     */
    public function init() {
        assert(!is_null($this->simplesamlphpComponentName), 'You must set simplesamlphp component name.');
        assert(!empty($this->redirectAfterLoginTo), 'You must set redirect after login to.');

        $componentName = $this->simplesamlphpComponentName;
        $this->simplesamlphpInstance = Yii::$app->$componentName;
    }

    /**
     * Run the login action. The user will be redirected to Simplesamlphp IdP login page, if he successfully login then he will be redirected to this page again. 
     * After that login the user to Yii application and then redirect the user to $redirectAfterLoginTo route.
     */
    public function run() {
	    $this->init();

        $this->setRootPathOfAlias();
        $this->loadRequiredClass();

        $this->simplesamlphpInstance->requireAuth();

        $userIdentity = new SSOUserIdentity($this->simplesamlphpInstance->username, '');
        Yii::app()->user->login($userIdentity);

        Yii::$app->controller->redirect($this->redirectAfterLoginTo);
    }

    private function setRootPathOfAlias() {
        
        if (Yii::getAlias('yii-simplesamlphp') === false) {
            Yii::setAlias('yii-simplesamlphp', realpath(dirname(__FILE__) . '/..'));
        }
    }

    private function loadRequiredClass() {
        //Yii::import('yii-simplesamlphp.components.SSOUserIdentity');
        Yii::autoload("SimpleSamlPhp\Components\SSOUserIdentity");
    }

}
