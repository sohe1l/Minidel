<span style="float:right">


  @if($store->type != 'Groceries' || $s->menu_section_id != null)
    <a href="/manage/{{$store->slug}}/menu/section/{{ $s->id }}/item/create">
      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    </a>
    &nbsp;&nbsp;&nbsp;
  @endif

  <a href="/manage/{{$store->slug}}/menu/section/{{ $s->id }}/up" title="Move up">
      <span class="glyphicon glyphicon-arrow-up"></span>
  </a>

  &nbsp;&nbsp;&nbsp;

  <a href="/manage/{{$store->slug}}/menu/section/{{ $s->id }}/down" title="Move down">
    <span class="glyphicon glyphicon-arrow-down"></span>
  </a>

  &nbsp;&nbsp;&nbsp;

  <a href="javascript:deleteSection({{ $s->id }})">
    <span class="glyphicon glyphicon-remove"></span>
  </a>

    &nbsp;&nbsp;&nbsp;


  @if($s->available)
    <a href="javascript:available('section', {{ $s->id }},0)" title="Currenly Available. Click to mark as unavailable.">
      <span style="color:#93C942" class="glyphicon glyphicon-ok-sign"></span>
    </a>
  @else
    <a href="javascript:available('section', {{ $s->id }},1)" title="Currenly Unavailable. Click to mark as available.">
      <span style="color:#C94242" class="glyphicon glyphicon-info-sign"></span>
    </a>
  @endif

</span>