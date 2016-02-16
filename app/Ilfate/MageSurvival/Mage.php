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
 * @copyright 2016 Watchmaster GmbH
 * @license   Proprietary license.
 * @link      http://www.watchmaster.de
 */
abstract class Mage
{
    /**
     * @var \Ilfate\Mage
     */
    protected $mageEntity;

    protected $x;
    protected $y;
    protected $d;
    protected $data;

    public function __construct(\Ilfate\Mage $mageEntity)
    {
        $this->mageEntity = $mageEntity;
        $this->data = json_decode($mageEntity->data, true);
        if (isset($this->data['x'])) {
            $this->x = $this->data['x'];
        }
        if (isset($this->data['y'])) {
            $this->y = $this->data['y'];
        }
    }



    public function viewExport()
    {
        $data = [
            'd' => $this->getD(),
        ];
        return $data;
    }

    public function move($data) {
        switch ($this->getD()) {
            case 0: $this->y -= 1; break;
            case 1: $this->x += 1; break;
            case 2: $this->y += 1; break;
            case 3: $this->x -= 1; break;
        }
        $this->save();
    }

    public function save()
    {
        $data = $this->data;
        $data['x'] = $this->getX();
        $data['y'] = $this->getY();
        $data['d'] = $this->getD();
        $this->mageEntity->data = json_encode($data);
        $this->mageEntity->save();
    }

    /**
     * @return \Ilfate\Mage
     */
    public function getMageEntity()
    {
        return $this->mageEntity;
    }

    /**
     * @param \Ilfate\Mage $mageEntity
     */
    public function setMageEntity($mageEntity)
    {
        $this->mageEntity = $mageEntity;
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
     * @return mixed
     */
    public function getD()
    {
        return $this->d ?: 0;
    }

    /**
     * @param mixed $d
     */
    public function setD($d)
    {
        $this->d = $d;
    }
}