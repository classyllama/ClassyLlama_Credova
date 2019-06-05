<?php


namespace ClassyLlama\Credova\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    const SALES_ORDER_TABLE = 'sales_order';
    const SALES_ORDER_CREDOVA_APPLICATION_ID = 'credova_application_id';
    const SALES_ORDER_CREDOVA_LICENSE_PUBLIC_ID = 'credova_federal_license_public_id';
    const SALES_ORDER_CREDOVA_LICENSE_UPLOAD_STATUS = 'credova_federal_license_upload_status';

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()
            ->addColumn(
                $setup->getTable(self::SALES_ORDER_TABLE),
                self::SALES_ORDER_CREDOVA_APPLICATION_ID,
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' =>'Credova Application ID'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable(self::SALES_ORDER_TABLE),
                self::SALES_ORDER_CREDOVA_LICENSE_PUBLIC_ID,
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' =>'Credova Federal License Public Id'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable(self::SALES_ORDER_TABLE),
                self::SALES_ORDER_CREDOVA_LICENSE_UPLOAD_STATUS,
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 20,
                    'comment' =>'Credova Federal License Upload Status'
                ]
            );

        $setup->endSetup();
    }
}
