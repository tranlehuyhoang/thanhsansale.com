<?php

namespace App\Services\Identities\UserTokenServices;

use App\Models\UserToken;
use App\Services\BaseService;
use App\Services\Common\SqlCommon;

class UserTokenService extends BaseService implements IUserTokenService
{

    public $tableName = 'user_tokens';

    /**
     *
     * @param mixed $token
     */
    public function GetByToken($token)
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "Token = '$token'", Null, Null, Null);
        $data = $this->context->fetch_one($buildSql);
        if ($data == Null) {
            return Null;
        }
        $userToken = new UserToken($data);
        return $userToken;
    }

    /**
     *
     * @param mixed $userId
     */
    public function GetByUserId($userId)
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "UserId = '$userId'",'CreatedAt',NULL,NULL);
        $data = $this->context->fetch($buildSql);
        if ($data == Null) {
            return Null;
        }
        $userTokens = [];
        foreach ($data as $item) {
            $userToken = new UserToken($item);
           array_push($userTokens,$userToken);
        }
        return $userTokens;
    }

    /**
     */
    public function GetAll()
    {
    }

    /**
     *
     * @param mixed $id
     */
    public function GetById($id)
    {
    }

    /**
     *
     * @param mixed $pageIndex
     * @param mixed $pageSize
     */
    public function GetWithPaginate($pageIndex, $pageSize)
    {
    }
}
