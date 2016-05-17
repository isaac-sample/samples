<?php
require_once __DIR__ . '/Wcp.php';
$wcp = new \Wcp\Wcp(); 

echo $wcp->create_acc('API_TEST_xxxxx', 'xxxxx', 'room_xxxxx', 'email'); 
echo $wcp->update_acc('xxxxx', 'room_xxxxx', '26'); 
echo $wcp->toggle_acc('xxxxx', 0);
echo $wcp->retrieve_acc('API_test_xxxxx', 'xxxxx@xxxxx.com');
echo $wcp->delete_acc('xxxxx');
