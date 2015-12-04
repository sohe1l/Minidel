<tr>
  <td style="width:100px"><img src="/img/item-thumb/{{ $item->photo or 'placeholder.svg' }}" class="img-thumbnail"></td>
  <td>
    <div>
    @if($item->available)
      <a href="javascript:available('item', {{ $item->id }},0)" title="Currenly Available. Click to mark as unavailable.">
        <span style="color:#93C942" class="glyphicon glyphicon-ok-sign"></span>
      </a>
    @else
      <a href="javascript:available('item', {{ $item->id }},1)" title="Currenly Unavailable. Click to mark as available.">
        <span style="color:#C94242" class="glyphicon glyphicon-info-sign"></span>
      </a>
    @endif
    &nbsp;
    <b>{{ $item->title }}</b> - {{ $item->price }}


    </div>

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