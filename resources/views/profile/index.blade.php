@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-lg-5">
        @include('user.partials.userblock')
        <hr>

          @if (!$statuses->count())
            <p> {{$user->getFirstNameOrUsername()}} hasn't posted anything yet. </p>
          @else
            @foreach ($statuses as $status)
               <div class="media">
                <a class="pull-left" href="{{ route('profile.index',['username'=>$status->user->username]) }}">
                        <img class="media-object" alt="{{ $status->user->getNameOrUsername() }}" src="{{$status->user->getAvatarUrl() }}">
                    </a>
                    <div class="media-body">
                        <h5 class="media-heading"><a href="{{ route('profile.index',['username'=>$status->user->username]) }}">{{ $status->user->getNameOrUsername() }}</a></h5>
                        <p>{{ $status->body }}!</p>
                        <ul class="list-inline">
                            <li> {{$status->created_at->diffForHumans() }}</li>
                         <!--  if ($status->user->id !== Auth::user()->id)
                           <li><a href="{{ route('status.like',['statusId' => $status->id]) }}">Like</a></li>
                           <li>4 likes</li>
                         endif -->
                        </ul>
                    
                          @foreach ($status->replies as $reply)
                              <div class="media">
                                  <a class="pull-left" href="{{ route('profile.index',['username'=> $reply->user->username]) }}">
                                      <img class="media-object" alt="{{$reply->user->getNameOrUsername() }}" src="{{$reply->user->getAvatarUrl() }}">
                                  </a>
                                  <div class="media-body">
                                      <h5 class="media-heading"><a href="#">{{$reply->user->getNameOrUsername() }} </a></h5>
                                      <p>{{$reply->body }}</p>
                                      <ul class="list-inline">
                                          <li>{{$reply->created_at->diffForHumans()}} </li>
                                      @if ($reply->user->id !== Auth::user()->id)
                                          <li><a href="{{ route('status.like',['statusId' => $reply->id]) }}">Like</a></li>
                                          <li>{{$reply->likes->count()}} {{ str_plural('Like',$reply->likes->count()) }}</li>
                                        @endif
                                      </ul>
                                  </div>
                              </div>
                     @endforeach
                          </div>
              @if ($authUserIsFriend || Auth::user()->id === $status->user->id)
                <form role="form" action="{{ route('status.reply',['statusId' => $status->id]) }}" method="post">
                    <div class="form-group">
                        <textarea name="reply-{{$status->id}}" class="form-control{{ $errors->has("reply-{$status->id}") ? ' has-error' : ''}} " rows="1" placeholder="Reply to this status"></textarea>
                        @if($errors->has("reply-{$status->id}"))
                              <span class="help-block" >{{ $errors ->first("reply-{$status->id}")}} </span>
                            @endif
                    </div>
                    
                      <input type="submit" value="Reply" class="btn btn-default btn-sm">
                      <input type="hidden" name="_token" value="{{Session::token()}}">
                    
                </form>
              @endif
              </div>    
            @endforeach
          @endif
    </div>
    <div class="col-lg-4 col-lg-offset-3">
        @if (Auth::user()->hasFriendRequestsPending($user))
          <p>Waiting for {{ $user->getFirstNameOrUsername()}} to accept your request </p> 
        @elseif(Auth::user()->hasFriendRequestsReceived($user))
                <a class="btn btn-primary" href="{{ route('friend.accept',['username'=>$user->username]) }}">Accept friend request</a>
        @elseif(Auth::user()->isFriendsWith($user))
        <p>You and {{$user->getFirstNameOrUsername() }} are friends</p>
        <form action="{{ route('friend.delete',['username' => $user->username]) }}" method="post">
          <input type="submit" value="Delete friend" class="btn btn-primary">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
        @elseif(Auth::user()->id !== $user->id)
        <a class="btn btn-primary" href="{{ route('friend.add',['username'=>$user->username]) }}">Add as friend </a>
        @endif

       	<h4> {{ $user->getFirstNameOrUsername()}}'s friends' </h4>

       	@if(!$user->friends()->count())
			     <p>{{$user->getFirstNameOrUsername() }} has no friends </p>
       	@else
    			@foreach ($user->friends() as $user)
    				@include('user.partials.userblock')
    			@endforeach
       	@endif
    </div>
</div>
@stop