<?php

require __DIR__ . '/vendor/autoload.php';

require_once getcwd() . '/db.php';

use db\FILERUN_CONNECTOR;


function getGalleryData($TAG_NAME)
{
    $FRC = new FILERUN_CONNECTOR();

    $FileRun = new FileRun\API\Client([
        'url' => $FRC::$FILERUN_URL,
        'client_id' => $FRC::$FILERUN_CLIENT_ID,
        'client_secret' => $FRC::$FILERUN_CLIENT_SECRET,
        'username' => $FRC::$FILERUN_USER_NAMES[0],
        'password' => $FRC::$FILERUN_USER_PASSWORDS[0],
        'scope' => ['profile', 'list', 'upload', 'download', 'weblink', 'delete', 'share', 'admin', 'modify', 'metadata']
    ]);


    // CONNECT TO FILERUN API
    $rs = $FileRun->connect();
    if (!$rs) {
        exit('Failed to connect: ' . $FileRun->getError());
    } else {
        echo 'Successfully connected<hr>';
    }

    // SEARCH FOR TAGGED FILES
    $rs = $FileRun->searchFiles(['path' => '/ROOT/HOME', 'meta' => ['tag' => [$TAG_NAME]]]);
    if ($rs && $rs['success']) {
        echo 'Searching home folder for files tagged "' . $TAG_NAME . '":<br>';
        echo '<div style="max-height:200px;padding:5px;overflow:auto;">';
        foreach ($rs['data']['files'] as $item) {
            echo "&nbsp;&nbsp;" . $item['path'] . '<br>';
            print_r($item);
        }
        echo '</div><hr>';
    } else {
        exit('Failed to retrieve search result: ' . $FileRun->getError());
    }

    $tagged_image_paths = array_map(
        function ($item) {
            return $item;
        },
        $rs['data']['files']
    );

    print_r($tagged_image_paths);

    // RETRIEVE COMMENTS
    // Must go direct to mysql because filerun is too shitty to provide this data via the API
    $QUERY = "SELECT T1.file_id as id, T2.val as comment, T4.path as path
    FROM
    df_modules_metadata_values T1,
    df_modules_metadata_values T2,
    df_modules_metadata_files T3,
    df_paths T4
    WHERE
    T1.field_id=7 AND
    T1.val='" . $TAG_NAME . "' AND
    T2.field_id=1 AND
    T1.file_id=T2.file_id AND
    T3.pid=T4.id AND
    T3.id=T2.file_id;
    ";

    $pathsAndComments = array();
    $query_result = mysqli_query($FRC::$MYSQLCONN, $QUERY);
    while ($row = mysqli_fetch_array($query_result)) {
        // Each entry in array is an object with path and comment
        // Multiple comments are combined into single comment separated by '<>'
        $pathsAndComments[$row['id']] = array(
            'path' => $row['path'],
            'comment' => isset($pathsAndComments[$row['id']]) && isset($pathsAndComments[$row['id']]['comment']) ?
                $row['comment'] . " <> " . $pathsAndComments[$row['id']]['comment'] : $row['comment']
        );
    }

    echo "<hr>";
    print_r($pathsAndComments);

    return $pathsAndComments;
}

getGalleryData('greece');
