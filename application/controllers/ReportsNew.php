<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 10/30/18
 * Time: 3:23 PM
 */

class ReportsNew extends MY_Controller {


    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['buildings'] = Building::find('all');
        $this->load->view('common/header');
        $this->load->view('report/reportsnew_home',$data);
        $this->load->view('common/footer');
    }


    public function report_expenses_bill($building_id)
    {
        ini_set('memory_limit', '-1');

        $id_building = $building_id; //$this->input->post("building_id", true);

        if ($id_building != "") {

            $building = Building::find($id_building);
            $data['properties'] = $building->properties;
            $data['building'] = $building;

            if ($building->payment_type <> BUILDING_PAYMENT_TYPE_MANUAL) {
                $view = $this->load->view('reportHTML/expense_bank_bill_single', $data, true);
            } else {
                $view = $this->load->view('reportHTML/expense_bill', $data);
            }

            $this->downloadPdf($view);

        }else
            echo 'error';
    }


    function downloadPdf($view = null){

        if(empty($view))
           echo json_encode(array('status'=>'error', 'message'=> 'View Empty'));

        $this->load->library('Pdf');
        $this->load->helper('download');


        $this->pdf->load_view($view);
        //$render = $this->pdf->render();
        //$stream = $this->pdf->stream("welcome.pdf");

        $this->pdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
       $render = $this->pdf->render();

// Output the generated PDF to Browser
       $stream = $this->pdf->stream();

         // var_dump($render);
        //var_dump($stream);


       // force_download('welcome.pdf' , $stream);

    }

}