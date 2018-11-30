<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter PDF Library
 *
 * Generate PDF's in your CodeIgniter applications.
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Chris Harvey
 * @license			MIT License
 * @link			https://github.com/chrisnharvey/CodeIgniter-PDF-Generator-Library
 */
use Dompdf\Dompdf;

//require_once FCPATH.'../vendor/php-activerecord/php-activerecord/ActiveRecord.php';
//require_once( FCPATH.'../vendor/dompdf/dompdf/autoload.inc.php');


//require_once(dirname(__FILE__) . '/dompdf/dompdf_config.inc.php');

class Pdf extends DOMPDF
{
    /**
     * Get an instance of CodeIgniter
     *
     * @access	protected
     * @return	void
     */
    protected function ci()
    {
        return get_instance();
    }
    /**
     * Load a CodeIgniter view into domPDF
     *
     * @access	public
     * @param	string	$view The view to load
     * @param	array	$data The view data
     * @return	void
     */
    public function load_view($view)
    {
        //$html = $this->ci()->load->view($view, $data, TRUE);
        $html = $view;
        $this->loadHtml($html);
    }
}