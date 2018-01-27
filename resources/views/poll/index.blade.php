@extends('layout.default')

@section('title')
<title>{{ trans('poll.polls') }} - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('polls') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('poll.polls') }}</span>
  </a>
</li>
@stop

@section('content')
<div class="box container">
  {{--<span class="badge-user" style="float: right;"><strong>{{ trans('poll.polls') }}:</strong> 0 | <strong>{{ trans('poll.total') }}:</strong> 0</span>--}}
  <div class="header gradient green">
    <div class="inner_content">
      <h1>{{ trans('poll.current') }}</h1>
    </div>
  </div>
  @if ($polls->count() == 0)
      <p>There are no polls, create one below!</p>
  @endif

  @foreach ($polls as $poll)
      <div class="polls">
          <h3 class="poll-title"><a href="{{ route('polls.show', $poll->id) }}">{{ $poll->title }}</a></h3>
      </div>
  @endforeach

  {{ $polls->links() }}

  <hr />

  @include('poll.forms.create_options')
</div>
@endsection
