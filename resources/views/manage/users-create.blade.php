@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

        <h2>Create new user<br>
            <small>During the specified timing the customers can place orders.</small>
        </h2>


            <div class="panel panel-default">
                <div class="panel-heading">Create new menu option</div>
                <div class="panel-body">

                    @include('errors.list')

                    {!! Form::open(array('url' => '/manage/'.$store->slug.'/users', 'class'=>'form-horizontal' ) ) !!}

                        <div class="form-group">
                            <label class="col-md-4 control-label">Wrok Mode</label>
                            <div class="col-md-6">
                                {!! Form::select('role_id', $roles_list, null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">User Email</label>
                            <div class="col-md-6">
                                {!! Form::text('email', null, $attributes=array('class'=>'form-control')); !!}
                            </div>
                        </div>

            
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="float:right">
                                    Create
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>




@endsection

@section('footer')
@endsection