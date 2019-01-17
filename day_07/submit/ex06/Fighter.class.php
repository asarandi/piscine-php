<?php
abstract class Fighter {
    public $skill;
    abstract function fight($target);
    public function __construct($i) {
        $this->skill = $i;
    }
}
?>
