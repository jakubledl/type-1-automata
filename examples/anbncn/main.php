<?php

require __DIR__ . '/../../vendor/autoload.php';

use Automata\Grammar;
use Automata\LookaheadParser;

$g = new Grammar();
$g->rule(          ['S'], ['a', 'S', 'Q']     );
$g->rule(          ['S'], ['a', 'b', 'c']     );
$g->rule(['b', 'Q', 'c'], ['b', 'b', 'c', 'c']);
$g->rule(     ['c', 'Q'], ['Q', 'c']          );

$parser = new LookaheadParser($g, [
    ['S' => 1, 'a' => 2],
    [LookaheadParser::ANY => 0],
    ['a' => 2, 'S' => 3, 'b' => 4],
    ['Q' => 5],
    ['Q' => 8, 'c' => 6, 'b' => 7],
    [LookaheadParser::ANY => -1],
    [LookaheadParser::ANY => -2],
    ['b' => 7, 'Q' => 8, 'c' => 9],
    ['Q' => 8, 'c' => 10],
    ['Q' => 8, 'c' => 11],
    [LookaheadParser::ANY => -4],
    [LookaheadParser::ANY => -3],
]);

// 1. valid input
$parser->parse(['a', 'a', 'a', 'b', 'b', 'b', 'c', 'c', 'c']);
echo 'accept' . PHP_EOL;

echo PHP_EOL . '-----------' . PHP_EOL . PHP_EOL;

// 2. parse error
try {
    $parser->parse(['a', 'a', 'a', 'b', 'c', 'c']);
} catch (RuntimeException $e) {
    echo $e->getMessage() . PHP_EOL;
}
