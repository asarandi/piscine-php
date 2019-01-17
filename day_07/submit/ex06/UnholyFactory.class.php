<?php
class UnholyFactory {
    private $requested = [];
    public function absorb($f) {                    //expects an instance of Fighter class
                                                    //stores instances in a $k => $v array // ($k = fighter type)
        if ($f instanceof Fighter) {
            $skill = $f->skill;
            if (isset($this->requested[$skill])) {
                print("(Factory already absorbed a fighter of type $skill)" . PHP_EOL);
            } else {
                $this->requested[$skill] = $f;            
                print("(Factory absorbed a fighter of type $skill)" . PHP_EOL);
            }
        } else {
            print("(Factory can't absorb this, it's not a fighter)" . PHP_EOL);
        }
    }
    public function fabricate($rf) {            //expects a string (fighter type) .. returns instances of Fighter class from array
        if (!isset($this->requested[$rf])) {
            print("(Factory hasn't absorbed any fighter of type $rf)" . PHP_EOL);
            return NULL;
        } else {
            print("(Factory fabricates a fighter of type $rf)" . PHP_EOL);  // i wonder if "fabricate" implies using `clone`
            return clone $this->requested[$rf];                             // corrector: even without clone test1.php produces output like pdf
        }
    }
}
?>
