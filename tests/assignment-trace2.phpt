--TEST--
Test for tracing array assignments in user-readable function traces
--INI--
xdebug.default_enable=1
xdebug.profiler_enable=0
xdebug.auto_trace=0
xdebug.trace_format=0
xdebug.collect_vars=1
xdebug.collect_params=3
xdebug.collect_return=0
xdebug.collect_assignments=1
xdebug.dump.GET=
xdebug.dump.SERVER=
--FILE--
<?php
$tf = xdebug_start_trace('/tmp/'. uniqid('xdt', TRUE));

function test()
{
	$t = array( 'a' => 4, 'b' => 9, 'c' => 13 );
	$t['d'] = 89;
	$t['a'] += $b;
	@$t['a'] += $b;
	$t['c'] /= 7;
	$t['b'] *= 9;
}
$t = array();
$t['a'] = 98;
$t['b'] = 4;
$t['b'] -= 8;
$t['b'] *= -0.5;
$t['b'] <<= 1;
$t['c'] = $t['b'] / 32;

test(1, 2, 3);

xdebug_stop_trace();
echo file_get_contents($tf);
unlink($tf);
?>
--EXPECTF--
Notice: Undefined variable: b in %sassignment-trace2.php on line 8

Call Stack:
%w%f %w%d   1. {main}() %sassignment-trace2.php:0
%w%f %w%d   2. test(1, 2, 3) %sassignment-trace2.php:21

TRACE START [%d-%d-%d %d:%d:%d]
                           => $tf = '/tmp/%s.xt' %sassignment-trace2.php:2
                           => $t = array () %sassignment-trace2.php:13
                           => $t['a'] = 98 %sassignment-trace2.php:14
                           => $t['b'] = 4 %sassignment-trace2.php:15
                           => $t['b'] -= 8 %sassignment-trace2.php:16
                           => $t['b'] *= -0.5 %sassignment-trace2.php:17
                           => $t['b'] <<= 1 %sassignment-trace2.php:18
                           => $t['c'] = 0.125 %sassignment-trace2.php:19
%w%f %w%d     -> test(1, 2, 3) %sassignment-trace2.php:21
                             => $t = array ('a' => 4, 'b' => 9, 'c' => 13) %sassignment-trace2.php:6
                             => $t['d'] = 89 %sassignment-trace2.php:7
                             => $t['a'] += NULL %sassignment-trace2.php:8
                             => $t['a'] += NULL %sassignment-trace2.php:9
                             => $t['c'] /= 7 %sassignment-trace2.php:10
                             => $t['b'] *= 9 %sassignment-trace2.php:11
%w%f %w%d     -> xdebug_stop_trace() %sassignment-trace2.php:23
%w%f %w%d
TRACE END   [%d-%d-%d %d:%d:%d]
