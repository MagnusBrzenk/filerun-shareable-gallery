<?php

require __DIR__ . '/vendor/autoload.php';
require_once getcwd() . '/db.php';

use db\FILERUN_CONNECTOR;

$FRC = new FILERUN_CONNECTOR();

$FileRun = new FileRun\API\Client([
    'url' => $FRC::$FILERUN_URL,
    'client_id' => $FRC::$FILERUN_CLIENT_ID,
    'client_secret' => $FRC::$FILERUN_CLIENT_SECRET,
    'username' => $FRC::$FILERUN_USER_NAMES[0],
    'password' => $FRC::$FILERUN_USER_PASSWORDS[0],
    'scope' => ['profile', 'list', 'upload', 'download', 'weblink', 'delete', 'share', 'admin', 'modify', 'metadata']
]);


//CONNECT
$rs = $FileRun->connect();
if (!$rs) {
    exit('Failed to connect: ' . $FileRun->getError());
}

echo 'Successfully connected<hr>';

//GET USER INFO
$userInfo = $FileRun->getUserInfo();
if (!$userInfo) {
    echo 'Failed to get user info: ' . $FileRun->getError();
    exit();
}
echo 'Hello <b>' . print_r($userInfo) . '</b>!<hr>';

//SEARCH FILES
$mytags = ['tag' => ['greece']];
$rs = $FileRun->searchFiles(['path' => '/ROOT/HOME', 'meta' => $mytags]);
// if (true && $rs && $rs['success']) {
//     echo 'Searching home folder for files tagged "greece":<br>';
//     echo '<div style="max-height:200px;padding:5px;overflow:auto;">';
//     foreach ($rs['data']['files'] as $item) {
//         echo "&nbsp;&nbsp;" . $item['path'] . '<br>';
//         print_r($item);
//     }
//     echo '</div><hr>';
// } else {
//     exit('Failed to retrieve search result: ' . $FileRun->getError());
// }

$greece_image_paths = array_map(
    function ($item) {
        // return $item['path'];
        return $item;
    },
    $rs['data']['files']
);

print_r($greece_image_paths);

//RETRIEVE COMMENTS
// MUST go direct to mysql DB because filerun is too shitty to provide this data via the API


$QUERY = "SELECT T1.file_id as id, T2.val as comment, T4.path as path
FROM
df_modules_metadata_values T1,
df_modules_metadata_values T2,
df_modules_metadata_files T3,
df_paths T4
WHERE
T1.field_id=7 AND
T1.val='" . 'greece' . "' AND
T2.field_id=1 AND
T1.file_id=T2.file_id AND
T3.pid=T4.id AND
T3.id=T2.file_id;
";

$queried_paths = array();
$queried_comments = array();
$result = array();
$query_result = mysqli_query($FRC::$MYSQLCONN, $QUERY);
while ($row = mysqli_fetch_array($query_result)) {
    // Extract paths and concatenated comments from query result
    // $queried_paths[$row['id']] = $row['path'];
    // $queried_comments[$row['id']] =
    //     isset($queried_comments) && isset($queried_comments[$row['id']]) ?
    //     $row['comment'] . " <> " . $queried_comments[$row['id']] : $row['comment'];

    $result[$row['id']] = array(
        'path' => $row['path'],
        'comment' => isset($result[$row['id']]) && isset($result[$row['id']]['comment']) ?
            $row['comment'] . " <> " . $result[$row['id']]['comment'] : $row['comment']
    );
}

// $result = array();
// for ($i = 0; $i < count($queried_paths); $i++) {
//     $result[] = array(
//         'path' => $queried_paths[$i],
//         'comment' => $queried_comments[$i],
//     );
// }


echo "<hr>";
print_r($result);
// echo "<hr>";
// print_r($queried_comments);
