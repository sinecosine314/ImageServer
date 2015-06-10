<?php defined ( 'BASEPATH' ) OR exit ( 'No direct script access allowed' );

require_once APPPATH . '/imageserverapp.php';

/**
 * Create (1) = POST to a base URI returning a new system created URI
 * Create (2) = POST with a new URI created by the user
 * Read       = GET
 * Update     = PUT with an existing URI
 * Delete     = DELETE
 *
 * Manage Images metadata.
 *
 * Supported methods and URI's:
 *     (Create 1) (albumid_post) POST .../api/images HTTP/1.1
 *     (Create 2) (albumid_post) POST .../api/images/id/{number} HTTP/1.1
 *
 *
 * OLD:
 *     (Create) (albumid_post) POST .../api/images/albumid/{number} HTTP/1.1
 *     (Create) (ids_post)     POST .../api/images/ids/{number}/{number} HTTP/1.1
 *     (Read)   (albumid_get)  GET .../api/images/albumid/{number} HTTP/1.1
 *     (Update) (ids_put)      PUT .../api/images/ids/{number}/{number} HTTP/1.1
 *     (Delete) (ids_delete)   DELETE .../api/images/ids/{number}/{number} HTTP/1.1
 *
 * @author knautz
 *
 */

/**
 * Apparently the REST_Controller cannot be autoloaded with out a hack to the
 * core components...
 *
 * @todo Try to work in the hack: http://forum.codeigniter.com/thread-59291.html
 *
 */

require_once APPPATH . '/libraries/REST_Controller.php';
require_once APPPATH . '/libraries/Util.php';

// Get the pplication constatnts
require_once APPPATH . '/imageserverapp.php';

class Images extends REST_Controller
{
    private $utils;

    function __construct()
    {
        // Construct our parent class
        parent::__construct();

        // Load the model
        $this->load->model ( _IS_MODEL_IMAGES_ );
        $this->load->library ( _IS_LIBRARY_IS_EXCEPTION_ );

        $this->utils = new Util();
    }

    /**
     * Create a new image with default information.
     * <p>
     * This method creates a new image in the database using default image
     * information.  In a REST sense, this method will POST to a base URI
     * and return a new server created URI.
     * <p>
     * Called with the following HTTP request:
     *     POST .../api/images/albumid/{number1} HTTP/1.1
     * <p>
     * @param integer $pAlbumId Required identifier of the album to which this
     * image belongs.
     */
    function albumid_post ( $pAlbumId )
    {
        $pDbgFlag=true;

        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Make sure the id is given.  IF the id is not given, try the get header
        if ( !$pAlbumId )
        {
            $this->post ( _IS_URI_ALBUMID_ );
        }

        // Since this is a default image, we do not get the POST parameters.  If
        // they were send, they are ignored.

        try
        {
            $image = $this->{_IS_MODEL_IMAGES_}->newImage ( $pAlbumId );
        }
        catch ( IS_Exception $e )
        {
            // newImage() throws the following exceptions
            switch ( $e->getCode() )
            {
                case _IS_ENUM_MISSING_ID_:
                case _IS_ENUM_BAD_ID_:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_400_ );
                    break;

                case _IS_ENUM_DB_INSERT_ERROR_:
                default:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_500_ );
                    break;
            }
        }

        // Success response: 201 Created
        $this->utils->send_response ( $image, _IS_HTTP_RESPONSECODE_201_ );
    }

    /**
     * Create a new image with user provided information.
     * <p>
     * This method creates a new image in the database using user provided image
     * information.  In a REST sense, this method will POST to a user created
     * URI and return a new resource.
     * <p>
     * Called with the following HTTP request:
     *     POST .../api/images/ids/{number1}/{number2} HTTP/1.1
     * <p>
     * @param integer $pAlbumId Required identifier of the album to which this
     * image belongs.
     * @param integer $pImageId Required identifier of the image.
     */
    function ids_post ( $pAlbumId, $pImageId )
    {
        $pDbgFlag=true;

        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Make sure the id is given.  IF the id is not given, try the get header
        if ( !$pAlbumId )
        {
            $this->post ( _IS_URI_ALBUMID_ );
        }

        // Get the parameters
        $title = $this->post ( _IS_DATA_TITLE_ );
        $description = $this->post ( _IS_DATA_DESCRIPTION_ );

        try
        {
            $image = $this->{_IS_MODEL_IMAGES_}->newImage ( $pAlbumId, $pImageId, $title, $description );
        }
        catch ( IS_Exception $e )
        {
            // newImage() throws the following exceptions
            switch ( $e->getCode() )
            {
                case _IS_ENUM_MISSING_ID_:
                case _IS_ENUM_BAD_ID_:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_400_ );
                    break;

                case _IS_ENUM_DB_INSERT_ERROR_:
                default:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_500_ );
                    break;
            }
        }

        // Success response: 201 Created
        $this->utils->send_response ( $image, _IS_HTTP_RESPONSECODE_201_ );
    }

    function albumid_get ( $pAlbumId )
    {
        $pDbgFlag=false;

        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Make sure the id is given.  IF the id is not given, try the get header
        if ( !$pAlbumId )
        {
            $this->get ( _IS_URI_ALBUMID_ );
        }

        // Get the album from the model
        try
        {
            $images = $this->{_IS_MODEL_IMAGES_}->getImagesForAlbum ( $pAlbumId );
        }
        catch ( Exception $e )
        {
            // getImagesForAlbum() throws the following exceptions
            switch ( $e->getCode() )
            {
                case _IS_ENUM_MISSING_ID_:
                case _IS_ENUM_BAD_ID_:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_400_ );
                    break;

                case _IS_ENUM_DB_SELECT_ERROR_:
                default:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_500_ );
                    break;
            }
        }

        // The images was found.
        if ( empty ( $images ) )
        {
            // Since $images is empty, no XML content will be sent back
            $this->utils->send_response( $images, _IS_HTTP_RESPONSECODE_404_ );
        }
        else
        {
            $this->utils->send_response( $images, _IS_HTTP_RESPONSECODE_200_ );
        }

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

        // Success response: 200 OK
        $this->utils->send_response ( $image );
    }

    function ids_put ( $pAlbumId, $pImageId )
    {
        $pDbgFlag=true;

        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Make sure the id is given.  IF the id is not given, try the get header
        if ( !$pAlbumId )
        {
            $this->put ( _IS_URI_ALBUMID_ );
        }

        if ( !$pImageId )
        {
            $this->put ( _IS_URI_IMAGEID_ );
        }

        // Get the parameters
        $title = $this->put ( _IS_DATA_TITLE_ );
        $description = $this->put ( _IS_DATA_DESCRIPTION_ );

        try
        {
            $image = $this->{_IS_MODEL_IMAGES_}->updateImage ( $pAlbumId, $pImageId, $title, $description, $pDbgFlag );
        }
        catch ( IS_Exception $e )
        {
            // newImage() throws the following exceptions
            switch ( $e->getCode() )
            {
                case _IS_ENUM_MISSING_ID_:
                case _IS_ENUM_BAD_ID_:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_400_ );
                    break;

                case _IS_ENUM_DB_INSERT_ERROR_:
                default:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_500_ );
                    break;
            }
        }

        // Success response: 201 Created
        $this->utils->send_response ( $image, _IS_HTTP_RESPONSECODE_201_ );
    }

    function index_delete ( $pAlbumId, $pImageId )
    {
        // Make sure the id is given.
        if ( !$pId )
        {
            $this->utils->delete ( _IS_URI_ID_ );
        }

        // If we do not have an id at this point, it is an error.
        if ( !$pId )
        {
            $this->utils->send_response
                (
                    array ( _IS_RESPONSE_FIELD_ERROR_ => _IS_EMSG_MISSING_ID_ ),
                    _CF_HTTP_RESPONSECODE_400_
                );
        }

        $this->utils->debug_msg ( __METHOD__, 'id is present. Proceeding...', true );

        // Delete the images from the image table and the file system.
        try
        {
            $this->utils->debug_msg ( __METHOD__, 'Calling model->deleteImagesForAlbumId()...', true );
            $result = $this->{_IS_MODEL_IMAGES_}->deleteImagesForAlbumId ( $pId, true );
        }
        catch ( Exception $e )
        {
            // getAlbumById() throws the following exceptions
            switch ( $e->getCode() )
            {
                case _IS_ENUM_MISSING_ID_:
                case _IS_ENUM_BAD_ID_:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_400_ );
                    break;

                case _IS_ENUM_DB_DELETE_ERROR_:
                default:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_500_ );
                    break;
            }
        }

        $this->utils->debug_msg ( __METHOD__, 'model->deleteImagesForAlbumId() successful.', true );

        // Delete the album from the album table.  This also removes the
        // directory associated with the album.
        try
        {
            $result = $this->{_IS_MODEL_ALBUMS_}->deleteAlbumById ( $pId, true );
        }
        catch ( Exception $e )
        {
            // getAlbumById() throws the following exceptions
            switch ( $e->getCode() )
            {
                case _IS_ENUM_MISSING_ID_:
                case _IS_ENUM_BAD_ID_:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_400_ );
                    break;

                case _IS_ENUM_DB_DELETE_ERROR_:
                default:
                    $this->utils->send_response ( $e, _IS_HTTP_RESPONSECODE_500_ );
                    break;
            }
        }

    }

}

/* End of file images.php */
/* Location: ImageServer-{version}/application/controllers/images.php */
