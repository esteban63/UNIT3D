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
 
namespace App\Casters;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\PollClosedException;
use App\Poll\Exceptions\PollTimeoutException;
use App\Poll\Exceptions\InvalidOptionException;
use App\Interfaces\PollUserInterface;
use App\Option;
use App\Poll;
use App\Vote;
use Carbon\Carbon;
use Exception;

class VoteCaster
{
    /**
     * @var App\Poll
     */
    protected $poll;

    /**
     * @var App\Option
     */
    protected $option;

    /**
     * @var App\Vote
     */
    protected $vote;

    /**
     * Constructor
     *
     * @param Poll   $poll
     * @param Option $option
     * @param Vote   $vote
     */
    public function __construct(Poll $poll, Option $option, Vote $vote)
    {
        $this->poll = $poll;
        $this->option = $option;
        $this->vote = $vote;
    }

    /**
     * Cast a vote for a single option or array of options
     *
     * @param  PollUserInterface $user
     * @param  integer           $pollId
     * @param  integer|array     $option
     */
    public function cast(PollUserInterface $user, $pollId, $option)
    {
        if (is_array($option))
        {
            $this->multichoice($user, $pollId, $option);
        }

        try
        {
            $option = $this->option->findOrFail($option);
            $poll = $option->poll;
        }
        catch (Exception $e)
        {
            throw new InvalidOptionException;
        }

        if ($poll->multichoice == false && $user->hasVoted($poll))
        {
            throw new DuplicateVoteException;
        }

        if ($poll->closed == true)
        {
            throw new PollClosedException;
        }

        if ($poll->ends_at != null && Carbon::parse($poll->ends_at)->isPast())
        {
            throw new PollTimeoutException;
        }

        return $this->vote->create([
            'user_id' => $user->id,
            'option_id' => $option->id,
        ]);
    }

    /**
     * Cast a vote for multiple options making sure the poll options that
     * the user voted for match the poll options avaliable to this poll
     *
     * @param  PollUserInterface $user
     * @param  integer           $pollId
     * @param  array             $options
     * @return boolean
     */
    protected function multichoice(PollUserInterface $user, $pollId, array $options = [])
    {
        $poll = $this->poll->with('options')->findOrFail($pollId);
        $pollOptions = $poll->options()->pluck('id')->toArray();

        if ($pollOptions != $options)
        {
            throw new InvalidOptionException;
        }

        if ($poll->multichoice == false && $user->hasVoted($poll))
        {
            throw new DuplicateVoteException;
        }

        if ($poll->closed == true)
        {
            throw new PollClosedException;
        }

        if ($poll->ends_at != null && Carbon::parse($poll->ends_at)->isPast())
        {
            throw new PollTimeoutException;
        }

        $now = Carbon::now()->toDateTimeString();
        $data = [];

        foreach ($options as $option)
        {
            $data[] = [
                'user_id' => $user->id,
                'option_id' => $option,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return $this->vote->insert($data);
    }
}
