<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
/*
|---------------------------------------------------------------
| LOAD THE GLOBAL FUNCTIONS
|---------------------------------------------------------------
*/
require_once('common.php');

/*
|---------------------------------------------------------------
| INSTANTIATE THE DATABASE CLASS
|---------------------------------------------------------------
*/
$db = new Database();

/*
|---------------------------------------------------------------
| INSTANTIATE THE ERROR CLASS
|---------------------------------------------------------------
*/
$error = new Error();
?>
<html>
	<head>
        	<meta charset="utf-8">
        	<meta name="viewport" content="width=device-width, initial-scale=1">
        	<title>viewMotion</title>
        	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  		<link href="style.css" type="text/css" rel="stylesheet" />
  		<link rel="stylesheet" href="jquery-ui-themes-1.12.1/themes/dark-hive/theme.css">
  		<link rel="stylesheet" href="jquery-ui-themes-1.12.1/themes/dark-hive/jquery-ui.min.css">
  		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script src="/build/heatmap.js"></script>
  		<script src="app.js"></script>
  		<script src="swfobject.js"></script>
	</head>
	<body>
		<div class="foreground" id="data_block"></div>
        	<div class="content">
                	<div class="background block_1">
                </div>
                <div class="left_block block_1">
                        <div class="content">
                            <div class="dataOutput">
                                        <div class="content">
	                                	<div id="datepicker"></div>
						<fieldset>
							<legend>Image</legend>
							<div id="toggle_visible_image" class="button ui-state-active">Visibility</div></br>
							<span class='section_name'>Opacity</span>
							<div id="slider_opacity_image">
							<div id="custom-handle_opacity_image" class="ui-slider-handle"></div>
						</div>
						</fieldset>

						<fieldset>
							<legend>Heatmap</legend>
							<div id="toggle_visible_heatmap" class="button ui-state-active">Visibility</div></br>
							<span class='section_name'>Opacity</span>
							<div id="slider_opacity_heatmap">
								<div id="custom-handle_opacity_heatmap" class="ui-slider-handle"></div>
							</div>
							<span class='section_name'>Color Max</span>
							<div id="slider">
								<div id="custom-handle_heatmap_colormax" class="ui-slider-handle"></div>
							</div>
						</fieldset>
						<fieldset>
							<legend>Capture</legend>
							<div id="toggle_visible_capture" class="button ui-state-active">Group</div>
							<div id="toggle_visible_select_capture" class="button ui-state-active">Select</div></br>
							<span class='section_name'>Opacity - Group</span>
							<div id="slider_opacity_capture">
								<div id="custom-handle_opacity_capture" class="ui-slider-handle"></div>
							</div>
							<span class='section_name'>Opacity - Select</span>
							<div id="slider_opacity_capture_select">
								<div id="custom-handle_opacity_capture_select" class="ui-slider-handle"></div>
							</div>
							<span class='section_name'>Highlight Type</span>
							<div id="slider_highlight_type">
								<div id="custom-handle_highlight_type" class="ui-slider-handle"></div>
					   		</div>
						</fieldset>
						<div class='year_chart'>
                                                <img src="MDD.php?type=2&year=2017&mult=2">
                                                <img src="MDD.php?type=2&year=2016&mult=2">
                                            </div>
                                        </div>
                            </div>
                        </div>
                </div>
                <div class="background block_2"></div>
                <div class="right_block block_2">
                        <div class="content">
				<div id="image_output">
                                	<div class="image_layer"></div>
                                	<div class="movie_layer" id="movie_layer"></div>
                                	<div class="heatmap"></div>
                                	<div class="capturemap_layer">
						<canvas id="capturemap" width="640" height="480"></div>
					</div>
                                	<div class="selection_layer">
						<canvas id="select_map" width="640" height="480"></div>
					</div>
				</div>
                                	<div class="bottom_block block_4"></div>
                        </div>
		</div>
</body>
</html>

