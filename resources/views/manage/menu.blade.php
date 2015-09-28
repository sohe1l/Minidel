@extends('layouts.default')

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
      <div class="col-lg-6">
        <a href="/manage/{{$store->slug}}/menu/item/create" class="btn btn-default">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          New Menu Item
        </a>
      </div><!-- /.col-lg-6 -->

      <div class="col-lg-6">
        {!! Form::open(array('url'=>'/manage/'.$store->slug.'/menu/section')) !!}
        <div class="input-group">
          <input type="text" name="title" class="form-control" placeholder="New Menu Section">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
          </span>
        </div><!-- /input-group -->
        {!! Form::close() !!}
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->

    <br>
 
    @forelse ($store->sections as $section)

          
                    <div class="panel panel-default">
                        <div class="panel-heading">
                          {{ $section->title }}
                          <a href="javascript:deleteSection({{ $section->id }})">
                            <span class="glyphicon glyphicon-remove" style="float:right"></span>
                          </a>
                        </div>
                        <table class="table">
                        @forelse ($section->items->sortBy('order') as $item)
                          <tr>
                            <td style="width:100px"><img src="/img/menu/{{ $item->photo or 'placeholder.svg' }}" class="img-thumbnail"></td>
                            <td>
                              <b>{{ $item->title }}</b> - {{ $item->price }} - <h1>{{ $item->section }}</h1> <br>
                              {{ $item->info }}
                            </td>
                            <td style="width:100px">


                              <a href="/manage/{{$store->slug}}/menu/item/{{ $item->id }}/edit" title="Edit">
                                <span class="glyphicon glyphicon-pencil"></span>
                              </a>

                              &nbsp;&nbsp;&nbsp;

                              <a href="/manage/{{$store->slug}}/menu/item/{{ $item->id }}/up" title="Move up">
                                <span class="glyphicon glyphicon-arrow-up"></span>
                              </a>
                              
                              
                              

                              <br><br>

                              <a href="javascript:deleteItem({{ $item->id }})" title="Delete">
                                <span class="glyphicon glyphicon-remove"></span>
                              </a>
                              &nbsp;&nbsp;&nbsp;
                              <a href="/manage/{{$store->slug}}/menu/item/{{ $item->id }}/down" title="Move down">
                                <span class="glyphicon glyphicon-arrow-down"></span>
                              </a>
                              
                              
                            </td>
                          </tr>
                        @empty
                          <tr><td>No menu items in this section!</td></tr>
                        @endforelse

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
  </script>

@endsection



