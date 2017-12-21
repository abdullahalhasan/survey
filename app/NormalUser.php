<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use Image;
class NormalUser extends Model
{
    //
    /**
     * Upload user image.
     *
     * @param string $img_location, $slug, $img_ext
     * @return user profile image.
     */
    public static function UserImageUpload($img_location, $name_slug, $img_ext)
    {
        $filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;
        $path = public_path('assets/images/user/normal/' . $filename);
        Image::make($img_location)->resize(150, 150)->save($path);
        $path2 = public_path('assets/images/user/normal/small/' . $filename);
        Image::make($img_location)->resize(30, 30)->save($path2);
        $user_profile_image=$filename;
        return $user_profile_image;
    }

    /**
     * App image upload.
     *
     * @param $FILE
     * @param $name_slug
     * @return string
     */
    public static function AppProfileImageUpload($FILE,$name_slug){

        try {
            $file = $FILE["file_upload"]['tmp_name'];
            $ext = explode('.',$FILE['file_upload']['name']);
            $file_ext   = array('jpg','png','gif','bmp','JPG','jpeg');
            $post_ext   = end($ext);
            $photo_name = explode(' ', trim(strtolower($FILE['file_upload']['name'])));
            $photo_name = implode('_', $photo_name);
            $photo_type = $FILE['file_upload']['type'];
            $photo_size = $FILE['file_upload']['size'];
            $photo_tmp  = $FILE['file_upload']['tmp_name'];
            $photo_error= $FILE['file_upload']['error'];
            if( in_array($post_ext,$file_ext) && ($photo_error == 0 )){
                $filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$post_ext;
                if (!file_exists('assets/images/user/normal/')) {
                    mkdir('assets/images/user/normal/', 0777, true);
                }
                $path = public_path('assets/images/user/normal/' . $filename);
                Image::make($file)->resize(150, 150)->save($path);
                if (!file_exists('assets/images/user/normal/small/'))
                    mkdir('assets/images/user/normal/small//', 0777, true);
                $path2 = public_path('assets/images/user/normal/small/' . $filename);
                Image::make($file)->resize(30, 30)->save($path2);
                $user_profile_image=$filename;
                return $user_profile_image;

            }
        } catch(\Exception $e) {
            $response["errors"]= [
                "statusCode"=> 501,
                "errorMessage"=> $e->getMessage(),
                "serverReferenceCode"=> date('Y-m-d H:i:s'),
            ];
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return \Response::json($response);
        }
    }
}
