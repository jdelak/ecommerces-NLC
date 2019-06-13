<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.2.0
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {

        $setup->startSetup();
        $flexsliderGroup = $setup->getTable('solidewebservices_flexslider_group');
        $flexsliderSlide = $setup->getTable('solidewebservices_flexslider_slide');

        if (!$context->getVersion()) {
            //no previous version found, InstallSchema was just executed
            //be careful, since everything below is true for installation !
        }

        /* VERSION 2.1.0 */
        if (version_compare($context->getVersion(), '2.1.0') < 0) {

            if ($setup->getConnection()->isTableExists($flexsliderGroup) == true) {

                $columns = [
                    'nav_size' => [
                        'type' => Table::TYPE_TEXT,
                        'size' => 32,
                        'nullable' => false,
                        'comment' => 'Group Navigation Size',
                        'after' => 'nav_style',
                    ],
                    'carousel_minitems' => [
                        'type' => Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'default' => '3',
                        'comment' => 'Carousel Minimum Items',
                    ],
                    'carousel_maxitems' => [
                        'type' => Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'default' => '5',
                        'comment' => 'Carousel Maximum Items',
                    ],
                    'carousel_move' => [
                        'type' => Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'default' => '0',
                        'comment' => 'Carousel Move Items',
                    ],
                    'group_animation_reverse' => [
                        'type' => Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'default' => '0',
                        'comment' => 'Group Animation Reverse',
                        'after' => 'group_animation_duration',
                    ],
                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn(
                        $flexsliderGroup,
                        $name,
                        $definition
                    );
                }

            }

        }

        /* VERSION 2.1.2 */
        if (version_compare($context->getVersion(), '2.1.2') < 0) {

            if ($setup->getConnection()->isTableExists($flexsliderGroup) == true) {
                $connection = $setup->getConnection();

                $connection->addIndex(
                    $flexsliderGroup,
                    $setup->getIdxName(
                        $flexsliderGroup,
                        ['title', 'identifier', 'group_type'],
                        AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['title', 'identifier', 'group_type'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                );

                $connection->addIndex(
                    $flexsliderSlide,
                    $setup->getIdxName(
                        $flexsliderSlide,
                        ['title', 'identifier'],
                        AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['title', 'identifier'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }

        }

        /* VERSION 2.2.0 */
        if (version_compare($context->getVersion(), '2.2.0') < 0) {

            if ($setup->getConnection()->isTableExists($flexsliderGroup) == true) {

                $columns = [
                    'pagination_active_color' => [
                        'type' => Table::TYPE_TEXT,
                        'length' => 32,
                        'nullable' => true,
                        'comment' => 'Pagination Active Color',
                        'after' => 'pagination_color',
                        'default' => '#293f67'
                    ],
                    'pagination_hover_color' => [
                        'type' => Table::TYPE_TEXT,
                        'length' => 32,
                        'nullable' => true,
                        'comment' => 'Pagination Hover Color',
                        'after' => 'pagination_color',
                        'default' => '#cccccc'
                    ],
                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn(
                        $flexsliderGroup,
                        $name,
                        $definition
                    );
                }
            }
        }

        $setup->endSetup();

    }
}
