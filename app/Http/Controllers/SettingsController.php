<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use App\Models\Setting;


class SettingsController extends Controller
{
    protected $rules = array();

    public function index(){

        if(in_array('Setting',session()->get('permission'))){
            $page_title = 'Settings';
            $page_icon  = 'fas fa-cogs';
            return view('dashboard.setting.setting', compact('page_title','page_icon'));
        }else{
            return redirect('/error')->with('error','You do not have permission to access this page.');
        }
    }

    /** BEGIN:: GET ALL SETTING DATA OF WEBSITE FROM DATABASE SETTING TABLE **/
    public function settingData(){
        $json = array();
        $setting = Setting::find(1);

        $website_title = $website_footer_text = $contact_email = $contact_no = $contact_address = '';
        $invoice_prefix = $min_order = $shipping_fee = $site_logo = $footer_logo = $favicon_icon = $facebook = '';
        $twitter = $linkedin = $youtube = $skype = $instagram = $mail_protocol = '';
        $mail_hostname = $mail_port = $mail_username = $mail_password = $mail_security = '';
        
        if(!empty($setting->website_title)){
            $website_title = $setting->website_title;
        }
        if(!empty($setting->website_footer_text)){
            $website_footer_text = $setting->website_footer_text;
        }

        if(!empty($setting->contact_email)){
            $contact_email = $setting->contact_email;
        }
        if(!empty($setting->contact_no)){
            $contact_no = $setting->contact_no;
        }
        if(!empty($setting->contact_address)){
            $contact_address = $setting->contact_address;
        }
        if(!empty($setting->invoice_prefix)){
            $invoice_prefix = $setting->invoice_prefix;
        }

        if(!empty($setting->shipping_fee)){
            $shipping_fee = $setting->shipping_fee;
        }
        if(!empty($setting->min_order)){
            $min_order = $setting->min_order;
        }
        
        if(!empty($setting->site_logo)){
            $site_logo = $setting->site_logo;
        }

        if(!empty($setting->footer_logo)){
            $footer_logo = $setting->footer_logo;
        }

        if(!empty($setting->favicon_icon)){
            $favicon_icon = $setting->favicon_icon;
        }

        if(!empty($setting->social_media)){
            if(!empty(json_decode($setting->social_media)->facebook)){
                $facebook = json_decode($setting->social_media)->facebook;
            }
            if(!empty(json_decode($setting->social_media)->twitter)){
                $twitter = json_decode($setting->social_media)->twitter;
            }
            if(!empty(json_decode($setting->social_media)->linkedin)){
                $linkedin = json_decode($setting->social_media)->linkedin;
            }
            if(!empty(json_decode($setting->social_media)->youtube)){
                $youtube = json_decode($setting->social_media)->youtube;
            }
            if(!empty(json_decode($setting->social_media)->skype)){
                $skype = json_decode($setting->social_media)->skype;
            }
            if(!empty(json_decode($setting->social_media)->instagram)){
                $instagram = json_decode($setting->social_media)->instagram;
            }
        }
        

        if(!empty($setting->mail_info)){
            if(!empty(json_decode($setting->mail_info)->mail_protocol)){
                $mail_protocol = json_decode($setting->mail_info)->mail_protocol;
            }
            if(!empty(json_decode($setting->mail_info)->mail_hostname)){
                $mail_hostname = json_decode($setting->mail_info)->mail_hostname;
            }
            if(!empty(json_decode($setting->mail_info)->mail_port)){
                $mail_port = json_decode($setting->mail_info)->mail_port;
            }
            if(!empty(json_decode($setting->mail_info)->mail_username)){
                $mail_username = json_decode($setting->mail_info)->mail_username;
            }
            if(!empty(json_decode($setting->mail_info)->mail_password)){
                $mail_password = json_decode($setting->mail_info)->mail_password;
            }
            if(!empty(json_decode($setting->mail_info)->mail_security)){
                $mail_security = json_decode($setting->mail_info)->mail_security;
            }
        }
        
        $json['setting'] = [

            'website_title'       => $website_title,
            'website_footer_text' => $website_footer_text,
            'contact_email'     => $contact_email,
            'contact_no'       => $contact_no, 
            'contact_address'  => $contact_address,
            'invoice_prefix'   => $invoice_prefix,
            'shipping_fee'     => $shipping_fee,
            'min_order'        => $min_order,
            'site_logo'        => $site_logo,
            'footer_logo'      => $footer_logo,
            'favicon_icon'     => $favicon_icon,

            'facebook'         => $facebook,
            'twitter'          => $twitter,
            'linkedin'         => $linkedin,
            'youtube'          => $youtube,
            'skype'            => $skype,
            'instagram'        => $instagram,

            'mail_protocol'    => $mail_protocol,
            'mail_hostname'    => $mail_hostname,
            'mail_port'        => $mail_port,
            'mail_username'    => $mail_username,
            'mail_password'    => $mail_password,
            'mail_security'    => $mail_security,

        ];
        return Response::json($json);
        
    }
    /** END:: GET ALL SETTING DATA OF WEBSITE FROM DATABASE SETTING TABLE **/

    /** BEGIN:: STORE SITE SETTING DATA **/
    public function store_site_data(Request $request){
        if($request->ajax()){

            $this->rules['website_title']       = 'required';
            $this->rules['website_footer_text'] = 'required';
            $this->rules['contact_email']       = 'required';
            $this->rules['contact_no']          = 'required';
            $this->rules['contact_address']     = 'required';
            $this->rules['invoice_prefix']      = 'required';
            $this->rules['shipping_fee']        = 'required';
            $this->rules['min_order']           = 'required';
            $this->rules['site_logo']           = 'image|mimes:jpeg,jpg,png,gif';
            $this->rules['footer_logo']         = 'image|mimes:jpeg,jpg,png,gif';
            $this->rules['favicon_icon']        = 'image|mimes:png,ico';

            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails()) {

                $json = array(
                    'errors' => $validator->errors()
                );

            } else {
                
                if(Setting::find(1)){
                    $data = Setting::find(1);
                }else{
                    $data = new Setting();
                }
                $data->website_title   = $request->website_title;
                $data->website_footer_text  = $request->website_footer_text;
                $old_logo = $old_footer_logo = $old_icon = '';
                $target_dir      = LOGO_PATH;
                if(!empty($request->site_logo)){
                    
                    if(!empty($data->site_logo)){
                        $old_logo = $data->site_logo;
                    }
                    
                    $data->site_logo = $this->upload_image($request->file('site_logo'),$old_logo,$target_dir);
                }

                if(!empty($request->footer_logo)){
                    
                    if(!empty($data->footer_logo)){
                        $$old_footer_logo = $data->footer_logo;
                    }
                    
                    $data->footer_logo = $this->upload_image($request->file('footer_logo'),$old_footer_logo,$target_dir);
                }

                if(!empty($request->favicon_icon)){
                    if(!empty($data->favicon_icon)){
                        $old_icon           = $data->favicon_icon;
                    }
                    
                    $data->favicon_icon = $this->upload_image($request->file('favicon_icon'),$old_icon,$target_dir);
                }

                $data->contact_email   = $request->contact_email;
                $data->contact_no      = $request->contact_no;
                $data->contact_address = $request->contact_address;
                $data->invoice_prefix  = $request->invoice_prefix;
                $data->shipping_fee    = $request->shipping_fee;
                $data->min_order       = $request->min_order;
                $social_media = [
                    'facebook'  => $request->facebook,
                    'twitter'   => $request->twitter,
                    'linkedin'  => $request->linkedin,
                    'youtube'   => $request->youtube,
                    'skype'     => $request->skype,
                    'instagram' => $request->instagram
                ];
                $data->social_media     = json_encode($social_media);
                if(Setting::find(1)){
                    $data->updated_at       = DATE;
                    if ($data->update()) {
                        $json['success'] = 'Data has been successfully added';
                    }
                }else{
                    $data->created_at       = DATE;
                    $data->updated_at       = DATE;
                    if ($data->save()) {
                        $json['success'] = 'Data has been successfully added';
                    }
                }

            }
            return Response::json($json);
        }
    }
    /** END:: STORE SITE SETTING DATA **/

    /** BEGIN:: STORE SITE MAIL SETTING DATA **/
    public function store_mail_data(Request $request){
        if($request->ajax()){

            $this->rules['mail_protocol'] = 'required';
            $this->rules['mail_hostname'] = 'required';
            $this->rules['mail_port']     = 'required|numeric';
            $this->rules['mail_username'] = 'required';
            $this->rules['mail_password'] = 'required';
            $this->rules['mail_security'] = 'required';

            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails()) {

                $json = array(
                    'errors' => $validator->errors()
                );

            } else {

                $data                 = Setting::find(1);
                $mail_info = [
                    'mail_protocol' => $request->mail_protocol,
                    'mail_hostname' => $request->mail_hostname,
                    'mail_port'     => $request->mail_port,
                    'mail_username' => $request->mail_username,
                    'mail_password' => $request->mail_password,
                    'mail_security' => $request->mail_security,
                ];
                $data->mail_info      = json_encode($mail_info);
                $data->updated_at     = DATE;
                if ($data->update()) {

                    $env_update = $this->changeEnvMailData([
                        'MAIL_DRIVER'    => $request->mail_protocol,
                        'MAIL_HOST'      => $request->mail_hostname,
                        'MAIL_PORT'      => $request->mail_port,
                        'MAIL_USERNAME'  => $request->mail_username,
                        'MAIL_PASSWORD'  => $request->mail_password,
                        'MAIL_ENCRYPTION'=> $request->mail_security
                    ]);
                    $json['success'] = 'Data is successfully added';
                }

            }
            return Response::json($json);
        }
    }
    /** END:: STORE SITE MAIL SETTING DATA **/

    /** BEGIN:: .ENV FILE EDIT METHOD (NEVER TOUCH THIS METHOD) **/
    protected function changeEnvMailData($data = array()){
        if(count($data) > 0){

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through given data
            foreach((array)$data as $key => $value){

                // Loop through .env-data
                foreach($env as $env_key => $env_value){

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if($entry[0] == $key){
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);
            
            return true;
        } else {
            return false;
        }
    }
    /** END:: .ENV FILE EDIT METHOD **/

}
