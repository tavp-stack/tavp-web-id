<?php

declare(strict_types=1);

use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;
use Tavp\Core\Database\Migrations\TableDefinition;

/**
 * Create the user_roles pivot table.
 */
return new class extends Migration
{
    public function up(SchemaBuilder $schema): void
    {
        $schema->createTable('user_roles', function (TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('user_id', 'bigInteger'));
            $table->add($schema->column('role_id', 'bigInteger'));
            $table->add($schema->column('created_at', 'timestamp'));
        });

        $schema->addIndex('user_roles', ['user_id', 'role_id'], 'idx_user_roles_unique', true);
    }

    public function down(SchemaBuilder $schema): void
    {
        $schema->dropTable('user_roles');
    }
};
