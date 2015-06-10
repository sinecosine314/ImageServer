<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/imageserverapp.php';

/**
 * Description of IS_Exceptions
 *
 * @author knautz
 */
class IS_Exception extends Exception
{
    protected $dbCode = '';

    protected $exceptionMessages = array
    (
        _IS_ENUM_DB_DELETE_ERROR_          =>  _IS_EMSG_DB_DELETE_ERROR_,
        _IS_ENUM_DB_INSERT_ERROR_          =>  _IS_EMSG_DB_INSERT_ERROR_,
        _IS_ENUM_DB_SELECT_ERROR_          =>  _IS_EMSG_DB_SELECT_ERROR_,
        _IS_ENUM_DB_UPDATE_ERROR_          =>  _IS_EMSG_DB_UPDATE_ERROR_,
        _IS_ENUM_MISSING_ID_               =>  _IS_EMSG_MISSING_ID_,
        _IS_ENUM_BAD_ID_                   =>  _IS_EMSG_BAD_ID_,
        _IS_ENUM_CANNOT_CREATE_DIR_        =>  _IS_EMSG_CANNOT_CREATE_DIR_,
        _IS_ENUM_CANNOT_DELETE_DIR_        =>  _IS_EMSG_CANNOT_DELETE_DIR_,
        _IS_ENUM_MISSING_PARAMETER_        =>  _IS_EMSG_MISSING_PARAMETER_,
        _IS_ENUM_ALBUM_ALREADY_EXISTS_     =>  _IS_EMSG_ALBUM_ALREADY_EXISTS_,
        _IS_ENUM_UPLOAD_DO_UPLOAD_FAILED_  =>  _IS_EMSG_UPLOAD_DO_UPLOAD_FAILED_
    );


    //public function __construct ( $message, $code = 0, Exception $previous = null )

    /**
     * _construct - IS_Exception constructor.
     *
     * @param integer $pCode The IS error code corresponding to the error.
     */
    public function __construct ( $pCode=0 , $pDbEnum='', $pDbEmsg='' )
    {
        //log_message ( 'info', 'In ' . __METHOD__ );
        //log_message ( 'info', $this->getTraceAsString() );

        // Create a PHP exception.
        parent::__construct ( '', $pCode, null );

        if ( $pCode !== 0 )
        {
            if ( $this->_is_database_error ( $pCode) )
            {
                // Load the database
                //$pCI->load->database();

                // Databse errors need to have the SQL error and message in them.
                //$message = "{$this->exceptionMessages[$pCode]}: {$pCI->db->_error_message()}";
                $message = "{$this->exceptionMessages[$pCode]}: $pDbEmsg}";
                $this->dbCode = $pDbEnum;
                $this->message = $message;
                log_message ( 'info', $this->message );
            }
            else
            {
                $this->message = $this->exceptionMessages[$pCode];
                log_message ( 'info', "[$pCode] {$this->exceptionMessages[$pCode]}" );
            }
        }
    }

    public function getDbCode()
    {
        return $this->dbCode;
    }

    private function _is_database_error ( $pCode )
    {
        if ( $pCode >= _IS_ENUM_DATABASE_ERROR_BASE_ &&
             $pCode < (_IS_ENUM_DATABASE_ERROR_BASE_ + _IS_NUM_DATABASE_ERRORS_ ) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

/* End of file IS_Exception.php */
/* Location: ImageServer-{version}/application/libraries/IS_Exception.php */
