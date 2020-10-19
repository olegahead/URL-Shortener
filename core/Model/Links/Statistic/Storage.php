<?php

namespace Model\Links\Statistic;

use Model\BaseStorage;

class Storage extends BaseStorage
{

    private $statisticsTableName = 'statistics';

    public function logLinkVisit(int $linkId, string $ip)
    {

        $pdo = self::getPDO();

        $statement = $pdo->prepare(
            "INSERT INTO `{$this->statisticsTableName}` (`link_id`, `ip`) VALUES (?, ?)"
        );

        $statement->execute([$linkId, $ip]);

    }

    public function getReferralsCountForLastNumDays(int $linkId, int $numDays = 0) : int
    {
        $endDay = new \DateTime();
        $endDay->setTime(23, 59, 59);

        $startDay = (clone $endDay);
        $startDay->setTime(0, 0, 0);

        if ($numDays) {
            $startDay->modify("-{$numDays} days");
        }

        return $this->getLinkReferralsCount($linkId, $startDay, $endDay);
    }

    private function getLinkReferralsCount(int $linkId, \DateTime $periodStart, \DateTime $periodEnd) : int
    {
        $pdo = self::getPDO();

        $statement = $pdo->prepare(
            "SELECT COUNT(*)
            FROM `{$this->statisticsTableName}` 
            WHERE link_id = ? AND `visit_datetime` BETWEEN ? AND ?"
        );

        if (
            $statement->execute([
                $linkId,
                $periodStart->format(static::DB_DATETIME_FORMAT),
                $periodEnd->format(static::DB_DATETIME_FORMAT),
            ])
        ) {
            return intval($statement->fetch(\PDO::FETCH_COLUMN) ?? null);
        }

        return 0;

    }

}