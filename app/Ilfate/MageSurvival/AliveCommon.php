<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 *
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
namespace Ilfate\MageSurvival;

/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 *            
 * @property $data
 * @property World $world
 *
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
abstract class AliveCommon
{
    /**
     * @var World
     */
    protected $world;
    
    const DATA_FLAG_KEY = 'f';
    const FLAG_FROZEN = 'frozen';
    const FLAG_BURN = 'burn';
    const FLAG_WEB = 'web';
    
    abstract public function update();
    abstract public function damage($value, $animationStage);
    abstract public function getId();

    public function addFlag($flag, $value = true)
    {
        $this->data[self::DATA_FLAG_KEY][$flag] = $value;
        $this->update();
    }
        
    public function removeFlag($flag)
    {
        unset($this->data[self::DATA_FLAG_KEY][$flag]);
        $this->update();
    }

    public function getFlag($flag)
    {
        if (isset($this->data[self::DATA_FLAG_KEY][$flag])) {
            return $this->data[self::DATA_FLAG_KEY][$flag];
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return World
     */
    public function getWorld()
    {
        return $this->world;
    }

    /**
     * @param World $world
     */
    public function setWorld($world)
    {
        $this->world = $world;
    }
}