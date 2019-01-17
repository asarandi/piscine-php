<?php
require_once 'Lannister.class.php';
class Jaime extends Lannister {
    public function sleepWith($i) {
        //        if (get_class($i) === 'Cersei') {
        if ($i instanceof Cersei) {        
            print("With pleasure, but only in a tower in Winterfell, then." . PHP_EOL);
        } else {
            parent::sleepWith($i);
        }
    }
}
?>
