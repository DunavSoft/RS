<?php
class Crunch
{
    var $files;

    public function __construct()
    {
        $this->files = [];
    }

    public function addFile($path)
    {
        if(!is_array($path))
        {
            $path = [$path];
        }

        foreach($path as $p)
        {
            $this->files[] = $p;
        }
        
    }
}

class CSSCrunch extends Crunch
{
    function crunch($dev=false)
    {
        $filename = md5(serialize($this->files)).'.css';

        if(!file_exists(FCPATH.'css/'.$filename))
        {
            $buffer = "";
            $dev_files = "";

            foreach ($this->files as $cssFile) {
				if(file_exists(FCPATH.$cssFile.'.css')) {
					$buffer .= file_get_contents(FCPATH.$cssFile.'.css');
					
					if($dev)
					{
						$dev_files .= theme_css($cssFile.'.css', true);
						continue;
					}
				}else
				{
					return lang('error_no_directory') . ' ' . FCPATH.$cssFile.'.css';
				}
            }

            if($dev)
            {
				return $dev_files;
            }

            // Remove comments
            $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
             
            // Remove space after colons
            $buffer = str_replace(': ', ':', $buffer);
             
            // Remove whitespace
            $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

			if(is_writable(FCPATH.'css')) {
				file_put_contents(FCPATH.'css/'.$filename, $buffer);
			}else {
				return lang('error_directory_no_writeable') . ' ' . FCPATH.'css/'.$filename;
			}
            
        }
        
        $this->files = [];
		
        return '<link href="'.theme_css($filename).'" type="text/css" rel="stylesheet" />';
    }

}

class JSCrunch extends Crunch
{

    function crunch($dev=false)
    {
        $filename = md5(serialize($this->files)).'.js';

        $buffer = "";
		$dev_files = "";

        if(!file_exists(FCPATH . $filename))
        {
            foreach ($this->files as $jsFile) {
				if(file_exists(FCPATH . $jsFile.'.js')) {
					$buffer .= file_get_contents(FCPATH . $jsFile.'.js');

					if($dev)
					{
						$dev_files .= theme_js($jsFile.'.js', true);
						continue;
					}
				}else {
					return lang('error_no_directory') . ' ' . FCPATH . $jsFile.'.js';
				}
            }
			
            if($dev)
            {
                return $dev_files;
            }

			if(is_writable(FCPATH.'/js')) {
				file_put_contents(FCPATH.'/js/'.$filename, $buffer);
			}else {
				return lang('error_directory_no_writeable') . ' ' . FCPATH.'/js/'.$filename;
			}
        }

		$this->files = [];
        return '<script type="text/javascript" src="'.theme_js($filename).'"></script>';
    }
}