<?php

declare(strict_types=1);

use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;
use Tavp\Core\Database\Migrations\TableDefinition;

/**
 * Create the content_taxonomy pivot table.
 *
 * Links content records to taxonomy terms (categories, tags).
 */
return new class extends Migration
{
    public function up($schema): void
    {
        $schema->createTable('content_taxonomy', function (TableDefinition $table) use ($schema) {
            $table->add($schema->column('id', 'bigInteger', ['identity' => true, 'primary' => true]));
            $table->add($schema->column('content_id', 'bigInteger'));
            $table->add($schema->column('content_type', 'string', ['size' => 64]));
            $table->add($schema->column('term_id', 'bigInteger'));
            $table->add($schema->column('term_type', 'string', ['size' => 32]));
            $table->add($schema->column('created_at', 'timestamp'));
        });

        $schema->addIndex('content_taxonomy', ['content_id', 'content_type', 'term_id'], 'idx_content_taxonomy_unique', true);
        $schema->addIndex('content_taxonomy', ['term_id'], 'idx_content_taxonomy_term_id');
    }

    public function down($schema): void
    {
        $schema->dropTable('content_taxonomy');
    }
};
