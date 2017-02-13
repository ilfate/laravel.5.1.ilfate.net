<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @copyright 2016 Watchmaster GmbH
 * @license   Proprietary license.
 * @link      http://www.watchmaster.de
 */
namespace Ilfate\ShipAi;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @link      http://ilfate.net
 */
class Hex extends Model
{
    const SIDE_SIZE_REM_GALAXY_VIEW = 1;
    const SIDE_SIZE_LIGHT_YEARS = 8000;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sa_hexes';

    protected $saveable = ['id', 'galaxy_id', 'empire_id', 'x', 'y', 'is_hidden'];

    protected $fillable = ['id', 'galaxy_id', 'empire_id', 'x', 'y', 'is_hidden'];

    public function galaxy()
    {
        return $this->hasOne(Galaxy::class, 'id', 'galaxy_id');
    }

    /**
     * @param $galaxyId
     *
     * @return Collection
     */
    public static function getHexMap($galaxyId)
    {
        return self::where('galaxy_id', '=', $galaxyId)->get();
    }

    /**
     * @param $id
     *
     * @return Hex
     */
    public static function loadFull($id)
    {
        $hex = self::findOrFail($id);
        $stars = Star::where('hex_id', '=', $id)->get();
        if ($stars->count() == 0) {
            $stars = Generator::createStarsInHex($id);
        }
        $hex->stars = $stars;
        return $hex;
    }

    public function getXCoordinate($radius)
    {
        $size = $this->x * ($this->getHexWidth($radius) + 0.2) + $this->y * ($this->getHexWidth($radius) / 2 + 0.1);
        return $size + 0.2;
    }
    /**
     * @return mixed
     */
    public function getYCoordinate($radius)
    {
        $size = $this->y * ($radius * 2 * 3 / 4 + 0.2);
        return $size ;
    }

    public function getCentralCoordinats($radius)
    {
        return [
            $this->x * $this->getHexWidth($radius) + $this->y * ($this->getHexWidth($radius) / 2),
            $this->y * ($radius * 2 * 3 / 4 )
        ];
    }

    public static function coordinatToHex($x, $y)
    {
        $size = self::SIDE_SIZE_LIGHT_YEARS;
        $q = ($x * sqrt(3)/3 - $y / 3) / $size;
        $r = $y * 2/3 / $size;
        return self::hex_round($q, $r);

    }

    protected static function hex_round($x, $y)
    {
        return self::cube_to_hex(self::cube_round(self::hex_to_cube([$x, $y])));
    }

    protected static function cube_round($h)
    {
        $rx = round($h[0]);
        $ry = round($h[1]);
        $rz = round($h[2]);

        $x_diff = abs($rx - $h[0]);
        $y_diff = abs($ry - $h[1]);
        $z_diff = abs($rz - $h[2]);

        if ($x_diff > $y_diff && $x_diff > $z_diff) {
            $rx = -$ry - $rz;
        } else if ($y_diff > $z_diff) {
            $ry = -$rx - $rz;
        } else {
            $rz = -$rx - $ry;
        }

        return [$rx, $ry, $rz];
    }

    public static function hex_to_cube($h)
    {
        if ($h instanceof Hex) $h = [$h->x, $h->y];
        $x = $h[0];
        $z = $h[1];
        $y = - $x -$z;
        return [$x, $y, $z];
    }

    public static function cube_to_hex($h)
    {
        $q = $h[0];
        $r = $h[2];
        return [$q, $r];
    }

    /**
     * @param $radius
     *
     * @return float
     */
    public function getHexWidth($radius)
    {
        return round($radius * sqrt(3), 2);
    }
}