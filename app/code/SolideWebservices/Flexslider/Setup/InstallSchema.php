<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.1.2
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Drop tables if exists
         */
        $installer->getConnection()->dropTable(
            $installer->getTable('solidewebservices_flexslider_group')
        );
        $installer->getConnection()->dropTable(
            $installer->getTable('solidewebservices_flexlider_slide')
        );
        $installer->getConnection()->dropTable(
            $installer->getTable('solidewebservices_flexslider_page')
        );
        $installer->getConnection()->dropTable(
            $installer->getTable('solidewebservices_flexslider_product')
        );
        $installer->getConnection()->dropTable(
            $installer->getTable('solidewebservices_flexslider_store')
        );

        /**
         * Create table solidewebservices_flexslider_group
         */
        $table = $installer->getConnection()
        ->newTable($installer->getTable('solidewebservices_flexslider_group')
            )->addColumn(
                'group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
                ],
                'Group ID'
            )->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                ['nullable' => false, 'default' => ''],
                'Group Title'
            )->addColumn(
                'identifier',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 32,
                ['nullable' => false, 'default' => ''],
                'Group Identifier'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null,
                ['nullable' => false, 'default' => '1'],
                'Group Active Status'
            )->addColumn(
                'group_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                128,
                ['nullable' => false, 'default' => 'content_top'],
                'Group Position'
            )->addColumn(
                'width',
                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                8,
                ['nullable' => true],
                'Group Width'
            )->addColumn(
                'group_smoothheight',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Group Smooth Height'
            )->addColumn(
                'group_sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                2,
                ['nullable' => true, 'default' => '0'],
                'Group Sort Order'
            )->addColumn(
                'thumbnail_size',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                4,
                ['nullable' => false, 'default' => '200'],
                'Group Thumbnail Size'
            )->addColumn(
                'group_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'basic'],
                'Group Type'
            )->addColumn(
                'group_random_slides',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Group Random Slides'
            )->addColumn(
                'theme',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'default'],
                'Group Theme'
            )->addColumn(
            'custom_theme',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => true, 'default' => ''],
            'Group Custom Theme'
            )->addColumn(
                'group_startdate',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => true],
                'Group Startdate'
            )->addColumn(
                'group_enddate',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => true],
                'Group Enddate'
            )->addColumn(
                'group_loggedin',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Group Logged In Only?'
            )->addColumn(
                'group_animation',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'slide'],
                'Group Animation'
            )->addColumn(
                'group_animation_direction',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'horizontal'],
                'Group Animation Direction'
            )->addColumn(
                'group_animation_duration',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['nullable' => false, 'default' => '600'],
                'Group Animation Duration'
            )->addColumn(
                'group_animation_easing',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                128,
                ['nullable' => false,
                'default' => 'swing'],
                'Group Animation Easing Effect'
            )->addColumn(
                'group_autoslide',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Group Autoslide'
            )->addColumn(
                'group_autoloop',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Group Autoloop'
            )->addColumn(
                'group_pauseonaction',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Group Pause On Action'
            )->addColumn(
                'group_pauseonhover',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Group Pause On Hover'
            )->addColumn(
                'group_slide_duration',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['nullable' => false, 'default' => '7000'],
                'Group Slide Duration'
            )->addColumn(
                'nav_show',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'hover'],
                'Group Show Navigation'
            )->addColumn(
                'nav_style',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => 'type-1'],
                'Group Navigation Style'
            )->addColumn(
                'nav_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => 'inside'],
                'Group Navigation Position'
            )->addColumn(
                'nav_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => '#666666'],
                'Group Navigation Color'
            )->addColumn(
                'pagination_show',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'always'],
                'Group Show Pagination'
            )->addColumn(
                'pagination_style',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'circular'],
                'Group Pagination Style'
            )->addColumn(
                'pagination_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'below'],
                'Group Pagination Position'
            )->addColumn(
                'pagination_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => '#ffffff'],
                'Group Pagination Color'
            )->addColumn(
                'loader_show',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Group Show Loader'
            )->addColumn(
                'loader_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'top'],
                'Group Loader Position'
            )->addColumn(
                'loader_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => '#eeeeee'],
                'Group Loader Color'
            )->addColumn(
                'loader_bgcolor',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => '#222222'],
                'Group Loader Background Color'
            )->addColumn(
                'caption_textcolor',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => '#ffffff'],
                'Group Caption Textcolor'
            )->addColumn(
                'caption_bgcolor',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => 'rgba(34, 34, 34, 0.8)'],
                'Group Caption Background Color'
            )->addColumn(
                'overlay_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'right'],
                'Group Overlay Position'
            )->addColumn(
                'overlay_textcolor',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => '#ffffff'],
                'Group Overlay Textcolor'
            )->addColumn(
                'overlay_bgcolor',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'rgba(34, 34, 34, 0.8)'],
                'Group Overlay Background Color'
            )->addColumn(
                'overlay_hovercolor',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => 'rgba(102, 102, 102, 0.8)'],
                'Group Pagination Color'
            )->setComment('Flexslider Groups Table'
        );
        $installer->getConnection()->createTable($table);
        /**
         * End create table solidewebservices_flexslider_group
         */

        /**
         * Create table solidewebservices_flexslider_slide
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('solidewebservices_flexslider_slide'
            )
            )->addColumn(
                'slide_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
                ],
                'Slide ID'
            )->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Slide Title'
            )->addColumn(
                'identifier',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => ''],
                'Slide Identifier'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Group Active Status'
            )->addColumn(
                'slide_sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                2,
                ['nullable' => true, 'default' => '0'],
                'Slide Sort Order'
            )->addColumn(
                'slide_startdate',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => true],
                'Slide Startdate'
            )->addColumn(
                'slide_enddate',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => true],
                'Slide Enddate'
            )->addColumn(
                'slide_loggedin',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Slide Logged In Only?'
            )->addColumn(
                'slide_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'image'],
                'Slide Type'
            )->addColumn(
                'image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => ''],
                'Slide Image'
            )->addColumn(
                'alt_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => ''],
                'Slide Image Alt Text'
            )->addColumn(
                'url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => ''],
                'Slide URL'
            )->addColumn(
                'url_target',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => ''],
                'Slide URL Target'
            )->addColumn(
                'caption_html',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => ''],
                'Slide Caption Text'
            )->addColumn(
                'caption_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'bottom-right'],
                'Slide Caption Position'
            )->addColumn(
                'caption_animation',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => '1'],
                'Slide Caption Animation'
            )->addColumn(
                'hosted_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Hosted Image?'
            )->addColumn(
                'hosted_image_url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                1024,
                ['nullable' => true, 'default' => ''],
                'Hosted Image URL'
            )->addColumn(
                'hosted_image_thumburl',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                1024,
                ['nullable' => true, 'default' => ''],
                'Hosted Image Thumbnail URL'
            )->addColumn(
                'video_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => ''],
                'Slide Video ID'
            )->addColumn(
                'video_autoplay',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Slide Video Autoplay?'
            )->setComment('Flexslider Slides Table'
        );
        $installer->getConnection()->createTable($table);
        /**
         * End create table solidewebservices_flexslider_slide
         */

        /**
         * Create table solidewebservices_flexslider_slide_group
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('solidewebservices_flexslider_slide_group')
            )->addColumn(
                'slide_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Slide ID'
            )->addColumn(
                'group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Group ID'
            )->addForeignKey(
                $installer->getFkName(
                    'solidewebservices_flexslider_slide_group',
                    'slide_id',
                    'solidewebservices_flexslider_slide',
                    'slide_id'
                ),
                'slide_id',
                $installer->getTable('solidewebservices_flexslider_slide'),
                'slide_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'solidewebservices_flexslider_slide_group',
                    'group_id',
                    'solidewebservices_flexslider_group',
                    'group_id'
                ),
                'group_id',
                $installer->getTable('solidewebservices_flexslider_group'),
                'group_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Flexslider Slide Group Table'
        );
        $installer->getConnection()->createTable($table);
        /**
         * End create table solidewebservices_slide_group
         */

        /**
         * Create table solidewebservices_flexslider_store
         */
        $table = $installer->getConnection()
        ->newTable($installer->getTable('solidewebservices_flexslider_store')
            )->addColumn(
                'group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Group ID'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Store ID'
            )->addIndex(
                $installer->getIdxName(
                    'solidewebservices_flexslider_store',
                    ['store_id']
                ),
                ['store_id']
            )->addForeignKey(
                $installer->getFkName(
                    'solidewebservices_flexslider_store',
                    'group_id',
                    'solidewebservices_flexslider_group',
                    'group_id'
                ),
                'group_id',
                $installer->getTable('solidewebservices_flexslider_group'),
                'group_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'solidewebservices_flexslider_store',
                    'store_id',
                    'store',
                    'store_id'
                ),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Flexslider Store Table'
        );
        $installer->getConnection()->createTable($table);
        /**
         * End create table solidewebservices_flexslider_store
         */

        /**
         * Create table solidewebservices_flexslider_page
         */
        $table = $installer->getConnection()
        ->newTable($installer->getTable('solidewebservices_flexslider_page')
            )->addColumn(
                'group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Group ID'
            )->addColumn(
                'page_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'primary' => true],
                'Page ID'
            )->addIndex(
                $installer->getIdxName(
                    'solidewebservices_flexslider_page',
                    ['page_id']
                ),
                ['page_id']
            )->addForeignKey(
                $installer->getFkName(
                    'solidewebservices_flexslider_page',
                    'group_id',
                    'solidewebservices_flexslider_group',
                    'group_id'
                ),
                'group_id',
                $installer->getTable('solidewebservices_flexslider_group'),
                'group_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'solidewebservices_flexslider_page',
                    'page_id',
                    'cms_page',
                    'page_id'
                ),
                'page_id',
                $installer->getTable('cms_page'),
                'page_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Flexslider Page Table'
        );
        $installer->getConnection()->createTable($table);
        /**
         * End create table solidewebservices_flexslider_page
         */

        /**
         * Create table solidewebservices_flexslider_category
         */
        $table = $installer->getConnection()
        ->newTable($installer->getTable('solidewebservices_flexslider_category')
            )->addColumn(
                'group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Group ID'
            )->addColumn(
                'category_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Category ID'
            )->addIndex(
                $installer->getIdxName(
                    'solidewebservices_flexslider_category',
                    ['category_id']
                ),
                ['category_id']
            )->addForeignKey(
                $installer->getFkName(
                    'solidewebservices_flexslider_category',
                    'group_id',
                    'solidewebservices_flexslider_group',
                    'group_id'
                ),
                'group_id',
                $installer->getTable('solidewebservices_flexslider_group'),
                'group_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'solidewebservices_flexslider_category',
                    'category_id',
                    'catalog_category_entity',
                    'entity_id'
                ),
                'category_id',
                $installer->getTable('catalog_category_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Flexslider Category Table'
        );
        $installer->getConnection()->createTable($table);
        /**
         * End create table solidewebservices_flexslider_category
         */

        /**
         * Create table solidewebservices_flexslider_product
         */
        $table = $installer->getConnection()
        ->newTable($installer->getTable('solidewebservices_flexslider_product')
            )->addColumn(
                'group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,
                'unsigned' => true, 'primary' => true],
                'Group ID'
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Product ID'
            )->addIndex(
                $installer->getIdxName(
                    'solidewebservices_flexslider_product',
                    ['product_id']
                ),
                ['product_id']
            )->addForeignKey(
                $installer->getFkName(
                    'solidewebservices_flexslider_product',
                    'group_id',
                    'solidewebservices_flexslider_group',
                    'group_id'
                ),
                'group_id',
                $installer->getTable('solidewebservices_flexslider_group'),
                'group_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'solidewebservices_flexslider_product',
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addIndex(
                $installer->getIdxName(
                    'solidewebservices_flexslider_product',
                    ['group_id', 'product_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['group_id', 'product_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )->setComment('Flexslider Product Table'
        );
        $installer->getConnection()->createTable($table);
        /**
         * End create table solidewebservices_flexslider_product
         */

        $installer->endSetup();

    }
}
