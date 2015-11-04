@extends('layouts.default')

@section('content')




<div class="row">
    <div class="col-xs-4" style="text-align: center">
        <img src="/img/user/{{ $user->dp or 'placeholder.svg' }}" class="img-thumbnail">
    </div>
    <div class="col-xs-8">
        <br><br>
        <h2 style="margin-top: 15px !important;">{{ $user->name }}</h2>
        <div>
            0 Photos &nbsp;&nbsp;&nbsp;&nbsp;
            0 Orders &nbsp;&nbsp;&nbsp;&nbsp;
            0 Checkins &nbsp;&nbsp;&nbsp;&nbsp;

        </div>
    </div>
</div>

<div class="row">

    <div class="col-xs-12 col-sm-7 col-md-8 col-lg-8">
        @foreach($user->photos()->where('imageable_type','App\Store')->where('type','general')->get() as $photo)
            <div class="col-xs-4 col-sm-4 col-md-3 col-xl-3">
              <a href="/img/store/{{$photo->path}}" class="thumbnail" data-lity><img src="/img/store-thumb/{{$photo->path}}" alt="...">
                <div style="text-align: center; color:black; font-size: 80%">{{ $photo->text }}</div>
              </a>
            </div>
        @endforeach
    </div>
</div>





@endsection
@section('footer')

@endsection