<span style="float:right">



  <a href="/manage/{{$store->slug}}/menu/section/{{ $section_id }}/up" title="Move up">
      <span class="glyphicon glyphicon-arrow-up"></span>
  </a>

  &nbsp;&nbsp;&nbsp;

  <a href="/manage/{{$store->slug}}/menu/section/{{ $section_id }}/down" title="Move down">
    <span class="glyphicon glyphicon-arrow-down"></span>
  </a>

  &nbsp;&nbsp;&nbsp;

  <a href="javascript:deleteSection({{ $section_id }})">
    <span class="glyphicon glyphicon-remove"></span>
  </a>

    &nbsp;&nbsp;&nbsp;


  @if($available)
    <a href="javascript:available('section', {{ $section_id }},0)" title="Currenly Available. Click to mark as unavailable.">
      <span style="color:#93C942" class="glyphicon glyphicon-ok-sign"></span>
    </a>
  @else
    <a href="javascript:available('section', {{ $section_id }},1)" title="Currenly Unavailable. Click to mark as available.">
      <span style="color:#C94242" class="glyphicon glyphicon-info-sign"></span>
    </a>
  @endif

</span>