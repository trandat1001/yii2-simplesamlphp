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

        // TODO - Rove
        /*
        $domain = "Lion";

        if($domain == "Tellus") // Tellus
        {
            $_SESSION['adfs_trust']     = 'http://adfs.4tellus.com/adfs/services/trust';
            $_SESSION['adfs_ls']        = 'https://adfs.4tellus.com/adfs/ls/';
            $_SESSION['adfs_cer1']      = 'MIIC4jCCAcqgAwIBAgIQc/Nod0UgVqNJO4fEseyL+DANBgkqhkiG9w0BAQsFADAtMSswKQYDVQQDEyJBREZTIEVuY3J5cHRpb24gLSBhZGZzLjR0ZWxsdXMuY29tMB4XDTE2MDgxOTAzNTQzMFoXDTE3MDgxOTAzNTQzMFowLTErMCkGA1UEAxMiQURGUyBFbmNyeXB0aW9uIC0gYWRmcy40dGVsbHVzLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAMFfAnjzgqASdUOy/ZGk1upZUjSXUNnU90MYZJP9GhBIS6mL21D9BiiwM0iKh+ZOQKnwtGrZ3zTZJPH1oL5B4ZmNU3PjHNGFhz+4J69PN2/glaPvlDAxI3DsL9NfJgNoORBlc1/wAnpCCKnyYKULJ06N4nd1aHKBkRAD4B7ct3Jc5EHlyzoN8bf+wYWNlXbJGJ/oBh+ZMj8R2aw1OOz+RTGLkmoMmH9eiu3wMbrRy+aWrrrVaCAQDOCoZbuPKMZpEvdVu0rtEtrw/z2im2ddk/0dFHFmAMr+nlS715NPTZgGD4H9If4VLoP/n5KeioxiWkAjwhjrrqn78HMIT+080EsCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAUYajWJX63cF0CYxiIZXd2ibSXQfeFsFOMhrEQsOamijUy9b4ABUevuqOPhKgZt8IVpq1AVzsHazmxpDjZOYwVS5jteFp01feKg0un0X24jsWC4Ee2aJYaikDZwn1BMB8IKOoL+2bSkcNaK/ahn1/dbt7j+l+r22kiI6pgst2ZXsJ4MOE/xmLLbo9v8KLMVmaoJOF/86dw/jR0p+iv3RH88LfdlhMdl42gtQZQtaJSIfEit/v8WsSvUCY2Q8g8DG2YBxa9HNMwp8ltkflLubOya/IyZF92w7HiMc2xejHxDqA3NDFLBg+2CGar+DKX/nhZHzZFK4mn1RmpFMJFB6aEg==';
            $_SESSION['adfs_cer2']      = 'MIIC3DCCAcSgAwIBAgIQHRNLLmYSqIRBPY8cQTtxOTANBgkqhkiG9w0BAQsFADAqMSgwJgYDVQQDEx9BREZTIFNpZ25pbmcgLSBhZGZzLjR0ZWxsdXMuY29tMB4XDTE2MDgxOTAzNTQzMFoXDTE3MDgxOTAzNTQzMFowKjEoMCYGA1UEAxMfQURGUyBTaWduaW5nIC0gYWRmcy40dGVsbHVzLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAMTVU1RRooT0ZIHNy1QYP6+8WNlqpCeWyYaaQPRBYI10TF8pkfwe892hjblj52ZizuOARnSFMuIYJTnjcVt+x1bpZQBTufhAadWQnujCUmx6qM+tAzQAgpn9/8qRHca1tOZ+PQ2/ELs07gmMwlNnTOD2ankby1xeSvWM4B5ACfExaZMxI3Vri4Fof70kRrBMBRBtHhfYF/NVYxF6ofqf4jqCuqJb5Fc1bX6d4d25t/veraCOZTN9LkUebX6vas0yS9SGQqG9NkTH7VrlbMPSJVHrmxfVbfSYV2U22x1mfw7t6IpHRv4P78Niu1V2gR96vzr4wah2diJy/1dt2/DUkjECAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAnlD3QDKVwilAuewdJ0nIAbA3z2FrYx5hwKVs/z125m7IUq6CtdSo7lHJOcyDrCswEAfQg9qFk7dxgKN4ThKkBqXUt8lOJPNMArErPnCfumWODa0gQSRl1a6Z9KLkI5abBTcMK46iD+mqSgmtHgBTVkFZIkeUAThduWrsaa7qPnqhzEl6QtduWpaYY4VMXi+fXChx4QSMwFSB6+8ieE2x+nxyqUvoMNf93EOJUcra5fFdGsrB6uGfEXvuquWx/6Z7hSkbmRjKzYsF+sGk3l81LjjqxKZwtpebKO/+8lJEk1H8C5+/B0Nw6eogGCqyr/uleLiHe9pkfdluH0BCfrAsbQ==';
        }
        elseif($domain == "Lion") // Lion
        {
            $_SESSION['adfs_trust']     = 'http://adfs.lion.com/adfs/services/trust';
            $_SESSION['adfs_ls']        = 'https://adfs.lion.com/adfs/ls/';
            $_SESSION['adfs_cer1']      = 'MIIC3DCCAcSgAwIBAgIQQ4SPX9HhRJdAuS6t0hk60zANBgkqhkiG9w0BAQsFADAqMSgwJgYDVQQDEx9BREZTIEVuY3J5cHRpb24gLSBhZGZzLmxpb24uY29tMB4XDTE2MDgxODEwMzUyOFoXDTE3MDgxODEwMzUyOFowKjEoMCYGA1UEAxMfQURGUyBFbmNyeXB0aW9uIC0gYWRmcy5saW9uLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKbQc/+/yeiHmIxadyt8GEoopH0nLmdMq7eb2NHq56YMqp7klPqLVjSIKsAspw3Al8/y16ZJ7BrObS4wQvpl9SortiFkhw5QUOiik2F131rok+opA+3ybp33BQf9s62eu7oPIoW7XxSi29NfmYI+cITee9j/b2us7RdaC0ZEFg2u30nK9A1DlAzfiVYi5sMwS+i+8OGBQi91jVARnpdSwBtQlfnenlvhnelcJDYyGmoQGtkNjrlT/VFMB0uHI6bbBT81Qh8SVZZmgoe9fDlSoR3RTzNL1ne5KUZpTKapOrwl2Ql3Q4tHXbb1AZs+HJPaEvcz0S+fIURgurK8ugqK2w8CAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAPo5a58GoiUspo1lWVwavz6sQQuUnFGL/lX03TuXIyToD+hY0198O4DzZhnbnXfsdN5u5sPx5p1xRHv0DEOyzGqlmU0ljOJCXQKI5DXh5+RoigBH2jFzC9kYSgv+ovPA+zwaWP7JdYLgPN9IYh66WKhwblIWjdy+e+tVHehSc1j4JgRZ/7jR+kZdSF4V96WUbbB1CjOWSPoFjm7vIIfkcQv8yMnMs6BZIZkWBkfhsmfQAE4IJ0/+kB9Mn1+QmicNRRUjeIKA2PmtDa9tmoqRxa/ogLEK49VVIPDY8r9oF8CexiOJ9MD0SM31GZvvfR0BRJ+8e7GK0NwOIpz8SuJsFKQ==';
            $_SESSION['adfs_cer2']      = 'MIIC1jCCAb6gAwIBAgIQPrcl2DyfwIpHVumyAnSPJDANBgkqhkiG9w0BAQsFADAnMSUwIwYDVQQDExxBREZTIFNpZ25pbmcgLSBhZGZzLmxpb24uY29tMB4XDTE2MDgxODEwMzUyN1oXDTE3MDgxODEwMzUyN1owJzElMCMGA1UEAxMcQURGUyBTaWduaW5nIC0gYWRmcy5saW9uLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAM6XMsY0K48BsCBGp0js8+St2sW5QpaVKS7/QjoT5WF2NSAGOsR+1ODWeahp52GWsLpeLxAY7e2fdm2NoopDdL5X5YqRS2Z3pWgwiN7D4SVIB4GBx7O4lz0YitQ+j5GsQ6NeLMrH15RD75St0ZwqRr9zdlS8vwhOeEGGkEdUgdCaocjIaCmieU3lU/HHiEBAOb0CxgoXNtI+pfYqi+fzjql6+Vd3qbl3z6+1xMPqNy6vHeGBgdrsJ7AI1TjiGSqQOGZHyUkKwrKmFyLxrWvTTNTr5K6s7eZ+AvNrdFpWA0rxETniX6YIMZAk6bJUzEO1DHt3RXtxYJHeF+NlOWEM52UCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAePy4gMGvecQqoJh+RXHVszZdGvKnv3x528KiUofaQoc5d2z/xr+Fxb4pQtCQi57TJuvwaTpyFO0WJzYlambStQupq65kUXhjE+6uFySDT3mm6meqrUdCzIarwo3bWT4bNyzDwrTw92fmErhaVJVNoPxUSCaBeLHPohT5E0/mH0gBGQxTcyQRI1I808u1MJ/1BUg3sn493tIbqWCnG7kJAeZg70oAA1eJedL2av2l2dWhK9u0IFStf2SvbiJOusCurhzKILRHQMiE2HK+KNi/Sz0UQ+SWPRZQQdPgpsT7emSo9am10jHb9h2o+3+rnSaT8lB8QpMm8jZKz6EfXJApAg==';
        }
        */

        $this->setRootPathOfAlias();
        $this->loadRequiredClass();

        $this->simplesamlphpInstance->requireAuth();

        echo "<pre>";
        print_r($this->simplesamlphpInstance->getAttributes());
        echo "</pre>";
        die();

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
