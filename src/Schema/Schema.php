<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 * Value comes from technology, technology comes from sharing~
 * https://github.com/alicfeng/laravel-migrate
 * AlicFeng | a@samego.com
 */

namespace AlicFeng\Migrate\Schema;

use AlicFeng\Migrate\Schema\Grammars\MySqlGrammar;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Database\Schema\Builder create(string $table, \Closure $callback)
 * @method static \Illuminate\Database\Schema\Builder drop(string $table)
 * @method static \Illuminate\Database\Schema\Builder dropIfExists(string $table)
 * @method static \Illuminate\Database\Schema\Builder table(string $table, \Closure $callback)
 *
 * @see \Illuminate\Database\Schema\Builder
 */
class Schema extends Facade
{
    /**
     * Get a schema builder instance for a connection.
     *
     * @param string $name
     * @return Builder
     */
    public static function connection($name)
    {
        $connection = static::$app['db']->connection($name);

        return static::customGrammar($connection);
    }

    /**
     * Get a schema builder instance for the default connection.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected static function getFacadeAccessor()
    {
        /**
         * @var Connection
         */
        $connection = static::$app['db']->connection();

        return static::customGrammar($connection);
    }

    /**
     * load custom MySqlGrammar when using MySqlConnection.
     * @param $connection
     * @return \Illuminate\Database\Schema\Builder
     */
    private static function customGrammar(Connection $connection)
    {
        // only for MySQL connection
        if ('Illuminate\\Database\\MySqlConnection' === get_class($connection)) {
            $grammar = $connection->withTablePrefix(new MySqlGrammar());
            $connection->setSchemaGrammar($grammar);
        }

        return $connection->getSchemaBuilder();
    }
}
