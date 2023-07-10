<?php

namespace app\models;

use app\models\db\Role as DbRole;
use app\models\db\RolePermission as DbRolePermission;
use app\models\db\Permission as ModelsPermission;
use app\models\db\User as ModelsUser;
use yii\caching\CacheInterface;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;
use yii\rbac\Assignment;
use yii\rbac\PhpManager;

class AuthManager extends PhpManager
{
    public $db = 'db';
    public $cache;
    public $cacheKey = 'rbac';

    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::class);
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, CacheInterface::class);
        }
    }

    /**
     * Проверка прав пользователя
     *
     * @param $userId
     * @param $permissionName
     * @param array $params
     * @return bool
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        $assignments = $this->getAssignments($userId);
        return $this->checkAccessRecursive($userId, $permissionName, $params, $assignments);
    }

    /**
     * @param $userId
     * @return array|mixed|Assignment[]
     */
    public function getAssignments($userId)
    {
        if ($this->isEmptyUserId($userId)) {
            return [];
        }

        $query = (new Query())
            ->select([
                'u.user_id',
                'p.permission',
                'role' => 'r.role',
            ])
            ->from(['u' => ModelsUser::tableName()])
            ->innerJoin(['r' => DbRole::tableName()], 'r.role_id = u.role_id')
            ->leftJoin(['rp' => DbRolePermission::tableName()], 'r.role_id = rp.role_id')
            ->leftJoin(['p' => ModelsPermission::tableName()], 'p.permission_id = rp.permission_id')
            ->where(['u.user_id' => (string)$userId]);


        $assignments = [
            'spectator' => new Assignment([
                'userId' => $userId,
                'roleName' => 'spectator',
            ]),
        ];


        foreach ($query->all($this->db) as $row) {
            if (!isset($assignments[$row['role']])) {
                $assignments[$row['role']] = new Assignment([
                    'userId' => $row['user_id'],
                    'roleName' => $row['role'],
                ]);
            }

            $assignments[$row['permission']] = new Assignment([
                'userId' => $row['user_id'],
                'roleName' => $row['permission'],
            ]);

        }

        if (!empty($row['role']) && $row['role'] === 'administrator') {
            $query = (new Query())
                ->from(DbRole::tableName());

            foreach ($query->all($this->db) as $row) {
                $assignments[$row['role']] = new Assignment([
                    'userId' => $userId,
                    'roleName' => $row['role'],
                ]);
            }

            $query = (new Query())
                ->from(ModelsPermission::tableName());

            foreach ($query->all($this->db) as $row) {
                $assignments[$row['permission']] = new Assignment([
                    'userId' => $userId,
                    'roleName' => $row['permission'],
                ]);
            }
        }


        return $assignments;
    }


    /**
     * @param int|string $user
     * @param string $itemName
     * @param array $params
     * @param Assignment[] $assignments
     * @return bool
     */
    protected function checkAccessRecursive($user, $itemName, $params, $assignments)
    {
        if (isset($assignments[$itemName])) {
            return true;
        }
        return false;
    }

    /**
     * @param $userId
     * @return bool
     */
    private function isEmptyUserId($userId)
    {
        return !isset($userId) || $userId === '';
    }
}
