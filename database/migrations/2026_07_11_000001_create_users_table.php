<?php

declare(strict_types=1);

use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;
use Tavp\Core\Database\Migrations\TableDefinition;

/**
 * Create the users table.
 *
 * OTP-first auth: password is optional, email is the primary identifier.
 */
return new class extends Migration
{
    public function up(SchemaBuilder $schema): void
    {
        $schema->createTable('users', function (TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('name', 'string', ['size' => 128, 'null' => true]));
            $table->add($schema->column('email', 'string', ['size' => 191]));
            $table->add($schema->column('phone', 'string', ['size' => 32, 'null' => true]));
            $table->add($schema->column('password', 'string', ['size' => 255, 'null' => true]));
            $table->add($schema->column('email_verified_at', 'timestamp', ['null' => true]));
            $table->add($schema->column('remember_token', 'string', ['size' => 100, 'null' => true]));
            $table->add($schema->column('created_at', 'timestamp'));
            $table->add($schema->column('updated_at', 'timestamp'));
        });

        $schema->addIndex('users', ['email'], 'idx_users_email_unique', true);
    }

    public function down(SchemaBuilder $schema): void
    {
        $schema->dropTable('users');
    }
};
