<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    protected $fillable = [
        'facebook_page', 'twitter_page', 'instagram_page', 'linkedin_page', 'whatsapp_share', 'facebook_share', 'instagram_share', 'twitter_share', 'linkedin_share', 'other_share', 'facebook_follow', 'twitter_follow', 'instagram_follow', 'linkedin_follow',
    ];

    /**
     * @param $method
     * @return array
     */
    public function rules($method)
    {
        /*$user = User::find($this->users);*/

        switch ($method) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [

                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [


                    ];
                }
            default:
                break;
        }
    }
}
