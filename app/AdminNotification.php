<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{

    protected $fillable = [
        'user_ids', 'selection', 'message_heading', 'image', 'message_url', 'message','link_type','link_ur_type','cat_id','sub_cat_id','vendor_product_id'
    ];


    /**
     * @param $method
     * @return array
     */
    public function rules($method,$id=0)
    {
        /*$user = User::find($this->users);*/

        switch($method)
        {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    $trans = [
                        'message_heading'=>'required',
                        'image.*'=>'required|image|mimes:jpeg,png,jpg,gif,webp',
                        'message'=>'required|max:300',
                        'link_type'=>'required',
                        'message_url'=>'required_if:link_type,external',
                        'cat_id'=>'required_if:link_type,internal',
                    ];
                    return  $trans;

                }
            case 'PUT':
                $trans = [
                    //'link'=>'required|url'
                ];
                return $trans;

            case 'PATCH':
                {
                    $trans = [
                        //'link'=>'required|url'
                    ];
                return  $trans;
                
                }
            default:break;
        }
    }


    public function messages($method)
    {
        /*$user = User::find($this->users);*/

        switch($method)
        {
            case 'GET':
            case 'DELETE':
                {
                    $page= [];

                    return $page ;

                }
            case 'POST':
                {
                    $page= [];

                    return $page ;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $page= [];

                    return $page;
                }
            default:break;
        }
    }

    public function getImageAttribute($value)
    {
        return Helper::hasImage($value);
    }
    public function getUserIdsAttribute($value)
    {
        $value = explode(',',$value);

        return $value;
    }
   


}
