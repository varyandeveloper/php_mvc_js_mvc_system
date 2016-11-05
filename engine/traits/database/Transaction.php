<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 5/29/2016
 * Time: 4:29 PM
 */

namespace engine\traits\database;

/**
 * Class Transaction
 * @package Engine\Additional\DB
 */
trait Transaction
{
    /**
     * @var int $transactionCount
     */
    private $__transactionCount = 0;
    /**
     * @var array $__errors
     * */
    private $__errors;

    /**
     * beginTransaction
     * Will start transaction
     * @return bool
     */
    protected function beginTransaction()
    {
        if (!$this->__transactionCount++) {
            return $this->__pdo->beginTransaction();
        }
        $this->__pdo->exec('SAVEPOINT trans' . $this->__transactionCount);
        return $this->__transactionCount >= 0;
    }

    /**
     * commit method
     * Will end transaction if is ok
     * @return bool
     */
    protected function commit()
    {
        if (!--$this->__transactionCount) {
            return $this->__pdo->commit();
        }
        return $this->__transactionCount >= 0;
    }

    /**
     * rollback method
     * Will end transaction with removing inseted data
     * @return bool
     */
    protected function rollback()
    {
        if (--$this->__transactionCount) {
            $this->exec('ROLLBACK TO trans' . $this->__transactionCount + 1);
            return true;
        }
        return $this->__pdo->rollback();
    }

    /**
     * prepare method
     * @param null|string $query
     * @return \PDOStatement
     * */
    public function query(string $query = null) : \PDOStatement
    {
        try {
            if (!empty($this->__insert) && !is_null($this->__insert)) {
                $this->beginTransaction();
                $this->finishQuery($query);
                $this->__result = $this->__pdo->prepare($this->__query);
                $this->__result->execute();
                $this->__lastInsertId = $this->__pdo->lastInsertId();
                $this->commit();
            } else {
                $this->finishQuery($query);
                //dp($this->__query);
                $this->__result = $this->__pdo->prepare($this->__query);
                $this->__result->execute();
            }
            $this->__rowCount = $this->__result->rowCount();
        } catch (\PDOException $exception) {

            if (!empty($this->__insert) && !is_null($this->__insert))
                $this->rollback();

            $this->__errors = [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ];
        }

        return $this->__result;
    }

    /**
     * finishQuery method
     * @functionality save all query in $this->__query and null all query parameters
     * @param string $query
     * @return void
     * */
    protected function finishQuery($query)
    {
        $this->__query = !is_null($query)
            ? $query : $this->__select .
            $this->__insert .
            $this->__update .
            $this->__delete .
            $this->__create .
            $this->__from .
            $this->__join .
            $this->__where .
            $this->__group .
            $this->__having .
            $this->__order .
            $this->__limit;

        $this->__insert = $this->__update = $this->__delete = $this->__select = $this->__create =
        $this->__from = $this->__join = $this->__where = $this->__group = $this->__having =
        $this->__order = $this->__limit = "";
    }
}