<?php
abstract class House {
    abstract function getHouseName();
    abstract function getHouseSeat();
    abstract function getHouseMotto();

    public function introduce() {
        $name = $this->getHouseName();
        $seat = $this->getHouseSeat();
        $motto = $this->getHouseMotto();
        print "House $name of $seat : \"$motto\"" . PHP_EOL;
    }
}
?>
