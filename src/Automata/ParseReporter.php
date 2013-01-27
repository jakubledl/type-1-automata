<?php

namespace Automata;

use SplDoublyLinkedList;
use SplStack;

interface ParseReporter
{
    public function report(SplDoublyLinkedList $left, SplDoublyLinkedList $right);
}
