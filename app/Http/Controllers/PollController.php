<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Poll;
use App\Option;
use App\Voter;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\StorePoll;
use App\Http\Requests\VoteOnPoll;

use \Toastr;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
        $polls = Poll::latest()->paginate(15);

        return view('poll.latest', compact('polls'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $slug
     *
     */
    public function show($slug)
    {
        $poll = Poll::whereSlug($slug)->firstOrFail();

        return view('poll.show', compact('poll'));
    }

    public function vote(VoteOnPoll $request)
    {
        $user = auth()->user();
        $poll = Option::findOrFail($request->input('option.0'))->poll;

        foreach ($request->input('option') as $option) {
            Option::findOrFail($option)->increment('votes');
        }

        if (Voter::where('user_id', $user->id)->where('poll_id', $poll->id)->exists()) {
            Toastr::error('Bro have already vote on this poll. Your vote has not been counted.', 'Whoops!', ['options']);

            return redirect('poll/' . $poll->slug . '/result');
        }

        if ($poll->ip_checking == 1) {
            $voter = Voter::create([
                'poll_id' => $poll->id,
                'user_id' => $user->id,
                'ip_address' => $request->ip()
            ]);
        }

        Toastr::success('Your vote has been counted.', 'Yay!', ['options']);

        return redirect('poll/' . $poll->slug . '/result');
    }

    public function result($slug)
    {
        $poll = Poll::whereSlug($slug)->firstOrFail();

        return view('poll.result', compact('poll'));
    }
}
