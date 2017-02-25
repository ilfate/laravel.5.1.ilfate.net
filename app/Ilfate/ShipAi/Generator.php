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
use Ilfate\MageSurvival\ChanceHelper;


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
abstract class Generator
{
    const DEFAULT_GALAXY_RADIUS = 9;



    public static function createGalaxy($name)
    {
        $galaxy = new Galaxy();
        $galaxy->name = $name;
        $galaxy->save();

//        $centralHex = new Hex();
//        $centralHex->galaxy_id = $galaxy->id;
//        $centralHex->x = 0;
//        $centralHex->y = 0;
//        $centralHex->is_hidden = true;
//        $centralHex->save();

        $galaxyRadius = self::DEFAULT_GALAXY_RADIUS;

        $xStartV = -1;
        $xStart = 0;
        $xEnd = $galaxyRadius;
        $xEndV = 0;

//        $r = 1;
//        $w = round($r * sqrt(3), 2);

        $bottomSideJumpStart = false;
        $bottomSideJumpEnd = false;
        $topSideJumpStart = false;
        $topSideJumpEnd = false;

        $return = '';
        for ($y = -$galaxyRadius; $y <= $galaxyRadius; $y++) {
            if ($y == -$galaxyRadius || $y == $galaxyRadius) {
                for($x = ceil($galaxyRadius * 0.43); $x <= ceil($galaxyRadius * 0.66); $x++) {
                    if ($y < 0) {
                $hex = new Hex();
                $hex->galaxy_id = $galaxy->id;
                $hex->x = $x;
                $hex->y = $y - 1;
                $hex->save();
//                        $ty = $y - 1;
//                        $mx = $x * ($w + 0.2) + $ty * ($w / 2 + 0.1) + 0.2;
//                        $my = $ty * ($r * 2 * 3 / 4 + 0.2);
//                        $return .= '<div style="width:2rem;height:2rem;position:absolute; margin: ' . $my . 'rem 0 0 ' . $mx . 'rem ">'
//                            . $x . ',' . $ty . '</div>';
                    } else {
                $hex = new Hex();
                $hex->galaxy_id = $galaxy->id;
                $hex->x = -$x;
                $hex->y = $y + 1;
                $hex->save();
//                        $by = $y + 1;
//                        $mx = -$x * ($w + 0.2) + $by * ($w / 2 + 0.1) + 0.2;
//                        $my = $by * ($r * 2 * 3 / 4 + 0.2);
//                        $return .= '<div style="width:2rem;height:2rem;position:absolute; margin: ' . $my . 'rem 0 0 ' . $mx . 'rem ">'
//                            . -$x . ',' . $by . '</div>';

                    }
                }
            }
            for($x = $xStart; $x <= $xEnd; $x++) {
                $hex = new Hex();
                $hex->galaxy_id = $galaxy->id;
                $hex->x = $x;
                $hex->y = $y;
                $hex->save();
//                $mx = $x * ($w + 0.2) + $y * ($w / 2 + 0.1) + 0.2;
//                $my = $y * ($r * 2 * 3 / 4 + 0.2);
//
//                $return .= '<div style="width:2rem;height:2rem;position:absolute; margin: ' . $my. 'rem 0 0 ' . $mx. 'rem ">'
//                    . $x . ',' . $y . '</div>';

            }

            if ($y == 0) { $xEndV = -1; $xStartV = 0; }
            if ($y > -$galaxyRadius * 0.78 && !$topSideJumpStart) { $xStart += -1; $xEnd += 1; $topSideJumpStart = true; }
            if ($y > -$galaxyRadius * 0.5 && !$topSideJumpEnd) { $xStart += 1; $xEnd += -1; $topSideJumpEnd = true; }
            if ($y > $galaxyRadius * 0.33 && !$bottomSideJumpStart) { $xStart += -1; $xEnd += 1; $bottomSideJumpStart = true; }
            if ($y > $galaxyRadius * 0.66 && !$bottomSideJumpEnd) { $xStart += 1; $xEnd += -1; $bottomSideJumpEnd = true; }

            if ($xStartV) $xStart += $xStartV;
            if ($xEndV) $xEnd += $xEndV;

        }
        return $return;
    }

    public static function createStarsInHex($hexId)
    {
        /**
         * @var Hex $hex
         */
        $hex = Hex::where('id', '=', $hexId)->get()->first();
        if (!$hex) return false;
        $center = $hex->getCentralCoordinats(Hex::SIDE_SIZE_LIGHT_YEARS);

        // Center star
        $star = new Star();
        $star->x = $center[0];
        $star->y = $center[1];
        $star->hex_id = $hex->id;
        $star->galaxy_id = $hex->galaxy_id;
        $star->type = Star::getRandomType();
        $star->number = NameGenerator::starNumber($star);
        $star->name = NameGenerator::starName($star);
        $star->save();

        $cube = Hex::hex_to_cube($hex);
        $distance = abs($cube[0]) + abs($cube[1]) + abs($cube[2]);
        // from 0 to 20

        $numberOfStars = (1 / $distance) * 180 + 35 + rand(0, 25);

        $xFrom = $center[0] - $hex->getHexWidth(Hex::SIDE_SIZE_LIGHT_YEARS) / 2;
        $xTo   = $center[0] + $hex->getHexWidth(Hex::SIDE_SIZE_LIGHT_YEARS) / 2;
        $yFrom = $center[1] - Hex::SIDE_SIZE_LIGHT_YEARS;
        $yTo   = $center[1] + Hex::SIDE_SIZE_LIGHT_YEARS;

        $stars = [$star];
        for ($i = 0; $i < $numberOfStars; $i++) {
            $x = rand($xFrom, $xTo);
            $y = rand($yFrom, $yTo);

            foreach ($stars as $neibour) {
                if (abs($neibour->x - $x) + abs($neibour->y - $y) <= 25) {
                    $i--;
                    continue;
                }
            }

            $hexStarIn = Hex::coordinatToHex($x, $y);
            if ($hexStarIn[0] != $hex->x || $hexStarIn[1] != $hex->y) {
                $i--;
                continue;
            }
            $star = new Star();
            $star->x = $x;
            $star->y = $y;
            $star->hex_id = $hex->id;
            $star->galaxy_id = $hex->galaxy_id;
            $star->type = Star::getRandomType();
            $star->number = NameGenerator::starNumber($star);
            $star->name = NameGenerator::starName($star);
            $star->save();
            $stars[] = $star;
        }

//        3725 5135
//        3737 5080
        return $stars;
    }

    public static function createStarSystem(Star $star)
    {
        $objects = [];

        $orbitsChases = [1,2,2,3,3,3,4,4,4,4,5,5,5,5,6,6,6,7,7,7,8,8,9,10];
        $numberOfOrbits = ChanceHelper::oneFromArray($orbitsChases);

        $averedgeDistance = 100 / $numberOfOrbits;
        $orbits = [];
        $names = [];
        for ($i = 0; $i < $numberOfOrbits; $i++) {
            $orbit = [];
            $r = ($i * $averedgeDistance + $averedgeDistance / 2);
            $orbit['r'] = $r + $r * (rand(-10, 10) / 100);
            $orbit['name'] = NameGenerator::starName($names);
            $names[] = $orbit['name'];
            $orbit['type'] = Location::randomOrbitType();
            $orbits[$i + 1] = $orbit;
        }

        $locationsChances = [0,0,1,1,1,2,2,2,2,3,3,3,4,4,5];
        $numberOfLocations = ChanceHelper::oneFromArray($locationsChances);
        $locations = [];
        if ($numberOfLocations > $numberOfOrbits) {
            if ($numberOfLocations > $numberOfOrbits + 1) {
                $numberOfLocations = $numberOfOrbits + 1;
            }
            $location = new Location();
            $location->type = '';
        }


        $star->objects = $objects;
        $star->save();
    }



}