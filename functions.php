<?php
function getDbConnection() {
    $host = 'localhost';
    $database = 'Articles';
    $user = 'artemiy';
    $password = 'Recognize13_';

    $link = mysqli_connect($host, $user, $password, $database);
    if (!$link)
    {
        http_response_code(500);
        exit('Database connection error.');
    }

    return $link;
}

function createCommentsTable() {
    $link = getDbConnection();
    $query = 'CREATE TABLE comments (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    article_id INTEGER NOT NULL,
    author TEXT NOT NULL,
    rate INTEGER NOT NULL,
    text TEXT NOT NULL,
    created DATE NOT NULL
    )';

    $result = $link->exec($query);
    if (false === $result) {
    http_response_code(500);
    exit('Database init error');
    }
}