<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Validator;
use Response;
use DB;

class BannerController extends Controller
{
    public function index()
    {
        if(in_array('Banner List',session()->get('permission'))){
            $page_title = 'Banner List';
            $page_icon  = 'far fa-image';
            $baners = Banner::all();
            return view('dashboard.banner.banner', compact('page_title','page_icon','banners'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function getList(Request $request)
    {
        if($request->ajax()){
            $slide_images = Banner::orderBy('sequence','asc')->get();

            $output = '';
    
            if(count($slide_images) > 0){
                $output .= '<div class="row" id="img-list">';
                foreach ($slide_images as  $img) {
                    if(!empty($img->title)){
                        $title = $img->title;
                    }else{
                        $title = '';
                    }
                    $output .= '<div class="col-lg-5 text-center mb-25">
                                    <div class="col-lg-12 pt-20 pb-20 shadow-effect" style="">
                                        <div class="house-product-img-box">
                                            <img class="lazyload" src="'.asset(LOADING_ICON).'" data-src="'.asset(BANNER_IMAGE_PATH.$img->image).'" alt="'.$title.'">
                                        </div>
                                        <h6 style="margin-top:15px;"><b>'.$title.'</b><br><b>(Sequence-'.$img->sequence.')</b></h6>
                                        <button type="button" onclick="delete_item('.$img->id.')" class="btn btn-primary btn-sm delete-img-btn" data-toggle="tooltip" title="Remove Image"><i class="fas fa-times"></i></button>
                                    </div>  
                                </div>';
                    
                }
                
            }else{
                $output .= '<div class="col-12 col-lg-12 text-center"><img src="'.asset(NO_IMAGE).'" alt="No Image Available" style="margin: 0 auto;"></div>'; 
            }
            $output .= '</div>';
            return Response::json($output);
        }
    }

    public function store(Request $request)
    {
        
        if($request->ajax()){

            $rules = [
                'title'         => 'nullable|unique:banners',
                'slider_image'  => 'required',
                'sequence'      => 'required|unique:banners|array',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $json = array(
                    'errors' => $validator->errors()
                );
            } else {

                
                if($request->hasFile('slider_image')){

                    $array = [];
                    $target_dir = BANNER_IMAGE_PATH;

                    foreach ($request->slider_image as $key => $value) {

                        $img_name = $this->upload_image($value,$img_name = '',$target_dir);
                        $array[] = [

                            'title'       => $request->title[$key],
                            'image'       => $img_name,
                            'link'        => $request->link[$key],
                            'sequence'    => $request->sequence[$key],
                            'created_at'  => DATE,
                            'updated_at'  => DATE
                        ];
                    }
                    $data = Banner::insert($array);
                    if($data){
                        $json['success'] = 'Image uploaded successfully';
                    }else{
                        $json['error']   = 'Image can not upload';
                    }
                }else {
                   $json['error']   = 'Please select at least one image';
                }
            }
            return Response::json($json);
       }else{
           return redirect()->back()->with('error','Unauthorized Access blocked!');
       }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()){
            $id = (int)$request->id;
            if(!empty($id)){
                $remove_image = Banner::find($id);
        
                if(!empty($remove_image->image)){
                    $target_dir = BANNER_IMAGE_PATH;
                    unlink($target_dir.$remove_image->image);
                }
                $remove_image->delete();
                if ($remove_image) {
                    $json['status']  = 'success';
                    $json['message'] = 'Image Removed Successfully ...';
                } else {
                    $json['status']  = 'error';
                    $json['message'] = 'Unable to remove image ...';
                }
            }else{
                $json['status']  = 'error';
                $json['message'] = 'undefined value ...';
            }
            
            return Response::json($json);
        }
    }
}
