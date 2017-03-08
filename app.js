//heatmap global
var heatmapInstance;
var capturemapInstance;
var selectionmapInstance;
var ctx;
var dataPacket;
var activeID = -1;

//Initialize Datepicker when the document finishes loading
$(document).ready(function(){
	capturemapInstance = document.getElementById('capturemap').getContext('2d');
	selectionmapInstance = document.getElementById('select_map').getContext('2d');
	ctx = document.getElementById('capturemap').getContext('2d');
//	ctx.shadowColor = "rgba(0, 0, 0, 0.55)";
//capturemapInstance = {
//  container: document.querySelector('.capturemap_layer')
//};

//capturemapInstance.getContext('2d');
//console.log(capturemapInstance);

// heatmap instance
heatmapInstance = h337.create({
  container: document.querySelector('.heatmap')
});//heatmap

// capturemap instance
//capturemapInstance = h337.create({
//  container: document.querySelector('.capturemap')
//});//capturemap


//Slider for the 'Color Max' control
var handle = $( "#custom-handle_heatmap_colormax" );
    $( "#slider" ).slider({
      range: "max",
      max: 80,
      value: 30,
      create: function() {
        handle.text( $( this ).slider( "value" ) );
      },
      slide: function( event, ui ) {
        handle.text( ui.value );
	console.log("Color Max: " + ui.value);
//	updateheatmap(ui.value);
var color_max_value = $("#custom-handle_heatmap_colormax").html();
updateDataPacket();
updateheatmap(color_max_value);

      }
    });

//Slider for the 'Highlight Type' control
var handle_highlight_type = $( "#custom-handle_highlight_type" );
    $( "#slider_highlight_type" ).slider({
      range: "max",
      max: 3,
      min: 1,
      value: 1,
      create: function() {
        handle_highlight_type.text( $( this ).slider( "value" ) );
      },
      slide: function( event, ui ) {
        handle_highlight_type.text( ui.value );
	console.log("HT: Type ui.value: " + ui.value);
	drawCaptureMap();
//	updateDataPacket(ui.value);
//	updateheatmap(ui.value);
      }
    });

//Slider for the 'Opacity' control
var heat_opacity_value = 1;
var handle_opacity_heatmap = $( "#custom-handle_opacity_heatmap" );
    $( "#slider_opacity_heatmap" ).slider({
      range: "max",
      max: 100,
      value: 100,
      create: function() {
        handle_opacity_heatmap.text( $( this ).slider( "value" ) );
      },
      slide: function( event, ui ) {
        handle_opacity_heatmap.text( ui.value );
	heat_opacity_value = ui.value / 100;
	$( ".heatmap").css({'opacity': heat_opacity_value});
      }
    });

//Slider for the Capture 'Opacity' control
var capture_opacity_value = 1;
var handle_capture_opacity = $( "#custom-handle_opacity_capture" );
    $( "#slider_opacity_capture" ).slider({
      range: "max",
      max: 30,
      value: 7,
      create: function() {
        handle_capture_opacity.text( $( this ).slider( "value" ) );
      },
      slide: function( event, ui ) {
        handle_capture_opacity.text( ui.value );
	capture_opacity_value = ui.value / 100;
	drawCaptureMap();
//	$( ".heatmap").css({'opacity': heat_opacity_value});
      }
    });

//Slider for the Capture 'Opacity' control
var capture_select_opacity_value = 1;
var handle_capture_select_opacity = $( "#custom-handle_opacity_capture_select" );
    $( "#slider_opacity_capture_select" ).slider({
      range: "max",
      max: 60,
      value: 20,
      create: function() {
        handle_capture_select_opacity.text( $( this ).slider( "value" ) );
      },
      slide: function( event, ui ) {
        handle_capture_select_opacity.text( ui.value );
	capture_select_opacity_value = ui.value / 100;
	drawSelection();
//	$( ".heatmap").css({'opacity': heat_opacity_value});
      }
    });

//Slider for the Image 'Opacity' control
var image_opacity_value = 1;
var handle_image_opacity = $( "#custom-handle_opacity_image" );
    $( "#slider_opacity_image" ).slider({
      range: "max",
      max: 100,
      value: 100,
      create: function() {
        handle_image_opacity.text( $( this ).slider( "value" ) );
      },
      slide: function( event, ui ) {
        handle_image_opacity.text( ui.value );
	image_opacity_value = ui.value / 100;
	$( ".image_layer").css({'opacity': image_opacity_value});
      }
    });

//Set button class
$( ".button" ).button();

//heatmap toggle action
$( "#toggle_visible_heatmap" ).click(function() {
      $( ".heatmap" ).toggleClass( "hidden_heatmap");
      $(this).toggleClass( "ui-state-active");
    });

//capturemap toggle action
$( "#toggle_visible_capture" ).click(function() {
      $( "#capturemap" ).toggleClass( "hidden_heatmap");
      $(this).toggleClass( "ui-state-active");
    });

//capturemap toggle action
$( "#toggle_visible_select_capture" ).click(function() {
      $( "#select_map" ).toggleClass( "hidden_heatmap");
      $(this).toggleClass( "ui-state-active");
    });

//image toggle action
$( "#toggle_visible_image" ).click(function() {
      $( ".image_layer" ).toggleClass( "hidden_heatmap");
      $(this).toggleClass( "ui-state-active");
    });

//initialize date picker
 $( "#datepicker" ).datepicker({
      onSelect: function(date) {
            updateData(date);
        },
      altField: "#alternate",
      altFormat: "yy-mm-dd",
      maxDate: "+0D",
      minDate:(new Date(2016, 9, 19))
    });//datepicker

 //Load the initial information for the current date
 updateData(new Date());

updateDataPacket();
// updateheatmap(30);
});//document_ready

function updateDataPacket(){

var points = [];
var max = 30;
var width = 640;
var height = 480;

$( "tr.motionRow" ).each(function( index) {
 $this = $(this)
  var row_id = $this.find(".data_event_number").html();
  var row_value = $this.find(".data_changed_pixels").html();
  var row_x = $this.find("td.data_motion_x").html();
  var row_y = $this.find(".data_motion_y").html();
  var motion_width = $this.find(".data_motion_width").html();
  var motion_height = $this.find(".data_motion_height").html();

var radius = 1;
radius = Math.max(motion_width, motion_height)/2;

  var point = {
    id: row_id,
    x: row_x,
    y: row_y,
    width: motion_width,
    height: motion_height,
    value: 10,
    // radius configuration on point basis
    radius: radius
  };
  points.push(point);

});

dataPacket = {
  max: max,
  data: points
};
return true;
};

function updateheatmap(input_max){
	dataPacket['max'] = input_max
	heatmapInstance.setData(dataPacket);
	return true;
};

//draw capture data onto layer, setting the active ID a different color and on top
function drawCaptureMap(){
	var capture_opacity_value = $("#custom-handle_opacity_capture").html();
//	console.log("COV: " + capture_opacity_value);
	var userChatColor ="rgba(48, 209, 209,"+ (capture_opacity_value/100)  +")";
	var startColor ="rgba(255,255,255,1)"
	//capturemapInstance.fillStyle = userChatColor;
	//capturemapInstance.clearRect(0, 0, 640, 480);

	capturemapInstance.fillStyle = userChatColor;
	capturemapInstance.clearRect(0, 0, 640, 480);

//	console.log("drawCapMap " + activeID);
//	console.log(dataPacket);

	capturemapInstance.globalCompositeOperation = "destination-over";
//	capturemapInstance.globalCompositeOperation = "destination-over";

	var highlight_type_value = $("#custom-handle_highlight_type").html();
//	console.log(highlight_type_value);

//Iterate over the list and draw the boxes based on the selected style
	$.each(dataPacket.data, function(key, value) {
	//    console.log(key, value);
//    console.log(value.id);
		capturemapInstance.fillRect(value.x-(value.width/2),value.y-(value.height/2),value.width,value.height);
		if (highlight_type_value == 2) {
			capturemapInstance.clearRect(value.x-(value.width/2)+2,value.y-(value.height/2)+2,value.width-4,value.height-4);
		}

//		ctx.clearRect(value.x-(value.width/2),value.y-(value.height/2),value.width,value.height)
//		ctx.stroke();

//		capturemapInstance.rect(value.x-(value.width/2),value.y-(value.height/2),value.width,value.height);
//		capturemapInstance.clearRect(value.x-(value.width/2),value.y-(value.height/2),value.width,value.height)
//		capturemapInstance.stroke();
	});
	capturemapInstance.globalCompositeOperation = "source-over";
	return true;
};

//draw capture data onto layer, setting the active ID a different color and on top
function drawSelection(){
        var capture_opacity_value = $("#custom-handle_opacity_capture_select").html();
      console.log("COV: " + capture_opacity_value);
        var userChatColor ="rgba(255, 48, 48,"+ (capture_opacity_value/100)  +")";
        var startColor ="rgba(255,255,255,1)"
//console.log(userChatColor);
        //capturemapInstance.fillStyle = userChatColor;
        selectionmapInstance.clearRect(0, 0, 640, 480);

        selectionmapInstance.fillStyle = userChatColor;
        selectionmapInstance.clearRect(0, 0, 640, 480);

        console.log("drawCapMap " + activeID);
//      console.log(dataPacket);

        selectionmapInstance.globalCompositeOperation = "destination-over";

        var highlight_type_value = $("#custom-handle_highlight_type").html();
//        console.log(highlight_type_value);

//Iterate over the list and draw the boxes based on the selected style
                if (activeID > -1) {
        $.each(dataPacket.data, function(key, value) {
        //    console.log(key, value);
//    console.log(value.id + " -- " + activeID);
		if (value.id == activeID){
                selectionmapInstance.fillRect(value.x-(value.width/2),value.y-(value.height/2),value.width,value.height);
//                capturemapInstance.fillRect(value.x-(value.width/2),value.y-(value.height/2),value.width,value.height);
//                capturemapInstance.clearRect(value.x-(value.width/2)+2,value.y-(value.height/2)+2,value.width-4,value.height-4);
		}
        });
                }
        selectionmapInstance.globalCompositeOperation = "source-over";
        return true;
};


//Update graph data using AJAX command
function updateData(date){

//ConvertDate
var dateConvert = new Date(date);

//Make AJAX call to get the data using data.php
$.ajax({
  type: "POST",
  url: "data.php",
  data: "day="+dateConvert.getDate()+"&month="+dateConvert.getMonth()+"&year="+dateConvert.getFullYear()+"&sid="+Math.random(),
  success: function(data) {

    //On success, push data into #data_block
    $('#data_block').html(data);

    //Initialize JQuery to allow the clicking on the event_file column to push the image to the image_layer class.
    $( "#data_block > table > tbody > tr > td.image_link > a" ).click(function () {
       var url = $(this).attr('data-target');
	activeID = $(this).attr('data-event');
	console.log("ActiveID: " + activeID);
	//redraw the capture map, setting the 'key' image id for selection
//	drawCaptureMap();
	drawSelection();
       image = new Image();
       image.src = url;
       image.onload = function () {
           $('.image_layer').empty().append(image);
       };
       image.onerror = function () {
           $('.image_layer').empty().html('That image is not available.');
       }

       $('.image_layer').empty().html('Loading...');
	return false;
   });//data_block Click

    //Initialize JQuery to allow the clicking on the event_file column to push the image to the image_layer class.
    $( "#data_block > table > tbody > tr > td.image_movie > a" ).click(function () {
       var url = $(this).attr('data-target');
//	activeID = $(this).html();
	var el = document.getElementById("movie_layer");
	console.log(el);
	swfobject.embedSWF(url, el, "640", "480", "9.0.0");
	//redraw the capture map, setting the 'key' image id for selection
//	drawCaptureMap();
//	drawSelection();
//       image = new Image();
//       image.src = url;
//       image.onload = function () {
//           $('.image_layer').empty().append(image);
//       };
//       image.onerror = function () {
//           $('.image_layer').empty().html('That image is not available.');
//       }
//
//       $('.image_layer').empty().html('Loading...');
	return false;
   });//data_block Click

var color_max_value = $("#custom-handle_heatmap_colormax").html();
var map_type_value = $("#custom-handle_heatmap_maptype").html();
updateDataPacket(map_type_value);
updateheatmap(color_max_value);
drawCaptureMap();
drawSelection();
   
}});

};

