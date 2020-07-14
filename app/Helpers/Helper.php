<?php 
namespace App\Helpers;

use Auth;
use DB;
use Illuminate\Support\Facades\Route;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Category;
use App\Models\ProductPriceDetail;
use App\Models\District;
use App\Models\Product;
class Helper
{
    public static function get_menu(){
        $role_id         = Auth::user()->role_id;        
        $current_route   = Route::getFacadeRoot()->current()->uri();        
        $current_submenu = explode("/", $current_route);

        if ($role_id == 1) {
            $menus = DB::table('menus')
                            ->where('parent_id', null)
                            ->orderBy('sequence', 'desc')
                            ->get();
        } else {

            $menus = DB::table('menu_permissions')
                ->select('menu_permissions.menu_id', 'menu_permissions.role_id', 'menus.*')
                ->join('menus', 'menu_permissions.menu_id', '=', 'menus.id')
                ->where(['menus.parent_id' => null, 'menu_permissions.role_id' => $role_id])
                ->orderBy('menus.sequence', 'desc')
                ->get();
        }
        if (count($menus) > 0) {
            $menus_array = array();
            foreach ($menus as $menu) {
                $sub_menus_array = array();

                if ($role_id == 1) {

                    $sub_menus = DB::table('menus')
                        ->where('parent_id', $menu->id)
                        ->orderBy('sequence', 'desc')
                        ->get();

                    if (count($sub_menus) > 0) {
                        foreach ($sub_menus as $sub_menu) {
                            

                            $sub_menu_url = explode("/", $sub_menu->menu_url);
                            
                            $submenu_active = '';
                            if (!empty($current_submenu['0']) && !empty($sub_menu_url['1'])) {
                                if ($current_submenu['0'] == $sub_menu_url['1']) {
                                    $submenu_active = 'm-menu__item--active m-menu__item--open';
                                }
                            }
                            $sub_menus_array[$sub_menu->id] = [
                                'active' => $submenu_active,
                                'sub_menu_name' => $sub_menu->menu_name,
                                'sub_menu_icon' => $sub_menu->icon,
                                'submenu_url' => $sub_menu->menu_url
                            ];
                        }

                    }

                } else {

                    $sub_menus = DB::table('menu_permissions')
                            ->select('menu_permissions.menu_id', 'menu_permissions.role_id', 'menus.*')
                            ->join('menus', 'menu_permissions.menu_id', '=', 'menus.id')
                            ->where(['menus.parent_id' => $menu->id, 'menu_permissions.role_id' => $role_id])
                            ->orderBy('menus.sequence', 'desc')
                            ->get();

                    if (count($sub_menus) > 0) {
                        foreach ($sub_menus as $sub_menu) {
                            

                            $sub_menu_url = explode("/", $sub_menu->menu_url);
                            
                            $submenu_active = '';
                            if (!empty($current_submenu['0']) && !empty($sub_menu_url['1'])) {
                                if ($current_submenu['0'] == $sub_menu_url['1']) {
                                    $submenu_active = 'm-menu__item--active m-menu__item--open';
                                }
                            }
                            $sub_menus_array[$sub_menu->id] = [
                                'active'        => $submenu_active,
                                'sub_menu_name' => $sub_menu->menu_name,
                                'sub_menu_icon' => $sub_menu->icon,
                                'submenu_url'   => $sub_menu->menu_url
                            ];
                        }

                    }
                    
                }
                $menu_url = explode("/", $menu->menu_url);
                     
                $menu_active = '';

                if (!empty($current_submenu['0']) && !empty($menu_url['1'])) {
                    if ($current_submenu['0'] == $menu_url['1']) {
                        $menu_active = 'm-menu__item--active';
                    }
                }
                
                
                $menus_array[$menu->id] = [
                    'active'    => $menu_active,
                    'menu_name' => $menu->menu_name,
                    'menu_icon' => $menu->icon,
                    'menu_url'  => $menu->menu_url,
                    'sub_menus' => $sub_menus_array
                ];
            }

        }
        return (isset($menus_array) ? $menus_array : '');
    }


    public static function readMore($text, $limit = 400){
        $text = $text." ";
        $text = substr($text, 0, $limit);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text."...";
        return $text;
    
    }

    public static function date_time($data)
    {
        return date('d M Y H:i:sA',strtotime($data));
    }

    public static function profile_photo(){
        if(!empty(auth()->user()->photo)){
            $profile_img = 'public/uploads/profile-img/'.Auth::user()->photo;
        }else {
            $profile_img = json_decode(DEFAULT_PROFILE_IMAGE)[Auth::user()->gender];
        }

        return $profile_img;
    }

    public static function auth_role_name(){
        $role = DB::table('roles')->where('id',Auth::user()->role_id)->first();
        return $role->role_name;
    }

    public static function get_site_data(){
        $data = Setting::find(1);
        return $data;
    }

    public function show_category(){
        $category = '';
        $category .= $this->multilevel_category();
        return $category;
    }
    function multilevel_category($parent_id = NULL)
    {
        $category = '';
        if($parent_id == 0){
            $categories = Category::categories(['parent_id' => 0]); 
        }else{
            $categories = Category::categories(['parent_id' => $parent_id]); 
        }

        if(!empty($categories)){
            foreach ($categories as $value) {
                $product_data = DB::table('product_has_categories as pc')
                            ->join('products as p','pc.product_id','=','p.id')
                            ->select('pc.product_id','p.*')
                            ->where(['p.status' => 1, 'pc.category_id' => $value->id])
                            ->where('pc.category_id',$value->id)
                            ->orWhere('pc.category_parent_id',$value->id)
                            ->groupBy('pc.product_id')
                            ->get();
                if(!empty($product_data)){
                    $total_product = count($product_data);
                }else{
                    $total_product = 0;
                }
                
                $category .= '<li><a href="'.url('category',$value->category_slug).'">'.$value->category_name.'</a><span class="badge  badge-light">'.$total_product.'</span>';

                $category .= '<ul>'.$this->multilevel_category($value->id).'</ul>';
                
                $category .= '</li>';
            }
        }
        return $category;
    }
    

    public static function get_product_weightwise_price($product_id){
        $weightwise_price = ProductPriceDetail::select('id','product_id','weight','unitname','price','is_default')
                        ->where(['product_id'=>$product_id])->orderBy('is_default','asc')->get();
        return $weightwise_price;
    }

    public static function website_pages()
    {
        return $data = DB::table('website_pages')->get();

    }

    public static function parent_category(){
        return $categories = DB::table('categories')->where('parent_id',0)->get()->random(5); 
    }                        
    public static function district(){
        return $district = District::all(); 
    }             
    
    public static function product_image($pid)
    {
        $image = Product::select('featured_image')->where('id',$pid)->first();
        return $image->featured_image;
    }
}
?>