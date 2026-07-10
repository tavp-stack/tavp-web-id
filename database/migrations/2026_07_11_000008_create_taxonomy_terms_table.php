<?php

declare(strict_types=1);

use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;

/**
 * Create the taxonomy_terms table.
 *
 * Supports hierarchical categories and flat tags.
 */
return new class extends Migration
{
    public function up(SchemaBuilder $schema): void
    {
        $schema->createTable('taxonomy_terms', function (SchemaBuilder\TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('type', 'string', ['size' => 32]));
            $table->add($schema->column('name', 'string', ['size' => 128]));
            $table->add($schema->column('slug', 'string', ['size' => 191]));
            $table->add($schema->column('description', 'string', ['size' => 255, 'null' => true]));
            $table->add($schema->column('parent_id', 'bigInteger', ['null' => true]));
            $table->add($schema->column('sort', 'integer', ['default' => 0]));
            $table->add($schema->column('created_at', 'timestamp'));
            $table->add($schema->column('updated_at', 'timestamp'));
        });

        $schema->addIndex('taxonomy_terms', ['type'], 'idx_taxonomy_terms_type');
        $schema->addIndex('taxonomy_terms', ['slug', 'type'], 'idx_taxonomy_terms_slug_type_unique', true);
    }

    public function down(SchemaBuilder $schema): void
    {
        $schema->dropTable('taxonomy_terms');
    }
};
