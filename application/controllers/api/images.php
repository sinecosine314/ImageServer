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
 *     (Create 1) (id_post)   POST .../api/images HTTP/1.1
 *     (Create 2) (id_post)   POST .../api/images/id/{number} HTTP/1.1
 *     (Read)     (id_get)    GET .../api/images/id/{number} HTTP/1.1
 *     (Update)   (id_put)    PUT .../api/images/id/{number} HTTP/1.1
 *     (Delete)   (id_delete) DELETE .../api/images/id/{number} HTTP/1.1
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
     *     POST .../api/images HTTP/1.1
     * <p>
     * The album identifier should be sent as the payload in the POST request as
     * a standard web browser would send it.
     * <p>
     * Example:
     *     albumid=3
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
    function index_post()
    {
        $pDbgFlag=false;

        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Get the album id
        $albumId = $this->post ( _IS_DATA_ALBUMID_ );

        // Make sure the id is given as it is required.
        if ( !$albumId )
        {
            $this->utils->send_response
                (
                    array ( _IS_RESPONSE_FIELD_CODE_ => _IS_ENUM_MISSING_ID_,
                            _IS_RESPONSE_FIELD_MSG_ => _IS_EMSG_MISSING_ID_ ),
                    _IS_HTTP_RESPONSECODE_400_
                );
        }

        // Since this is a default image, we do not get the POST parameters.  If
        // they were send, they are ignored.

        try
        {
            $this->utils->debug_msg ( __METHOD__, 'Calling model->newImage()...', true );
            $image = $this->{_IS_MODEL_IMAGES_}->newImage ( $albumId );
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

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

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
     *     POST .../api/images/id/{number} HTTP/1.1
     * <p>
     * The image meta data should be sent as the payload in the POST request as
     * a standard web browser would send it.  Three parameters should be sent:
     * title, description and albumid.
     * <p>
     * Example:
     *     title=This%20is%20a%20title&description=This%20is%20a%20description&albumid=3
     * <p>
     * TESTING
     * <p>
     * Correct usage:
     * curl -i -H "Accept: application/xml" -X POST \
     *     --data "title=New%20Title&description=New%20description&albumid=3" \
     *     http://localhost:8888/ImageServer-1.0.0/api/images/id/4
     * <p>
     * Missing title parameter:
     * curl -i -H "Accept: application/xml" -X POST \
     *     --data "description=New%20description&albumid=3" \
     *     http://localhost:8888/ImageServer-1.0.0/api/images
     * Missing description parameter:
     * curl -i -H "Accept: application/xml" -X POST \
     *     --data "title=New%20Title&albumid=3" \
     *     http://localhost:8888/ImageServer-1.0.0/api/images
     * Missing album id parameter:
     * curl -i -H "Accept: application/xml" -X POST \
     *     --data "title=New%20Title&description=New%20description" \
     *     http://localhost:8888/ImageServer-1.0.0/api/images
     * Missing all parameters:
     * curl -i -H "Accept: application/xml" -X POST \
     *     http://localhost:8888/ImageServer-1.0.0/api/images
     * <p>
     * @param integer $pImageId Required identifier of the image.
     */
    function id_post ( $pImageId )
    {
        $pDbgFlag=false;

        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Make sure the id is given.  IF the id is not given, try the get header
        if ( !$pImageId )
        {
            $this->post ( _IS_URI_IMAGEID_ );
        }

        // Get the parameters
        $title = $this->post ( _IS_DATA_TITLE_ );
        $description = $this->post ( _IS_DATA_DESCRIPTION_ );
        $albumId = $this->post ( _IS_DATA_ALBUMID_ );

        // Make sure the parameters are given as they are required.
        if ( !$albumId || !$description || !$title )
        {
            $this->utils->send_response
                (
                    array ( _IS_RESPONSE_FIELD_CODE_ => _IS_ENUM_MISSING_ID_,
                            _IS_RESPONSE_FIELD_MSG_ => _IS_EMSG_MISSING_ID_ ),
                    _IS_HTTP_RESPONSECODE_400_
                );
        }

        try
        {
            $this->utils->debug_msg ( __METHOD__, 'Calling model->newImage()...', true );
            $image = $this->{_IS_MODEL_IMAGES_}->newImage ( $albumId, $pImageId, $title, $description );
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

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

        // Success response: 201 Created
        $this->utils->send_response ( $image, _IS_HTTP_RESPONSECODE_201_ );
    }

    /**
     * Retrieve an images metadata.
     * <p>
     * This method retrieves an images metadata from the database.  In a REST
     * sense, this method GETs a resource and returns it to the user.
     * <p>
     * Called with the following HTTP request:
     *     GET .../api/images/id/{number} HTTP/1.1
     * <p>
     * TESTING
     * <p>
     * Correct usage:
     * curl -i -H "Accept: application/xml" -X GET \
     *     http://localhost:8888/ImageServer-1.0.0/api/images/id/4
     * <p>
     * Non-existant album:
     * curl -i -H "Accept: application/xml" -X GET \
     *     http://localhost:8888/ImageServer-1.0.0/api/images/id/999999
     * <p>
     * @param integer $pAlbumId Required identifier of the album that contains
     * the images.
     */
    function id_get ( $pAlbumId )
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
            $this->utils->debug_msg ( __METHOD__, 'Calling model->getImagesForAlbum()...', true );
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

    /**
     * Update an existing image with user provided information.
     * <p>
     * This method updates an existing image in the database using user provided
     *  image information.  In a REST sense, this method will PUT to an existing
     *  URI and return the modified resource.
     * <p>
     * Called with the following HTTP request:
     *     PUT .../api/images/id/{number} HTTP/1.1
     * <p>
     * The image meta data should be sent as the payload in the PUT request as
     * a standard web browser would send it.  Three parameters should be sent:
     * title, description and albumid.
     * <p>
     * Example:
     *     title=This%20is%20a%20title&description=This%20is%20a%20description&albumid=3
     * <p>
     * TESTING
     * <p>
     * Correct usage:
     * curl -i -H "Accept: application/xml" -X PUT \
     *     --data "title=Modified%20Title&description=Modified%20description&albumid=3" \
     *     http://localhost:8888/ImageServer-1.0.0/api/images/id/4
     * <p>
     * Missing title parameter:
     * curl -i -H "Accept: application/xml" -X PUT \
     *     --data "description=Modified%20description&albumid=3" \
     *     http://localhost:8888/ImageServer-1.0.0/api/images
     * Missing description parameter:
     * curl -i -H "Accept: application/xml" -X PUT \
     *     --data "title=Modified%20Title&albumid=3" \
     *     http://localhost:8888/ImageServer-1.0.0/api/images
     * Missing album id parameter:
     * curl -i -H "Accept: application/xml" -X PUT \
     *     --data "title=Modified%20Title&description=Modified%20description" \
     *     http://localhost:8888/ImageServer-1.0.0/api/images
     * Missing all parameters:
     * curl -i -H "Accept: application/xml" -X PUT \
     *     http://localhost:8888/ImageServer-1.0.0/api/images
     * <p>
     * @param integer $pImageId Required identifier of the image.
     */
    function id_put ( $pImageId )
    {
        $pDbgFlag=false;

        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Make sure the id is given.  IF the id is not given, try the get header
        if ( !$pImageId )
        {
            $this->put ( _IS_URI_IMAGEID_ );
        }

        // Get the parameters
        $title = $this->put ( _IS_DATA_TITLE_ );
        $description = $this->put ( _IS_DATA_DESCRIPTION_ );
        $albumId = $this->put ( _IS_DATA_ALBUMID_ );

        // Make sure the parameters are given as they are required.
        if ( !$albumId || !$description || !$title )
        {
            $this->utils->send_response
                (
                    array ( _IS_RESPONSE_FIELD_CODE_ => _IS_ENUM_MISSING_ID_,
                            _IS_RESPONSE_FIELD_MSG_ => _IS_EMSG_MISSING_ID_ ),
                    _IS_HTTP_RESPONSECODE_400_
                );
        }

        try
        {
            $this->utils->debug_msg ( __METHOD__, 'Calling model->updateImage()...', true );
            $image = $this->{_IS_MODEL_IMAGES_}->updateImage ( $albumId, $pImageId, $title, $description, $pDbgFlag );
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

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

        // Success response: 201 Created
        $this->utils->send_response ( $image, _IS_HTTP_RESPONSECODE_201_ );
    }

    /**
     * Delete an images metadata.
     * <p>
     * This method deletes an images metadata from the database.  In a REST
     * sense, this method DELETEs a resource.
     * <p>
     * Called with the following HTTP request:
     *     DELETE .../api/images/id/{number} HTTP/1.1
     * <p>
     * TESTING
     * <p>
     * Correct usage:
     * curl -i -H "Accept: application/xml" -X DELETE \
     *     http://localhost:8888/ImageServer-1.0.0/api/images/id/4
     * <p>
     * Non-existant album:
     * curl -i -H "Accept: application/xml" -X DELETE \
     *     http://localhost:8888/ImageServer-1.0.0/api/images/id/999999
     * <p>
     * @param integer $pAlbumId Required identifier of the album that contains
     * the images.
     */
    function id_delete ( $pImageId )
    {
        $pDbgFlag=false;

        $this->utils->start_method ( __METHOD__, $pDbgFlag );

        // Make sure the id is given.
        if ( !$pImageId )
        {
            $this->utils->delete ( _IS_URI_ID_ );
        }

        // Delete the images from the image table and the file system.
        try
        {
            $this->utils->debug_msg ( __METHOD__, 'Calling model->deleteImagesForAlbumId()...', true );
            $result = $this->{_IS_MODEL_IMAGES_}->deleteImage ( $pImageId, true );
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

        $this->utils->end_method ( __METHOD__, $pDbgFlag );

    }

}

/* End of file images.php */
/* Location: ImageServer-{version}/application/controllers/images.php */
