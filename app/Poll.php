<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use App\Option;
use App\Vote;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'multichoice',
        'closed',
        'ends_at',
    ];

    /**
     * Options relation
     *
     * @return lluminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(\App\Option::class);
    }

    /**
     * Number of options for ths poll
     *
     * @return integer
     */
    public function optionsCount()
    {
        return $this->options()->count();
    }

    /**
     * Votes relation
     *
     * @return Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function votes()
    {
        return $this->hasManyThrough(\App\Vote::class, \App\Option::class);
    }

    /**
     * Total votes for this poll
     *
     * @return integer
     */
    public function totalVotes()
    {
        return $this->votes()->count();
    }

    /**
     * Has this user id already voted in this poll
     *
     * @param  integer  $userId
     * @return boolean
     */
    public function hasVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->count() > 0;
    }
}
