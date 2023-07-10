<?php

namespace app\models\db;

use app\models\db\Role;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const SCENARIO_CREATE = 'create';

    /**
     * @return array
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_CREATE => ['username', 'password', 'role_id'],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['username', 'password', 'role_id'], 'required', 'on' => self::SCENARIO_CREATE],
            [['username'], 'unique', 'on' => [self::SCENARIO_CREATE]],
            [['role_id'], 'exist', 'targetClass' => Role::class, 'targetAttribute' => 'role_id', 'on' => [self::SCENARIO_CREATE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::find()->where('user_id = :id', ['id' => $id])->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::find()
                ->where('access_token = :access_token', ['access_token' => $token])
                ->andWhere('access_token is not null')
                ->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::find()
            ->with(['role', 'role.permissions'])
            ->where('LOWER(login) = LOWER(:login)', ['login' => trim($username)])
            ->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function refreshToken()
    {
        $this->access_token = \Yii::$app->security->generateRandomString();
        $this->save();
        $this->refresh();
    }
}
