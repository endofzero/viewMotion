# viewMotion

HTML/Javascript based GUI to view motion data from a PI.


Heatmap is from https://github.com/pa7/heatmap.js/

SWF Object : https://github.com/swfobject/swfobject

MDD uses data from pChart

# Motion

Here is the query used to insert the data by motion:

			sql_query insert into security(camera, event_number, filename, frame, file_type, changed_pixels, noise_level, motion_width, motion_height, motion_x, motion_y, time_stamp, event_time_stamp) values('%t', '%v', '%f', '%q', '%n', '%D', '%N', '%i', '%J', '%K', '%L', '%Y-%m-%d %T', '%C')

