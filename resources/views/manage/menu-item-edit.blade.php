@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New menu item</div>
                <div class="panel-body">

                    @include('errors.list')

                    {!! Form::model($item, array('class'=>'form-horizontal', 'files' => true,
                            'url' => '/manage/'.$store->slug.'/menu/item/'.$item->id )) !!}

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Section</label>
                            <div class="col-md-6">
                                {!! Form::select('menu_section_id', $sectionList, null, $attributes=array('class'=>'form-control') );  !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Title</label>
                            <div class="col-md-6">
                                {!! Form::text('title' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Info</label>
                            <div class="col-md-6">
                                {!! Form::textarea('info' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Price</label>
                            <div class="col-md-6">
                                {!! Form::text('price' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Options</label>
                            <div class="col-md-6">
                                {!! Form::select('options[]', $store->optionsList(), $item->optionsSelectedList(),['id'=>'options_select', 'class'=>'form-control', 'multiple']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Image</label>
                            <div class="col-md-6">
                                {!! Form::file('imagefile', $attributes=array('class'=>'')); !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                {!! Form::submit('Update Information!', $attributes=array('class'=>'btn btn-primary')); !!}
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
    <script type="text/javascript">
        $("#options_select").select2();
    </script>
@endsection