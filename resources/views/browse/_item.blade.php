 @if($item->available) 
  <tr>
    <td>
      @if($item->photo)
        <a href="/img/item/{{$item->photo}}" data-lity>
          <img src="/img/item-thumb/{{$item->photo}}" class="img-thumbnail pull-left itemPhoto">
        </a>
      @endif
      <span style="font-size:1.1em; font-weight:bold;">{{ $item->title }}</span> - {{ $item->price }}
      <span v-on:click="addItem({{ $item->id }})" title="Add" style="float:right;">
        <span v-if="quans[{{$item->id}}]" class="badge" style="background-color: #fe602c; margin-top: -5px; font-size:1.2em">
          {{ " {"."{". "quans[".$item->id  ."] }"."} " }}
        </span>
        &nbsp;
        <span class="glyphicon glyphicon-plus" style="color:#fe602c; margin-top: 5px; font-size:1.2em"></span>
      </span>
      <br>
      {{ $item->info }}
    </td>
  </tr>
@endif