@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li><a href="/manage/{{$store->slug}}/menu">Menu</a></li>
  <li class="active">Edit Menu Item</li>
</ol>
@endsection

@section('content')



<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'menu'))
  </div>
  <div class="col-md-9">
    <h3>Edit Menu Item <br> <small>Use the following form to edit a menu item.</small></h3>

                    @include('errors.list')

                    {!! Form::model($item, array('class'=>'form-horizontal', 'files' => true,
                            'url' => '/manage/'.$store->slug.'/menu/item/'.$item->id )) !!}

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Section</label>
                            <div class="col-md-6">
                                <select name="menu_section_id" class="form-control">
                                    @if($store->type == 'Groceries')
                                        @foreach ($store->sections->where('menu_section_id',null)->sortBy('order') as $section)
                                            <option value="{{ $section->id }}" disabled>{{ $section->title }}</option>
                                            @foreach ($section->subsections->sortBy('order') as $sub)
                                                <option value="{{ $sub->id }}" {{ ($item->menu_section_id==$sub->id)?'selected':''  }}>{{ $sub->title }}</option>
                                            @endforeach
                                        @endforeach

                                    @else
                                        @foreach ($store->sections->where('menu_section_id',null)->sortBy('order') as $section)
                                        <option value="{{ $section->id }}" {{ ($item->menu_section_id==$section->id)?'selected':''  }}>{{ $section->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
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
                            <label class="col-md-4 control-label">Tags</label>
                            <div class="col-md-6">
                                {!! Form::select('tags[]', \App\itemTag::tagList($store->type), $item->tagsSelectedList(), ['id'=>'tags_select', 'class'=>'form-control', 'multiple']) !!}
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




@endsection

@section('footer')
    <script type="text/javascript">
        $("#options_select").select2();
        $("#tags_select").select2();
    </script>
@endsection