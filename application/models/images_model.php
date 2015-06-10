<?php defined ( 'BASEPATH' ) OR exit ( 'No direct script access allowed' );

require_once APPPATH . '/imageserverapp.php';

/**
 * Database access for the Images controller.
 *
 * @author knautz
 */
class Images_model extends CI_Model
{
    private $utils;

    function __construct()
    {
        parent::__construct();

        // Load the database.
        $this->load->database();

        // Instantiate a Util object.
        $this->utils = new Util();
    }

    /**
     * Delete an images metadata.
     * <p>
     * Given an image identifier, this methos deletes the inages metadata from
     * the database.
     * <p>
     * @param integer $pImageId Required identifier that uniquely identifies the
     * image to delete.
     * @param boolean $pDbgFlag Optional flag that puts the method into "debug"
     * mode. If $pDbgFlag is TRUE, the method writes debug entries to the log
     * for most operations; if FALSE (default) no debug logging is performed.
     * @throws IS_Exception _IS_ENUM_MISSING_ID_, _IS_ENUM_BAD_ID_ and
     * _IS_ENUM_DB_DELETE_ERROR_.
     */
    function deleteImage ( $pImageId,
                           $pDbgFlag=false )
    {
        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // The image id is required.
        // Ensure the album id is given.
        try
        {
            $this->utils->check_id_param ( $pImageId );
        }
        catch ( IS_Exception $ex )
        {
            throw $ex;
        }

        // Delete the image referenced in the db associated with the given
        // album id.
        $result = $this->utils->perform_query ( _IS_DB_Q_IMAGE_DELETEIMAGE_, $pImageId, $pDbgFlag );
        if ( !$result )
        {
            log_message ( 'error', _IS_ENUM_DB_DELETE_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_DELETE_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_DELETE_ERROR_ );
        }
        $this->utils->debug_msg ( __METHOD__, '*** Image(s) deleted from database.', $pDbgFlag );

        $this->utils->end_method ( __METHOD__, $pDbgFlag );
    }

    /**
     * Delete all images that are part of an album.
     * <p>
     * <p>
     * @param integer $pAlbumId Required identifier that uniquely identifies the
     * image to delete.
     * @param boolean $pDbgFlag Optional flag that puts the method into "debug"
     * mode. If $pDbgFlag is TRUE, the method writes debug entries to the log
     * for most operations; if FALSE (default) no debug logging is performed.
     * @throws IS_Exception _IS_ENUM_MISSING_ID_, _IS_ENUM_BAD_ID_ and
     * _IS_ENUM_DB_DELETE_ERROR_.
     */
    function deleteImagesForAlbumId ( $pAlbumId,
                                      $pDbgFlag=false )
    {
        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Ensure the album id is given.
        try
        {
            $this->utils->check_id_param ( $pAlbumId );
        }
        catch ( IS_Exception $ex )
        {
            throw $ex;
        }

        // Delete the images referenced in the db associated with the given
        // album id.
        $result = $this->utils->perform_query ( _IS_DB_Q_ALBUM_DELETEIMAGESBYALBUMID_, $pAlbumId, $pDbgFlag );
        if ( !$result )
        {
            log_message ( 'error', _IS_ENUM_DB_DELETE_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_DELETE_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_DELETE_ERROR_ );
        }
        $this->utils->debug_msg ( __METHOD__, '*** Image(s) deleted from database.', $pDbgFlag );

        $this->utils->end_method ( __METHOD__, $pDbgFlag );
    }

    /**
     * Get all images
     * Produces: SELECT * FROM tableImages
     * @return array CI database query result set
     */
    function getImagesAll()
    {
        $query = $this->db->get ( _CF_DB_TABLE_IMAGES_ );
        return $query->result();
    }

    /**
     * Get images given their image id
     * Produces: SELECT * FROM tableImages WHERE uiImageId={id}
     * @param int $pId image identifier
     * @return array CI database query result set
     */
    function getImagesForAlbum ( $pAlbumId,
                                 $pDbgFlag=false )
    {
        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Ensure the album id is given.
        try
        {
            $this->utils->check_id_param ( $pAlbumId );
        }
        catch ( Exception $ex )
        {
            throw $ex;
        }

        // Get the image associated with the given album id
        $result = $this->utils->perform_query ( _IS_DB_Q_IMAGE_SELECTIMAGESBYALBUMID_, $pAlbumId, $pDbgFlag );

        if ( !$result )
        {
            $errArray = $this->utils->log_db_error ( _IS_ENUM_DB_SELECT_ERROR_, _IS_EMSG_DB_SELECT_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_, $errArray['code'], $errArray['message'] );
        }
        $this->utils->debug_msg ( __METHOD__, 'query result: ' . print_r ( $result, true ), $pDbgFlag );

        $this->utils->debug_msg ( __METHOD__, 'num of rows returned: ' . $result->num_rows(), $pDbgFlag );

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

        return $result->result();
    }

    /**
     * Create a new image.
     * <p>
     * This method creates a new image. One of two procedures are used depending
     * on how newImage() was called. The first usage is when newImage() is
     * called without parameters. This means the system will generawte a new
     * image using system defaults. The second usage will have the user provide
     * all three parameters.
     * <p>
     * @param integer $pAlbumId Required identifier that uniquely identifies the
     * album to which the image will belong.
     * @param integer $pImageId Optional identifier that uniquely identifies the
     * image to create.
     * @param string $pTitle Optional title for the new image.
     * @param string $pDescription Optional description for the new image.
     * @param boolean $pDbgFlag Optional flag that puts the method into "debug"
     * mode. If $pDbgFlag is TRUE, the method writes debug entries to the log
     * for most operations; if FALSE (default) no debug logging is performed.
     * @return array CI database query result set
     * @throws IS_Exception _IS_ENUM_DB_INSERT_ERROR_ and
     * _IS_ENUM_DB_SELECT_ERROR_
     */
    function newImage ( $pAlbumId,
                        $pImageId=0,
                        $pTitle='Image Title',
                        $pDescription='Image Description',
                        $pDbgFlag=false )
    {
        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // We do not need to use a transaction here as no file system
        // manipulation is done (like in the album).
        if ( $pImageId == 0 )
        {
            // Create a new default image
            $result = $this->utils->perform_query ( _IS_DB_Q_IMAGE_NEWDEFAULTIMAGE_, $pTitle, $pDescription, $pAlbumId, $pDbgFlag );
            if ( !$result )
            {
                $errArray = $this->utils->log_db_error ( _IS_ENUM_DB_INSERT_ERROR_, _IS_EMSG_DB_INSERT_ERROR_ );
                throw new IS_Exception ( _IS_ENUM_DB_INSERT_ERROR_, $errArray['code'], $errArray['message'] );
            }

            // Since we are creating a new default resource, the record with the
            // highest image id will be our newly inserted record.
            $result = $this->utils->perform_query ( _IS_DB_Q_IMAGE_SELECTHIGHESTIMAGEID_, $pDbgFlag );
            if ( !$result )
            {
                $errArray = $this->utils->log_db_error ( _IS_ENUM_DB_SELECT_ERROR_, _IS_EMSG_DB_SELECT_ERROR_ );
                throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_, $errArray['code'], $errArray['message'] );
            }
        }
        else
        {
            // Create a new image

            // The controller ensures the image does not already exist!!!

            $result = $this->utils->perform_query ( _IS_DB_Q_IMAGE_NEWIMAGE_, $pImageId, $pTitle, $pDescription, $pAlbumId, $pDbgFlag );
            if ( !$result )
            {
                // No need to rollback here because nothing has happened yet.
                $errArray = $this->utils->log_db_error ( _IS_ENUM_DB_INSERT_ERROR_, _IS_EMSG_DB_INSERT_ERROR_ );
                throw new IS_Exception ( _IS_ENUM_DB_INSERT_ERROR_, $errArray['code'], $errArray['message'] );
            }

            // Since we are a new user defined resource, we already have the
            // album id - it was provided by the user.
            $result = $this->utils->perform_query ( _IS_DB_Q_IMAGE_SELECTIMAGEBYID_, $pImageId, $pDbgFlag );
            if ( !$result )
            {
                $errArray = $this->utils->log_db_error ( _IS_ENUM_DB_SELECT_ERROR_, _IS_EMSG_DB_SELECT_ERROR_ );
                throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_, $errArray['code'], $errArray['message'] );
            }
        }

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

        return $result->result();
    }

    function updateImage ( $pAlbumId,
                           $pImageId,
                           $pTitle,
                           $pDescription,
                           $pDbgFlag=false )
    {
        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Update if the parameters are present.
        if ( isset($pAlbumId) && isset($pImageId) && isset($pTitle) && isset($pDescription) )
        {
            $result = $this->utils->perform_query ( _IS_DB_Q_IMAGE_UPDATEIMAGE_, $pAlbumId, $pTitle, $pDescription, $pImageId, $pDbgFlag );
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

        $result = $this->utils->perform_query ( _IS_DB_Q_IMAGE_SELECTIMAGEBYID_, $pImageId, $pDbgFlag );
        if ( !$result )
        {
            $errArray = $this->utils->log_db_error ( _IS_ENUM_DB_SELECT_ERROR_, _IS_EMSG_DB_SELECT_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_, $errArray['code'], $errArray['message'] );
        }

        return $result->result();
    }

}

/* End of file images_model.php */
/* Location: ImageServer-{version}/application/models/images_model.php */
