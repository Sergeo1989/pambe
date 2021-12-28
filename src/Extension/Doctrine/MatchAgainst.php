<?php

namespace App\Extension\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class MatchAgainst extends FunctionNode
{
    public function parse(Parser $parser)
    {
        
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        
    }
}