<?php

namespace Model;

class BaseStorage
{
    const DB_DATETIME_FORMAT = 'Y-m-d H:i:s';

    private static $pdo;

    protected static function getPDO()
    {
        if (!self::$pdo) {

            self::$pdo = new \PDO(
                \Settings::get('db')['dsn'],
                \Settings::get('db')['username'],
                \Settings::get('db')['password']
            );

            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        }

        return self::$pdo;
    }

    public function beginTransaction() : bool
    {
        self::getPDO()->query('SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ');
        return self::getPDO()->beginTransaction();
    }

    public function commit() : bool
    {
        return self::getPDO()->commit();
    }

    public function rollback() : bool
    {
        return self::getPDO()->rollBack();
    }

}