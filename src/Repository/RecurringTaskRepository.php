<?php

namespace App\Repository;

use App\Entity\RecurringTask;
use App\Service\DateToDisplay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecurringTask>
 */
class RecurringTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecurringTask::class);
    }

    public function getList(): array
    {
        $queryBuilder = $this->createQueryBuilder('rt');
        $queryBuilder
            ->where('rt.nextTime IS NOT NULL')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    'rt.end IS NULL',
                    $queryBuilder->expr()->andX(
                        'rt.end >= CURRENT_DATE()',
                        'rt.nextTime <= rt.end'
                    )
                )
            );
        $query = $queryBuilder->getQuery();
        $recurringTasks = $query->getResult();
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');
        $passedTasks = [];
        $todayTasks = [];
        $tomorrowTasks = [];
        $futureTasks = [];
        $dateToDisplay = new DateToDisplay();
        $formater = new \IntlDateFormatter('FR-fr', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
        foreach ($recurringTasks as $recurringTask) {
            /** @var RecurringTask $recurringTask */
            $recurringTask
                ->setDateToDisplay($dateToDisplay->get($recurringTask->getNextTime(), $recurringTask->getNbDaysBeforeToDisplay()))
                ->setNextTimeToStr($formater->format($recurringTask->getNextTime()));
            if ($recurringTask->getProcrastinated() && $recurringTask->getProcrastinated() > $recurringTask->getDateToDisplay()) {
                $recurringTask->setDateToDisplay($recurringTask->getProcrastinated());
            }
            $canProcrastinate = ($recurringTask->getDateToDisplay() < $recurringTask->getNextTime()) ? true : false;
            if ($recurringTask->getDateToDisplay() < $today) {
                $passedTasks[] = $recurringTask;
            } elseif ($recurringTask->getDateToDisplay() == $today) {
                $recurringTask->setCanProcrastinate($canProcrastinate);
                $todayTasks[] = $recurringTask;
            } elseif ($recurringTask->getDateToDisplay() == $tomorrow) {
                $tomorrowTasks[] = $recurringTask;
            } else {
                $futureTasks[] = $recurringTask;
            }
        }

        usort($passedTasks, fn($a, $b) => $a->getDateToDisplay() <=> $b->getDateToDisplay());
        usort($todayTasks, fn($a, $b) => $a->getDateToDisplay() <=> $b->getDateToDisplay());
        usort($tomorrowTasks, fn($a, $b) => $a->getDateToDisplay() <=> $b->getDateToDisplay());
        usort($futureTasks, fn($a, $b) => $a->getDateToDisplay() <=> $b->getDateToDisplay());

        return [
            'passed' => $passedTasks,
            'today' => $todayTasks,
            'tomorrow' => $tomorrowTasks,
            'future' => $futureTasks,
        ];
    }

    public function done(RecurringTask $recurringTask): void
    {
        if ($recurringTask->getFrequency() && $recurringTask->getFrequencyUnit()) {
            switch ($recurringTask->getFrequencyUnit()) {
                case 'day':
                    $intervalStr = 'P' . $recurringTask->getFrequency() . 'D';
                    break;
                case 'week':
                    $intervalStr = 'P' . $recurringTask->getFrequency() . 'W';
                    break;
                default:
                    $intervalStr = 'P' . $recurringTask->getFrequency() . 'M';
                    break;
            }
            $interval = new \DateInterval($intervalStr);
            $today = new \DateTime('today');
            $nextTime = (clone $today)->add($interval);
            if ($nextTime < $today) {
                $nextTime = clone $today;
            }
            if ($recurringTask->getEnd() && $recurringTask->getEnd() < $nextTime) {
                $nextTime = null;
            }
        } else {
            $nextTime = null;
        }
        $recurringTask->setNextTime($nextTime);
    }

    //    /**
    //     * @return RecurringTask[] Returns an array of RecurringTask objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RecurringTask
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
