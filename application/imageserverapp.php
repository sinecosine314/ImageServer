<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Hospitals
 *
 * @author knautz
 *
 */


/*****************************************************************************\
 *
 * CodeIgniter
 *
\*****************************************************************************/


/*
 * URI fields
 */
define ( '_IS_URI_ID_', 'id' );
define ( '_IS_URI_ALBUMID_', 'albumid' );
define ( '_IS_URI_IMAGEID_', 'imageid' );

/*
 * Data fields for POST/PUT/DELETE requests
 */
define ( '_IS_DATA_ALBUMID_', 'albumid' );
define ( '_IS_DATA_DESCRIPTION_', 'description' );
define ( '_IS_DATA_TITLE_', 'title' );

/*
 * Controllers
 */

/*
 * Models
 */
define ( '_IS_MODEL_ALBUMS_', 'Albums_model' );
define ( '_IS_MODEL_IMAGES_', 'Images_model' );
define ( '_IS_MODEL_UPLOAD_', 'Upload_model' );

/*
 * Views
 */


/*
 * Libraries
 */
define ( '_IS_LIBRARY_IS_EXCEPTION_', 'IS_Exception' );
define ( '_IS_LIBRARY_UTIL_', 'Util' );

/*
 * Response Fields
 */
define ( '_IS_RESPONSE_FIELD_MSG_',   'message' );
define ( '_IS_RESPONSE_FIELD_CODE_',  'code' );
define ( '_IS_RESPONSE_FIELD_ERROR_', 'error' );


/*****************************************************************************\
 *
 * Database (MySQL)
 *
\*****************************************************************************/


/*
 * Album Queries
 */
define ( '_IS_DB_Q_ALBUM_DELETEALBUMBYID_',          'query_deleteAlbumById' );
define ( '_IS_DB_Q_ALBUM_DELETEIMAGESBYALBUMBYID_',  'query_deleteImagesByAlbumById' );
define ( '_IS_DB_Q_ALBUM_NEWDEFAULTALBUM_',          'query_newDefaultAlbum' );
define ( '_IS_DB_Q_ALBUM_NEWALBUM_',                 'query_newAlbum' );
define ( '_IS_DB_Q_ALBUM_SELECTHIGHESTALBUMID_',     'query_selectHighestAlbumId' );
define ( '_IS_DB_Q_ALBUM_SELECTNEWESTALBUM_',        'query_selectNewestAlbum' );
define ( '_IS_DB_Q_ALBUM_SELECTALLALBUMS_',          'query_selectAllAlbums' );
define ( '_IS_DB_Q_ALBUM_SELECTALBUMBYID_',          'query_selectAlbumById' );
define ( '_IS_DB_Q_ALBUM_SELECTIMAGELISTBYALBUMID_', 'query_selectImageListByAlbumId' );
define ( '_IS_DB_Q_ALBUM_UPDATEALBUM_',              'query_updateAlbum' );

/*
 * Image Queries
 */
define ( '_IS_DB_Q_IMAGE_NEWDEFAULTIMAGE_',          'query_newDefaultImage' );
define ( '_IS_DB_Q_IMAGE_NEWIMAGE_',                 'query_newImage' );
define ( '_IS_DB_Q_IMAGE_SELECTALBUMFORIMAGEID_',    'query_selectAlbumForImagesId' );
define ( '_IS_DB_Q_IMAGE_SELECTIMAGESBYALBUMID_',    'query_selectImagesByAlbumId' );
define ( '_IS_DB_Q_IMAGE_SELECTIMAGEBYID_',          'query_selectImageById' );
define ( '_IS_DB_Q_IMAGE_SELECTHIGHESTIMAGEID_',     'query_selectHighestImageId' );
define ( '_IS_DB_Q_IMAGE_UPDATEIMAGE_',              'query_updateImage' );
define ( '_IS_DB_Q_IMAGE_UPDATEFILENAME_',           'query_updateDilename' );
define ( '_IS_DB_Q_IMAGE_DELETEIMAGE_',              'query_deleteImage' );
define ( '_IS_DB_Q_ALBUM_DELETEIMAGESBYALBUMID_',    'query_deleteImagesByAlbumId' );

/*
 * Join Queries
 */
define ( '_IS_DB_Q_JOIN_SELECTFOLDERNAMEBYIMAGEID_', 'query_selectFolderNameByImageId' );


/*****************************************************************************\
 *
 * Messages
 *
\*****************************************************************************/


/*
 * Database Exceptions
 */

// Number of database errors
define ( '_IS_NUM_DATABASE_ERRORS_', 4 );

// Error numbers
define ( '_IS_ENUM_DATABASE_ERROR_BASE_', 10000 );
define ( '_IS_ENUM_DB_DELETE_ERROR_', _IS_ENUM_DATABASE_ERROR_BASE_ + 1 );
define ( '_IS_ENUM_DB_INSERT_ERROR_', _IS_ENUM_DATABASE_ERROR_BASE_ + 2 );
define ( '_IS_ENUM_DB_SELECT_ERROR_', _IS_ENUM_DATABASE_ERROR_BASE_ + 3 );
define ( '_IS_ENUM_DB_UPDATE_ERROR_', _IS_ENUM_DATABASE_ERROR_BASE_ + 4 );

// Error messages
define ( '_IS_EMSG_DATABASE_ERROR_',  'Datebase Error.');
define ( '_IS_EMSG_DB_DELETE_ERROR_', 'Error deleting data from the database.');
define ( '_IS_EMSG_DB_INSERT_ERROR_', 'Error inserting data into the database.');
define ( '_IS_EMSG_DB_SELECT_ERROR_', 'Error selecting data from the database.');
define ( '_IS_EMSG_DB_UPDATE_ERROR_', 'Error updating data from the database.');

/*
 * Application Exceptions
 */

// Number of exceptions
define ( '_IS_NUM_EXCEPTION_', 8 );

// Exception numbers
define ( '_IS_ENUM_EXCEPTION_BASE_', 20000 );
define ( '_IS_ENUM_MISSING_ID_', _IS_ENUM_EXCEPTION_BASE_ + 1 );
define ( '_IS_ENUM_BAD_ID_', _IS_ENUM_EXCEPTION_BASE_ + 2 );
define ( '_IS_ENUM_CANNOT_CREATE_DIR_', _IS_ENUM_EXCEPTION_BASE_ + 3 );
define ( '_IS_ENUM_CANNOT_DELETE_DIR_', _IS_ENUM_EXCEPTION_BASE_ + 4 );
define ( '_IS_ENUM_MISSING_PARAMETER_', _IS_ENUM_EXCEPTION_BASE_ + 5 );
define ( '_IS_ENUM_ALBUM_NOT_EMPTY_', _IS_ENUM_EXCEPTION_BASE_ + 6 );
define ( '_IS_ENUM_ALBUM_ALREADY_EXISTS_', _IS_ENUM_EXCEPTION_BASE_ + 7 );
define ( '_IS_ENUM_UPLOAD_DO_UPLOAD_FAILED_', _IS_ENUM_EXCEPTION_BASE_ + 8 );

// Exception messages
define ( '_IS_EMSG_MISSING_ID_',              'The identifier is missing.' );
define ( '_IS_EMSG_BAD_ID_',                  'An incorrect identifier has been provided.' );
define ( '_IS_EMSG_CANNOT_CREATE_DIR_',       'Error creating the album directory.' );
define ( '_IS_EMSG_CANNOT_DELETE_DIR_',       'Error deleting the album directory.' );
define ( '_IS_EMSG_MISSING_PARAMETER_',       'Missing a required parameter.' );
define ( '_IS_EMSG_ALBUM_NOT_EMPTY_',         'Album not empty.' );
define ( '_IS_EMSG_ALBUM_ALREADY_EXISTS_',    'Album already exists.' );
define ( '_IS_EMSG_UPLOAD_DO_UPLOAD_FAILED_', 'do_upload() failed.' );

/*
 * General messages
 */
define ( '_IS_MSG_THROWING_EXCEPTION_', ' throwing exception: ' );


/*****************************************************************************\
 *
 * HTTP
 *
\*****************************************************************************/


define ( '_IS_HTTP_RESPONSECODE_200_', 200 );   // OK
define ( '_IS_HTTP_RESPONSECODE_201_', 201 );   // Created
define ( '_IS_HTTP_RESPONSECODE_400_', 400 );   // Bad request
define ( '_IS_HTTP_RESPONSECODE_404_', 404 );   // Not found
define ( '_IS_HTTP_RESPONSECODE_409_', 409 );   // Conflict
define ( '_IS_HTTP_RESPONSECODE_500_', 500 );   // Internal server error

//define ( '_CF_HTTP_REQUESTHEADER_REALPOST_', 'X-carefinder-realpost' );


/*****************************************************************************\
 *
 * Files, folders and the file system
 *
\*****************************************************************************/


define ( '_IS_FS_DATA_FOLDER_', 'data');
define ( '_IS_FS_ALBUM_PREFACE_', 'A');
define ( '_IS_FS_ALBUM_PERMISSIONS', 0755 );


/* End of file imageserverapp.php */
/* Location: ImageServer-{version}/application/imageserverapp.php */

?>