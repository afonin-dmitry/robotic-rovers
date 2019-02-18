<?php
abstract class Coordinate {
    /**
     * @var int
     */
    protected $_x;

    /**
     * @var int
     */
    protected $_y;

    public function getX() {
        return $this->_x;
    }

    public function getY() {
        return $this->_y;
    }

    /**
     * @param $number integer|string
     * @throws Exception
     */
    public function setX($number) {
        $this->_x = $this->validate($number);
    }

    /**
     * @param $number integer|string
     * @throws Exception
     */
    public function setY($number) {
        $this->_y = $this->validate($number);
    }

    /**
     * @param $number
     * @return int
     * @throws Exception
     */
    private function validate($number) {
        if (!is_int($number)) {
            if (!ctype_digit($number)) {
                throw new Exception("Invalid type of variable, integer expected");
            }
            else {
                $number = (int)$number;
            }
        }

        return $number;
    }
}

class Point extends Coordinate {
    /**
     * PositionPoint constructor.
     * @param $x integer|string
     * @param $y integer|string
     * @throws Exception
     */
    public function __construct($x, $y) {
        $this->setX($x);
        $this->setY($y);

        return $this;
    }
}

Class PositionPoint extends Point {
    /**
     * @param $vector DirectionVector
     * @return PositionPoint
     * @throws Exception
     */
    public function getMovedOnVector($vector) {
        $x = $this->getX() + $vector->getX();
        $y = $this->getY() + $vector->getY();

        return new self($x, $y);
    }
}

class DirectionVector extends Coordinate {
    private $cardinal_compass_points = [
        "N" => [0, 1],
        "S" => [0, -1],
        "E" => [1, 0],
        "W" => [-1, 0]
    ];

    public function setDirection($cardinal_compass_point) {
        list($this->_x, $this->_y) = $this->cardinal_compass_points[$cardinal_compass_point];
    }
}

class Direction {
    private $_vector;

    private $_cardinal_compass_point;

    private $cardinal_compass_points = ["N", "S", "W", "E"];

    private $right_rotation = [
        "N" => "E",
        "S" => "W",
        "W" => "N",
        "E" => "S"
    ];

    private $left_rotation = [
        "N" => "W",
        "S" => "E",
        "W" => "S",
        "E" => "N"
    ];

    /**
     * Direction constructor.
     * @param $cardinal_compass_point string
     * @param $vector DirectionVector
     * @throws Exception
     */
    public function __construct($cardinal_compass_point, $vector) {
        if (!in_array($cardinal_compass_point, $this->cardinal_compass_points)) {
            throw new Exception("Invalid cardinal compass point");
        }

        $this->_cardinal_compass_point = $cardinal_compass_point;
        $this->_vector = $vector;
    }

    public function getVector() {
        $this->_vector->setDirection($this->_cardinal_compass_point);

        return $this->_vector;
    }

    public function getCardinalCompassPoint() {
        return $this->_cardinal_compass_point;
    }

    public function rotateRight() {
        $this->_cardinal_compass_point = $this->right_rotation[$this->_cardinal_compass_point];
    }

    public function rotateLeft() {
        $this->_cardinal_compass_point = $this->left_rotation[$this->_cardinal_compass_point];
    }
}

class Plateau {
    private $_upper_corner;

    /**
     * Plateau constructor.
     * @param $upper_corner Point
     */
    public function __construct($upper_corner) {
        $this->_upper_corner = $upper_corner;
    }

    /**
     * @param $point Point
     * @return bool
     */
    public function havePoint($point) {
        if ($point->getX() > $this->_upper_corner->getX() || $point->getX() < 0) {
            return false;
        }
        if ($point->getY() > $this->_upper_corner->getY() || $point->getY() < 0) {
            return false;
        }

        return true;
    }
}

class Rover {
    private $_position;

    /**
     * Rover constructor.
     * @param $position Position
     */
    public function __construct($position) {
        $this->_position = $position;
    }

    public function rotateLeft() {
        $this->_position->getDirection()->rotateLeft();
    }

    public function rotateRight() {
        $this->_position->getDirection()->rotateRight();
    }

    public function getPosition() {
        return $this->_position;
    }

    /**
     * @return PositionPoint
     * @throws Exception
     */
    public function getNextPosition() {
        return $this->_position->getPoint()->getMovedOnVector($this->_position->getDirection()->getVector());
    }

    /**
     * @param $point PositionPoint
     */
    public function moveToPoint($point) {
        $this->_position->setPoint($point);
    }
}

class Position {
    /**
     * @var PositionPoint
     */
    private $_point;

    /**
     * @var Direction
     */
    private $_direction;

    /**
     * Position constructor.
     * @param $point PositionPoint
     * @param $direction Direction
     */
    public function __construct($point, $direction) {
        $this->setPoint($point);
        $this->_direction = $direction;
    }

    /**
     * @return PositionPoint
     */
    public function getPoint() {
        return $this->_point;
    }

    /**
     * @param $point PositionPoint
     */
    public function setPoint($point) {
        $this->_point = $point;
    }

    /**
     * @return Direction
     */
    public function getDirection() {
        return $this->_direction;
    }
}