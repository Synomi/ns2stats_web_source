ns2stats_web_source - http://ns2stats.com
===================

NS2Stats - Statistics for Natural Selection 2 PC Game, website source code

We are looking for coders!
If you are interested please send me an email to synomi66@gmail.com or join our irc channel ns2stats @ quakenet.

More info: http://forums.unknownworlds.com/discussion/129495/ns2stats-statistics-for-natural-selection-2

ns2stats.com uses http://www.yiiframework.com/


-- Setting up your own ns2stats site --
Database schema can be found: 
/protected/data/ns2stats.schema.2015-04-03.sql

Cronjobs you need (edit paths):
* * * * * /usr/bin/wget -O /dev/null http://ns2stats.com/update/startParseLog
0 */2 * * * /usr/bin/wget -O /dev/null http://ns2stats.com/update/getplayercountry > /dev/null
30 */2 * * * /usr/bin/wget -O /dev/null http://ns2stats.com/update/getservercountry > /dev/null

Optional cronjobs (edit paths), deletes old round files:
20 5 * * * /usr/bin/find /var/www/ns2stats.com/protected/data/round-logs/incomplete -name "round-log*" -mtime +7 -delete
30 5 * * * /usr/bin/find /var/www/ns2stats.com/protected/data/round-logs/completed -name "round-log*" -mtime +7 -delete
40 5 * * * /usr/bin/find /var/www/ns2stats.com/protected/data/round-logs/failed -name "round-log*" -mtime +7 -delete
50 5 * * * /usr/bin/find /var/www/ns2stats.com/protected/data/round-logs/parselogs -name "log-*" -mtime +7 -delete

You will need steam api key to for steam login:
http://steamcommunity.com/dev

