<?php
$dbconn = pg_connect("host=hotspotdb.cwboxzguz674.us-east-1.rds.amazonaws.com dbname=mobile user=gators password=hotspotuf")
or die('Could not connect: ' . pg_last_error());

$query = 'SELECT chat_id
  FROM chat_room
  WHERE (NOT EXISTS (
        SELECT TRUE
        FROM messages
        WHERE "Room_id" = chat_room."chat_id"
        AND (EXTRACT(epoch FROM NOW() - messages."TimeStamp") / 86400) < 10)
        AND NOT EXISTS (
        SELECT TRUE
        FROM chat_room_users
        WHERE "Room_id" = chat_room."chat_id"
        AND (EXTRACT(epoch FROM NOW() - chat_room_users."joined") / 86400) < 10));';

$result = pg_query($query) or die ('something happened :(');
$log = fopen("log.txt", "a");
fwrite($log, "Log Start\n-----------------\n");
while ($row = pg_fetch_row($result)) {
	fwrite($log, $row[0]);
	fwrite($log, "\n");
}
fwrite($log, "-----------------\nLog End\n\n");

?>
