 @if($item->available) 
  <tr>
    <td style="width:100px"><img src="/img/menu/{{ $item->photo or 'placeholder.svg' }}" class="img-thumbnail"></td>
    <td>
      <b>{{ $item->title }}</b> - {{ $item->price }}
      <span v-on="click: addItem({{ $item->id }})" title="Add" style="float:right">
        <span v-if="quans[{{$item->id}}]" class="badge" style="background-color: #f0ad4e">
          {{ " {"."{". "quans[".$item->id  ."] }"."} " }}
        </span>
        &nbsp;
        <span class="glyphicon glyphicon-plus" style="color:#f0ad4e"></span>
      </span>
      <br>
      {{ $item->info }}
    </td>
  </tr>
@endif