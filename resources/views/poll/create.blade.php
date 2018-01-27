@extends('layout.default')

@section('title')
<title>{{ trans('poll.results') }} - {{ Config::get('other.title') }}</title>
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
<div class="container">
    <h2>Create new poll</h2>

    @include('poll::forms.errors')

    @include('poll::forms.create')
</div>
@endsection
