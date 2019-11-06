<?php

/**
 * Class statusStat
 */
class statusStat
{
    /**
     * @var statusCheckinModel
     */
    protected $checkinModel;

    /**
     * statusStat constructor.
     *
     * @throws waException
     */
    public function __construct()
    {
        $this->checkinModel = stts()->getModel(statusCheckin::class);
    }

    /**
     * @param DateTime $date
     *
     * @return array
     * @throws Exception
     */
    public function timeByWeek(DateTime $date)
    {
        $week = statusWeekFactory::createWeekByDate($date);

        $statistics = $this->checkinModel->countTimeByDates(
            $week->getFirstDay()->getDate()->format('Y-m-d'),
            $week->getLastDay()->getDate()->format('Y-m-d')
        );

        $result = [];
        /** @var statusUser $user */
        foreach (stts()->getEntityRepository(statusUser::class)->findAll() as $user) {
            $time = ifset($statistics, $user->getId(), 0);
            $result[$user->getId()] = [
                'time' => $time,
                'timeStr' => statusTimeHelper::getTimeDurationInHuman(
                    0,
                    $time * statusTimeHelper::SECONDS_IN_MINUTE,
                    ''
                ),
            ];
        }

        return $result;
    }
}