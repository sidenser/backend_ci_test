<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Class MY_Input
 */
class MY_Input extends CI_Input
{
    /**
     * Fetch an item from the php://input stream
     *
     * Useful when you need to access PUT, DELETE or PATCH request data.
     *
     * @param	string	$index		Index for item to be fetched
     * @param	bool	$xss_clean	Whether to apply XSS filtering
     * @return	mixed
     */
    public function input_stream($index = NULL, $xss_clean = NULL)
    {
        if(!$this->_input_stream && preg_match('/application\/json/', $this->request_headers()['Content-Type'] ?? ''))
        {
            $this->_input_stream = json_decode($this->raw_input_stream, true);
        }

        return parent::input_stream($index, $xss_clean);
    }
}