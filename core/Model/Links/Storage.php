<?php

namespace Model\Links;

class Storage extends \Model\BaseStorage
{
    private $linksTableName = 'links';

    public function getLastGeneratedHash() :? string
    {
        $pdo = self::getPDO();

        $result = $pdo->query(
            "SELECT `hash` 
            FROM `{$this->linksTableName}`
            ORDER BY `id` DESC
            LIMIT 1"
        );

        $row = $result->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return $row['hash'];
        }

        return null;
    }

    public function findExistingLink(string $findByField, string $value) :? array
    {
        $result = null;

        $pdo = self::getPDO();

        $statement = $pdo->prepare(
            "SELECT * 
            FROM `{$this->linksTableName}`
            WHERE `{$findByField}` = ?
            LIMIT 1"
        );

        if (
            $statement->execute([$value])
            and $result = $statement->fetch(\PDO::FETCH_ASSOC)
        ) {
            $result['ttl'] = \DateTime::createFromFormat(
                static::DB_DATETIME_FORMAT,
                $result['ttl'],
                new \DateTimeZone('GMT+0')
            );
        }

        return $result ?: null;
    }

    public function insert(string $link, string $hash, \DateTime $ttl) : bool
    {
        $pdo = self::getPDO();

        $statement = $pdo->prepare(
            "INSERT INTO `{$this->linksTableName}` (`link`, `hash`, `ttl`) VALUES (?, ?, ?)"
        );

        $result = $statement->execute([
            $link,
            $hash,
            $ttl->format(static::DB_DATETIME_FORMAT)
        ]);

        return $result;
    }

    public function actualizeLinkTTL(string $link, \DateTime $ttl) : bool
    {
        $pdo = self::getPDO();

        $statement = $pdo->prepare(
            "UPDATE `{$this->linksTableName}` SET `ttl` = ? WHERE `link` = ? LIMIT 1"
        );

        $result = $statement->execute([
            $ttl->format(static::DB_DATETIME_FORMAT),
            $link
        ]);

        return $result;
    }

    public function removeExpiredLinks()
    {
        $pdo = self::getPDO();

        $current_utc_datetime = new \DateTime('now', new \DateTimeZone('GMT+0'));

        $pdo->prepare("DELETE FROM {$this->linksTableName} WHERE `ttl` < ?")->execute([
            $current_utc_datetime->format(static::DB_DATETIME_FORMAT)
        ]);
    }

}