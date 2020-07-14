<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use DB;
use Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductUnit;
use App\Models\ProductHasCategory;
use App\Models\ProductHasImage;
use App\Models\ProductPriceDetail;

class ProductController extends Controller
{
    /** THIS ARRAY WORKING AS A VALIDATION RULES **/
    protected $rules = array();

    public function index(){
        if(in_array('Product List',session()->get('permission'))){

            $page_title = 'Product List'; // page title value
            $page_icon  = 'fas fa-th-list'; // page icon value
            $brands     = Brand::brands(['status'=>1]); // get brand list from brands method of brand model
            return view('dashboard.product.product', compact('page_title','page_icon','brands'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function getList(Request $request)
    {
        if(in_array('Product List',session()->get('permission'))){
            if($request->ajax()){

                $product = new Product();

                if(!empty($request->product_name)){
                    $product->setProductName($request->product_name);
                }
                if(!empty($request->brand_id)){
                    $product->setBrandID($request->brand_id);
                }
                if(!empty($request->status)){
                    $product->setStatus($request->status);
                }

                $product->setSearchValue($request->input('search.regex'));
                $product->setOrderValue($request->input('order.0.column'));
                $product->setDirValue($request->input('order.0.dir'));
                $product->setLengthValue($request->input('length'));
                $product->setStartValue($request->input('start'));

                $list = $product->getList();
                
                $data = array();
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    if($value->status == 1){
                        $status = 'checked';
                    }else{
                        $status = '';
                    }
                    if(!empty($value->featured_image)){
                        $featured_image = '<img class="category_icon" src="'.asset(PRODUCT_IMAGE_PATH.$value->featured_image).'" alt="Featured Image" />';
                    }else{
                        $featured_image = '<img class="category_icon" src="'.asset(UPLOAD_PATH).'/no-image-available.png" alt="No Image Avaliable" />';
                    }

                    $action = '';

                    if(in_array('Product View',session()->get('permission'))){
                        $action .= '<li><a href="'.url("/product/view",$value->id).'">'.$this->view_icon.'</a></li>';
                    }
                    if(in_array('Product Delete',session()->get('permission'))){
                        $action .= '<li><a class="delete_data" data-id="'.$value->id.'" >'.$this->delete_icon.'</a></li>';
                    }
                    $btngroup = '<div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-outline btn-primary dropdown-toggle"><i class="fas fa-th-list"></i></button>
                                    <ul class="dropdown-menu  pull-right">
                                        ' . $action . '
                                    </ul>
                                </div>';
                    if(!empty($value->brand_name)){
                        $brand_name = $value->brand_name;
                    }else{
                        $brand_name = '<span class="m-badge m-badge--wide m-badge--danger font-weight-bold"> No Brand </span>';
                    }
                    $price_weight_data = $this->get_featured_price($value->id);
                    $price = $weight ='';
                    if(!empty($price_weight_data)){
                        $price = $price_weight_data['price'];
                        $weight = $price_weight_data['weight'];
                    }
                    $row    = array();
                    $row[]  = $no;
                    $row[]  = $featured_image;
                    $row[]  = $value->product_name;
                    $row[]  = $brand_name;
                    $row[]  = $weight;
                    $row[]  = $price;

                    if(in_array('Product Edit',session()->get('permission'))){
                    $row[]  = '<span class="m-switch m-switch--icon m-switch--primary">
                                    <label>
                                        <input type="checkbox" class="change_status" data-id="' . $value->id . '" name="status" '.$status.'>
                                        <span></span>
                                    </label>
                                </span>';
                    }
                    if(in_array('Product Delete',session()->get('permission'))
                        || in_array('Product View',session()->get('permission'))){
                    $row[]  = $btngroup;
                    }

                    $data[] = $row;

                }
                $output = $this->dataTableDraw($request->input('draw'), $product->count_all(),
                                                $product->count_filtered(),$data);


                echo json_encode($output);
            }
        }
    }

    private function get_featured_price($product_id)
    {
        $query = ProductPriceDetail::product_price_details(['product_id' => $product_id]);
        $data = [];
        if(!empty($query)){
            foreach ($query as $item) {
                if ($item->is_default == 1){
                    $data = [
                        'price'  => 'QAR '.number_format($item->price,2),
                        'weight' => $item->weight.$item->unitname
                    ];
                }
            }
        }
        return $data;
    }
    /********************************/

    public function create()
    {
        if(in_array('Product Add',session()->get('permission'))){
            $page_title = 'Product Add'; // page title value
            $page_icon  = 'fas fa-plus-square'; // page icon value
            $categories = $this->show_category();// get category list from categories method of category model
            $brands     = Brand::brands(['status'=>1]); // get brand list from brands method of brand model
            $units      = ProductUnit::units(); // get unit list from units method of product unit model
            return view('dashboard.product.product-add', compact('page_title','page_icon','categories','brands','units'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function show_category(){
        $category = '';
        $category .= $this->multilevel_category();
        return $category;
    }
    function multilevel_category($parent_id = NULL, $level = 0)
    {
        $category = '';
        if($parent_id == 0){
            
       /* $categories=Category::where(['id' => 11,'status'=>1])->orderBy('category_name','asc')->get(); */

            $categories = Category::where(['parent_id' => 0,'status'=>1])->orderBy('category_name','asc')->get();
        }else{
            $categories = Category::where(['parent_id' => $parent_id,'status'=>1])->orderBy('category_name','asc')->get(); 
        }

        if(!empty($categories)){
            foreach ($categories as $value) {
                $category .= '<option value="'.$value->id.'">'. str_repeat("&#9866;", $level).' '.$value->category_name.'</option>'.PHP_EOL;
                $subcat = Category::where(['parent_id' =>$value->id,'status'=>1])->orderBy('category_name','asc')->get(); 
                if(!empty($subcat) && count($subcat) > 0){
                    $category .= $this->multilevel_category($value->id,$level+1);
                }else{
                    $category .= $this->multilevel_category($value->id);
                }
                
                
            }
        }
        return $category;
    }



    /*************/

    public function store(Request $request)
    {
        if($request->ajax()){


            $this->rules['category']          = 'required';
            $this->rules['product_name']      = 'required|string';
            $this->rules['weight']            = 'required';
            $this->rules['unit']              = 'required';
            $this->rules['price']             = 'required|numeric|min:0.1';
            $this->rules['featured_image']    = 'image|mimes:jpeg,png,jpg';

            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $json = array(
                    'errors' => $validator->errors()
                );
            } else {
                $product = new Product();
                $featured_data = [];
                $product->brand_id      = $request->brand_id;
                $product->product_name  = $request->product_name;
                
                $product->product_variation  = $request->product_variation;
                
                $product->description   = $request->description ;
                $product->creator_id    = auth()->user()->id;             
                $product->modifier_id   = auth()->user()->id;             
                $product->created_at    = DATE;
                $product->updated_at    = DATE;

                $target_dir = PRODUCT_IMAGE_PATH;
                if(!empty($request->file('featured_image'))){
                    $featured_image          = '';
                    
                    $product->featured_image = $this->upload_image($request->file('featured_image'),$featured_image,$target_dir);
                }

                $product->save();

                if ($product) {
                    
                    
                    $product_slug = $this->custom_slug($request->brand_id,$request->product_name,$request->product_variation,$request->weight,$request->unit,$product->id);
                    $slug_update = Product::find($product->id);
                    $slug_update->product_slug = $product_slug;
                    $slug_update->update();
                    //insert category data 
                    $this->insert_product_category($request->category,$product->id); //argument category id and product id

                    //product featureed data array
                    $product_featured_data[] = [
                        'product_id'    => $product->id,
                        'weight'        => $request->weight,
                        'unitname'      => $request->unit,
                        'price'         => $request->price,
                        'is_default'    => 1,
                        "created_at"    => DATE,
                        "updated_at"    => DATE,
                    ];
                    
                    //product additional weughtwise price data
                    $weightwise_price = [];
                    $product_weightwise_price = $request->weightwise_price; //data recevived from local storage variable
                    if ($product->id && !empty($product_weightwise_price)) {
                        foreach ($product_weightwise_price as $item) {
                            $weightwise_price[] = array(
                                "product_id"    => $product->id,
                                "weight"        => $item["weight"],
                                "unitname"      => $item["unitname"],
                                "price"         => $item["price"],
                                'is_default'    => 2,
                                "created_at"    => DATE,
                                "updated_at"    => DATE,
                            );
                        }
                        
                        
                    }
                    $merged_data = array_merge($product_featured_data,$weightwise_price);
                    ProductPriceDetail::insert($merged_data);
                    

                    //product additional image upload
                    if($request->hasFile('product_additional_image')){

                       $this->product_additional_image_upload($request->product_additional_image,$product->id,$target_dir);
                    }
                    $json['success'] = 'Data saved successfully';
                       
                }else{
                    $json['error']   = 'Data can not save';
                }
            }
            return Response::json($json);
            
        }else{
           return redirect()->back()->with('error','Unauthorized Access blocked!');
        }
    }

    private function custom_slug($brand_id='',$product_name,$product_variation='',$weight,$unit,$product_id){
        if(!empty($brand_id) && !empty($product_variation)){
            $brand = Brand::find($brand_id);
            if(!empty($brand)){
                $url = (PRODUCT_SLUG_PREFIX + $product_id).' '.$brand->brand_name.' '.$product_variation.' '.$product_name.' '.$weight.$unit;
            }else{
                $url = (PRODUCT_SLUG_PREFIX + $product_id).' '.$product_variation.' '.$product_name.' '.$weight.$unit;
            }
        }elseif (!empty($brand_id) && empty($product_variation)) {
            $brand = Brand::find($brand_id);
            if(!empty($brand)){
                $url = (PRODUCT_SLUG_PREFIX + $product_id).' '.$brand->brand_name.' '.$product_name.' '.$weight.$unit;
            }
        
        }elseif (empty($brand_id) && !empty($product_variation)) {

            $url = (PRODUCT_SLUG_PREFIX + $product_id).' '.$product_variation.' '.$product_name.' '.$weight.$unit;
            
        }else{
            $url = (PRODUCT_SLUG_PREFIX + $product_id).' '.$product_name.' '.$weight.$unit;
        }

        return $this->formatUrl($url);
    }

    private function formatUrl($str, $sep='-')
    {

            $res = strtolower($str);
            $res = preg_replace('/[^[:alnum:]]/', ' ', $res);
            $res = preg_replace('/[[:space:]]+/', $sep, $res);
            return trim($res, $sep);
    }

    protected function product_additional_image_upload($file_name,$product_id,$target_dir){
        $image_array = [];
        foreach ($file_name as $key => $value) {

            $img_name = $this->upload_image($value,$img_name = '',$target_dir);
            $image_array[] = [
                'product_id'     => $product_id,
                'image'          => $img_name,
                'created_at'     => DATE,
                'updated_at'     => DATE
            ];
        }
        ProductHasImage::insert($image_array);
         return true;
    }

    protected function insert_product_category($categories, $product_id){
        $product_has_category = array();
        foreach ($categories as $value) {
            $cat_data = Category::find($value);
            $product_has_category[] = array(
                'product_id'  => $product_id,
                'category_id' => $value,
                'category_parent_id' => $cat_data->parent_id,
                'created_at'  => DATE,
                'updated_at'  => DATE,
            );
        }
        ProductHasCategory::insert($product_has_category);
        return true;
    }

    /** BEGIN:: DATA SHOW BY THIS METHOD **/
    public function show($id)
    {
        if(in_array('Product View',session()->get('permission'))){
            if((int)$id){
                $page_title = 'Product Details'; // page title value
                $page_icon  = 'fas fa-th-list'; // page icon value
                $product_variation = '';
                $product_data = DB::table('products as p')
                                ->select('p.*','b.brand_name')
                                ->leftjoin('brands as b','p.brand_id','=','b.id')
                                ->where('p.id',$id)
                                ->first();
                if($product_data){
                    $weightwise_price = ProductPriceDetail::select('id','product_id','weight','unitname','price','is_default')
                                                    ->where(['product_id'=>$id])
                                                    ->orderBy('is_default','asc')
                                                    ->get();
                    if(!empty($weightwise_price)){
                        $product_variation = view('dashboard.product.product-variation',compact('weightwise_price'))->render();
                    }
                    
                    $product_has_categories = ProductHasCategory::where('product_id',$id)->get();
                    $product_category = [];
                    if(!empty($product_has_categories)){
                        foreach ($product_has_categories as $value) {
                            array_push($product_category,$value->category_id);
                        }
                    }
                    $data['product_category'] = $product_category;
                    $data['weightwise_price'] = $weightwise_price; // get category list from categories method of category model
                    $data['categories'] = Category::categories(['status'=>1]); // get category list from categories method of category model
                    $data['brands']     = Brand::brands(['status'=>1]); // get brand list from brands method of brand model
                    $data['units']      = ProductUnit::units(); // get unit list from units method of product unit model
                    return view('dashboard.product.product-view', compact('page_title','page_icon','product_data','product_variation','data'));
                }else{
                    return redirect('/error')->with('error','Product not exist!');
                }
            }else{
                return redirect('/error')->with('error','Undefined value rejected.');
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
        
    }
    public function product_price_list(Request $request){
        if($request->ajax()){
            $pid = $request->product_id;
            if(!empty($pid)){
                $weightwise_price = ProductPriceDetail::select('id','product_id','weight','unitname','price','is_default')
                                                ->where(['product_id'=>$pid])
                                                ->orderBy('is_default','asc')
                                                ->get();
                return view('dashboard.product.product-variation',compact('weightwise_price'))->render();
            }
                    
        }
    }
    public function product_image_list(Request $request){
        if($request->ajax()){
            $pid = $request->product_id;
            if(!empty($pid)){
                $data = ProductHasImage::product_images(['product_id'=>$pid]);
                return view('dashboard.product.product-images',compact('data'))->render();
            }
            
        }
        
    }
    /** END:: DATA SHOW BY THIS METHOD **/

    /** BEGIN:: DATA UPDATE BY THIS METHOD **/
    public function update(Request $request)
    {
        if(in_array('Product Edit',session()->get('permission'))){
            if($request->ajax()){

                $this->rules['category']          = 'required';
                $this->rules['product_name']      = 'required|string';
                $this->rules['weight']            = 'required';
                $this->rules['unit']              = 'required';
                $this->rules['price']             = 'required|numeric|min:0.1';
                $this->rules['featured_image']    = 'image|mimes:jpeg,png,jpg';

                $validator = Validator::make($request->all(), $this->rules);
                if ($validator->fails()) {
                    $json = array(
                        'errors' => $validator->errors()
                    );
                } else {
                    $id      = (int)$request->id;
                    $product = Product::findOrFail($id);

                    $featured_data          = [];
                    $product->brand_id      = $request->brand_id;
                    $product->product_name  = $request->product_name;
                    $product->product_slug  = $this->custom_slug($request->brand_id,$request->product_name,$request->product_variation,$request->weight,$request->unit,$product->id);
                    $product->product_variation = $request->product_variation;
                    $product->description   = $request->description;           
                    $product->modifier_id   = auth()->user()->id; 
                    $product->updated_at    = DATE;

                    $target_dir = PRODUCT_IMAGE_PATH;
                    if(!empty($request->file('featured_image'))){
                        $featured_image          = $product->featured_image;
                        $product->featured_image = $this->upload_image($request->file('featured_image'),$featured_image,$target_dir);
                    }

                    $product->update();

                    if ($product) {
                        
                        //insert category data 
                        if(!empty($request->category)){
                            ProductHasCategory::where(['product_id'=>$id])->delete();
                            $this->insert_product_category($request->category,$product->id); //argument category id and product id
                        }
                        

                        $product_price = ProductPriceDetail::where(['product_id'=>$id,'is_default'=>1])->first();

                        $product_price->product_id = $product->id;
                        $product_price->weight     = $request->weight;
                        $product_price->unitname   = $request->unit;
                        $product_price->price      = $request->price;
                        $product_price->updated_at = DATE;
                        $product_price->update();
                        
                        $json['success'] = 'Data saved successfully';
                        
                    }else{
                        $json['error']   = 'Data can not save';
                    }
                }
                return Response::json($json);
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function store_product_price(Request $request)
    {
        if(in_array('Product Edit',session()->get('permission'))){
            if($request->ajax()){

                $this->rules['weight']    = 'required';
                $this->rules['unitname']  = 'required';
                $this->rules['price']     = 'required|numeric|min:0.1';

                $validator = Validator::make($request->all(), $this->rules);
                if ($validator->fails()) {
                    $json = array(
                        'errors' => $validator->errors()
                    );
                } else {

                    $product_price = new ProductPriceDetail();

                    $product_price->product_id = (int)$request->product_id;
                    $product_price->weight     = $request->weight;
                    $product_price->unitname   = $request->unitname;
                    $product_price->price      = $request->price;
                    $product_price->created_at = DATE;
                    $product_price->updated_at = DATE;
                    $product_price->save();

                    if ($product_price) {
                        $json['success'] = 'Data saved successfully';                       
                    }else{
                        $json['error']   = 'Data can not save';
                    }
                }
                return Response::json($json);
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }
    public function product_price_update(Request $request){
        if($request->ajax()){
            // dd($request->all());
            if(!empty($request->hidden_id)){
                foreach($request->hidden_id as $key => $value){
                    $update_price = ProductPriceDetail::find($value);
                    if($update_price){
                        $update_price->weight = $request->weight[$key];
                        $update_price->unitname = $request->unitname[$key];
                        $update_price->price = $request->price[$key];
                        $update_price->updated_at = DATE;
                        $update_price->update();
                    }
                }
                $json['success'] = 'Updated successfully';
            }
            return Response::json($json);
        }
        
    }
    public function upload_product_image(Request $request){
        if($request->hasFile('product_image')){
            $image_array = [];
            $target_dir = PRODUCT_IMAGE_PATH;
            foreach ($request->product_image as $key => $value) {

                $img_name = $this->upload_image($value,$img_name = '',$target_dir);
                $image_array[] = [
                    'product_id'     => $request->product_id,
                    'image'          => $img_name,
                    'created_at'     => DATE,
                    'updated_at'     => DATE
                ];
            }
            $upload = ProductHasImage::insert($image_array);
            if($upload){
                $json['success'] = 'Uploaded successfully';
            }else{
                $json['error'] = 'Error in upload.';
            }
        }else{
            $json['error'] = 'Please select image.';
        }
        return Response::json($json);
    }
    /** END:: DATA UPDATE BY THIS METHOD **/

    /** BEGIN:: DATA DELETE BY THIS METHOD **/
    public function destroy(Request $request){
        if(in_array('Product Delete',session()->get('permission'))){
            if($request->ajax()){
                $id = $request->id;
                $related_data_exist = DB::table('order_has_products')->where('product_id',$id)->get();
                if(count($related_data_exist) > 0){
                    $json['status']  = 'error';
                    $json['message'] = 'This data is related with other table data. At first delete those data.';
                }else{
                    $product_category_delete = ProductHasCategory::where('product_id',$id)->delete();
                    $product_price_delete    = ProductPriceDetail::where('product_id',$id)->delete();
                    $product_image_delete    = ProductHasImage::where('product_id',$id)->get();
                    if( $product_category_delete && $product_price_delete){
                        $target_dir = PRODUCT_IMAGE_PATH;
                        if(!empty($product_image_delete)){
                            if(count($product_image_delete) > 0){
                                foreach ($product_image_delete as $remove_image) {
                                    if(!empty($remove_image->image)){
                                        if (is_dir($target_dir)) {
                                            unlink($target_dir.$remove_image->image);
                                        }
                                    }
                                }
                            }
                        }
                        $product_delete = Product::find($id);
                        if(!empty($product_delete)){
                            if(!empty($product_delete->featured_image)){
                                if (is_dir($target_dir)) {
                                    unlink($target_dir.$product_delete->featured_image);
                                }
                            }
                        }
                        $product_delete->delete();
                        if ($product_delete) {
                            $json['status'] = 'success';
                            $json['message'] = 'Data has been deleted successfully.';
                        } else {
                            $json['status'] = 'error';
                            $json['message'] = 'Unable to delete data.';
                        }
                    }else{
                        $json['status'] = 'error';
                        $json['message'] = 'Unable to delete data.';
                    }
                    
                }
                
                return Response::json($json);
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    public function delete_product_price(Request $request){
        if(in_array('Product Delete',session()->get('permission'))){
            if($request->ajax()){
                $id  = (int)$request->id;
                $product_price_delete  = ProductPriceDetail::find($id)->delete();
                if($product_price_delete){
                    $json['status']  = 'success';
                    $json['message'] = 'Data has been deleted successfully.';

                }else{
                    $json['status']  = 'error';
                    $json['message'] = 'Unable to delete data.';
                }
                return Response::json($json);
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }
    public function delete_product_image(Request $request){
        if(in_array('Product Delete',session()->get('permission'))){
            if($request->ajax()){
                $id  = (int)$request->id;
                $product_image_delete  = ProductHasImage::find($id);
                    if($product_image_delete){
                        $target_dir = PRODUCT_IMAGE_PATH;
                        if(!empty($product_image_delete->image)){
                            if (is_dir($target_dir)) {
                                unlink($target_dir.$product_image_delete->image);
                            }
                        }

                        $delete = $product_image_delete->delete();
                        if ($delete) {
                            $json['status']  = 'success';
                            $json['message'] = 'Image has been removed successfully.';
                        } else {
                            $json['status']  = 'error';
                            $json['message'] = 'Unable to delete data.';
                        }
                    }else{
                        $json['status']  = 'error';
                        $json['message'] = 'Unable to delete data.';
                    }
                    
                
                
                return Response::json($json);
            }
            
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }
    /** END:: DATA DELETE BY THIS METHOD **/

    /** BEGIN:: STATUS CHANGE BY THIS METHOD **/
    public function changeStatus(Request $request){
        if($request->ajax()){
            if(in_array('Product Edit',session()->get('permission'))){
                $id     = $request->id;
                $status = $request->status;
                if((int)$id && (int)$status){
                    
                    $data = Product::find($id);

                    $data->status      = $status;
                    $data->modifier_id = auth()->user()->id;    
                    $data->updated_at  = DATE;
                    if($data->update()){
                        $json['success'] = 'Status has been changed successfully';
                    }else{
                        $json['error'] = 'Status can not change';
                    }
                    
                }else{
                    $json['error'] = 'Status can not change';
                }
                return Response::json($json);
            }else{
                return redirect('/error')->with('error','You do not have permission to access this page.');
            }

        }
    }
    /** END:: STATUS CHANGE BY THIS METHOD **/
    


}
