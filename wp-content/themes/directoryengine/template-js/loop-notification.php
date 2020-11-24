<script type="text/template" id="ae-place-notification">

    <div class="pending-place-wrapper">
        <a href="{{=permalink }}" class="img-place">
            <img src="{{=the_post_thumnail}}" alt="{{=post_title}}" />
        </a>
        <div class="pending-place-detail">
            <h2 class="title-pending-place">
                <a href="{{=permalink }}" title="{{=post_title}}">{{=post_title}}</a>
            </h2>
            <p class="address-pending-place"><i class="fa fa-map-marker"></i>{{=et_full_location}}</p>
            <p class="desc-pending-place">{{=trim_post_content}}</p>
        </div>
        <div class="action-pending-place">
            <span class="status-pending-place action">{{=paid_status}}</span>
            <span class="enable-pending-place action" data-action="approve"><i class="fa fa-check"></i></span>
            <span class="disable-pending-place action" data-action="reject"><i class="fa fa-times"></i></span>
        </div>
    </div>

</script>