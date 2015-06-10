<?php defined ( 'BASEPATH' ) OR exit ( 'No direct script access allowed' );

require_once APPPATH . '/imageserverapp.php';
require_once APPPATH . '/libraries/Util.php';

/**
 * Description of newPHPClass
 *
 * @author knautz
 */
class Albums_model extends CI_Model
{
    /**
     * A Util class object which gives the model access to utility methods.
     * @var object
     */
    private $utils;

    /**
     * Class constructor.
     * <p>
     * This methos is the class constructor. The superclass constructor is
     * called and the database class is loaded. Finally, a new Util object is
     * instantiated.
     */
    function __construct()
    {
        parent::__construct();

        // Load the database
        $this->load->database();

        $this->utils = new Util();
    }

    /**
     * Check to see if an album has images.
     * <p>
     * This method checks the database to see if there are any images associated
     * with the given album id. This is done by accessing the database and
     * checking for the number of rows returned.
     * <p>
     * @param integer $pAlbumId Required identifier that uniquely identifies the
     * album to check.
     * @param boolean $pDbgFlag Optional flag that puts the method into "debug"
     * mode. If $pDbgFlag is TRUE, the method writes debug entries to the log
     * for most operations; if FALSE (default) no debug logging is performed.
     * @return boolean TRUE if images exist fir the given album; FALSE otherwise.
     * @throws IS_Exception _IS_ENUM_MISSING_ID_, _IS_ENUM_BAD_ID_ and
     * _IS_ENUM_DB_SELECT_ERROR_.
     */
    function albumHasImages ( $pAlbumId, $pDbgFlag=false )
    {
        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Ensure the album id is given.
        try
        {
            $this->utils->check_album_id ( $pAlbumId );
        }
        catch ( IS_Exception $ex )
        {
            throw $ex;
        }

        // -------
        // Proceed
        // -------

        // Look in the image table for images associated with an album.  The
        // path parameter is blank becuase for this method, we do not care
        // about the path.
        $result = $this->utils->perform_query ( _IS_DB_Q_IMAGE_SELECTIMAGESBYALBUMID_, ' ', $pAlbumId, $pDbgFlag );
        if ( !$result )
        {
            log_message ( 'error', _IS_ENUM_DB_SELECT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_SELECT_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_ );
        }

        $this->utils->debug_msg ( __METHOD__, 'num of rows returned: ' . $result->num_rows(), $pDbgFlag );

        // We expect 1+ row(s) if the album is found.
        if ( $result->num_rows() < 1 )
        {
            $this->utils->debug_msg ( __METHOD__, 'query result: ' . print_r ( $result, true ), $pDbgFlag );
            $this->utils->debug_msg ( __METHOD__, 'result->num_rows < 1, returning false', $pDbgFlag );
            return false;
        }
        else
        {
            $this->utils->debug_msg ( __METHOD__, 'result->num_rows >= 1, returning true', $pDbgFlag );
            return true;
        }

        $this->utils->end_method ( __METHOD__, $pDbgFlag );
    }

    /**
     * Create a new album for holding images.
     * <p>
     * This method creates new albums for holding images. One of two stored
     * procedures are called depending on how newAlbum() was called. The first
     * usage will have newAlbum() called without any parameters. This means the
     * system will generawte a new album using system defaults. The second usage
     * will have the user provide all three parameters.
     * <p>
     * @param integer $pId Optional identifier that uniquely identifies the
     * album to create.
     * @param string $pTitle Optional title for the new album.
     * @param string $pDescription Optional description for the new album.
     * @param boolean $pDbgFlag Optional flag that puts the method into "debug"
     * mode. If $pDbgFlag is TRUE, the method writes debug entries to the log
     * for most operations; if FALSE (default) no debug logging is performed.
     * @return array CI database query result set
     * @throws IS_Exception _IS_ENUM_DB_INSERT_ERROR_, _IS_ENUM_DB_SELECT_ERROR_
     * and _IS_ENUM_CANNOT_CREATE_DIR_.
     */
    function newAlbum ( $pId=0, $pTitle='Album Title', $pDescription='Album Description', $pDbgFlag=false )
    {
        $pDbgFlag = true;

        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Create the name of the album folder. We need it toadd to the db.
        $albumName = $this->utils->get_new_folder_name();
        $this->utils->debug_msg ( __METHOD__, "Creating album named `$albumName`", $pDbgFlag );

        // CI has a reallt nice transaction manager built in!  We are doing it
        // manually however because of the eception throwing.
        $this->utils->debug_msg(__METHOD__, '*** Begin new album transaction.', $pDbgFlag );
        $this->db->trans_begin();

        if ( $pId == 0 )
        {
            // Create a new default album
            $result = $this->utils->perform_query ( _IS_DB_Q_ALBUM_NEWDEFAULTALBUM_, $pTitle, $pDescription, $albumName, $pDbgFlag );
            if ( !$result )
            {
                // No need to rollback here because nothing has happened yet.
                log_message ( 'error', _IS_ENUM_DB_INSERT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_INSERT_ERROR_ );
                throw new IS_Exception ( _IS_ENUM_DB_INSERT_ERROR_,
                                         $this->db->_error_number(),
                                         $this->db->_error_message() );
            }

            // Since we are creating a new default resource, th record with the
            // highest album id will be our newly inserted record.
            $result = $this->utils->perform_query ( _IS_DB_Q_ALBUM_SELECTHIGHESTALBUMID_, $pDbgFlag );
            if ( !$result )
            {
                $this->utils->debug_msg(__METHOD__, '*** Rollback new album transaction.', $pDbgFlag );
                $this->db->trans_rollback();
                log_message ( 'error', _IS_ENUM_DB_SELECT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_SELECT_ERROR_ );
                throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_,
                                         $this->db->_error_number(),
                                         $this->db->_error_message() );
            }
        }
        else
        {
            // Create a new album

            // The controller ensures the album does not already exist!!!

            $result = $this->utils->perform_query ( _IS_DB_Q_ALBUM_NEWALBUM_, $pId, $pTitle, $pDescription, $albumName, $pDbgFlag );
            if ( !$result )
            {
                // No need to rollback here because nothing has happened yet.
                log_message ( 'error', _IS_ENUM_DB_INSERT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_INSERT_ERROR_ );
                throw new IS_Exception ( _IS_ENUM_DB_INSERT_ERROR_,
                                         $this->db->_error_number(),
                                         $this->db->_error_message() );
            }

            // Since we are a new user defined resource, we already have the
            // album id - it was provided by the user.
            $result = $this->utils->perform_query ( _IS_DB_Q_ALBUM_SELECTALBUMBYID_, $pId, $pDbgFlag );
            if ( !$result )
            {
                $this->utils->debug_msg(__METHOD__, '*** Rollback new album transaction.', $pDbgFlag );
                $this->db->trans_rollback();
                log_message ( 'error', _IS_ENUM_DB_SELECT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_SELECT_ERROR_ );
                throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_,
                                         $this->db->_error_number(),
                                         $this->db->_error_message() );
            }
        }

        // We have made it through the database portion of the method.
        // WE HAVE AN OPEN TRANSACTION AT THIS POINT.

        // -----------------------------------------------------------
        // Create a new directory in the data directory for the album.
        // -----------------------------------------------------------

        if ( $result->num_rows() > 0 )
        {
            foreach ( $result->result() as $row )
            {
                $retval = @mkdir ( _IS_FS_DATA_FOLDER_ . "/$albumName", _IS_FS_ALBUM_PERMISSIONS );
                if ( !$retval )
                {
                    // If the mkdir() goes south, we need to rollback the
                    // transaction.
                    $this->utils->debug_msg(__METHOD__, '*** Rollback new album transaction.', $pDbgFlag );
                    $this->db->trans_rollback();
                    log_message ( 'error', _IS_ENUM_CANNOT_CREATE_DIR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_CANNOT_CREATE_DIR_ );
                    throw new IS_Exception ( _IS_ENUM_CANNOT_CREATE_DIR_ );
                }
                else
                {
                    // All good! Commit!
                    $this->utils->debug_msg(__METHOD__, '*** Commit new album transaction.', $pDbgFlag );
                    $this->db->trans_commit();
                }
            }
        }
        else
        {
            // This is a problem... rollback!

            /**
             * @todo Not sure what to do here...
             */
            $this->utils->debug_msg(__METHOD__, '*** Rollback new album transaction.', $pDbgFlag );
            $this->db->trans_rollback();
        }

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

        return $result->result();
    }

    /**
     * Update an existing album.
     * <p>
     * This method updates existing albums. After the prameters are ensured to
     * be present, the method updates the database with the information from
     * the parameters.
     * <p>
     * @param integer $pId Required identifier that uniquely identifies the
     * album to create.
     * @param string $pTitle Required title for the new album.
     * @param string $pDescription Required description for the new album.
     * @param boolean $pDbgFlag Optional flag that puts the method into "debug"
     * mode. If $pDbgFlag is TRUE, the method writes debug entries to the log
     * for most operations; if FALSE (default) no debug logging is performed.
     * @return array CI database query result set
     * @throws IS_Exception _IS_ENUM_MISSING_PARAMETER_, _IS_ENUM_INSERT_ERROR_
     */
    function updateAlbum ( $pId, $pTitle, $pDescription, $pDbgFlag=false )
    {
        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Update if the parameters are present.
        if ( isset($pId) && isset($pTitle) && isset($pDescription) )
        {
            $result = $this->utils->perform_query ( _IS_DB_Q_ALBUM_UPDATEALBUM_, $pTitle, $pDescription, $pId, $pDbgFlag );
        }
        else
        {
            log_message ( 'error', _IS_ENUM_MISSING_PARAMETER_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_MISSING_PARAMETER_ );
            throw new IS_Exception ( _IS_ENUM_MISSING_PARAMETER_ );
        }

        if ( !$result )
        {
            log_message ( 'error', _IS_EMSG_INSERT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_INSERT_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_INSERT_ERROR_,
                                    $this->db->_error_number(),
                                    $this->db->_error_message() );
        }

        $this->utils->debug_msg ( __METHOD__, 'query result: ' . print_r ( $result, true ), $pDbgFlag );

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

        return $result->result();
    }

    /**
     * Retrieves all albums.
     * <p>
     * This method retrieves all albums from the database.
     * <p>
     * @param boolean $pDbgFlag Optional flag that puts the method into "debug"
     * mode. If $pDbgFlag is TRUE, the method writes debug entries to the log
     * for most operations; if FALSE (default) no debug logging is performed.
     * @return array CI database query result set
     * @throws IS_Exception _IS_ENUM_DB_SELECT_ERROR_
     */
    function getAlbumsAll ( $pDbgFlag=false )
    {
        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        $result = $this->utils->perform_query ( _IS_DB_Q_ALBUM_SELECTALLALBUMS_ );
        if ( !$result )
        {
            log_message ( 'error', _IS_ENUM_DB_SELECT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_SELECT_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_,
                                    $this->db->_error_number(),
                                    $this->db->_error_message() );
        }

        $this->utils->debug_msg ( __METHOD__, 'query result: ' . print_r ( $result, true ), $pDbgFlag );

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

        return $result->result();
    }

    /**
     * Get albums given their album id.
     * <p>
     * This method retrieves the album whose identifier is given as a parameter.
     * After the identifier is ensured to be present and valid, this method
     * gets the data from the database, ensures 1 record was retrieved, selects
     * the proper images that go with the album and returns the combined album
     * and image information to the user.
     * <p>
     * @param integer $pId Required identifier that uniquely identifies the
     * album to create.
     * @param boolean $pDbgFlag Optional flag that puts the method into "debug"
     * mode. If $pDbgFlag is TRUE, the method writes debug entries to the log
     * for most operations; if FALSE (default) no debug logging is performed.
     * @return array CI database query result set
     * @throws IS_Exception _IS_ENUM_MISSING_ID_, _IS_ENUM_BAD_ID_ and
     * _IS_ENUM_DB_SELECT_ERROR_.
     */
    function getAlbumById ( $pId, $pDbgFlag=false )
    {
        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Ensure the album id is given.
        try
        {
            $this->utils->check_album_id ( $pId );
        }
        catch ( Exception $ex )
        {
            throw $ex;
        }

        // Proceed
        // -------

        // Get the album associated with the given album id
        $result = $this->utils->perform_query ( _IS_DB_Q_ALBUM_SELECTALBUMBYID_, $pId, $pDbgFlag );

        if ( !$result )
        {
            log_message ( 'error', _IS_ENUM_DB_SELECT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_SELECT_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_,
                                     $this->db->_error_number(),
                                     $this->db->_error_message() );
        }
        $this->utils->debug_msg ( __METHOD__, 'query result: ' . print_r ( $result, true ), $pDbgFlag );

        $this->utils->debug_msg ( __METHOD__, 'num of rows returned: ' . $result->num_rows(), $pDbgFlag );

        // We expect 1 row if the album is found.
        if ( $result->num_rows() != 1 )
        {
            $this->utils->debug_msg ( __METHOD__, 'query result: ' . print_r ( $result, true ), $pDbgFlag );
            $this->utils->debug_msg ( __METHOD__, 'result->num_rows != 1, returning false', $pDbgFlag );
            return false;
        }
        else
        {
            $this->utils->debug_msg ( __METHOD__, 'result->num_rows == 1, continuing...', $pDbgFlag );
        }

        // It should be safe to assume that only a single result was returned
        // by the db call here.
        $retResult = $result->result()[0];
        $this->utils->debug_msg ( __METHOD__, 'query result[0]: ' . print_r ( $retResult, true ), $pDbgFlag );

        // Construct the path to the images so that the query can add the path
        // to the query results.
        $path = $this->config->item ( 'base_url' ) . '/' . _IS_FS_DATA_FOLDER_ . '/'
                . _IS_FS_ALBUM_PREFACE_ . sprintf ( "%07d", $pId ) . '/';
        $this->utils->debug_msg ( __METHOD__, "path = $path", $pDbgFlag );

        // Execute the query
        $result = $this->utils->perform_query ( _IS_DB_Q_ALBUM_SELECTIMAGELISTBYALBUMID_, $path, $pId, $pId, $pDbgFlag );
        if ( !$result )
        {
            log_message ( 'error', _IS_ENUM_DB_SELECT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_SELECT_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_,
                                     $this->db->_error_number(),
                                     $this->db->_error_message() );
        }
        $this->utils->debug_msg ( __METHOD__, 'query result: ' . print_r ( $result, true ), $pDbgFlag );

        $retResult->images = $result->result();

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

        return $retResult;
    }

    /**
     * Delete an album given its identifier.
     * <p>
     * This method deletes an album given its unique identifier provided by the
     * user. After a check of the identifier, the method removes the record from
     * the database and removes the directory (and its contents) from the
     * file system.
     * <p>
     * NOTE: this method assumes that deleteImagesForAlbumId() has been or will
     * be called to remove the images from the database.
     * <p>
     * @param integer $pId Required identifier that uniquely identifies the
     * album to delete.
     * @param boolean $pDbgFlag Optional flag that puts the method into "debug"
     * mode. If $pDbgFlag is TRUE, the method writes debug entries to the log
     * for most operations; if FALSE (default) no debug logging is performed.
     * @throws IS_Exception _IS_ENUM_MISSING_ID_, _IS_ENUM_BAD_ID_ and
     * _IS_ENUM_DB_DELETE_ERROR_.
     */
    function deleteAlbumById ( $pId, $pDbgFlag=false )
    {
        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Ensure the album id is given.
        try
        {
            $this->utils->check_album_id ( $pId );
        }
        catch ( Exception $ex )
        {
            throw $ex;
        }

        // Proceed
        // -------

        // Delete the album associated with the given album id
        $result = $this->utils->perform_query ( _IS_DB_Q_ALBUM_DELETEALBUMBYID_, $pId, $pDbgFlag );
        if ( !$result )
        {
            log_message ( 'error', _IS_ENUM_DB_DELETE_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_DELETE_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_DELETE_ERROR_,
                                     $this->db->_error_number(),
                                     $this->db->_error_message() );
        }
        $this->utils->debug_msg ( __METHOD__, '*** Album deleted from database.', $pDbgFlag );

        // Remove the directory associated with the given album id
        //$folderName =  _IS_FS_DATA_FOLDER_ . '/' . _IS_FS_ALBUM_PREFACE_ . sprintf ( "%07d", $id );
        $folderName =  sprintf ( "%s/%s%07d", _IS_FS_DATA_FOLDER_, _IS_FS_ALBUM_PREFACE_, $pId );
        $this->utils->debug_msg ( __METHOD__, "*** Deleting folder '$folderName'.", $pDbgFlag );

        $retval = $this->utils->delete_all ( $folderName );
        if ( !$retval )
        {
            log_message ( 'error', _IS_ENUM_CANNOT_CREATE_DIR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_CANNOT_CREATE_DIR_ );
            throw new IS_Exception ( _IS_ENUM_CANNOT_CREATE_DIR_ );
        }

        $this->utils->end_method ( __METHOD__, $pDbgFlag );
    }

}

/* End of file albums_model.php */
/* Location: ImageServer-{version}/application/models/albums_model.php */
