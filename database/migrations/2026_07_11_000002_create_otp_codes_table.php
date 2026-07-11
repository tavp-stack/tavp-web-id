<?php

declare(strict_types=1);

use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;
use Tavp\Core\Database\Migrations\TableDefinition;

/**
 * Create the otp_codes table.
 *
 * Stores hashed OTP codes for login verification.
 * Codes are SHA-256 hashed, short-lived, and limited in attempts.
 */
return new class extends Migration
{
    public function up($schema): void
    {
        $schema->createTable('otp_codes', function (TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('identifier', 'string', ['size' => 191]));
            $table->add($schema->column('code_hash', 'string', ['size' => 64]));
            $table->add($schema->column('channel', 'string', ['size' => 16, 'default' => 'email']));
            $table->add($schema->column('expires_at', 'timestamp'));
            $table->add($schema->column('attempts', 'integer', ['default' => 0]));
            $table->add($schema->column('created_at', 'timestamp'));
            $table->add($schema->column('updated_at', 'timestamp'));
        });

        $schema->addIndex('otp_codes', ['identifier'], 'idx_otp_codes_identifier');
    }

    public function down($schema): void
    {
        $schema->dropTable('otp_codes');
    }
};
