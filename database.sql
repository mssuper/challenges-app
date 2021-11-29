CREATE
USER 'challenges-app'@'localhost' IDENTIFIED WITH mysql_native_password AS 'FVN5jmJldbtTGgA7';GRANT USAGE ON *.* TO
'challenges-app'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;CREATE
DATABASE IF NOT EXISTS `challenges-app`;GRANT ALL PRIVILEGES ON `challenges-app`.* TO
'challenges-app'@'localhost';GRANT ALL PRIVILEGES ON `challenges-app\_%`.* TO
'challenges-app'@'localhost';