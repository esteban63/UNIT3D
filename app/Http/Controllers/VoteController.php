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

namespace App\Http\Controllers;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\InvalidOptionException;
use App\Exceptions\PollClosedException;
use App\Exceptions\PollTimeoutException;
use App\Casters\VoteCaster;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use \Toastr;

class VoteController extends BaseController
{
    use ValidatesRequests;

    /**
     * @var App\Casters\VoteCaster
     */
    protected $voteCaster;

    /**
     * Constructor
     *
     * @param VoteCaster $voteCaster
     */
    public function __construct(VoteCaster $voteCaster)
    {
        $this->voteCaster = $voteCaster;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ( ! auth()->check())
        {
            abort(403, 'Not logged in!');
        }

        $request->validate([
            'poll_id' => 'exists:polls,id',
        ]);

        try
        {
            $this->voteCaster->cast(
                auth()->user(),
                $request->input('poll_id'),
                $request->input('options')
            );
        }
        catch (DuplicateVoteException $e)
        {
            abort(400, 'You have already voted in this poll');
        }
        catch (PollClosedException $e)
        {
            abort(400, 'This poll is closed');
        }
        catch (PollTimeoutException $e)
        {
            abort(400, 'You can no longer vote in this poll');
        }
        catch (InvalidOptionException $e)
        {
            abort(400, 'You voted on an option that does not exist');
        }

        return redirect()
            ->route('poll.show', $request->input('poll_id'))
            ->with(Toastr::success('Your vote has been counted.', 'Yay!', ['options']));
    }
}
