<?php

declare(strict_types=1);

use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;
use Tavp\Core\Database\Migrations\TableDefinition;

/**
 * Navigation menus and their (nestable) items.
 */
return new class extends Migration
{
    public function up($schema): void
    {
        $schema->createTable('menus', function (TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('name', 'string', ['size' => 128]));
            $table->add($schema->column('location', 'string', ['size' => 64, 'null' => true]));
            $table->add($schema->column('created_at', 'timestamp'));
            $table->add($schema->column('updated_at', 'timestamp'));
        });

        $schema->createTable('menu_items', function (TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('menu_id', 'bigInteger'));
            $table->add($schema->column('parent_id', 'bigInteger', ['null' => true]));
            $table->add($schema->column('label', 'string', ['size' => 191]));
            $table->add($schema->column('url', 'string', ['size' => 255]));
            $table->add($schema->column('target', 'string', ['size' => 16, 'default' => '_self']));
            $table->add($schema->column('sort', 'integer', ['default' => 0]));
            $table->add($schema->column('created_at', 'timestamp'));
            $table->add($schema->column('updated_at', 'timestamp'));
        });
    }

    public function down($schema): void
    {
        $schema->dropTable('menu_items');
        $schema->dropTable('menus');
    }
};
