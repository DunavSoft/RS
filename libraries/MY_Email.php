<?php

class MY_Email extends CI_Email {

    // перекрываем метод дл€ вызова исправленной функции _prep_q_encoding
    // неверно дроб€щей заголовки в utf8
    public function subject($subject)
    {
        $subject = $this->_prep_q_encoding_fix($subject);
        $this->_set_header_fix('Subject', $subject);
        return $this;
    }
    
    // перекрываем метод дл€ вызова исправленной функции _prep_q_encoding
    // неверно дроб€щей заголовки в utf8
    public function from($from, $name = '', $return_path = NULL)
    {
        if (preg_match( '/\<(.*)\>/', $from, $match))
        {
            $from = $match['1'];
        }

        if ($this->validate)
        {
            $this->validate_email($this->_str_to_array($from));
        }

        // prepare the display name
        if ($name != '')
        {
            // only use Q encoding if there are characters that would require it
            if ( ! preg_match('/[\200-\377]/', $name))
            {
                // add slashes for non-printing characters, slashes, and double quotes, and surround it in double quotes
                $name = '"'.addcslashes($name, "\0..\37\177'\"\\").'"';
            }
            else
            {
                $name = $this->_prep_q_encoding_fix($name, TRUE);
            }
        }

        $this->_set_header_fix('From', $name.' <'.$from.'>');
        $this->_set_header_fix('Return-Path', '<'.$from.'>');

        return $this;
    }
    
    // дублируем метод, т.к. обратитьс€ из наследника к защищенному методу родител€ нельз€
    private function _set_header_fix($header, $value)
    {
        $this->_headers[$header] = $value;
    }
    
    // доработанна€ _prep_q_encoding дл€ работы со строками в utf8
    private function _prep_q_encoding_fix($str, $from = FALSE)
    {
        $str = str_replace(array("\r", "\n", "\t"), array('', '', '_'), $str);
        return '=?utf-8?b?' . base64_encode( $str ) . '?=';
    }
	
	
	//
	/**
  * Add a Header Item
  *
  * A public function to allow extra headers to be inserted.
  *
  * @access public
  * @param string  $header
  * @param string  $value
  * @return void
  */
	public function set_header($header, $value)
	{
	  $this->_headers[$header] = $value;
	}
	
	 public function clear_debugger_messages()
	{
		$this->_debug_msg = array();
	}

	public function get_debugger_messages()
	{
		return $this->_debug_msg;
	}
    
}
