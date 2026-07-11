<?php

declare(strict_types=1);

use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;
use Tavp\Core\Database\Migrations\TableDefinition;

/**
 * Create the role_permissions pivot table.
 */
return new class extends Migration
{
    public function up(SchemaBuilder $schema): void
    {
        $schema->createTable('role_permissions', function (TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('role_id', 'bigInteger'));
            $table->add($schema->column('permission_id', 'bigInteger'));
            $table->add($schema->column('created_at', 'timestamp'));
        });

        $schema->addIndex('role_permissions', ['role_id', 'permission_id'], 'idx_role_permissions_unique', true);
    }

    public function down(SchemaBuilder $schema): void
    {
        $schema->dropTable('role_permissions');
    }
};
