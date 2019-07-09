<?php

use db\FILERUN_CONNECTOR;

require_once getcwd() . '/db.php';

function getGalleryData($TAG_NAME, $VERBOSE = false)
{
    $FRC = new FILERUN_CONNECTOR();

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

    if (!!$VERBOSE) {
        echo "<hr>";
        print_r($pathsAndComments);
    }

    return $pathsAndComments;
}

getGalleryData('greece', true);
