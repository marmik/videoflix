<?php include 'header_browse.php';?>
<?php
	$series_details	=	$this->db->get_where('series' , array('series_id' => $series_id))->result_array();
	foreach ($series_details as $row):
	?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/hovercss/demo.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/frontend/' . $selected_theme;?>/hovercss/set1.css" />
<style>
	.movie_thumb{}
	.btn_opaque{font-size:20px; border: 1px solid #939393;text-decoration: none;margin: 10px;background-color: rgba(0, 0, 0, 0.74); color: #fff;}
	.btn_opaque:hover{border: 1px solid #939393;text-decoration: none;background-color: rgba(57, 57, 57, 0.74);color:#fff;}
	.video_cover {
	position: relative;padding-bottom: 30px;
	}
	.video_cover:after {
	content : "";
	display: block;
	position: absolute;
	top: 0;
	left: 0;
	background-image: url(<?php echo $this->crud_model->get_poster_url('series' , $row['series_id']);?>); 
	width: 100%;
	height: 100%;
	opacity : 0.2;
	z-index: -1;
	background-size:cover;
	}
	.select_black{background-color: #000;height: 45px;padding: 12px;font-weight: bold;color: #fff;}
	.profile_manage{font-size: 25px;border: 1px solid #ccc;padding: 5px 30px;text-decoration: none;}
	.profile_manage:hover{font-size: 25px;border: 1px solid #fff;padding: 5px 30px;text-decoration: none;color:#fff;}
</style>
<!-- VIDEO PLAYER -->
<div class="video_cover">
	<div class="container" style="padding-top:100px; text-align: center;">
		<div class="row">
			<div class="col-lg-12">
				<script src="https://content.jwplatform.com/libraries/O7BMTay5.js"></script>
				<div id="video_player_div"><?php echo $row['title'];?></div>
				<script>
					jwplayer("video_player_div").setup({
						playlist: [
						
						<?php
						$episodes	=	$this->crud_model->get_episodes_of_season($season_id);
						foreach ($episodes as $row2):
						?>
							{
								image	:	"<?php echo $this->crud_model->get_thumb_url('episode' , $row2['episode_id']);?>",
						  		file	:	"<?php echo $row2['url'];?>", 
								title	:	"<?php echo $row2['title'];?>"
							},
						<?php endforeach;?>
						],
						
						"width": "100%",
						aspectratio: "16:9",
						listbar: {
						  position: 'right',
						  size: 260
						},
					  });
					
					
					
				</script>
			</div>
		</div>
	</div>
</div>
<!-- VIDEO DETAILS HERE -->
<div class="container" style="margin-top: 30px;">
	<div class="row">
		<div class="col-lg-8">
			<div class="row">
				<div class="col-lg-2">
					<img src="<?php echo $this->crud_model->get_thumb_url('series' , $row['series_id']);?>" style="height: 60px; margin:20px;" />
				</div>
				<div class="col-lg-10">
					<!-- VIDEO TITLE -->
					<h3>
						<?php echo $row['title'];?>
					</h3>
					<!-- RATING CALCULATION -->
					<div>
						<?php
							for($i = 1 ; $i <= $row['rating'] ; $i++):
							?>
						<i class="fa fa-star" aria-hidden="true"></i>
						<?php endfor;?>
						<?php
							for($i = 5 ; $i > $row['rating'] ; $i--):
							?>
						<i class="fa fa-star-o" aria-hidden="true"></i>
						<?php endfor;?>
					</div>
				</div>
			</div>
		</div>
		<script>
			// submit the add/delete request for mylist
			// type = movie/series, task = add/delete, id = movie_id/series_id
			function process_list(type, task, id)
			{
				$.ajax({
					url: "<?php echo base_url();?>index.php?browse/process_list/" + type + "/" + task + "/" + id, 
					success: function(result){
			        //alert(result);
			        if (task == 'add')
			        {
			        	$("#mylist_button_holder").html( $("#mylist_delete_button").html() );
			        }
			        else if (task == 'delete')
			        {
			        	$("#mylist_button_holder").html( $("#mylist_add_button").html() );
			        }
			    }});
			}
			
			// Show the add/delete wishlist button on page load
			   $( document ).ready(function() {
			
			   	// Checking if this movie_id exist in the active user's wishlist
			    mylist_exist_status = "<?php echo $this->crud_model->get_mylist_exist_status('series' , $row['series_id']);?>";
			
			    if (mylist_exist_status == 'true')
			    {
			    	$("#mylist_button_holder").html( $("#mylist_delete_button").html() );
			    }
			    else if (mylist_exist_status == 'false')
			    {
			    	$("#mylist_button_holder").html( $("#mylist_add_button").html() );
			    }
			});
		</script>
		<div class="col-lg-4">
			<!-- ADD OR DELETE FROM PLAYLIST -->
			<span id="mylist_button_holder">
			</span>
			<span id="mylist_add_button" style="display:none;">
			<a href="#" class="btn btn-danger btn-md" style="font-size: 16px; margin-top: 20px;" 
				onclick="process_list('series' , 'add', <?php echo $row['series_id'];?>)"> 
			<i class="fa fa-plus"></i> Add to My list
			</a>
			</span>
			<span id="mylist_delete_button" style="display:none;">
			<a href="#" class="btn btn-default btn-md" style="font-size: 16px; margin-top: 20px;" 
				onclick="process_list('series' , 'delete', <?php echo $row['series_id'];?>)"> 
			<i class="fa fa-check"></i> Added to My list
			</a>
			</span>
			<!-- MOVIE GENRE -->
			<div style="margin-top: 10px;">
				<strong>Genre</strong> : 
				<a href="<?php echo base_url();?>index.php?browse/series/<?php echo $row['genre_id'];?>">
				<?php echo $this->db->get_where('genre',array('genre_id'=>$row['genre_id']))->row()->name;?>
				</a>
			</div>
			<!-- MOVIE YEAR -->
			<div>
				<strong>Year</strong> : <?php echo $row['year'];?>
			</div>
		</div>
	</div>
	<div class="row" style="margin-top:20px;">
		<div class="col-lg-12">
			<div class="bs-component">
				<ul class="nav nav-tabs">
					<li style="width:25%;">
						<a href="#about" data-toggle="tab">
						About
						</a>
					</li>
					<li class="active" style="width:25%;">
						<a href="#episode" data-toggle="tab">
						Episode
						</a>
					</li>
					<li style="width:25%;">
						<a href="#cast" data-toggle="tab">
						Cast
						</a>
					</li>
					<li style="width:25%;">
						<a href="#more" data-toggle="tab">
						More
						</a>
					</li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<!-- TAB FOR DESCRIPTION -->
					<div class="tab-pane" id="about">
						<p>
							<?php echo $row['description_long'];?>
						</p>
					</div>
					<!-- TAB FOR EPISODES -->
					<div class="tab-pane active in" id="episode">
						<p>
						<div class="btn-group">
							<div class="btn-group">
								<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<?php echo $this->db->get_where('season', array('season_id'=>$season_id))->row()->name;?>
								<span class="caret"></span>
								</a>
								<ul class="dropdown-menu" style="z-index: 10000;">
									<?php
										$seasons	=	$this->db->get_where('season', array('series_id'=>$series_id))->result_array();
										foreach ($seasons as $row2):
										?>
									<li><a href="<?php echo base_url();?>index.php?browse/playseries/<?php echo $series_id.'/'.$row2['season_id'];?>">
										<?php echo $row2['name'];?></a>
									</li>
									<?php
										endforeach;?>
								</ul>
							</div>
						</div>
						<div class="row">
							<?php
								$counter	=	0;
								$episodes	=	$this->crud_model->get_episodes_of_season($season_id);
								foreach ($episodes as $row2):
								?>
							<div class="col-md-3">
								<a href="#" onclick="jwplayer().playlistItem(<?php echo $counter++;?>)">
								<img src="<?php echo $this->crud_model->get_thumb_url('episode' , $row2['episode_id']);?>" 
									style="height: 150px; margin-top:10px;" /></a>
								<br>
								<h5><?php echo $row2['title'];?></h5>
							</div>
							<?php endforeach;?>
						</div>
						</p>
					</div>
					<!-- TAB FOR ACTORS -->
					<div class="tab-pane " id="cast">
						<p>
							<?php
								$actors	=	json_decode($row['actors']);
								for($i = 0; $i< sizeof($actors) ; $i++)
								{
									?>
						<div style="float: left; text-align:center; color: #fff; font-weight: bold;">
							<img src="<?php echo $this->crud_model->get_actor_image_url($actors[$i]);?>" 
								style="height: 160px; margin:5px;" />
							<br>
							<?php echo $this->db->get_where('actor', array('actor_id'=>$actors[$i]))->row()->name;?>
						</div>
						<?php
							}
							?>
						</p>
					</div>
					<!-- TAB FOR SAME CATEGORY MOVIES -->
					<div class="tab-pane  " id="more">
						<p>
						<div class="content">
							<div class="grid">
								<?php 
									$series = $this->crud_model->get_series($row['genre_id'] , 10, 0);
									foreach ($series as $row)
									{
										$title	=	$row['title'];
										$link	=	base_url().'index.php?browse/playseries/'.$row['series_id'];
										$thumb	=	$this->crud_model->get_thumb_url('series' , $row['series_id']);
										include 'thumb.php';
									}
									?>
							</div>
						</div>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr style="border-top:1px solid #333;">
	<?php include 'footer.php';?>
</div>
<?php endforeach;?>