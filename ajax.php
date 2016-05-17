<?php
...
// process group operation, called at group popup, if var not exist! min. load! 
if (isset($_GET['group1'])) {
    $sql = "SELECT g.id as groupid, g.name as groupname, i.item_id1 as itemid, i.item_name1 as itemname FROM group1 g
            LEFT JOIN group_item1 gi ON gi.groupid = g.id
            LEFT JOIN item1 i ON gi.itemid = i.item_id1
            ORDER BY g.id";

    $stmt = $ODB->query($sql);
    $aR1 = $stmt->fetchall(PDO::FETCH_ASSOC); 

    // next step, make array of unique groups, each group of array of item objects, each object item id + item name
    $aGroups = array(); 
    $groupid = 0; 
    foreach ($aR1 as $r) {
        if ($groupid != $r['groupid']) {    // first item in this group, could be null
            $itemno = 0;    // reset to 0
            $groupid = $r['groupid'];   // update the loop condition
        } else {  // more than 1 item in this group, add a new array element of item 
            $aGroups[$groupid]['item'][++$itemno]['id'] = $r['itemid'];
            $aGroups[$groupid]['item'][$itemno]['itemname'] = $r['itemname'];
        }
    }
    $sJSON = json_encode ($aGroups);  
    echo $sJSON;
    return;
}
...
