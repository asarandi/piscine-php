<?php
class Vertex {
    public static $verbose = FALSE;

    private $_x;
    private $_y;
    private $_z;
    private $_w;
    private $_color;

    public static function doc() {    
        return file_get_contents(str_replace('.class.php', '.doc.txt', __FILE__));
    }

    public function __construct(array $kwargs) {
        if (isset($kwargs['x']) && isset($kwargs['y']) && isset($kwargs['z']))
        {
            $this->_x = $kwargs['x'];
            $this->_y = $kwargs['y'];
            $this->_z = $kwargs['z'];

            if (isset($kwargs['w'])) {
                $this->_w = $kwargs['w'];
            } else {
                $this->_w = 1.0;
            }

            if (isset($kwargs['color'])) {
                $this->_color = $kwargs['color'];
            } else {
                $this->_color = new Color(['rgb' => 0xffffff]);
            }

            if (self::$verbose)
                print("$this constructed" . PHP_EOL);
        }
    }

    public function __destruct() {
        if (self::$verbose)
            print("$this destructed" . PHP_EOL);
    }

    public function __toString() {
        if (self::$verbose)
            return sprintf("Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f, %s )", $this->_x, $this->_y, $this->_z, $this->_w, $this->_color);
        else
            return sprintf("Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f )", $this->_x, $this->_y, $this->_z, $this->_w);
    }
}
?>
