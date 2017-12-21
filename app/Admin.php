<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use Image;
class Admin extends Model
{
    /**
     * Upload user image.
     *
     * @param string $img_location, $slug, $img_ext
     * @return user profile image.
     */
    public static function UserImageUpload($img_location, $name_slug, $img_ext)
    {
        $filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;
        $path = public_path('assets/images/user/admin/' . $filename);
        Image::make($img_location)->resize(150, 150)->save($path);
        $path2 = public_path('assets/images/user/admin/small/' . $filename);
        Image::make($img_location)->resize(30, 30)->save($path2);
        $user_profile_image=$filename;
        return $user_profile_image;
    }
}
