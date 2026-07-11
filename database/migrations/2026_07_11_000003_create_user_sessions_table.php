<?php

declare(strict_types=1);

use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;
use Tavp\Core\Database\Migrations\TableDefinition;

/**
 * Create the user_sessions table.
 *
 * Database-backed sessions for persistent login state.
 */
return new class extends Migration
{
    public function up(SchemaBuilder $schema): void
    {
        $schema->createTable('user_sessions', function (TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('user_id', 'bigInteger'));
            $table->add($schema->column('token', 'string', ['size' => 255]));
            $table->add($schema->column('ip_address', 'string', ['size' => 45, 'null' => true]));
            $table->add($schema->column('user_agent', 'string', ['size' => 255, 'null' => true]));
            $table->add($schema->column('last_activity', 'timestamp'));
            $table->add($schema->column('created_at', 'timestamp'));
            $table->add($schema->column('updated_at', 'timestamp'));
        });

        $schema->addIndex('user_sessions', ['user_id'], 'idx_user_sessions_user_id');
        $schema->addIndex('user_sessions', ['token'], 'idx_user_sessions_token_unique', true);
    }

    public function down(SchemaBuilder $schema): void
    {
        $schema->dropTable('user_sessions');
    }
};
