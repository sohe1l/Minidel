@extends('layouts.default')

@section('content')

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li><a href="/manage/{{$store->slug}}/menu">Menu</a></li>
  <li class="active">New Menu Item</li>
</ol>

<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'menu'))
  </div>
  <div class="col-md-9">
    <h3>New Menu Item <br> <small>Use the following form to add a new Item to your menu.</small></h3>



                 
                    @include('errors.list')

                    {!! Form::open(['class'=>'form-horizontal', 'files' => true,
                            'url' => '/manage/'.$store->slug.'/menu/item/']) !!}

                        <div class="form-group">
                            <label class="col-md-4 control-label">Section</label>
                            <div class="col-md-6">
                                <select name="menu_section_id" class="form-control">
                                    @foreach ($store->sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Title</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Info</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="info">{{ old('info') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Price</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="price" value="{{ old('price') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Options</label>
                            <div class="col-md-6">
                                {!! Form::select('options[]', $store->optionsList(), null, ['id'=>'options_select', 'class'=>'form-control', 'multiple']) !!}
                            
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
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    {!! form::close() !!}

    </div>
</div>


@endsection

@section('footer')
    <script type="text/javascript">
        $("#options_select").select2();
    </script>
@endsection