<?php

declare(strict_types=1);

use Phalcon\Db\Column;
use Tavp\Core\Database\Migrations\Migration;
use Tavp\Core\Database\Migrations\SchemaBuilder;

/**
 * Add a role column to the users table.
 *
 * Roles were previously hardcoded in config. Storing the role per user
 * lets an admin manage accounts (email + role) from the admin panel, with
 * config values kept only as a fallback for the built-in admin.
 */
return new class extends Migration
{
    public function up($schema): void
    {
        $conn = $this->conn($schema);
        if (!$this->hasColumn($conn, 'role')) {
            $conn->addColumn('users', null, new Column('role', [
                'type' => Column::TYPE_VARCHAR,
                'size' => 32,
                'notNull' => false,
                'default' => 'editor',
            ]));
        }
    }

    public function down($schema): void
    {
        $conn = $this->conn($schema);
        if ($this->hasColumn($conn, 'role')) {
            $conn->dropColumn('users', null, 'role');
        }
    }

    private function hasColumn($conn, string $name): bool
    {
        foreach ($conn->describeColumns('users') as $col) {
            if ($col->getName() === $name) {
                return true;
            }
        }
        return false;
    }

    private function conn($schema)
    {
        $ref = new \ReflectionProperty(SchemaBuilder::class, 'connection');
        $ref->setAccessible(true);
        return $ref->getValue($schema);
    }
};
