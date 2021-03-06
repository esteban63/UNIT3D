@extends('layout.default')

@section('title')
<title>{{ trans('request.add-request') }} - {{ Config::get('other.title') }}</title>
@endsection

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('breadcrumb')
<li>
    <a href="{{ url('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('request.requests') }}</span>
    </a>
</li>
<li>
    <a href="{{ url('add_request') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('request.add-request') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
  @if($user->can_request == 0)
  <div class="container">
    <div class="jumbotron shadowed">
      <div class="container">
        <h1 class="mt-5 text-center">
          <i class="fa fa-times text-danger"></i> {{ trans('request.no-privileges') }}
        </h1>
      <div class="separator"></div>
    <p class="text-center">{{ trans('request.no-privileges-desc') }}!</p>
  </div>
  </div>
  </div>
  @else
  <div class="col-sm-12">
      <div class="well well-sm mt-20">
          <p class="lead text-orange text-center">{{ trans('request.no-imdb-id') }}</strong>
          </p>
      </div>
  </div>
<h1 class="upload-title">{{ trans('request.add-request') }}</h1>
{{ Form::open(['route' => 'add_request', 'method' => 'post', 'role' => 'form', 'class' => 'upload-form']) }}
<div class="block">
  <div class="upload col-md-12">
            <div class="form-group">
                <label for="name">{{ trans('request.title') }}</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
               <label for="name">IMDB ID ({{ trans('request.required') }})</label>
               <input type="number" name="imdb" value="0" class="form-control" required>
           </div>

           <div class="form-group">
              <label for="name">TMDB ID </label>
              <input type="number" name="tmdb" value="0" class="form-control" required>
          </div>

           <div class="form-group">
               <label for="name">TVDB ID </label>
               <input type="number" name="tvdb" value="0" class="form-control" required>
           </div>

           <div class="form-group">
               <label for="name">MAL ID </label>
               <input type="number" name="mal" value="0" class="form-control" required>
           </div>

      <div class="form-group">
        <label for="category_id">{{ trans('request.category') }}</label>
        <select name="category_id" class="form-control">
          @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="type">{{ trans('request.type') }}</label>
        <select name="type" class="form-control">
          @foreach($types as $type)
            <option value="{{ $type->name }}">{{ $type->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="description">{{ trans('request.description') }}</label>
        <textarea id="request-form-description" name="description" cols="30" rows="10" class="form-control"></textarea>
      </div>

      <div class="form-group">
        <label for="bonus_point">{{ trans('request.reward') }}</label>
          <input class="form-control" name="bounty" type="number" min='100' value="100" required>
          <span class="help-block">{{ trans('request.reward-desc') }}</span>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
      {{ Form::close() }}
    <br>
  </div>
</div>
@endif
</div>
@endsection

@section('javascripts')
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
<script>
$(document).ready(function() {
    var wbbOpt = { }
    $("#request-form-description").wysibb(wbbOpt);
});
</script>
@endsection
