<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 * Value comes from technology, technology comes from sharing~
 * https://github.com/alicfeng/laravel-migrate
 * AlicFeng | a@samego.com
 */

namespace AlicFeng\Migrate\Schema\Grammars;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\MySqlGrammar as BaseMySqlGrammar;
use Illuminate\Support\Fluent;

/**
 * Class MySqlGrammar.
 * @version 1.0.0
 * @author  AlicFeng
 */
class MySqlGrammar extends BaseMySqlGrammar
{
    /**
     * @function    compileCreate
     * @description rewrite func for supported setting comment as well as auto_increment
     * @return string
     * @author      AlicFeng
     */
    public function compileCreate(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        $sql = parent::compileCreate($blueprint, $command, $connection);

        // 1. supported table comment
        if (isset($blueprint->comment)) {
            $blueprint->comment = str_replace("'", "\'", trim($blueprint->comment));
            $sql                .= " comment = '{$blueprint->comment}'";
        }

        // 2. supported field auto_increment value setting
        if (isset($blueprint->auto_increment) && is_numeric($blueprint->auto_increment)) {
            $sql .= " auto_increment = {$blueprint->auto_increment}";
        }

        return $sql;
    }
}
