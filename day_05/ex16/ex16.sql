select count(1) as 'movies' from member_history where (`date` between '2006-10-30' and '2007-07-27') or (date_format(`date`, '%m-%d') = '12-24') ;
