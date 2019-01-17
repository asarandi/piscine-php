<?php
class NightsWatch implements IFighter {
    private $_recruits = [];
    public function recruit($i) {
        //        if (in_array('IFighter', class_implements($i))) {
        if ($i instanceof IFighter) {
            $this->_recruits[] = $i;
        }
    }
    public function fight() {
        foreach ($this->_recruits as $rec) {
            $rec->fight();
        }
    }
}
?>
