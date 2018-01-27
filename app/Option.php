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

use App\Poll;
use App\Vote;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'poll_id',
        'label',
    ];

    /**
     * Poll relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function poll()
    {
        return $this->belongsTo(\App\Poll::class);
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

    /**
     * Votes count
     *
     * @return integer
     */
    public function votesCount()
    {
        return $this->hasOne(\App\Vote::class)
            ->selectRaw('option_id, count(*) as count')
            ->groupBy('option_id');
    }

    /**
     * Percentage of total votes for this option
     *
     * @param  integer $totalVotes
     * @return integer
     */
    public function votesPercent($totalVotes = 0)
    {
        $optionVotesCount = $this->votesCount['count'];

        if ($optionVotesCount == 0 && $totalVotes == 0)
        {
            return 0;
        }

        return round(($optionVotesCount / $totalVotes) * 100);
    }
}
