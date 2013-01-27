<?php

namespace Automata;

use RuntimeException;
use SplDoublyLinkedList;
use SplStack;

class LookaheadParser
{
    protected $grammar;
    protected $table;
    protected $reporter;

    const ANY = 0;

    public function __construct(Grammar $g, array $table, ParseReporter $reporter = null)
    {
        $this->grammar = $g;
        $this->table = $table;
        $this->reporter = $reporter ?: new EchoParseReporter();
    }

    public function parse(array $input)
    {
        $left = new SplDoublyLinkedList();
        $right = new SplDoublyLinkedList();
        $stack = new SplStack();

        foreach ($input as $token) {
            $right->push($token);
        }

        $right->push('$eof');
        $stack->push(0);

        while (true) {
            $this->reporter->report($left, $right);

            $state = $stack->top();
            $map = $this->table[$state];

            if (isset($map[self::ANY])) {
                $action = $map[self::ANY];
            } else {
                $la = $right->bottom();

                if (!isset($map[$la])) {
                    $this->parseError($la, $state);
                }

                $action = $map[$la];
            }

            if ($action > 0) {
                $stack->push($action);
                $left->push($right->shift());
            } elseif ($action < 0) {
                $rule = $this->grammar->getRule(-$action);
                $popCount = count($rule[1]);

                while ($popCount > 0) {
                    $left->pop();
                    $stack->pop();

                    $popCount--;
                }

                foreach (array_reverse($rule[0]) as $c) {
                    $right->unshift($c);
                }
            } else {
                return true;
            }
        }
    }

    protected function parseError($token, $state)
    {
        throw new RuntimeException(sprintf(
            'Unexpected token "%s" in state %d.', $token, $state
        ));
    }
}
