@extends('layout.default')

@section('title')
<title>{{ trans('poll.poll') }} - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('polls') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('poll.polls') }}</span>
  </a>
</li>
<li>
  <a href="{{ route('poll', ['slug' => $poll->slug]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('poll.poll') }}</span>
  </a>
</li>
@stop

@section('content')
<div class="box container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $poll->title }}</h3>
        </div>
        <div class="panel-body">
            @foreach ($poll->options as $option)
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $option->votesPercent($totalVotes) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $option->votesPercent($totalVotes) }}%; min-width: 2em;">
                        {{ $option->votesPercent($totalVotes) }}%
                    </div>
                </div>
            @endforeach
            <p>Total votes: {{ $totalVotes }}</p>
            <hr />
            @include('poll::forms.options', ['options' => $poll->options])
        </div>
    </div>

    @auth(config('poll.admin_middleware'))
        <form action="{{ route('polls.destroy', $poll->id) }}" method="POST" role="form">
            <button type="submit" class="btn btn-danger">Delete poll</button>
        </form>
    @endauth
</div>
@endsection
