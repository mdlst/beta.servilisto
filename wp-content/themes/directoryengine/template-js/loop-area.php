<script type="text/template" id="ae-area-loop">
    <div class="area-wrapper">
        <a href="{{= link }}"><img src="{{= image }}" alt="Imagen"></a>
        <div class="area-info">
            <h2>{{= name }}</h2>
                <# if(show_count == '1'){ #>
                <span class="place-number">{{= count }} Places</span>    
                <# } #>        
        </div>
    </div>
</script>