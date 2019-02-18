<?php

class RoboticRovers {
    /**
     * @var Plateau
     */
    private $_plateau;

    /**
     * @var array
     */
    private $_rovers = [];

    /**
     * RoboticRovers constructor.
     * @param $plateau Plateau
     */
    public function __construct($plateau) {
        $this->_plateau = $plateau;
    }

    public function getOutput() {
        $output = [];
        /* @var $rover Rover */
        foreach($this->_rovers as $rover) {
            $data = [];
            $data[] = $rover->getPosition()->getPoint()->getX();
            $data[] = $rover->getPosition()->getPoint()->getY();
            $data[] = $rover->getPosition()->getDirection()->getCardinalCompassPoint();
            $output[] = implode(" ", $data);
        }

        return implode(PHP_EOL, $output);
    }

    /**
     * @param $rover Rover
     * @param $instructions string
     * @return Rover
     * @throws Exception
     */
    public function moveRover($rover, $instructions) {
        foreach(str_split($instructions) as $char) {
            switch ($char) {
                case "L":
                    $rover->rotateLeft();
                    break;
                case "R":
                    $rover->rotateRight();
                    break;
                case "M":
                    $point = $rover->getNextPosition();
                    if ($this->_plateau->havePoint($point)) {
                        $rover->moveToPoint($point);
                    }
                    break;
            }
        }

        $this->_rovers[] = $rover;

        return $rover;
    }
}