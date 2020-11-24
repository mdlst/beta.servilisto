<?php 
$ae_users   = AE_Users::get_instance();
$user = $ae_users->current_user;
$location = __('Earth', ET_DOMAIN);
if(isset($user->location) && $user->location !== ''){
    $location = $user->location;
}
?>
<li class="user-list-item">
	<div class="row">
		<div class="col-md-6 col-xs-6">
        	<a href="<?php echo $user->author_url; ?>" class="list-user-page-avatar"><?php echo $user->avatar; ?></a>
            <div class="info-name-user">
            	<a href="<?php echo $user->author_url; ?>" class="name"><?php echo $user->display_name; ?></a>
                <span class="location-user"><i class="fa fa-map-marker"></i><?php echo $location; ?></span>
            </div>
        </div>
        <div class="col-md-6 col-xs-6">
        	<ul class="list-item-place-user">
            <?php if(isset($user->place_list) && count($user->place_list)!= 0){
                $i = 0;
                foreach ($user->place_list as $key => $value) {
                    if($i== 3 ){ 
                ?>
                        <li><a href="<?php echo $user->author_url; ?>" class="last-item-place-user"><?php echo count($user->place_list) - 3 ;?>+</li>
                <?php 
                    break;
                    }else{ ?>
                    <li><a href="<?php echo $value->permalink; ?>"><img src="<?php if($value->the_post_thumnail){ echo $value->the_post_thumnail;} ?>"></a></li>
                <?php 
                    }       
                    $i++;
                }
                }else{
                        _e("There's no place", ET_DOMAIN);
                } ?>
            </ul>
        </div>
    </div>
</li>