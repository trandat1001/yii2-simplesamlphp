<?php
namespace SimpleSamlPhp\Components;

use yii\base\Object;
use Yii;
use yii\BaseYii;

/**
 * This components works as wrapper of Simplesamlphp Sp Api that can be use in Yii 1 application.
 */
class Simplesamlphp extends Object {

    /**
     * Path of lib/_autoload.php of your Simplesamlphp Sp.
     */
    public $autoloadPath;

    /**
     * Authentication source you will use.
     */
    public $authSource;

    /**
     * SimpleSAML_Auth_Simple's object.
     */
    private $authSimple;

    /**
     * Init this component.
     * It loads Simplesamlphp autoloader and initialize $authSimple with SimpleSAML_Auth_Simple instance using authSource specified in $this->authSource.
     */
    public function init() {
        assert(!is_null($this->autoloadPath), 'You must set autoload path to Simplesamlphp_SP/lib/_autoload.php.');
        assert(!is_null($this->authSource), 'You must set your Simplesamlphp SP auth source.');

        $this->loadSimplesamlPhp();
        $this->authSimple = new \SimpleSAML_Auth_Simple($this->authSource);

        parent::init();
    }

    /**
     * Register Simplesamlphp autoloader
     */
    public function loadSimplesamlPhp() {
        require_once($this->autoloadPath);
        //YiiBase::registerAutoloader('SimpleSAML_autoload', true);
        BaseYii::autoload('SimpleSAML_autoload');
        
    }

    /**
     * Make sure user is authenticated. If the user is not authenticated, he will be rediected to Simplesamlphp IdP login page. If he is authenticated, it does nothing.
     * @see https://simplesamlphp.org/docs/stable/simplesamlphp-sp-api#section_3
     */
    public function requireAuth(array $params = array()) {
        $this->authSimple->requireAuth($params);
    }

    /**
     * Log in the current user. He will be redirected to Simplesamlphp IdP login page. After a successfull login, he will be redirected to the referer page.
     * @see https://simplesamlphp.org/docs/stable/simplesamlphp-sp-api#section_4
     */
    public function login(array $params = array()) {
        $this->authSimple->login($params);
    }

    /**
     * Logout the current user. Clear Simplesamlphp Sp and Simplesamlphp IdP session and redirected to the referer page.
     * @see https://simplesamlphp.org/docs/stable/simplesamlphp-sp-api#section_5
     */
    public function logout($params = NULL) {
        $this->authSimple->logout($params);
    }

    public function logoutRove($params = NULL, $aADFS) {
        $this->authSimple->logoutRove($params, $aADFS);
    }

    /**
     * Get login url.
     * @see https://simplesamlphp.org/docs/stable/simplesamlphp-sp-api#section_8
     */
    public function getLoginURL($returnTo = null) {
        $this->authSimple->getLogoutUrl($returnTo);
    }

    /**
     * Get logout url.
     * @see https://simplesamlphp.org/docs/stable/simplesamlphp-sp-api#section_9
     */
    public function getLogoutURL($returnTo = null) {
        $this->authSimple->getLogoutUrl($returnTo);
    }

    /**
     * Check wether the user is authenticated or not.
     * @see https://simplesamlphp.org/docs/stable/simplesamlphp-sp-api#section_9
     * @return bool true if user is authenticated, false it he is not.
     */
    public function isAuthenticated() {
        return $this->authSimple->isAuthenticated();
    }

    /**
     * Get attributes which are returned from Simplesamlphp IdP after a successfull login.
     * @see https://simplesamlphp.org/docs/stable/simplesamlphp-sp-api#section_6
     * @return array attributes
     */
    public function getAttributes() {
        return $this->authSimple->getAttributes();
    }

    /**
     * Get auth data.
     * @see https://simplesamlphp.org/docs/stable/simplesamlphp-sp-api#section_7
     * @return mixed
     */
    public function getAuthData($name) {
        return $this->authSimple->getAuthData($name);
    }
    
    /**
	 * Retrieve all authentication data.
	 *
	 * @return array|NULL  All persistent authentication data, or NULL if we aren't authenticated.
	 */
	public function getAuthDataArray() {

		return $this->authSimple->getAuthDataArray();
	}

    /**
     * Get attribute by it's key.
     * @return string the attribute value
     */
    public function __get($name) {
        return isset($this->getAttributes()[$name]) ? $this->getAttributes()[$name][0] : null;
    }

}
