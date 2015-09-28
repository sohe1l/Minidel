@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

        <h2>Edit a menu option <br>
            <small>Options can attach to your menu items to [allow your customers to select different options such as sizes, topppings, and etc...</small>
        </h2>


            <div class="panel panel-default">
                <div class="panel-heading">Create new menu option</div>
                <div class="panel-body">

                    @include('errors.list')

                    <form class="form-horizontal" role="form" method="POST" action="/manage/{{ $store->slug }}/options/{{$option->id}}/">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Option Group Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ $option->name }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Min selection</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="min" value="{{ $option->min }}">
                            </div>

                            <div class="col-md-4">
                                enter 0 if optional 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Max selection</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="max" value="{{ $option->max }}">
                            </div>

                            <div class="col-md-4">
                                enter 0 if optional 
                            </div>
                        </div>

                        <hr>

                        

                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4"><b>Option Title</b></div>
                            <div class="col-md-2"><b>Extra Charge</b></div>
                        </div>


                        <?php
                            $items = array();
                            $prices = array();
                            $i=1;
                            foreach($option->options as $item){
                                $items[$i] = $item->name;
                                $prices[$i] = $item->price;
                                $i ++;
                            }
                        ?>

                        @for ($i = 1; $i<21; $i++)

                        <div class="form-group">
                            <label class="col-md-4 control-label">Option {{$i}}</label>
                            <div class="col-md-4">
                                <input type="text" placeholder="name" class="form-control" name="name{{$i}}" value="{{ $items[$i] or old('name'.$i) }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" placeholder="price = 0" class="form-control" name="price{{$i}}" value="{{ $prices[$i]  or old('price'.$i) }}">
                            </div>
                        </div>

                        @endfor














                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="float:right">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection

@section('footer')
@endsection