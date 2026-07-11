<?php

declare(strict_types=1);

use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;

/**
 * Create the webhooks table.
 *
 * Stores webhook endpoint configurations for CMS events.
 */
return new class extends Migration
{
    public function up(SchemaBuilder $schema): void
    {
        $schema->createTable('webhooks', function (SchemaBuilder\TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('name', 'string', ['size' => 128]));
            $table->add($schema->column('url', 'string', ['size' => 255]));
            $table->add($schema->column('events', 'string', ['size' => 500]));
            $table->add($schema->column('secret', 'string', ['size' => 255, 'null' => true]));
            $table->add($schema->column('active', 'boolean', ['default' => true]));
            $table->add($schema->column('created_at', 'timestamp'));
            $table->add($schema->column('updated_at', 'timestamp'));
        });
    }

    public function down(SchemaBuilder $schema): void
    {
        $schema->dropTable('webhooks');
    }
};
