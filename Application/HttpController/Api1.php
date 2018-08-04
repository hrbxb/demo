<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/8/5
 * Time: 上午12:24
 */

namespace App\HttpController;


use App\Model\User\UserModelOne;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\MysqlPoolObj;
use EasySwoole\Component\Pool\PoolManager;

class Api1 extends Base
{
    private $db;

    protected function onRequest($action): ?bool
    {
        $this->db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();
        if(!$this->db instanceof MysqlPoolObj){
            throw new \Exception('Db Pool is Empty');
        }
        return true;
    }

    protected function onException(\Throwable $throwable): void
    {
        $this->response()->write($throwable->getMessage());
    }

    protected function afterAction($actionName): void
    {
        PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($this->db);

    }

    public function gc()
    {
        parent::gc(); // TODO: Change the autogenerated stub
        /*
        * 因为控制器是对象池模式，因此请重置自定义属性,否则会被下个请求复用。
        */
        $this->db = null;
    }

    function allUser()
    {
        $model = new UserModelOne($this->db);
        $res = $model->all();
        $this->writeJson(200,$res);
    }

}