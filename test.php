<?php
use PHPUnit\Framework\TestCase;
require_once "models.php";
require_once "test_data.php";
require_once "RoboticRovers.php";

class RoboticRoversTest extends TestCase{
    public function testOutput() {
        $input = explode(PHP_EOL, TEST_INPUT);

        list($x, $y) = explode(" ", array_shift($input));
        $plateau = new Plateau(new Point($x, $y));
        $robotic_rovers = new RoboticRovers($plateau);

        for ($i = 0; $i < count($input); $i += 2) {
            list($x, $y, $char) = explode(" ", $input[$i]);

            $position_point = new PositionPoint($x, $y);
            $direction = new Direction($char, new DirectionVector());
            $position = new Position($position_point, $direction);
            $rover = new Rover($position);
            $robotic_rovers->moveRover($rover, $input[$i+1]);
        }

        $this->assertSame(TEST_OUTPUT, $robotic_rovers->getOutput());
    }
}