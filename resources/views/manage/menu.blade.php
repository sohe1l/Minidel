@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Menu</li>
</ol>
@endsection


@section('content')



<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'menu'))
  </div>
  <div class="col-md-9">
  
    <h2>Menu Items<br>
        <small>Using our menu builder you can easily make your menu online.</small>
    </h2>

  @include('errors.list')


    <div class="row">
      <div class="col-sm-4">
        <?php /*
        <a href="/manage/{{$store->slug}}/menu/item/create" class="btn btn-default">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          New Menu Item
        </a>
        */ ?>
      </div><!-- /.col-lg-6 -->

    @if($store->type == 'Groceries')

      <div class="col-sm-8" style="text-align: right">
        {!! Form::open(array('url'=>'/manage/'.$store->slug.'/menu/section', 'class'=>'form-inline')) !!}
        <div class="form-group">
          {!! Form::select('parent', \App\menuSection::selectFromCollection($store->sections,true), null, ['placeholder' => 'Pick parent', 'class'=>'form-control']); !!}
        </div><!-- /input-group -->
        <div class="form-group">
          <input type="text" name="title" class="form-control" placeholder="New Menu Section">
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
        </div><!-- /input-group -->

        {!! Form::close() !!}
      </div><!-- /.col-lg-6 -->

    @else
      <div class="col-sm-2">
        &nbsp;
      </div><!-- /.col-lg-6 -->

      <div class="col-sm-6">
        {!! Form::open(array('url'=>'/manage/'.$store->slug.'/menu/section')) !!}
        <div class="input-group">
          <input type="text" name="title" class="form-control" placeholder="New Menu Section">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
          </span>
        </div><!-- /input-group -->
        {!! Form::close() !!}
      </div><!-- /.col-lg-6 -->
    @endif



    </div><!-- /.row -->

    <br>
 

    @forelse ($store->sections->where('menu_section_id',null)->sortBy('order') as $section)

          
      <div class="panel panel-default">
          <div class="panel-heading">
            {{ $section->title }}
            @include('manage._section-controls', array('s'=>$section))
          </div>
          <table class="table">
          @foreach ($section->items->sortBy('order') as $item)
            @include('manage._item')
          @endforeach

          @foreach ($section->subsections->sortBy('order') as $subsection)
          <tr>
            <td colspan="3">
              <b>{{ $subsection->title }}</b>
              @include('manage._section-controls', array('s'=>$subsection))
            </td>
          </tr>
            @foreach ($subsection->items->sortBy('order') as $item)
              @include('manage._item')
            @endforeach
          @endforeach

          </table>

           
      </div>
    @empty
      <h4 style="text-align: center">To begin add a new section to your menu!</h4>
    @endforelse















  </div>

</div>


<div id="insert">
</div>



@endsection

@section('footer')
  <script type="text/javascript">
      function deleteSection(section_id){
        var r = confirm("Deleting a section will delete all the items in it as well. Please confirm.");
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/menu/section/'+section_id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }

      function deleteItem(item_id){
        var r = confirm("Are you sure you want to delete this item ?");
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/menu/item/'+item_id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }

      function available(type, item_id,isAvailable){
        if(isAvailable == 1) var mes = "Are you sure you want to mark this item as available?";
        if(isAvailable == 0) var mes = "Are you sure you want to mark this item as unavailable?"; 
        var r = confirm(mes);
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/menu/'+type+'/'+item_id+'/available" method="post"><input type="hidden" name="_method" value="PUT" /><input type="hidden" name="available" value="'+isAvailable+'" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }
  </script>
@endsection



