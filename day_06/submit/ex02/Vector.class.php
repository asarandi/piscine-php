<?php
class Vector {
    public static $verbose = FALSE;

    private $_x;
    private $_y;
    private $_z;
    private $_w;

    public function x(){ return $this->_x; }
    public function y(){ return $this->_y; }
    public function z(){ return $this->_z; }
    public function w(){ return $this->_w; }

    public static function doc() {    
        return file_get_contents(str_replace('.class.php', '.doc.txt', __FILE__));
    }

    public function __construct(array $kwargs) {

        if (isset($kwargs['dest'])) {

            $dest = $kwargs['dest'];
            if (isset($kwargs['orig'])) {
                $orig = $kwargs['orig'];
            } else {
                $orig = new Vertex(['x' => 0, 'y' => 0, 'z' => 0, 'w' => 1.0]);
            }

            $this->_x = $dest->x() - $orig->x();
            $this->_y = $dest->y() - $orig->y();
            $this->_z = $dest->z() - $orig->z();
            $this->_w = 0.0;

            if (self::$verbose)
                print("$this constructed" . PHP_EOL);
        }
    }

    public function __destruct() {
        if (self::$verbose)
            print("$this destructed" . PHP_EOL);
    }

    public function __toString() {
        return sprintf("Vector( x:%.2f, y:%.2f, z:%.2f, w:%.2f )", $this->_x, $this->_y, $this->_z, $this->_w);
    }

    public function magnitude() {
        //returns float
        return sqrt(
            pow($this->_x, 2) +
            pow($this->_y, 2) +
            pow($this->_z, 2)
        );
    }

    public function normalize() {
        return new self([
            'dest' => new Vertex([
                'x' => $this->_x / $this->magnitude(),
                'y' => $this->_y / $this->magnitude(),
                'z' => $this->_z / $this->magnitude()
            ])
        ]);
    }

    public function add(Vector $rhs) {
        //returns Vector
        return new self([
            'dest' => new Vertex([
                'x' => $this->_x + $rhs->x(),
                'y' => $this->_y + $rhs->y(),
                'z' => $this->_z + $rhs->z()
            ])
        ]);
    }

    public function sub(Vector $rhs) {
        //returns Vector
        return new self([
            'dest' => new Vertex([
                'x' => $this->_x - $rhs->x(),
                'y' => $this->_y - $rhs->y(),
                'z' => $this->_z - $rhs->z()
            ])
        ]);
    }

    public function opposite() {
        //returns Vector
        return new self([
            'dest' => new Vertex([
                'x' => $this->_x * -1,
                'y' => $this->_y * -1,
                'z' => $this->_z * -1
            ])
        ]);
    }

    public function scalarProduct($k) {
        //returns Vector
        return new self([
            'dest' => new Vertex([
                'x' => $this->_x * $k,
                'y' => $this->_y * $k,
                'z' => $this->_z * $k
            ])
        ]);
    }

    public function dotProduct(Vector $rhs) {
        //returns float
        return (
            ($this->_x * $rhs->x()) +
            ($this->_y * $rhs->y()) +
            ($this->_z * $rhs->z())
            );
    }

    public function cos(Vector $rhs) {
        //returns float
        return $this->dotProduct($rhs) / ($this->magnitude() * $rhs->magnitude());
    }

    public function crossProduct(Vector $rhs) {
        //returns Vector, right hand mark
         return new self([
            'dest' => new Vertex([
                'x' => ($this->y() * $rhs->z()) - ($this->z() * $rhs->y()),
                'y' => ($this->z() * $rhs->x()) - ($this->x() * $rhs->z()),
                'z' => ($this->x() * $rhs->y()) - ($this->y() * $rhs->x())
            ])
        ]);   
    }
}
?>
