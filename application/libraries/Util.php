<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/******************************************************************************\
|* This class contains utility methofds used throughout the application.
|* <p>
 * The methods support "debug" mode. If a boolean parameter is passed in to the
 * methods, he methods will print debugging information to the CI log.
 * <p>
|* @author knautz
|* <p>
|* @todo This should probably be a static class with static methods. Not sure
|* how CI works with static classes/methods.
|*
\******************************************************************************/

// Get the pplication constatnts
require_once APPPATH . '/imageserverapp.php';

class Util
{
    private $_ci;

    /**************************************************************************\
     * This method is the classd construcor.
     * <p>
     * This method is called when a Util object is created. I retrieves the CI
     * instance and loads both the album model and the IS exception library.
     \*************************************************************************/
    function __construct()
    {
        $this->_ci =& get_instance();

        // Load the model
        $this->_ci->load->model ( _IS_MODEL_ALBUMS_ );
        $this->_ci->load->library ( _IS_LIBRARY_IS_EXCEPTION_ );
    }

    public function start_method ( $pMethodName, $pDbgFlag )
    {
        if ( $pDbgFlag )
        {
            log_message ( 'debug', "---| In model->$pMethodName() |---" );
            log_message ( 'debug', "*** $pMethodName(): IN DEBUG MODE *** " );
        }
    }

    public function end_method ( $pMethodName, $pDbgFlag )
    {
        if ( $pDbgFlag )
            log_message ( 'debug', "---| Leaving model->$pMethodName() |---" );
    }

    public function debug_msg ( $pMethodName, $pMsg, $pDbgFlag )
    {
        if ( $pDbgFlag )
            log_message ( 'debug', "*** $pMethodName(): $pMsg" );
    }

    public function log_db_error ( $pErrNum, $pErrMsg )
    {
        $errArray = $this->_ci->db->error();
        log_message ( 'error', $pErrNum . _IS_MSG_THROWING_EXCEPTION_ . $pErrMsg );
        log_message ( 'error', $errArray['code'] . ':' . $errArray['message'] );
        return $errArray;
    }

    public function check_id_param ( $pAlbumId )
    {
        if ( !isset ( $pAlbumId ) )
        {
            // No ID
            log_message ( 'error', _IS_ENUM_MISSING_ID_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_MISSING_ID_ );
            throw new IS_Exception ( _IS_ENUM_MISSING_ID_ );
        }

        if (  $pAlbumId <= 0 )
        {
            // Bad ID
            log_message ( 'error', _IS_ENUM_BAD_ID_ . _IS_MSG_THROWING_EXCEPTION_ . _IS_EMSG_BAD_ID_ );
            throw new IS_Exception ( _IS_ENUM_BAD_ID_ );
        }
    }

    public function get_new_folder_name()
    {
        return ( uniqid ( '', true ) );
    }

    // From: http://php.net/manual/en/function.rmdir.php#98499
    // Just tell it what directory you want deleted, in relation to the page
    // that this function is executed. Then set $empty = true if you want the
    // folder just emptied, but not deleted. If you set $empty = false, or just
    // simply leave it out, the given directory will be deleted, as well.
    public function delete_all ( $directory, $empty = false )
    {
        if ( substr ( $directory, -1 ) == "/" )
        {
            $directory = substr ( $directory, 0, -1 );
        }

        if ( !file_exists ( $directory ) || !is_dir ( $directory ) )
        {
            return false;
        }
        else if ( !is_readable ( $directory ) )
        {
            return false;
        }
        else
        {
            $directoryHandle = opendir ( $directory );

            while ( $contents = readdir ( $directoryHandle ) )
            {
                if ( $contents != '.' && $contents != '..' )
                {
                    $path = $directory . "/" . $contents;

                    if ( is_dir ( $path ) )
                    {
                        delete_all ( $path );
                    }
                    else
                    {
                        unlink ( $path );
                    }
                }
            }

            closedir ( $directoryHandle );

            if ( $empty == false )
            {
                if ( !rmdir ( $directory ) )
                {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * Send a CI response.
     * <p>
     * This method sends an HTTP response via the REST_Controllers response
     * method.
     * @param IS_Exception $pRespData
     * @param type $pHttpRespCode
     */
    public function send_response ( $pRespData, $pHttpRespCode=0 )
    {
        $sendRespCode = _IS_HTTP_RESPONSECODE_200_;

        if ( $pRespData instanceof IS_Exception )
        {
            // --------------
            // Error response
            // --------------
            $sendData = array
                            (
                                _IS_RESPONSE_FIELD_CODE_ => $pRespData->getCode(),
                                _IS_RESPONSE_FIELD_MSG_=> $pRespData->getMessage()
                            );

            $dbCode = $pRespData->getDbCode();

            // We have a database error. Use the database return code to try
            // to send back a better response than "400 Bad Request".
            if ( $dbCode )
            {
                if ( $dbCode == 1062 )
                {
                    // 1062 is a MySQL duplicate entry error.
                    $sendRespCode = _IS_HTTP_RESPONSECODE_409_;
                }
            }
            elseif ( $pHttpRespCode > 0 )
            {
                $sendRespCode = $pHttpRespCode;
            }
            else
            {
                // If all else fails, send "400 Bad Request".
                $sendRespCode = _IS_HTTP_RESPONSECODE_400_;
            }
        }
        else
        {
            // -----------------------------------------------
            // Response that is not the result of an exception
            // -----------------------------------------------
            $sendData = $pRespData;
            if ( $pHttpRespCode > 0 )
            {
                $sendRespCode = $pHttpRespCode;
            }
            else
            {
                // If all else fails, just send "200 OK".
                $sendRespCode = _IS_HTTP_RESPONSECODE_200_;
            }
        }

        log_message ( 'info', "Sending response code $sendRespCode." );
        $this->_ci->response ( $sendData, $sendRespCode );
    }

    public function perform_query()
    {
        log_message ( 'debug', '---| In model->_performQuery() |---' );
        log_message ( 'debug', '*** _performQuery: IN DEBUG MODE *** ' );

        $dbgFlag = false;
        $queryArgs = array();

        $numArgs = func_num_args();
        $argList = func_get_args();

//        log_message ( 'debug', '*** _performQuery: original argList = ' . print_r($argList,true) );

        if ( is_bool ( $argList[$numArgs-1] ) )
        {
            // If the last element of the args array is a boolean, then assume
            // is is a debug flag and remove if from the array.
            $dbgFlag = array_pop ( $argList );
            if ( $dbgFlag )
                log_message ( 'debug', '*** _performQuery: argList after pop of dbgFlag = ' . print_r($argList,true) );
        }

        // The first argument is the query title
        $queryTitle = array_shift ( $argList );
        if ( $dbgFlag )
            log_message ( 'debug', '*** _performQuery: queryTitle = ' . $queryTitle );

        if ( $dbgFlag )
            log_message ( 'debug', '*** _performQuery: final argList = ' . print_r($argList,true) );

        $sql = $this->_ci->config->item ( $queryTitle );

        if ( $dbgFlag )
            log_message('debug', '*** _performQuery: $sql = ' . $sql);

        $result = $this->_ci->db->query ( $sql, $argList );

        if ( $dbgFlag )
            log_message('debug', '*** _performQuery: last query = ' . $this->_ci->db->last_query() );

        if ( $dbgFlag )
            log_message ( 'debug', '---| Leaving model->_performQuery() |---' );

        return $result;
    }

}
