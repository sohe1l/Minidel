<div class="listing storeListing clearfix" v-for="{{$vRepeat}}">
    <div class="col-sm-2">
      <a href="/@{{s.slug}}/order">
        <img v-bind:src="s.logo?'/img/logo/'+s.logo:'/img/logo/placeholder.svg'" class="img-responsive hidden-xs">
        <img v-bind:src="s.cover?'/img/cover/'+s.cover:'/img/cover/placeholder.svg'" class="img-responsive visible-xs">
      </a>
    </div>
    <div class="col-sm-10">
        <div class="title"><a href="/@{{s.slug}}/order">@{{ s.name }}</a></div>
        <div>
            <span class="label label-success">@{{ (selected.type == 'pickup' && s.is_open == 'true')?'Open Now':''  }}</span>
            <span class="label label-danger">@{{ (selected.type == 'pickup' && s.is_open == 'false')?'Closed Now':''  }}</span>

            <span class="label label-success">@{{ (selected.type == 'mini' && s.is_deliver_building == 'true' )?'Delivers Now':''  }}</span>

            <span class="label label-success">@{{ (selected.type == 'delivery' && s.is_deliver_area == 'true' )?'Delivers Now':''  }}</span>

            <span v-if="s.pivot.min && s.pivot.min!=0" class="label label-info">Minimum Delivery @{{ s.pivot.min }}</span>
            <span v-if="s.pivot.fee && s.pivot.fee!=0" class="label label-info">Delivery Fee @{{ s.pivot.fee }}</span>
            <span v-if="s.pivot.feebelowmin && s.pivot.feebelowmin!=0" class="label label-info">Delivery Free (below minimum) @{{ s.pivot.feebelowmin }}</span>
            <span v-if="s.pivot.discount && s.pivot.discount!=0"  class="label label-warning">@{{ s.pivot.discount }}% Discount</span>
        </div>
        <div v-if="s.info">@{{ s.info.substr(0, 150) }}@{{ (s.info.length>150)?'...':'' }} </div>
    </div>
</div>