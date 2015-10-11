@extends('layouts.default')

@section('content')
    <div class="jumbotron">
        <h1>Welcome to Larabook!</h1>

        <p>Welcome to the premier place to talk about Laravel with others. Why don't you sign up to see what all the fuss is about?</p>

        <a href='{{ url("auth/register/") }}' class='btn btn-large btn-primary'>Sign up!</a>


    </div>

    <div>



    </div>
@stop