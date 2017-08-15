<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
//             rememberMe must be a boolean value
//            ['rememberMe', 'boolean'],
//             password is validated by validatePassword()
//            ['password', 'validatePassword'],
        ];
    }

//    /**
//     * Validates the password.
//     * This method serves as the inline validation for password.
//     *
//     * @param string $attribute the attribute currently being validated
//     * @param array $params the additional name-value pairs given in the rule
//     */
//    public function validatePassword($attribute, $params)
//    {
//        if (!$this->hasErrors()) {
//            $user = $this->getUser();
//            if (!$user || !$user->validatePassword($this->password)) {
//                $this->addError($attribute, 'Incorrect username or password.');
//            }
//        }
//    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            if($this->getUser())
                return Yii::$app->user->login($this->getUser(),0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            
            Yii::$app->jira->username=$this->username;
            Yii::$app->jira->password=$this->password;
            Yii::$app->jira->jiraUrl='https://isddesign.atlassian.net';
            
            $jira_user = Yii::$app->jira->request('GET','/myself','');
            var_dump($jira_user);
            if($jira_user){
                $this->_user = User::findByUsername($jira_user['name']);
                
                if(!$this->_user)
                    $this->_user = new User();
                
                $this->_user->username = $jira_user['name'];
                $this->_user->email = $jira_user['emailAddress'];
                
                $this->_user->setPassword($this->password);
                $this->_user->generateAuthKey();
                
                return $this->_user->save() ? $this->_user : null;
                
            }
        }

        return $this->_user;
    }
}
