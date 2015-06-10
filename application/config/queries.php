<?php

/**
 * queries.php - Queries used for the application
 *
 * Queries are stored using Codeigniter query binding. Query bindings simplify
 * query syntax by letting the system put the queries together. This is done by
 * placing questoion makes in the query where the system will replace with data
 * from the program.
 *
 * @see http://www.codeigniter.com/user_guide/database/queries.html
 *
 */


/******************************************************************************\
 *
 *  ALBUM QUERIES
 *
\******************************************************************************/


/*
 * query_deleteAlbumById - Delete an album given its id.
 */
$config[_IS_DB_Q_ALBUM_DELETEALBUMBYID_] =
    'DELETE FROM ' .
        'tableAlbums ' .
    'WHERE ' .
        'uiAlbumId = ?';

/*
 * query_deleteImagesByAlbumById - Delete all the images associated with a
 * given albums id.
 */
$config[_IS_DB_Q_ALBUM_DELETEIMAGESBYALBUMBYID_] =
    'DELETE FROM ' .
        'tableImages ' .
    'WHERE ' .
        'uiAlbumId = ?';

/*
 * query_newAlbum - Create a new album with all user defined fields
 */
$config[_IS_DB_Q_ALBUM_NEWALBUM_] =
    'INSERT INTO ' .
        'tableAlbums (uiAlbumId, vcTitle, vcDescription, vcFolderName) ' .
    'VALUES ' .
        '(?, ?, ?, ?)';

/*
 * query_newDefaultAlbum - Create a new default album
 */
$config[_IS_DB_Q_ALBUM_NEWDEFAULTALBUM_] =
    'INSERT INTO ' .
        'tableAlbums (vcTitle, vcDescription, vcFolderName) ' .
    'VALUES ' .
        '(?, ?, ?)';

/*
 * query_selectAlbumById - Select album by ID
 */
$config[_IS_DB_Q_ALBUM_SELECTALBUMBYID_] =
    'SELECT ' .
        'a.uiAlbumId AS albumId, ' .
        'a.vcTitle AS albumTitle, ' .
        'a.vcDescription AS albumDescription ' .
    'FROM ' .
        'tableAlbums AS a ' .
    'WHERE ' .
        'a.uiAlbumId = ?';

/*
 * query_selectAllAlbums - Select all albums
 */
$config[_IS_DB_Q_ALBUM_SELECTALLALBUMS_] =
    'SELECT ' .
        'uiAlbumId AS albumId, ' .
        'vcTitle AS albumTitle, ' .
        'vcDescription AS albumDescription ' .
    'FROM ' .
        '`tableAlbums`';

/*
 * query_selectHighestAlbumId - Select the album with the highest ID
 */
$config[_IS_DB_Q_ALBUM_SELECTHIGHESTALBUMID_] =
    'SELECT ' .
        'a.uiAlbumId AS albumId, ' .
        'a.vcTitle AS albumTitle, ' .
        'a.vcDescription AS albumDescription ' .
    'FROM ' .
        'tableAlbums AS a ' .
    'WHERE ' .
        'uiAlbumId = (SELECT MAX(a.uiAlbumId) FROM tableAlbums)';

/*
 * Select image list by the album ID
 */
$config[_IS_DB_Q_ALBUM_SELECTIMAGELISTBYALBUMID_] =
    'SELECT ' .
        'i.uiImageId AS imageId, ' .
        'i.vcDescription AS imageDescription, ' .
        'i.vcTitle AS imageTitle, ' .
        'CONCAT(?, i.vcFilename) AS imageFileName ' .
    'FROM tableAlbums AS a, tableImages AS i ' .
    'WHERE ' .
        'a.uiAlbumId = ? AND ' .
        'i.uiAlbumId = ?';

/*
 * Select image list by the album ID
 */
$config[_IS_DB_Q_ALBUM_UPDATEALBUM_] =
    'UPDATE tableAlbums ' .
    'SET vcTitle = ?, vcDescription = ? ' .
    'WHERE uiAlbumId = ?';


/******************************************************************************\
 *
 *  IMAGE QUERIES
 *
\******************************************************************************/


/*
 * query_newDefaultImage - Create a new default image
 */
$config[_IS_DB_Q_IMAGE_NEWIMAGE_] =
    'INSERT INTO ' .
        'tableImages (uiImageId, vcTitle, vcDescription, uiAlbumId) ' .
    'VALUES ' .
        '(?, ?, ?, ?)';

/*
 * query_newDefaultImage - Create a new default image
 */
$config[_IS_DB_Q_IMAGE_NEWDEFAULTIMAGE_] =
    'INSERT INTO ' .
        'tableImages (vcTitle, vcDescription, uiAlbumId) ' .
    'VALUES ' .
        '(?, ?, ?)';

/*
 *
 */
$config[_IS_DB_Q_IMAGE_SELECTALBUMFORIMAGEID_] =
    'SELECT ' .
        'i.uiAlbumId AS albumId ' .
    'FROM ' .
        'tableImages AS i ' .
    'WHERE ' .
        'uiImageId = ?';

/*
 * query_selectHighestAlbumId - Select the album with the highest ID
 */
$config[_IS_DB_Q_IMAGE_SELECTHIGHESTIMAGEID_] =
    'SELECT ' .
        'i.uiImageId AS imageId, ' .
        'i.vcTitle AS imageTitle, ' .
        'i.vcDescription AS imageDescription, ' .
        'i.uiAlbumId AS albumId ' .
    'FROM ' .
        'tableImages AS i ' .
    'WHERE ' .
        'uiImageId = (SELECT MAX(uiImageId) FROM tableImages)';

/*
 * Select image list by the album ID
 */
$config[_IS_DB_Q_IMAGE_SELECTIMAGESBYALBUMID_] =
    'SELECT ' .
        'i.uiImageId AS imageId, ' .
        'i.vcDescription AS imageDescription, ' .
        'i.vcTitle AS imageTitle, ' .
        'i.vcFilename AS imageFileName, ' .
        'i.uiAlbumId AS albumId ' .
    'FROM tableImages AS i ' .
    'WHERE ' .
        'i.uiAlbumId = ?';

/*
 * Select image list by the album ID
 */
$config[_IS_DB_Q_IMAGE_SELECTIMAGEBYID_] =
    'SELECT ' .
        'i.uiImageId AS imageId, ' .
        'i.vcDescription AS imageDescription, ' .
        'i.vcTitle AS imageTitle, ' .
        'i.vcFilename AS imageFileName, ' .
        'i.uiAlbumId AS albumId ' .
    'FROM ' .
        'tableImages AS i ' .
    'WHERE ' .
        'i.uiImageId = ?';

/*
 * Select image list by the album ID
 */
$config[_IS_DB_Q_IMAGE_UPDATEIMAGE_] =
    'UPDATE ' .
        'tableImages ' .
    'SET ' .
        'uiAlbumId = ?, vcTitle = ?, vcDescription = ? ' .
    'WHERE '  .
        'uiImageId = ?';

/*
 *
 */
$config[_IS_DB_Q_IMAGE_UPDATEFILENAME_] =
    'UPDATE ' .
        'tableImages ' .
    'SET ' .
        'vcFilename = ? ' .
    'WHERE '  .
        'uiImageId = ?';

/*
 * Delete an image given its id
 */
$config[_IS_DB_Q_IMAGE_DELETEIMAGE_] =
    'DELETE FROM ' .
        'tableImages ' .
    'WHERE ' .
        'uiImageId = ?';

/*
 * Delete an image given its id
 */
$config[_IS_DB_Q_ALBUM_DELETEIMAGESBYALBUMID_] =
    'DELETE FROM ' .
        'tableImages ' .
    'WHERE ' .
        'uiAlbumId = ?';


/******************************************************************************\
 *
 *  JOINED QUERIES
 *
\******************************************************************************/


/*
 * Delete an image given its id
 */
$config[_IS_DB_Q_JOIN_SELECTFOLDERNAMEBYIMAGEID_] =
    'SELECT ' .
        'a.vcFolderName AS folderName ' .
    'FROM ' .
        'tableAlbums AS a, ' .
        'tableImages AS i ' .
    'WHERE ' .
        'i.uiImageId = ? ' .
        'AND ' .
        'i.uiAlbumId=a.uiAlbumId';

/* End of file queries.php */
/* Location: ImageServer-{version}/application/config/queries.php */
