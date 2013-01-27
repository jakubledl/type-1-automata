<?php

namespace Automata;

class Grammar
{
    protected $rules = [0 => null];

    public function rule(array $lhs, array $rhs)
    {
        $this->rules[] = [$lhs, $rhs];
    }

    public function getRule($n)
    {
        return $this->rules[$n];
    }
}
