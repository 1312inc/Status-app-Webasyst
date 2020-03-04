<?php

/**
 * Class statusReportDataProject
 */
class statusReportDataProject implements statusReportDataProviderInterface
{
    const TYPE = 'project';

    /**
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     * @param null|int          $contactId
     *
     * @return statusReportDataDto[]
     * @throws waException
     */
    public function getData(DateTimeInterface $start, DateTimeInterface $end, $contactId = null)
    {
        $dtos = [];
        $sql = <<<SQL
select sum(if(isnull(sp.name), sc.total_duration, ifnull(scp.duration, 0))) duration,
       ifnull(sp.id, 0) id
from status_checkin sc
         left join status_checkin_projects scp on sc.id = scp.checkin_id
         left join status_project sp on scp.project_id = sp.id
where date(sc.date) between s:start and s:end %s
group by sp.id;
SQL;

        $data = stts()->getModel()
            ->query(
                sprintf($sql, $contactId ? 'and sc.contact_id = i:id' : ''),
                [
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $end->format('Y-m-d H:i:s'),
                    'id' => $contactId,
                ]
            )->fetchAll('id');

        $projectIds = array_column($data, 'id');
        if (!$projectIds) {
            return $dtos;
        }

        /** @var statusProject[] $projects */
        $projects = stts()->getEntityRepository(statusProject::class)->findById($projectIds);
        if (count($projects))
        {
            foreach ($projects as $project) {
                $projectId = $project->getId();
                $dtos[$projectId] = new statusReportDataDto(
                    $project->getName(),
                    $data[$projectId]['duration'],
                    $projectId,
                    self::TYPE
                );
                $dtos[$projectId]->icon = sprintf(
                    '<i class="icon16 color" style="background: %s;"></i>',
                    $project->getColor()
                );
            }
        }
        if (isset($data[0])) {
            $dtos[0] = new statusReportDataDto(
                _w('No project'),
                $data[0]['duration'],
                0,
                self::TYPE
            );
            $dtos[0]->icon = '<i class="icon16 color" style="background: #ef81ce; background: linear-gradient(135deg, #ef81ce 25%, #f3a3d5 25%, #f3a3d5 50%, #ef81ce 50%, #ef81ce 75%, #f3a3d5 75%, #f3a3d5 100%) top center/5px 5px;"></i>';
        }

        return $dtos;
    }
}