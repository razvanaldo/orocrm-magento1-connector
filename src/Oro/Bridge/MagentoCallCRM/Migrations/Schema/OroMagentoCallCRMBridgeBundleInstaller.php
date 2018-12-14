<?php

namespace Oro\Bridge\MagentoCallCRM\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroMagentoCallCRMBridgeBundleInstaller implements
    Installation,
    ActivityExtensionAwareInterface
{
    /** @var ActivityExtension */
    protected $activityExtension;

    /**
     * {@inheritdoc}
     */
    public function setActivityExtension(ActivityExtension $activityExtension)
    {
        $this->activityExtension = $activityExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->addCallActivityRelations($schema);
    }

    /**
     * @param Schema $schema
     */
    private function addCallActivityRelations(Schema $schema)
    {
        $associationTables = [
            'orocrm_magento_customer',
            'orocrm_magento_order',
            'orocrm_magento_cart',
        ];

        foreach ($associationTables as $tableName) {
            $associationTableName = $this->activityExtension->getAssociationTableName('orocrm_call', $tableName);
            if (!$schema->hasTable($associationTableName)) {
                $this->activityExtension->addActivityAssociation($schema, 'orocrm_call', $tableName);
            }
        }
    }
}
