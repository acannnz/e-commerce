<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Dwoo Parser Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Parser
 * @license	 http://philsturgeon.co.uk/code/dbad-license
 * @link		http://philsturgeon.co.uk/code/codeigniter-dwoo
 */
 
class MY_Parser extends CI_Parser 
{
	private $_ci;

	public function __construct($config = array())
	{
		$this->_ci = & get_instance();
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a view file
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	public function parse($template, $data = array(), $return = false)
	{
		$string = $this->_ci->load->view($template, $data, true);

		return $this->_parse($string, $data, $return);
	}

	// --------------------------------------------------------------------

	/**
	 *  String parse
	 *
	 * Parses pseudo-variables contained in the string content,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	public function parse_string($string, $data = array(), $return = false)
	{
		return $this->_parse($string, $data, $return);
	}
	
	// --------------------------------------------------------------------

	/**
	 *  Parse
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	protected
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	protected function _parse($string, $data, $return = false)
	{
		// Start benchmark
		$this->_ci->benchmark->mark('parse_start');

		// Convert from object to array
		is_array($data) or $data = (array) $data;

		$data = array_merge($data, $this->_ci->load->_ci_cached_vars);

		$parser = new Lex\Parser();
		$parser->scopeGlue(':');
		$parser->cumulativeNoparse(true);
			
		$parsed = $parser->parse($string, $data, array($this, 'parser_callback'));
		
		// Finish benchmark
		$this->_ci->benchmark->mark('parse_end');
		
		// Return results or not ?
		if ( ! $return)
		{
			$this->_ci->output->append_output($parsed);
			return;
		}

		return $parsed;
	}

	// ------------------------------------------------------------------------

    /**
     * Parser Callback
     *
     * @param  string $module
     * @param  string $attribute
     * @param  string $content
     *
     * @return mixed
     */
    public function parser_callback($module, $attribute, $content)
    {
        $return_view = NULL;
        $parsed_return = '';
		
		$output = self::get_view($module,$attribute);
        $return_view = $output;

        //loop it up, if its array no use in the template, gotta work it here.
        if(is_array($output))
        {
            // Need to make sure we have a array and no objects inside the array too.

			$parser = new Lex\Parser();
			$parser->scopeGlue(':');
			
            foreach($output as $result)
            {
                $parsed_return .= $parser->parse($content, $result, array($this, 'parser_callback'));
            }

            unset($parser);

            $return_view =  $parsed_return;
        }

        return $return_view;
    }

    // ------------------------------------------------------------------------


    /**
     * Runs module or library callback methods.
     *
     * @access private
     *
     * @param  string $module    Module Class Name
     * @param  array  $attribute Attributes to run Method with
     * @param  string $method    Method to call.
     *
     * @return mixed
     */
    private function get_view($module = '', $attribute = array(), $method = 'index')
    {
        $return_view = false;

        // Get the required module
        $module = str_replace(':','/',$module);

        if(($pos = strrpos($module, '/')) != FALSE)
        {
            $method = substr($module, $pos + 1);
            $module = substr($module, 0, $pos);
        }

        if($class = $this->_ci->load->module($module))
        {
            //if the method is callable
            if (method_exists($class, $method))
            {
                ob_start();
                $output = call_user_func_array(array($class, $method), $attribute);
                $buffer = ob_get_clean();
                $output = ($output !== NULL) ? $output : $buffer;

                $return_view = $output;
            }
        }

        //maybe it is a library
        else if(!$return_view && strpos($module,'/') === FALSE)
        {
            if(class_exists($module))
            {
                ob_start();
                $output = call_user_func_array(array($module, $method), $attribute);
                $buffer = ob_get_clean();
                $output = ($output !== NULL) ? $output : $buffer;

                $return_view = $output;
            }

        }

        return $return_view;

    }
}

// END MY_Parser Class

/* End of file MY_Parser.php */

