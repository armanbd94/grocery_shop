<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** Begin :: Edit, View And Delete Button Icon With Text **/
    protected $edit_icon   = '<i class="fas fa-edit text-info"></i>&nbsp;Edit';
    protected $view_icon   = '<i class="fas fa-eye text-success"></i>&nbsp;View';
    protected $delete_icon = '<i class="fas fa-trash text-danger"></i>&nbsp;Delete';
     /** End :: Edit, View And Delete Button Icon With Text **/

     /** Begin :: Image Uploading Method. It has 3 arguments (input file, old file name if have else give null,file directory path) **/
    protected function upload_image($request_file,$old_file = null,$path){
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $target_dir      = $path;         
        $filenameWithExt = $request_file->getClientOriginalName(); // Get filename with extension like index.jpg
        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME); // Get just filename  like index          
        $extension       = $request_file->getClientOriginalExtension(); // Get just extension like .jpg

        $fileNameToStore = $filename.uniqid().'.'.$extension; //Filename to store  like index1545gfh5465.jpg                
        $request_file->move($target_dir, $fileNameToStore); //move file in targetted folder
        if (!empty($old_file)) {
            unlink($target_dir.$old_file);  //remove old file from folder
        }

        return $fileNameToStore;
    }

    /** Begin :: Datatable Server Site Draw Custom Built Method (Don't Ever Touch This Method) **/
    protected function dataTableDraw($draw,$count_all,$count_filtered, $data){
        return $output = array(
            "draw"            => $draw,
            "recordsTotal"    => $count_all,
            "recordsFiltered" => $count_filtered,
            "data"            => $data
        );
    }
    /** End :: Datatable Server Site Draw Custom Built Method **/

    //Variable Custom Dump Function
    function dp($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;

    }

    //Custom XSS(Cross Site Scripting) Validate Method
    public function validate($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
    public function dateFormat($data)
    {
        return date('jS F, Y',strtotime($data));
    }
    

    
}
