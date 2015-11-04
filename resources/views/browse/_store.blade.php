<div class="listing storeListing clearfix" v-repeat="{{$vRepeat}}">
    <div class="col-sm-2">
        <img v-attr="src: logo?'/img/logo/'+logo:'/img/logo/placeholder.svg'" class="img-responsive hidden-xs">
        <img v-attr="src: cover?'/img/cover/'+cover:'/img/cover/placeholder.svg'" class="img-responsive visible-xs">
    </div>
    <div class="col-sm-10">
        <div class="title"><a href="/@{{slug}}/order">@{{ name }}</a></div>
        <div>
            <span class="label label-success">@{{ is_open == 'true'?'Open Now':''  }}</span>
            <span class="label label-danger">@{{ is_open == 'false'?'Closed Now':''  }}</span>
        <span class="label label-success">@{{ (is_deliver_building == 'true' || is_deliver_area == 'true')?'Deliveres Now':''  }}</span>
            <span v-if="pivot.min && pivot.min!=0" class="label label-info">Minimum Delivery @{{ pivot.min }}</span>
            <span v-if="pivot.fee && pivot.fee!=0" class="label label-info">Delivery Fee @{{ pivot.fee }}</span>
            <span v-if="pivot.feebelowmin && pivot.feebelowmin!=0" class="label label-info">Delivery Free (below minimum) @{{ pivot.feebelowmin }}</span>
            <span v-if="pivot.discount && pivot.discount!=0"  class="label label-warning">@{{ pivot.discount }}% Discount</span>
        </div>
        <div>@{{ info }}</div>
    </div>
</div>