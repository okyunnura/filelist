<?php

use Phinx\Migration\AbstractMigration;

class FirstMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('contents')
            ->addColumn('parent_id', 'string', ['null' => true])
            ->addColumn('type', 'string', ['null' => true])
            ->addColumn('path', 'string', ['null' => true])
            ->addColumn('timestamp', 'integer', ['null' => true])
            ->addColumn('size', 'biginteger', ['null' => true])
            ->addColumn('dirname', 'string', ['null' => true])
            ->addColumn('basename', 'string', ['null' => true])
            ->addColumn('extension', 'string', ['null' => true])
            ->addColumn('regex', 'json', ['null' => true])
            ->addColumn('candidate', 'json', ['null' => true])
            ->addColumn('author_id', 'integer', ['null' => true])
            ->addColumn('enabled', 'boolean', ['null' => true])
            ->addColumn('multiple', 'boolean', ['null' => true])
            ->addColumn('forecast', 'string', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }
}
