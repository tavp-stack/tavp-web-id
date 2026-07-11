<?php

declare(strict_types=1);

use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;
use Tavp\Core\Database\Migrations\TableDefinition;

/**
 * Create the permissions table.
 */
return new class extends Migration
{
    public function up($schema): void
    {
        $schema->createTable('permissions', function (TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('name', 'string', ['size' => 128]));
            $table->add($schema->column('label', 'string', ['size' => 128]));
            $table->add($schema->column('description', 'string', ['size' => 255, 'null' => true]));
            $table->add($schema->column('created_at', 'timestamp'));
            $table->add($schema->column('updated_at', 'timestamp'));
        });

        $schema->addIndex('permissions', ['name'], 'idx_permissions_name_unique', true);
    }

    public function down($schema): void
    {
        $schema->dropTable('permissions');
    }
};
