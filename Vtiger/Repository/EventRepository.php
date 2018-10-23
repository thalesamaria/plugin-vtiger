<?php

declare(strict_types=1);

/*
 * @copyright   2018 Mautic Inc. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://www.mautic.com
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticVtigerCrmBundle\Vtiger\Repository;

use MauticPlugin\MauticVtigerCrmBundle\Enum\CacheEnum;
use MauticPlugin\MauticVtigerCrmBundle\Vtiger\Connection;
use MauticPlugin\MauticVtigerCrmBundle\Vtiger\Model\Event;
use MauticPlugin\MauticVtigerCrmBundle\Vtiger\Repository\Cache\FieldCache;
use MauticPlugin\MauticVtigerCrmBundle\Vtiger\Repository\Helper\RepositoryHelper;
use MauticPlugin\MauticVtigerCrmBundle\Vtiger\Repository\Mapping\ModelFactory;

class EventRepository extends BaseRepository
{
    use RepositoryHelper;

    /**
     * @var ModelFactory
     */
    private $modelFactory;

    /**
     * @param Connection   $connection
     * @param FieldCache   $fieldCache
     * @param ModelFactory $modelFactory
     */
    public function __construct(
        Connection $connection,
        FieldCache $fieldCache,
        ModelFactory $modelFactory
    )
    {
        parent::__construct($connection, $fieldCache);
        $this->modelFactory = $modelFactory;
    }

    /**
     * @param Event $module
     *
     * @return Event
     */
    public function create(Event $module): Event
    {
        return $this->createUnified($module);
    }

    /**
     * @param string $id
     *
     * @return Event
     *
     * @throws \MauticPlugin\MauticVtigerCrmBundle\Exceptions\InvalidRequestException
     * @throws \MauticPlugin\MauticVtigerCrmBundle\Exceptions\InvalidQueryArgumentException
     */
    public function retrieve(string $id): Event
    {
        return $this->findOneBy(['id' =>$id]);
    }

    /**
     * @param $contactId
     *
     * @return array|Event[]
     */
    public function findByContactId($contactId): array
    {
        return $this->findBy(['contact_id'=>(string) $contactId]);
    }

    /**
     * @param array $contactIds
     *
     * @return array|Event[]
     *
     * @throws \MauticPlugin\MauticVtigerCrmBundle\Exceptions\AccessDeniedException
     * @throws \MauticPlugin\MauticVtigerCrmBundle\Exceptions\AuthenticationException
     * @throws \MauticPlugin\MauticVtigerCrmBundle\Exceptions\DatabaseQueryException
     * @throws \MauticPlugin\MauticVtigerCrmBundle\Exceptions\InvalidQueryArgumentException
     * @throws \MauticPlugin\MauticVtigerCrmBundle\Exceptions\InvalidRequestException
     * @throws \MauticPlugin\MauticVtigerCrmBundle\Exceptions\SessionException
     * @throws \MauticPlugin\MauticVtigerCrmBundle\Exceptions\VtigerPluginException
     * @throws \MauticPlugin\IntegrationsBundle\Exception\PluginNotConfiguredException
     */
    public function findByContactIds(array $contactIds): array
    {
        $moduleName = $this->getModuleFromRepositoryName();

        $query = 'select * from '.$moduleName;
        $query .= sprintf(" where contact_id in ('%s')", join("','", $contactIds));

        $return = [];

        $offset = 0;
        $limit = 100;

        do {
            $queryLimiter = sprintf('LIMIT %d,%d', $offset, $limit);
            $result       = $this->connection->get('query', ['query' => $query.' '.$queryLimiter]);
            foreach ($result as $key => $moduleObject) {
                $return[] = $this->modelFactory->createEvent((array) $moduleObject);
            }
            $offset += $limit;
        } while (count($result));

        return $return;
    }

    /**
     * @return string
     */
    public function getModuleFromRepositoryName(): string
    {
        return CacheEnum::EVENT;
    }
}
