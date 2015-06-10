<?php defined ( 'BASEPATH' ) OR exit ( 'No direct script access allowed' );

/**
 * Upload images and manipulate the image folder/name in the database.
 *
 * Supported methods and URI's:
 *     (id_post) POST .../api/upload/id/{number} HTTP/1.1
 *
 * @author knautz
 *
 */

/**
 * Apparently the REST_Controller cannot be autoloaded with out a hack to the
 *core components...
 *
 * @todo Try to work in the hack: http://forum.codeigniter.com/thread-59291.html
 *
 */
require_once APPPATH . '/libraries/REST_Controller.php';
require_once APPPATH . '/libraries/Util.php';

// Get the pplication constatnts
require_once APPPATH . 'imageserverapp.php';

class Upload extends REST_Controller
{
    private $utils;

    function __construct()
    {
        // Construct our parent class
        parent::__construct();

        $this->load->library ( _IS_LIBRARY_IS_EXCEPTION_ );
        $this->load->library ( _IS_LIBRARY_UTIL_ );

        $this->load->model ( _IS_MODEL_IMAGES_ );
        $this->load->model ( _IS_MODEL_UPLOAD_ );

        $this->utils = new Util();
    }

    /**
     * Upload an image.
     * <p>
     * This method uploads an image.  In a REST sense, this method will POST to
     * an existing URI.
     * <p>
     * Called with the following HTTP request:
     *     POST .../api/upload/id/{number} HTTP/1.1
     * <p>
     * The binary image data should be sent as the payload in the POST request
     * as a standard web browser would send it.  The CI File Uploading class
     * requires that the image be sent in a multipart form.  In other words,
     * a form element might look like this:
     * <p>
     * <form method="post" action="some_action" enctype="multipart/form-data" />
     * <p>
     * @see https://ellislab.com/codeigniter/user-guide/libraries/file_uploading.html
     * <p>
     * TESTING
     * <p>
     * Correct usage:
     * curl -i -H "Accept: application/xml" -X POST \
     *     --data "albumid=3" \
     *     http://localhost:8888/ImageServer-1.0.0/api/images
     * <p>
     * Missing album id parameter:
     * curl -i -H "Accept: application/xml" -X POST \
     *     http://localhost:8888/ImageServer-1.0.0/api/images
     */
    function id_post ( $pImageId )
    {
        $dbgFlag = true;

        // Make sure the id is given.  IF the id is not given, try the get header
        if ( !$pImageId )
        {
            $this->post ( _IS_URI_IMAGEID_ );
        }

        // Get the album id associated with the image.
        try
        {
            $this->utils->debug_msg ( __METHOD__, 'Calling model->uploadImage()...', true );
            $retval = $this->{_IS_MODEL_UPLOAD_}->uploadImage ( $pImageId, $dbgFlag );
        }
        catch ( IS_Exception $e )
        {
            // uploadImage() throws the following exceptions
            switch ( $e->getCode() )
            {
                case _IS_ENUM_MISSING_ID_:
                case _IS_ENUM_BAD_ID_:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_400_ );
                    break;

                case _IS_ENUM_UPLOAD_DO_UPLOAD_FAILED_:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_404_ );
                    break;

                case _IS_ENUM_DB_SELECT_ERROR_:
                default:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_500_ );
                    break;
            }
        }

        $this->utils->send_response ( null, _IS_HTTP_RESPONSECODE_201_ );

        $this->utils->end_method ( __METHOD__, $pDbgFlag );
    }

}

/* End of file albums.php */
/* Location: ImageServer-{version}/application/controllers/albums.php */
