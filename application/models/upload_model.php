<?php defined ( 'BASEPATH' ) OR exit ( 'No direct script access allowed' );

require_once APPPATH . '/imageserverapp.php';

/**
 * Database access for the Images controller.
 *
 * @author knautz
 */
class Upload_model extends CI_Model
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
     * Upload an image.
     * <p>
     * This method uploads an image to the system. Per CodeIgniter, the image
     * must be sent as multipart. First, the method gets the folder name by
     * using the provided image identifier. Next, the parameters are set for
     * the do_upload() function; do_upload() is called. Finally, the database
     * is updated with the URI of the image.
     * <p>
     * @param integer $pImageId Required identifier that uniquely identifies the
     * image to delete.
     * @param boolean $pDbgFlag Optional flag that puts the method into "debug"
     * mode. If $pDbgFlag is TRUE, the method writes debug entries to the log
     * for most operations; if FALSE (default) no debug logging is performed.
     * @throws IS_Exception _IS_ENUM_MISSING_ID_, _IS_ENUM_BAD_ID_,
     * _IS_ENUM_DB_SELECT_ERROR_, _IS_ENUM_UPLOAD_DO_UPLOAD_FAILED_.
     */
    function uploadImage ( $pImageId, $pDbgFlag=false )
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

        // Get the name of the folder to put the image in.
        $result = $this->utils->perform_query ( _IS_DB_Q_JOIN_SELECTFOLDERNAMEBYIMAGEID_, $pImageId, $pDbgFlag );
        if ( !$result )
        {
            log_message ( 'error', _IS_ENUM_DB_SELECT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_SELECT_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_SELECT_ERROR_ );
        }

        foreach ($result->result() as $row)
        {
            $folderName = $row->folderName;
        }
        $this->utils->debug_msg ( __METHOD__, "*** Images folder name is: $folderName.", $pDbgFlag );

        // Construct the upload path.
        $config['upload_path'] = _IS_FS_DATA_FOLDER_ . '/' . $folderName;
        $this->utils->debug_msg ( __METHOD__, 'config[\'upload_path\'] = ' . $config['upload_path'] , $pDbgFlag );

        // Set the rest of the parameters.
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        $this->load->library ( 'upload', $config );
        $this->upload->initialize ( $config );

        // Do the upload!
        $retval = $this->upload->do_upload ( 'userfile' );
        if ( !$retval )
        {
            // ERROR
            log_message ( 'error', _IS_ENUM_UPLOAD_DO_UPLOAD_FAILED_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_UPLOAD_DO_UPLOAD_FAILED_ );
            throw new IS_Exception ( _IS_ENUM_UPLOAD_DO_UPLOAD_FAILED_ );
        }

        $arr = $this->upload->data();
        $this->utils->debug_msg ( __METHOD__, '*** upload data = ' . print_r ( $arr, true), $pDbgFlag );

        // Update the image record in the db.  Store the actual URI for the user
        // so they can easily access.
        $uri = $this->config->item('base_url') . '/' . $config['upload_path'] . '/' . $arr['file_name'];
        $this->utils->debug_msg ( __METHOD__, "*** final uri = $uri", $pDbgFlag );
        $result = $this->utils->perform_query ( _IS_DB_Q_IMAGE_UPDATEFILENAME_, $uri, $pImageId, $pDbgFlag );
        if ( !$result )
        {
            log_message ( 'error', _IS_ENUM_DB_INSERT_ERROR_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_DB_INSERT_ERROR_ );
            throw new IS_Exception ( _IS_ENUM_DB_INSERT_ERROR_ );
        }

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

        // SUCCESS
        return true;
    }

}

/* End of file upload_model.php */
/* Location: ImageServer-{version}/application/models/upload_model.php */
