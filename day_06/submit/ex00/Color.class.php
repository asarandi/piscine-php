
<?php
class Color {
    public static $verbose = FALSE;

    public $red;
    public $green;
    public $blue;

    public static function doc() {    
        return file_get_contents(str_replace('.class.php', '.doc.txt', __FILE__));
    }

    public function __construct(array $kwargs) {
        if (isset($kwargs['red'])) {
            $this->red = (int)$kwargs['red'];
        }

        if (isset($kwargs['green'])) {
            $this->green = (int)$kwargs['green'];
        }

        if (isset($kwargs['blue'])) {
            $this->blue = (int)$kwargs['blue'];
        }

        if (isset($kwargs['rgb'])) {
            $rgb = (int)$kwargs['rgb'];
            $this->red = (int) (($rgb >> 16) & 255);
            $this->green = (int) (($rgb >> 8) & 255);
            $this->blue = (int) ($rgb & 255);
        }

        if (self::$verbose)
            print("$this constructed." . PHP_EOL);
    }

    public function __destruct() {
        if (self::$verbose)
            print("$this destructed." . PHP_EOL);
    }

    public function __toString() {
        return sprintf("Color( red: % 3d, green: % 3d, blue: % 3d )", $this->red, $this->green, $this->blue);
    }

    public function add($rhs) {
        return new self ( [
            'red'   => $this->red   + $rhs->red,
            'green' => $this->green + $rhs->green,
            'blue'  => $this->blue  + $rhs->blue
        ]);
    }

    public function sub($rhs) {
        return new self ( [
            'red'   => $this->red   - $rhs->red,
            'green' => $this->green - $rhs->green,
            'blue'  => $this->blue  - $rhs->blue
        ]);
    }

    public function mult($f) {
        return new self ( [
            'red'   => $this->red   * $f,
            'green' => $this->green * $f,
            'blue'  => $this->blue  * $f
        ]);
    }

}
?>
