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

namespace App\Traits;

use App\Poll;
use App\Vote;

trait PollUser
{
    /**
     * Has user id already voted in this poll
     *
     * @param  integer  $userId
     * @return boolean
     */
    public function hasVoted(Poll $poll)
    {
        return $poll->votes()->where('user_id', $this->id)->count() > 0;
    }

    /**
     * Votes relation
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany(\App\Vote::class);
    }
}
