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
class Star extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sa_stars';

//    protected $saveable = ['id', 'user_id', 'name', 'data', 'resources', 'inventory', 'events'];

    public function hex()
    {
        return $this->hasOne(Hex::class, 'id', 'hex_id');
    }
}