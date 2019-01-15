select count(1) as 'nb_susc', floor(avg(price)) as 'av_susc', sum(mod(duration_sub, 42)) as 'ft' from subscription;
