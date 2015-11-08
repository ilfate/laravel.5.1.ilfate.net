<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilya.rubinchik@home24.de>
 * @copyright 2012-2013 Home24 GmbH
 * @license   Proprietary license.
 * @version   "SVN: $Id$"
 * @link      http://www.fp-commerce.de
 */
namespace Ilfate\Cosmos\Ship;

/**
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class Ship
{
    const VIEW_ATTRIBUTE_SHIP_MIN_X = 'minX';
    const VIEW_ATTRIBUTE_SHIP_MIN_Y = 'minY';
    const VIEW_ATTRIBUTE_SHIP_WIDTH = 'maxX';
    const VIEW_ATTRIBUTE_SHIP_HEIGHT = 'maxY';

    use \Ilfate\Cosmos\Ship\FrontEnd\Ship;

    protected $player;
    /**
     * @var Module[]
     */
    protected $modules;
    /**
     * @var Cell[]
     */
    protected $cells;
    /**
     * @var System[]
     */
    protected $systems;

    /**
     * @var CrewMember[]
     */
    protected $crewMembers;

    /**
     * @var array
     */
    protected $viewAttibutes = [];

    public function serialise()
    {
        $modules = [];
        foreach ($this->getModules() as $module)
        {
            $modules[] = $module->serialise();
        }
        $systems = [];
        foreach ($this->getSystems() as $system)
        {
            $systems[] = $system->serialise();
        }
        $crewMembers = [];
        foreach ($this->getCrewMembers() as $crewMember)
        {
            $crewMembers[] = $crewMember->serialise();
        }
        $shipDataArr = [
            implode(':', $modules),
            implode(':', $systems),
            implode(':', $crewMembers),
        ];
        return implode('|', $shipDataArr);
    }

    /**
     * @param $string
     */
    public static function createShipFromSerialiasedString($string)
    {
        $ship = new self();
        list (
            $modules,
            $systems,
            $crewMembers
            ) = explode('|', $string);
        $modules = explode(':', $modules);
        foreach ($modules as $moduleString) {
            $moduleObject = Module::createFromSerialised($moduleString);
            $ship->addModule($moduleObject);
        }
        $systems = explode(':', $systems);
        foreach ($systems as $systemString) {
            $systemObject = System::createFromSerialised($systemString);
            $ship->addSystem($systemObject);
        }
        $crewMembers = explode(':', $crewMembers);
        foreach ($crewMembers as $crewMemberString) {
            $crewMemberObject = CrewMember::createFromSerialised($crewMemberString);
            $ship->addCrewMember($crewMemberObject);
        }
        return $ship;
    }

    /**
     * @param Module $module
     */
    public function addModule(Module $module)
    {
        $this->modules[$module->getId()] = $module;
        $module->setShip($this);
        $module->install();
    }
    /**
     * @param System $system
     */
    public function addSystem(System $system)
    {
        $this->systems[$system->getId()] = $system;
        $system->setShip($this);
        $system->install();
    }
    /**
     * @param CrewMember $crewMember
     */
    public function addCrewMember(CrewMember $crewMember)
    {
        $this->crewMembers[$crewMember->getId()] = $crewMember;
    }

    /**
     * @param $x
     * @param $y
     *
     * @return null|Cell
     */
    public function getCell($x, $y)
    {
        if (!empty($this->cells[$y][$x])) {
            return $this->cells[$y][$x];
        }
        return null;
    }

    public function addCell(Cell $cell)
    {
        $x = $cell->getX();
        $y = $cell->getY();
        $this->cells[$y][$x] = $cell;
    }

    /**
     * @return void
     */
    public function prepareToRender()
    {
        $minX = 0;
        $maxX = 0;
        foreach ($this->cells as &$row) {
            ksort($row);
            $currentMinX = head($row)->getX();
            if ($currentMinX < $minX) $minX = $currentMinX;
            $currentMaxX = last($row)->getX();
            if ($currentMaxX > $maxX) $maxX = $currentMaxX;
        }
        ksort($this->cells);
        $minY = head(head($this->cells))->getY();
        $this->viewAttibutes[self::VIEW_ATTRIBUTE_SHIP_MIN_X] = $minX;
        $this->viewAttibutes[self::VIEW_ATTRIBUTE_SHIP_MIN_Y] = $minY;
        $this->viewAttibutes[self::VIEW_ATTRIBUTE_SHIP_WIDTH] = $maxX - $minX + 1;
        $this->viewAttibutes[self::VIEW_ATTRIBUTE_SHIP_HEIGHT] = head(last($this->cells))->getY() - $minY + 1;

    }

    /**
     * @param $name
     *
     * @return null|string
     */
    public function getViewAttribute($name)
    {
        if ($this->viewAttibutes[$name]) {
            return $this->viewAttibutes[$name];
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param mixed $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * @return Module[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param Module[] $modules
     */
    public function setModules($modules)
    {
        $this->modules = $modules;
    }

    /**
     * @return Cell[]
     */
    public function getCells()
    {
        return $this->cells;
    }

    /**
     * @param Cell[] $cells
     */
    public function setCells($cells)
    {
        $this->cells = $cells;
    }

    /**
     * @return System[]
     */
    public function getSystems()
    {
        return $this->systems;
    }

    /**
     * @param System[] $systems
     */
    public function setSystems($systems)
    {
        $this->systems = $systems;
    }

    /**
     * @return CrewMember[]
     */
    public function getCrewMembers()
    {
        return $this->crewMembers;
    }

    /**
     * @param CrewMember[] $crewMembers
     */
    public function setCrewMembers($crewMembers)
    {
        $this->crewMembers = $crewMembers;
    }

    /**
     * @return array
     */
    public function getViewAttibutes()
    {
        return $this->viewAttibutes;
    }

    /**
     * @param array $viewAttibutes
     */
    public function setViewAttibutes($viewAttibutes)
    {
        $this->viewAttibutes = $viewAttibutes;
    }
}