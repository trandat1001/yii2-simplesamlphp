<?php
namespace SimpleSamlPhp\Components;

use yii\base\Object;
use app\models\User;
use Yii;

class SSOUserIdentity extends User {

    public function authenticate() {
        return true;
    }

}
