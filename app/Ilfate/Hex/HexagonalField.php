<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @version   "SVN: $Id$"
 * @link      http://ilfate.net
 */
namespace Ilfate\Hex;
use Illuminate\Http\Request;

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
class HexagonalField
{

    const SESSION_NAME = 'hex.game.save';

    const ACTION_BUILD_WALL = 'buildWall';

    const PATTERN_SAME_COLOR_CONFIG = 'same';

    protected $config;

    protected $updates;

    protected $currentWallType = 'lineY';

    /**
     * @var Cell[]
     */
    protected $cells;

    public function __construct()
    {
        $this->config = \Config::get('hex.game');
        $radius = 7;
        $table = $this->getFieldByRadius($radius);
        foreach ($table as $y => $row) {
            foreach ($row as $x => $type) {
                switch ($type) {
                    case 'gun':
                        $cell = new Gun($this, $x, $y, $type);
                        break;
                    case 'cell':
                    default:
                        $cell = new Cell($this, $x, $y, $type);
                        break;
                }
                $this->cells[$y][$x] = $cell;
            }

        }
    }

    /**
     * @param Request $request
     */
    public function load(Request $request)
    {
        $saved = $request->session()->get(self::SESSION_NAME);
        if (!$saved) {
            return;
        }
        $this->currentWallType = $saved['currentWallType'];
        foreach ($saved['cells'] as $cellData) {
            $x = $cellData['x'];
            $y = $cellData['y'];
            $cell = $this->getCell($x, $y);
            $cell->import($cellData);
        }

    }

    /**
     * @param Request $request
     */
    public function save(Request $request)
    {
        $data = [
            'cells'
        ];
        foreach ($this->cells as $y => $row) {
        foreach ($row as $x => $cell) {
                $cellData = $cell->export();
                if ($cellData) {
                    $data['cells'][] = $cellData;
                }
            }
        }
        if (!empty($data['cells'])) {
            $data['currentWallType'] = $this->currentWallType;
            $request->session()->put(self::SESSION_NAME, $data);
        }
    }

    /**
     *
     */
    public function build()
    {
        foreach ($this->cells as $row) {
            foreach ($row as $cell) {
                if ($cell instanceof \ilfate\Hex\Gun) {
                    $cell->setUpGuns();
                }
            }
        }
    }

    /**
     *
     */
    public function rebuild()
    {
        $changedLasers = [];
        foreach ($this->cells as $y => $row) {
            foreach ($row as $x => $cell) {
                if ($cell instanceof \ilfate\Hex\Gun) {
                    $changedLaser = $cell->checkGunsForChanges();
                    if ($changedLaser) {
                        $changedLasers[$y][$x] = $changedLaser;
                    }
                }
            }
        }
        $this->updates['lasers'] = $changedLasers;
    }

    /**
     * @param $type
     * @param $data
     */
    public function action($type, $data)
    {
        switch ($type) {
            case self::ACTION_BUILD_WALL:
                $this->addWall($data['x'], $data['y'], $this->currentWallType);
                $this->rebuild();

                break;
        }
    }

    public function addWall($x, $y, $wallType)
    {
        if (empty($this->config['wallTypes'][$wallType])) {
            throw new \Exception('Wrong wall type');
        }
        $cells = $this->config['wallTypes'][$wallType];
        foreach ($cells as $cellCoordinates) {
            $newX = $x + $cellCoordinates[0];
            $newY = $y + $cellCoordinates[1];
            if (!empty($cellCoordinates[2])) {
                if ($cellCoordinates[2] == self::PATTERN_SAME_COLOR_CONFIG) {
                    if (!isset($preSetColor)) {
                        $preSetColor = mt_rand(0, 2);
                    }
                    $colors = [$preSetColor];
                } else {
                    $colors = $cellCoordinates[2];
                }
            } else {
                $colors = [mt_rand(0,2)];
            }

            $cell = $this->getCell($newX, $newY);
            if (!$cell) {
                continue;
            }
            switch ($cell->getType()) {
                case Cell::TYPE_CELL:
                    $wall = $cell->makeAWall();
                    $wall->setColors($colors);
                    $this->updates['walls'][] = [
                        'x' => $newX,
                        'y' => $newY,
                        'status' => 'new',
                        'class' => $wall->getAdditionalClasses()
                    ];
                    break;
                case Cell::TYPE_WALL:
                    $cell->addColors($colors);
                    $this->updates['walls'][] = [
                        'x' => $newX,
                        'y' => $newY,
                        'status' => 'updated',
                        'class' => $cell->getAdditionalClasses()
                    ];
                    break;
            }

        }


    }

    public function getWallsPatterns()
    {
        $selectedPattern = $this->config['wallTypes'][$this->currentWallType];
        return json_encode([$selectedPattern]);
    }

    /**
     * @param $x
     * @param $y
     *
     * @return Cell
     */
    public function getCell($x, $y)
    {
        if (!empty($this->cells[$y][$x])) {
            return $this->cells[$y][$x];
        }
        return false;
    }

    /**
     * @param Cell $cell
     */
    public function setCell(Cell $cell)
    {
        $this->cells[$cell->getY()][$cell->getX()] = $cell;
    }

    /**
     * @return Cell[]
     */
    public function getCells()
    {
        return $this->cells;
    }

    public function getNeighborsForCell($x, $y)
    {
        $result = [];
        $direction = 0;
        foreach ($this->getNeighborsCoordinates() as $coordinats) {
            $nx = $x + $coordinats[0];
            $ny = $y + $coordinats[1];
            if (!empty($this->cells[$ny][$nx])) {
                $result[$direction] = $this->cells[$ny][$nx];
            }
            $direction++;
        }
        return $result;
    }
    public function getNeighborForCell($x, $y, $direction)
    {
        $directions = $this->getNeighborsCoordinates()[$direction];
        $nx = $x + $directions[0];
        $ny = $y + $directions[1];
        if (!empty($this->cells[$ny][$nx])) {
            return $this->cells[$ny][$nx];
        }

        return false;
    }

    /**
     * @return array
     */
    public function getNeighborsCoordinates()
    {
        return [
            [0, -1],
            [1, -1],
            [1, 0],
            [0, 1],
            [-1, 1],
            [-1, 0],
        ];
    }

    public function getOppositeDirection($direction)
    {
        $direction -= 3;
        if ($direction < 0) {
            $direction = 6 + $direction;
        }
        return $direction;
    }

    public function getFieldByRadius($radius)
    {
        $table = [];
        $startX = 0;
        $endX = $radius;
        for ($i = -$radius; $i <= $radius; $i++) {
            $table[$i] = [];
            for ($i2 = $startX; $i2 <= $endX; $i2++) {
                if (abs($i) == $radius || $i2 == $startX || $i2 == $endX) {
                    $table[$i][$i2] = 'gun';
                } else {
                    $table[$i][$i2] = 'cell';
                }
            }
            $startX--;
            if ($startX < -$radius) {
                $startX = -$radius;
                $endX--;
            }
        }
        return $table;
    }

    /**
     * @return mixed
     */
    public function getUpdates()
    {
        return $this->updates;
    }

    /**
     * @param mixed $updates
     */
    public function setUpdates($updates)
    {
        $this->updates = $updates;
    }

}