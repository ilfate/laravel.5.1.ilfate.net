<?php

namespace Ilfate;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    const GUEST_USER_SESSION_KEY = 'guestUser';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    protected $appends = array('guest_id', 'guest_name');

    protected $guarded = array('guest_id', 'guest_name');



    public static function getUser()
    {
        if (Auth::check())
        {
            $user = Auth::user();
        } else {
            $user = User::getGuest();
        }
        return $user;
    }

    public static function getGuest()
    {
//        $userData = Session::get(self::GUEST_USER_SESSION_KEY, null);
//        if (!$userData) {
//            // user is first time here
//            $user = new User;
//            self::saveUser($user);
//        } else {
//            $user = unserialize($userData);
//        }
        $user = new User;
        $user->id = $user->getId();
        $user->is_guest = true;
        $user->email = $user->id . '@guest.com'; // teporary email for guest
        $user->save();
        Auth::loginUsingId($user->getId(), true);
        return $user;
    }

    public static function saveUser(User $user)
    {
        if ($user->id) {
            $user->save();
        } else {
            Session::set(self::GUEST_USER_SESSION_KEY, serialize($user));
        }
    }

    public function getId()
    {
        if (!$this->id) {
            $this->id = mt_rand(100000, 9999999) . '2';
            $existingUser = User::where('id', $this->id)->first();
            if ($existingUser) {
                return $this->id = $this->getId();
            }
        }
        return $this->id;
    }

    public function getName()
    {
        if ($this->id) {
            return $this->name;
        } else {
            if ($this->guest_name === false) {
                $names = ['viking', 'dwarf', 'ranger', 'man', 'smith'];
                $who = $names[array_rand($names)];
                $types = ['wild', 'calm', 'brave', 'strange', 'fast', 'enraged', 'smart'];
                $type = $types[array_rand($types)];
                $this->guest_name = 'The ' . $type . ' ' . $who;
                User::saveUser($this);
            }
            return $this->guest_name;
        }
    }

    public function getGuestIdAttribute()
    {
        if (isset($this->attributes['guest_id'])) {
            return $this->attributes['guest_id'];
        }
        return false;
    }
    public function setGuestIdAttribute($guestId)
    {
        $this->attributes['guest_id'] = $guestId;
    }

    public function getGuestNameAttribute()
    {
        if (isset($this->attributes['guest_name'])) {
            return $this->attributes['guest_name'];
        }
        return false;
    }
    public function setGuestNameAttribute($guestName)
    {
        $this->attributes['guest_name'] = $guestName;
    }

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = array())
    {
        if (!$this->id) {
            // we are creating user for the first time
            unset($this->attributes['guest_id']);
            unset($this->attributes['guest_name']);
            $this->attributes['password'] = Hash::make($this->attributes['password']);

            Session::forget(self::GUEST_USER_SESSION_KEY);
        }
        return parent::save($options);
    }

    /**
     * Get all mages
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mages()
    {
        return $this->hasMany('Ilfate\Mage', 'player_id', 'id');
    }

    public function mage_user()
    {
        return $this->hasOne('Ilfate\MageUser', 'user_id', 'id');
    }

}
