<?php
/**
 * PHP Version 7.1.7-1
 * Functions for users
 *
 * @category  File
 * @package   Config
 * @author    Mohamed Yahya
 * @copyright ULEARN  
 * @license   BSD Licence
 * @link      Link
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

use Illuminate\Support\Facades\Storage;
use Image;
use SiteHelpers;

/**
 * Class contain functions for admin
 *
 * @category  Class
 * @package   Config
 * @author    Mohamed Yahya
 * @copyright ULEARN
 * @license   BSD Licence
 * @link      Link
 */
class ConfigController extends Controller
{
   
    public function saveConfig(Request $request)
    {
        // get the input values from form
        $input = $request->all();
        $code = $request->input('code');

        unset($input['_token']);
        unset($input['code']);

        // get the uploaded files
        $files = $request->file();

        foreach ($files as $file_key => $file_array) {
            // delete old file
            if (isset($input['old_'.$file_key]) && Storage::exists($input['old_'.$file_key])) {
                Storage::delete($input['old_'.$file_key]);
            }
            unset($input['old_'.$file_key]);

            // save the file with the original name
            $file_name = $request->file($file_key)->getClientOriginalName();
            // create path
            $path = "config";

            // check if the file name already exists
            $new_file_name = SiteHelpers::checkFileName($path, $file_name);

            // store the file
            $path = $request->file($file_key)->storeAs($path, $new_file_name);

            // upload the image and save the image name in the array to save it in the DB
            $input[$file_key] = $path;
        }

        // save the configuration options
        Config::save_options($code, $input);

        return $this->return_output('flash', 'success', 'saved', 'back', '200');
    }

    public function pageHome(Request $request)
    {
        $config = Config::get_options('pageHome');
        return view('admin.config.page_home', compact('config'));
    }

    public function pageAbout(Request $request)
    {
        $config = Config::get_options('pageAbout');
        return view('admin.config.page_about', compact('config'));
    }

    public function pageContact(Request $request)
    {
        $config = Config::get_options('pageContact');
        return view('admin.config.page_contact', compact('config'));
    }

    public function settingGeneral(Request $request)
    {
        $config = Config::get_options('settingGeneral');
        return view('admin.config.setting_general', compact('config'));
    }

    public function settingPayment(Request $request)
    {
        $config = Config::get_options('settingPayment');
        return view('admin.config.setting_payment', compact('config'));
    }

    public function settingEmail(Request $request)
    {
        $config = Config::get_options('settingEmail');
        return view('admin.config.setting_email', compact('config'));
    }

}
