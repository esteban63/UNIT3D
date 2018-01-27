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

use App\Exceptions\PollOptionsException;
use App\Http\Requests\CreatePoll;
use App\Option;
use App\Poll;
use App\Repositories\PollRepository;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class PollController extends BaseController
{
    use ValidatesRequests;

    /**
     * @var App\Poll
     */
    protected $poll;


    /**
     * @var App\Repositories\PollRepository
     */
    protected $pollRepository;

    /**
     * Constructor
     *
     * @param Poll           $poll
     * @param PollRepository $pollRepository
     */
    public function __construct(Poll $poll, PollRepository $pollRepository)
    {
        $this->poll = $poll;
        $this->pollRepository = $pollRepository;

        $this->middleware('is_modo', [
            'only' => ['create', 'store', 'destroy'],
        ]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $polls = $this->poll->paginate(10);

        return view('poll.index', compact('polls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title = 'Create new poll';
        $options = ($request->input('options') > config('poll.max_options')) ? config('poll.max_options') : $request->input('options');

        return view('poll.create', compact('title', 'options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePoll $request)
    {
        try
        {
            $poll = $this->pollRepository->create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'options' => $request->input('options'),
                'multichoice' => $request->input('multichoice'),
                'ends_at' => $request->input('ends_at'),
            ]);
        }
        catch (PollOptionsException $e)
        {
            abort(400, 'A poll must contain between 1 and '.config('max_options').' options');
        }

        return redirect()
            ->route('poll.index')
            ->with(Toastr::success('Poll Created Succefully.', 'Yay!', ['options']));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $poll = $this->pollRepository->results($id);
        $totalVotes = $poll->totalVotes();

        return view('poll.show', compact('poll', 'totalVotes'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->pollRepository->delete($id);

        return redirect()
            ->route('polls.index')
            ->with(Toastr::info('Poll Deleted Successfully.', 'Yay!', ['options']));
    }
}
