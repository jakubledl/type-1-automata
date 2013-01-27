<?php

namespace Automata;

use SplDoublyLinkedList;
use SplStack;

class EchoParseReporter implements ParseReporter
{
    public function report(SplDoublyLinkedList $left, SplDoublyLinkedList $right)
    {
        $left = iterator_to_array($left);
        $right = iterator_to_array($right);

        echo sprintf("%s | %s",
            implode(' ', $left),
            implode(' ', $right)
        ) . PHP_EOL;
    }
}
