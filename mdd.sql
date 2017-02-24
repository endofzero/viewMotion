SELECT Distinct DATE(event_time_stamp),
EXTRACT(Month from event_time_stamp) as Month,
EXTRACT(Day from event_time_stamp) as Day,
sum(changed_pixels) as sum from security WHERE (EXTRACT(Year from event_time_stamp)='2016') 
group by DATE(event_time_stamp) 
order by event_time_stamp;


SELECT Distinct DATE(event_time_stamp),
EXTRACT(Month from event_time_stamp) as Month,
EXTRACT(Day from event_time_stamp) as Day,
sum(changed_pixels) as changed_pixel_sum,
avg(changed_pixels) as changed_pixel_avg,
count(changed_pixels) as event_count from security 
WHERE file_type='1' AND (EXTRACT(Year from event_time_stamp)='2016') 
group by DATE(event_time_stamp) 
order by event_time_stamp
