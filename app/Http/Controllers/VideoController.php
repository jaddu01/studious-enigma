<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Scopes\StatusScope;

use App\User;
use App\Video;
use App\Pdf;
use App\Language;
use App\Subscriber;
use App\SubscriberVideo;
use App\AutoNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;
use App\VideoDurationSave;
use FFMpeg;
use Carbon\Carbon;

class VideoController extends Controller
{
    protected $video;
    protected $method;
    protected $zone;
    function __construct(Request $request, Video $video, Pdf $pdf,User $user,Language $language,VideoDurationSave $videosave,Subscriber $subscriber,SubscriberVideo $subscriberVideo)
    {
        parent::__construct();
        $this->model=$video;
        $this->pdf=$pdf;
        $this->subscriber=$subscriber;
        $this->subscriberVideo=$subscriberVideo;
        $this->user=$user;
        $this->language=$language;
        $this->videosave = $videosave;
       
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		//echo "dsgdf";die;
	
        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        
        $language=  $this->language->pluck('id','language');
 
		
		$videoDaily =  $this->model->where(['type'=> 0])->orderBy('sort', 'ASC')->get();		
		$videoPremium =  $this->model->where(['type'=> 2])->orderBy('sort', 'ASC')->get();		
		$videoUnpaid =  $this->model->where(['type'=> 1])->orderBy('sort', 'ASC')->get();		
		
        return view('admin/pages/video/index')->with('videoDaily',$videoDaily)->with('videoPremium',$videoPremium)->with('videoUnpaid',$videoUnpaid)->with('language',$language);
    }
    public function allvideo(Request $request)
    {
	
        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        
        $language=  $this->language->pluck('id','language');
 
		
		$videoDaily =  $this->model->where(['type'=> 0])->orderBy('sort', 'ASC')->get();		
		$videoPremium =  $this->model->where(['type'=> 2])->orderBy('sort', 'ASC')->get();		
		$videoUnpaid =  $this->model->where(['type'=> 1])->orderBy('sort', 'ASC')->get();	
			
		
        return view('admin/pages/video/allvideo')->with('videoDaily',$videoDaily)->with('videoPremium',$videoPremium)->with('videoUnpaid',$videoUnpaid);
    }
    
    public function classify_materials() {
		 if ($this->user->can('index', User::class)) {
           return abort(403, 'not able to access');
        }
        
        $language = $this->language->pluck('id', 'language');
        $fn_videos = $this->model->where(['type' => 2])->where('material_type', 'video')->orderBy('sort', 'ASC')->get();		
		$fn_pdfs = $this->model->where(['type' => 2])->where('material_type', 'pdf')->orderBy('sort', 'ASC')->get();	
		$fn_images = $this->model->where(['type' => 2])->where('material_type', 'image')->orderBy('sort', 'ASC')->get();
		$fn_audios = $this->model->where(['type' => 2])->where('material_type', 'audio')->orderBy('sort', 'ASC')->get();
		
		return view('admin/pages/video/classify_materials', compact('fn_videos', 'fn_pdfs', 'fn_images', 'fn_audios'));
	}
    
    public function indexPdf(Request $request)
    {
	
        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        
        $language=  $this->language->pluck('id','language');
		
		
			$video=  $this->pdf->orderBy('sort', 'ASC')->get();	
		
		
        return view('admin/pages/video/indexpdf')->with('videos',$video)->with('language',$language);
    }

    public function userlist(Request $request)
    {
	
        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        
		
        $user =$this->user->where([['id', '!=', 1]])->get();
     //   print_r( $user);die;
        foreach($user as $userlist){
			$videos=$this->videosave->where(['user_id'=>$userlist->id])->groupby(['video_id'])->with(['Video'])->whereHas('Video', function ($query) {
		$query->where('type', '=', 1);
		})->get();
			$videoseen=$this->videosave->where(['user_id'=>$userlist->id,'video_duration_save'=>2])->groupby(['video_id'])->with(['Video'])->whereHas('Video', function ($query) {
		$query->where('type', '=', 1);
		})->get();
		
	
			$videoslists=$videos->toArray();
			$usernew['number_of_video']=count($videoseen);
			$usernew['email']=$userlist->email;
			$usernew['name']=$userlist->name;
			
			$usernew['referral']=url('/home/index/'.$userlist->ref_token);
			$free_video[]=$usernew;
		}
        foreach($user as $userlist){
		$videos=$this->videosave->where(['user_id'=>$userlist->id])->groupby(['video_id'])->with(['Video'])->whereHas('Video', function ($query) {
		$query->where('type', '=', 2);
		})->get();
		$videoseen=$this->videosave->where(['user_id'=>$userlist->id,'video_duration_save'=> 2])->groupby(['video_id'])->with(['Video'])->whereHas('Video', function ($query) {
		$query->where('type', '=', 2);
		})->get();
			//echo count($videoseen);
		//	die;
			//echo "<pre>";print_r($videoseen->toArray());
			
			$videoslists=$videos->toArray();
			//echo "<pre>";print_r($videos->toArray());die;
		
			$usernew['number_of_video']=count($videoseen);
			$usernew['email']=$userlist->email;
			$usernew['name']=$userlist->name;
			$user_date =	\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $userlist->created_at)->format('Y-m-d');

			$today_date=date('Y-m-d');
			$diff = strtotime($today_date) - strtotime($user_date);
			$todatal_day_difference= round($diff / 86400)+1;
			$usernew['day_count']=$todatal_day_difference;
			$paid_video[]=$usernew;
		}
		//echo "<pre>";print_r($videos->toArray());die;
		
		//echo "<pre>";print_r($free_video);die;
		
		if(empty($free_video)){
			$free_video="";
		}
		if (empty($paid_video)){
			
			$paid_video="";
		}
		
		
       return view('admin/pages/video/user')->with("freevideo",$free_video)->with("premiumvideo",$paid_video);
                
    }
    public function freeuser(Request $request)
    {
	return view('admin/pages/video/free');
        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        
        
      // return view('admin/pages/video/free')->with('ulists',$ulists);
                
    }
    
    public function video_free() {
		$lists=$this->subscriber->with('SubscriberVideo')->get();
      
	//echo "<pre>";print_r($lists->toArray());die;
		foreach($lists as $key=>$list){
			$li['id']=$key+1;
			$li['user_id']=$list->id;
			$li['email']=$list->email;
			$count=$this->subscriberVideo->where(['user_id'=>$list->id ,'complete'=>1 ])->get();
			
			
			$li['count_watch']=count($count);
			$user_date =	\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $list->created_at)->format('Y-m-d');
			$today_date=date('Y-m-d');
			$diff = strtotime($today_date) - strtotime($user_date);
			$todatal_day_difference= round($diff / 86400)+1;
			$li['total_days']=$todatal_day_difference;
			$users=$this->user->where(['id'=>$list->user_id])->first();
			$li['referred_by']=$users['name'];
			$ulists[]=$li;
		}
	
			//echo "<pre>";print_r($ulists);die;
		
		if(empty($ulists)){
			$ulists="";
		}
		
		 return Datatables::of($ulists)
           
             
            ->addColumn('id',function ($ulists){
               return $ulists['user_id'];
            })
            ->addColumn('email',function ($ulists){
               return $ulists['email'];
            })
            ->addColumn('count_watch',function ($ulists){
               return $ulists['count_watch'];
            })
            ->addColumn('total_days',function ($ulists){
               return $ulists['total_days'];
            })
            ->addColumn('referred_by',function ($ulists){
               return $ulists['referred_by'];
            })
            ->addColumn('action',function ($ulists){
               return '<button type="button" onclick="deleteRow('.$ulists["user_id"].')" class="btn btn-danger btn-xs">Delete</button>';
            })
           
            ->make(true);
	}
    
    
    public function freeuserlist(Request $request)
    {
	
        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        
        
		
       return view('admin/pages/video/free');
                
    }
    

    public function paidvideo()
    {
	

        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        $video=  $this->model->where(['video_type'=>1,'type'=>1])->orderBy('sort', "ASC")->get();
        return view('admin/pages/video/paidvideo')->with('videos',$video);
    }
    public function referral()
    {
	

        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        
        return view('admin/pages/video/referral');
    }
    
    
    
    public function freevideo()
    {
	       if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        $video=  $this->model->where(['video_type'=>0,'type'=>1])->orderBy('sort', "ASC")->get();
        return view('admin/pages/video/freevideo')->with('videos',$video);
    }
    
    
    public function paidUnpaidBothType()
    {

        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        $video=  $this->model->where(['notification_type'=>2,'type'=>1])->orderBy('sort', "ASC")->get();
        return view('admin/pages/video/bothvideos')->with('videos',$video);
    }
    
    
    
    

    public function create(){
		//echo $this->user->can('create', User::class); die;
		 //die('asfd');
        if ($this->user->can('create', User::class)) {
            return abort(403,'not able to access');
        }
		
		$language=  $this->language->pluck('language','id');
        $validator = JsValidatorFacade::make($this->model->rules('POST'));

        return view('admin/pages/video/add')->with('validator',$validator)->with('language',$language);
    }
    public function createPdf(){
		//echo $this->user->can('create', User::class); die;
		 //die('asfd');
        if ($this->user->can('create', User::class)) {
            return abort(403,'not able to access');
        }
		
		$language=  $this->language->pluck('language','id');
        $validator = JsValidatorFacade::make($this->model->rules('POST'));

        return view('admin/pages/video/addpdf')->with('validator',$validator)->with('language',$language);
    }

	public function store(Request $request) {
		$input = $request->all();
		//echo "<pre>";print_r($this->model->rules);die;
	
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));
		
						
        if ($validator->fails()) {
			//	echo $validator->errors()->first();die;
				
        //    Session::flash('danger',$validator->errors());
            return $validator->errors();
            	$response = [
			'error' => true,
			'code' => 1,
			'message' => (!empty($validator->errors()) ? $validator->errors() : trans('site.success')),
		];
            return response()->json($response, 400);
        }else{



          
            try {

                $product = $this->model->create($input);
                
                //print_r($request->all());die;
                
                if($request->hasFile('video')){
				//	ini_set ('memory_limit', '1024M') ;
						$imageName = Helper::videoUpload($request->file('video'),true);
						$product->video=$imageName;
						$sort_val=$this->model->where("type","=",$request->type)->max('sort');
						$product->sort=$sort_val+1;

						$name= preg_replace('/.[^.]*$/', '', $imageName);
						$newname='public/upload/videos/'.$name.'-frame.jpg';
						
						$image_details = $request->file('video');
						$image_mime_type = $image_details->getClientMimeType();
						//echo $image_mime_type;die;
						if ($image_mime_type == 'application/pdf') {
							if (is_file('public/upload/videos/pdf_icon.jpg')) {
								if (file_exists('public/upload/videos/pdf_icon.jpg')) {
									$filePath = 'videos/pdf_icon.jpg';
									$newname = 'public/upload/videos/pdf_icon.jpg';
									Storage::disk('s3')->put($filePath, file_get_contents($newname));
									
									$product->thumnail_image = 'pdf_icon.jpg';
									$product->material_type = 'pdf';
								} else {
									$imagePath = "/public/upload/pdf_icon.jpg";
									$newPath = "/public/upload/videos/pdf_icon.jpg";
									
									if (copy($imagePath , $newPath)) {
										$filePath = 'videos/pdf_icon.jpg';
										$newname = 'public/upload/videos/pdf_icon.jpg';
										Storage::disk('s3')->put($filePath, file_get_contents($newname));
										
										$product->thumnail_image = 'pdf_icon.jpg';
										$product->material_type = 'pdf';
									}
								}
							} else {
								$imagePath = "public/upload/pdf_icon.jpg";
								$newPath = "public/upload/videos/pdf_icon.jpg";
									
								if (copy($imagePath , $newPath)) {
									$filePath = 'videos/pdf_icon.jpg';
									$newname = 'public/upload/videos/pdf_icon.jpg';
									Storage::disk('s3')->put($filePath, file_get_contents($newname));
									
									$product->thumnail_image = 'pdf_icon.jpg';
									$product->material_type = 'pdf';
								}
							}
						} else if ($image_mime_type == 'video/mp4') {
							$ffmpeg = FFMpeg\FFMpeg::create();
							$video = $ffmpeg->open("https://s3.ap-south-1.amazonaws.com/brvideoapp/videos/".$imageName);
							$video->filters()->resize(new FFMpeg\Coordinate\Dimension(320, 240))->synchronize();
							$video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(3))->save($newname);
						
							$filePath='videos/'. $name.'-frame.jpg';
							Storage::disk('s3')->put($filePath, file_get_contents($newname));
							$product->thumnail_image = $name.'-frame.jpg';
							$product->material_type = 'video';
						} else if ($image_mime_type == 'image/jpeg') {
							$new_file = time().'_'.$name.'.jpg';
							$filePath = 'videos/'.$new_file;
							$newname = 'public/upload/videos/'.$new_file;
							
							$destinationPath = 'public/upload/videos';
				
							if ($image_details->move($destinationPath, $new_file)) {
							}							
							
							Storage::disk('s3')->put($filePath, file_get_contents($newname));
							
							$product->thumnail_image = time().'_'.$name.'.jpg';
							$product->material_type = 'image';
						} else if ($image_mime_type == 'audio/mpeg') {
							if (is_file('public/upload/videos/pdf_icon.jpg')) {
								if (file_exists('public/upload/videos/pdf_icon.jpg')) {
									$filePath = 'videos/mp3_icon.jpg';
									$newname = 'public/upload/videos/mp3_icon.jpg';
									Storage::disk('s3')->put($filePath, file_get_contents($newname));
									
									$product->thumnail_image = 'mp3_icon.jpg';
									$product->material_type = 'audio';
								} else {
									$imagePath = "/public/upload/mp3_icon.jpg";
									$newPath = "/public/upload/videos/mp3_icon.jpg";
									
									if (copy($imagePath , $newPath)) {
										$filePath = 'videos/mp3_icon.jpg';
										$newname = 'public/upload/videos/mp3_icon.jpg';
										Storage::disk('s3')->put($filePath, file_get_contents($newname));
										
										$product->thumnail_image = 'mp3_icon.jpg';
										$product->material_type = 'audio';
									}
								}
							} else {
								$imagePath = "public/upload/mp3_icon.jpg";
								$newPath = "public/upload/videos/mp3_icon.jpg";
									
								if (copy($imagePath , $newPath)) {
									$filePath = 'videos/mp3_icon.jpg';
									$newname = 'public/upload/videos/mp3_icon.jpg';
									Storage::disk('s3')->put($filePath, file_get_contents($newname));
									
									$product->thumnail_image = 'mp3_icon.jpg';
									$product->material_type = 'audio';
								}
							}
						}
						
						$product->save(); 
						unlink($newname);
                }
				
				
				
		
		$response = [
		'error' => false,
		'code' => 0,
		'message' => "Material uploaded",
		];
				return response()->json($response, 200);	
               // Session::flash('success','video uploaded');
            } catch (\Exception $e) {
				
				
				//echo print_r($e);die;
                Session::flash('danger',$e->getMessage());
                $message = $e->getMessage();
                $type='error';
			$validator->errors();
		$response = [
		'error' => true,
		'code' => 1,
		'message' => $e->getMessage(),
		];
           
           return response()->json($response, 400);
           
            }

     
        }
    }
    
   public function storePdf(Request $request)
    {
        $input = $request->all();
	//	echo "<pre>";print_r($input);die;
		

        $validator = Validator::make($request->all(),$this->pdf->rules($this->method),$this->pdf->messages($this->method));

        if ($validator->fails()) {
			//	echo $validator->errors()->first();die;
				
            Session::flash('danger',$validator->errors()->first());
            return redirect('admin/video/createpdf')->withErrors($validator)->withInput();
        }else{



          
            try {

                $product = $this->pdf->create($input);
               

                if($request->hasFile('pdf')){
				//	ini_set ('memory_limit', '1024M') ;
                  $imageName = Helper::PDFfileUpload($request->file('pdf'),true);
                  $product->pdf=$imageName;
                  $sort_val=$this->pdf->max('sort');
				  $product->sort=$sort_val+1;
                }
                if($request->hasFile('image')){
				//	ini_set ('memory_limit', '1024M') ;
                  $imageNamenew = Helper::saveImages($request->file('image'),false);
                  $product->image=$imageNamenew;
                  
                }
              // echo "<pre>" ; print_r($product);die;
				$product->save(); 
                
				

					
                Session::flash('success','Pdf uploaded');
            } catch (\Exception $e) {
				
				
				
                Session::flash('danger',$e->getMessage());
                $message = $e->getMessage();
                $type='error';

            }

            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
        $video=$this->model->findOrFail($id);  
		$language=  $this->language->pluck('language','id');    
		return view('admin/pages/video/edit')->with('video',$video)->with('languages',$language);
    }
    public function editpdf($id)
    {
        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
        $video=$this->pdf->findOrFail($id);  
		$language=  $this->language->pluck('language','id');    
		return view('admin/pages/video/editpdf')->with('video',$video)->with('languages',$language);
    }
    

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
     
	public function update(Request $request, $id) {
		if ($this->user->can('edit', User::class)) {
			return abort(403,'not able to access');
        }

        $input = $request->all();
        $validator = Validator::make($request->all(), $this->model->rules($this->method, $id),$this->model->messages($this->method));

        if ($validator->fails()) {
		Session::flash('danger',$validator->errors()->first());
		return redirect()->back()
			->withErrors($validator)
			->withInput();
        } else {
			if ($request->hasFile('video')) {
				$imageName = Helper::videoUpload($request->file('video'), true);
				$input['video'] = $imageName;

				$name = preg_replace('/.[^.]*$/', '', $imageName);
				$newname = 'public/upload/videos/'.$name.'-frame.jpg';
				
				$image_details = $request->file('video');
				$image_mime_type = $image_details->getClientMimeType();
				
				if ($image_mime_type == 'application/pdf') {
					if (is_file('public/upload/videos/pdf_icon.jpg')) {
						if (file_exists('public/upload/videos/pdf_icon.jpg')) {
							$filePath = 'videos/pdf_icon.jpg';
							$newname = 'public/upload/videos/pdf_icon.jpg';
							Storage::disk('s3')->put($filePath, file_get_contents($newname));
							
							$input['thumnail_image'] = 'pdf_icon.jpg';
							$input['material_type'] = 'pdf';
						} else {
							$imagePath = "/public/upload/pdf_icon.jpg";
							$newPath = "/public/upload/videos/pdf_icon.jpg";
							
							if (copy($imagePath , $newPath)) {
								$filePath = 'videos/pdf_icon.jpg';
								$newname = 'public/upload/videos/pdf_icon.jpg';
								Storage::disk('s3')->put($filePath, file_get_contents($newname));
								
								$input['thumnail_image'] = 'pdf_icon.jpg';
								$input['material_type'] = 'pdf';
							}
						}
					} else {
						$imagePath = "public/upload/pdf_icon.jpg";
						$newPath = "public/upload/videos/pdf_icon.jpg";
							
						if (copy($imagePath , $newPath)) {
							$filePath = 'videos/pdf_icon.jpg';
							$newname = 'public/upload/videos/pdf_icon.jpg';
							Storage::disk('s3')->put($filePath, file_get_contents($newname));
							
							$input['thumnail_image'] = 'pdf_icon.jpg';
							$input['material_type'] = 'pdf';
						}
					}
				} else if ($image_mime_type == 'video/mp4') {
					$ffmpeg = FFMpeg\FFMpeg::create();
					$video = $ffmpeg->open("https://s3.ap-south-1.amazonaws.com/brvideoapp/videos/".$imageName);
					$video->filters()->resize(new FFMpeg\Coordinate\Dimension(320, 240))->synchronize();
					$video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(3))->save($newname);
				
					$filePath='videos/'. $name.'-frame.jpg';
					Storage::disk('s3')->put($filePath, file_get_contents($newname));
					
					
					$input['thumnail_image'] = $name.'-frame.jpg';
					$input['material_type'] = 'video';
				} else if ($image_mime_type == 'image/jpeg' || $image_mime_type == 'image/jpg') {
					$new_file = time().'_'.$name.'.jpg';
					$filePath = 'videos/'.$new_file;
					$newname = 'public/upload/videos/'.$new_file;
					
					$destinationPath = 'public/upload/videos';
		
					if ($image_details->move($destinationPath, $new_file)) {
					}							
					
					Storage::disk('s3')->put($filePath, file_get_contents($newname));
					
					$input['thumnail_image'] = time().'_'.$name.'.jpg';
					$input['material_type'] = 'image';
				} else if ($image_mime_type == 'audio/mpeg') {
					if (is_file('public/upload/videos/pdf_icon.jpg')) {
						if (file_exists('public/upload/videos/pdf_icon.jpg')) {
							$filePath = 'videos/mp3_icon.jpg';
							$newname = 'public/upload/videos/mp3_icon.jpg';
							Storage::disk('s3')->put($filePath, file_get_contents($newname));
							
							$input['thumnail_image'] = 'mp3_icon.jpg';
							$input['material_type'] = 'audio';
						} else {
							$imagePath = "/public/upload/mp3_icon.jpg";
							$newPath = "/public/upload/videos/mp3_icon.jpg";
							
							if (copy($imagePath , $newPath)) {
								$filePath = 'videos/mp3_icon.jpg';
								$newname = 'public/upload/videos/mp3_icon.jpg';
								Storage::disk('s3')->put($filePath, file_get_contents($newname));
								
								$input['thumnail_image'] = 'mp3_icon.jpg';
								$input['material_type'] = 'audio';
							}
						}
					} else {
						$imagePath = "public/upload/mp3_icon.jpg";
						$newPath = "public/upload/videos/mp3_icon.jpg";
							
						if (copy($imagePath , $newPath)) {
							$filePath = 'videos/mp3_icon.jpg';
							$newname = 'public/upload/videos/mp3_icon.jpg';
							Storage::disk('s3')->put($filePath, file_get_contents($newname));
							
							$input['thumnail_image'] = 'mp3_icon.jpg';
							$input['material_type'] = 'audio';
						}
					}
				}
				
				unlink($newname);
			}
			
			
			 /*  if($request->hasFile('video')){
                $imageName = Helper::videoUpload($request->file('video'),true);
						$input['video']=$imageName;
						$name= preg_replace('/.[^.]*$/', '', $imageName);
						$newname='public/upload/videos/'.$name.'-frame.jpg';
						$ffmpeg = FFMpeg\FFMpeg::create();
						$video = $ffmpeg->open("https://s3.ap-south-1.amazonaws.com/brvideoapp/videos/".$imageName);
						$video->filters()->resize(new FFMpeg\Coordinate\Dimension(320, 240))->synchronize();
						$video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(3))->save($newname);
						$filePath='videos/'. $name.'-frame.jpg';
						
						Storage::disk('s3')->put($filePath, file_get_contents($newname));
						$input['thumnail_image']=$name.'-frame.jpg';
				unlink($newname);
				
                }
				
           $user= $this->model->FindOrFail($id)->fill($input)->save(); */

			$user = $this->model->FindOrFail($id)->fill($input)->save();
			return redirect()->back()->with('success', 'Video updated');
        }
    }
    
    public function updatepdf(Request $request, $id)
    {

        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }

        $input = $request->all();
        $validator = Validator::make($request->all(), $this->pdf->rules($this->method,$id),$this->pdf->messages($this->method));

        if ($validator->fails()) {
Session::flash('danger',$validator->errors()->first());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }else{
			//print_r($request->all());die;
			   if($request->hasFile('pdf')){
				//	ini_set ('memory_limit', '1024M') ;
                  $imageName = Helper::PDFfileUpload($request->file('pdf'),true);
                 
                  $sort_val=$this->pdf->max('sort');
				  
                

				$input['pdf']=$imageName; 
                }
			 if($request->hasFile('image')){
				//	ini_set ('memory_limit', '1024M') ;
                  $imageNamenew = Helper::saveImages($request->file('image'),false);
                  $input['image']=$imageNamenew;
                  
                }
			
			
			
			
			
			
           $user= $this->pdf->FindOrFail($id)->fill($input)->save();
            return redirect()->route('video.indexpdf')->with('success','Pdf updated');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->user->can('delete', User::class)) {
            return abort(403,'not able to access');
        }
       /*print_r((new Helper())->delete_cat($this->user->all(),$id,'',''));*/
       
		$video=$this->model->where('id',$id)->first();
		if(!empty($video)){
			$path='videos/'.$video['video'];
			$path1='videos/'.$video['thumnail_image'];
			Storage::disk('s3')->delete($path);
			Storage::disk('s3')->delete($path1);
		}
        $flight = $this->model->where('id',$id)->delete();
        
        if($flight){
            return response()->json([
                'status' => true,
                'message' => 'deleted'
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'some thing is wrong'
            ],400);
        }


    }
    public function delete($id)
    {
		
		//echo "sdgdfg";die;
		
		
        if ($this->user->can('delete', User::class)) {
            return abort(403,'not able to access');
        }
       /*print_r((new Helper())->delete_cat($this->user->all(),$id,'',''));*/
       
		$video=$this->subscriber->where('id',$id)->first();
		if(!empty($video)){
			
		}
        $flight = $this->subscriber->where('id',$id)->delete();
        
        if($flight){
            return response()->json([
                'status' => true,
                'message' => 'deleted'
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'some thing is wrong'
            ],400);
        }


    }
    public function deletepdf($id)
    {
        if ($this->user->can('delete', User::class)) {
            return abort(403,'not able to access');
        }
       /*print_r((new Helper())->delete_cat($this->user->all(),$id,'',''));*/
       

        $flight = $this->pdf->where('id',$id)->delete();
        if($flight){
            return response()->json([
                'status' => true,
                'message' => 'deleted'
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'some thing is wrong'
            ],400);
        }


    }


	/*  public function anyData(Request $request)
    {  
      
        $video =$this->model;
      
       

      $video=  $video->get();
   
        return Datatables::of($video)
           

            ->addColumn('title',function ($video){
                return $video->title;
            })
            ->addColumn('video',function ($video){
                //return $video->video;
                
              return   '<video src="{{ asset("storage/app/public/upload")'.'/'.$video->video.'}}"  autobuffer autoloop loop controls poster="/images/video.png"></video>';
                
                
                
            })
            ->addColumn('action',function ($video){
                return '<a href="'.route("user.edit",$video->id).'" class="btn btn-success btn-xs">Edit</a><button type="button" onclick="deleteRow('.$video->id.')" class="btn btn-danger btn-xs">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);

    }*/


    public function changeStatus(Request $request){
        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
        if($request->status==1){
            $status='0';
        }else{
            $status='1';
        }

        $user= $this->user->findOrFail($request->id)->update(['status'=>$status]);

        if($request->ajax()){
            if($user){
                return response()->json([
                    'status' => true,
                    'message' => 'successfully updated'
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'some thing is wrong'
                ],400);
            }
        }
    }
    
public function UpdateType(Request $request){

		if(isset($request->id) && isset($request->type) && $request->type!=null && !empty($request->id)){
			foreach($request->id as $idArray){
				$user= Video::where('id', $idArray)->update(array('video_type' => $request->type,'notification_type' => $request->daily_notification));
			}

			if($request->ajax()){
				if($user){
					return response()->json([
					'status' => true,
					'message' => 'successfully updated'
					],200);
				}else{
					return response()->json([
					'status' => false,
					'message' => 'some thing is wrong'
					],400);
				}
			}
			}else{

				return response()->json([
				'status' => false,
				'message' => 'Please Select video Or type'
				],400);

			}



}
public function UpdateStatusDetail(Request $request){

		if(isset($request->post_order_ids)){
			$post_order =$request->post_order_ids;
			foreach($post_order as $data){
				
					$user= Video::where(['id'=> $data['id'],'type'=>$data['type']])->update(array('sort' =>$data['sort_number']));
				
				
		}
				
				
				
					return response()->json([
					'status' => true,
					'message' => 'updated'
					],200);	
				
			}else{
				return response()->json([
					'status' => false,
					'message' => 'some thing is wrong'
					],400);	
				}
	
			



}

public function UpdateStatusDetailPdf(Request $request){

		if(isset($request->post_order_ids)){
			$post_order =$request->post_order_ids;
			
			foreach($post_order as $data){
				
					$user= PDF::where('id', $data['id'])->update(array('sort' =>$data['sort_number']));
				
				
		}
	return response()->json([
					'status' => true,
					'message' => 'updated'
					],200);
			}else{

				return response()->json([
				'status' => false,
				'message' => 'some thing is wrong'
				],400);

			}



}

    public function getUserByPhone(Request $request){
        $request->request->remove('_token');
        $user = $this->user->select('*')->with(['deliveryLocation']);
        foreach ($request->all() as $key=>$item){
            $user->where([$key=>$item]);
        }
        $user = $user->first();
        if ($user){
            $user->deliveryLocation = $user->deliveryLocation->keyBy('id');
        }

        if($user){
            return response()->json([
                'status' => true,
                'message' => 'successfully',
                'data'=>$user
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'no record found'
            ],400);
        }
    }
    
    
    
    
    public function anyData(Request $request)
    {  
        

       

        $user->get();
        // print_r($user);die;
        return Datatables::of($user)
           
             ->addColumn('image',function ($user){
                return '<img src="" height="75" width="75"/>';
            })
            ->addColumn('action',function ($user){
                return '<a href="'.route("user.edit",$user->id).'" class="btn btn-success btn-xs">Edit</a>'.(($user->user_type=='vendor' and $user->role=='user') ? '<a href="'.url("admin/user/product",$user->id).'" class="btn btn-success btn-xs">Product</a>' : '').'<button type="button" onclick="deleteRow('.$user->id.')" class="btn btn-danger btn-xs">Delete</button><input class="data-toggle-coustom " data-size="mini"  data-toggle="toggle" type="checkbox" user-id="'.$user->id.'" '.(($user->status==1) ? "checked" : "") . ' value="'.$user->status.'" >';
            })
            ->rawColumns(['image','action'])
            ->make(true);

    }
    
    
    public function report_notification() {
		/* $notifications = DB::table('notifications')->orderby('id', 'DESC')->get()->toArray();
		echo '<pre>';
		print_r($notifications);die; */
		return view('admin/pages/video/report_notification');
		 if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
	}
    
    public function send_report() {
		$notifications = DB::table('notifications')->orderby('id', 'DESC')->get();
		
		 return Datatables::of($notifications)
           
             
           
            ->addColumn('message',function ($notifications){
				return $notifications->message;
            })
            ->addColumn('image',function ($notifications){
				if (isset($notifications->image) && !empty($notifications->image)) {
					$image_user = env('AMAZON_BUCKET_URL')."pdfs/".$notifications->image;
					return '<img src="'.$image_user.'" height="75" width="75"/>';
				} else {
					return '';
				}
				
            })
            ->addColumn('created_date',function ($notifications){
				return date('d-m-Y H:i a', strtotime($notifications->created_at));
            })
            ->rawColumns(['image'])
            ->make(true);
	}
    
     public function paid_auto_notification() {
		/* $notifications = DB::table('notifications')->orderby('id', 'DESC')->get()->toArray();
		echo '<pre>';
		print_r($notifications);die; */
		return view('admin/pages/video/paid_auto_report_notification');
		 if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
	}
	
	public function paid_send_report() {
		$notifications = DB::table('auto_notifications')->where('message_for', 1)->orderby('id', 'DESC')->get();
		
		 return Datatables::of($notifications)
           
		->addColumn('id', function ($notifications){
			return $notifications->id;
		})
		->addColumn('message', function ($notifications){
			return $notifications->message;
		})
		->addColumn('created_date',function ($notifications){
			return date('d-m-Y H:i a', strtotime($notifications->created_at));
		})
		->make(true);
	}
	
	public function unpaid_auto_notification() {
		/* $notifications = DB::table('notifications')->orderby('id', 'DESC')->get()->toArray();
		echo '<pre>';
		print_r($notifications);die; */
		return view('admin/pages/video/unpaid_auto_report_notification');
		 if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
	}
    
    public function unpaid_send_report() {
		$notifications = DB::table('auto_notifications')->where('message_for', 2)->orderby('id', 'DESC')->get();
		
		 return Datatables::of($notifications)
           
		->addColumn('id', function ($notifications){
			return $notifications->id;
		})
		->addColumn('message', function ($notifications){
			return $notifications->message;
		})
		->addColumn('created_date',function ($notifications){
			return date('d-m-Y H:i a', strtotime($notifications->created_at));
		})
		->make(true);
	}
    
   public function register_user() {
		/* $notifications = DB::table('notifications')->orderby('id', 'DESC')->get()->toArray();
		echo '<pre>';
		print_r($notifications);die; */
		return view('admin/pages/video/register_user');
		 if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
	} 
    
    
    public function token_user() {
		$users = DB::table('users')->where('device_token', '!=', null)->orderby('id', 'DESC')->get();
		
		 return Datatables::of($users)
           
             
           
            ->addColumn('name',function ($users){
				return ucfirst($users->name);
            })
            ->addColumn('phone_number',function ($users){
				return $users->phone_number;
            })
            ->addColumn('email',function ($users){
				return $users->email;
            })
            ->make(true);
	}
    
    
    public function paid_excel_report() {
		$notifications = DB::table('auto_notifications')->where('message_for', 1)->orderby('id', 'DESC')->get();
		
		if (!empty($notifications)) {
			$excel_output = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="clear:both; display:table; width:100%; border-left:1px solid #eee;" >';
			$excel_output.='<tr>
			  <th align="center" colspan="15"><h2>Paid Auto Notification</h2></th>
			  </tr>';
			  
			$excel_output.='<tr style="border:1px solid #000;">
				<td style="border:1px solid #000;" align="center"><strong>S.no.</strong></td>
				<td style="border:1px solid #000;" align="center"><strong>Message</strong></td>
				<td style="border:1px solid #000;" align="center"><strong>Created At</strong></td>
			</tr>';
			 
			$i = 1;
			foreach ($notifications as $notification) {
				$excel_output.='<tr style="border:1px solid #000;" >
					<td style="border:1px solid #000;" align="center">' . $i  . '</td>
					<td style="border:1px solid #000;" align="center">' . $notification->message .'</td>
					<td style="border:1px solid #000;" align="center">' . $notification->created_at.'</td>
				</tr>';
				$i++;
			}
			
			$excel_output.='</table>';
			$file = date("M_d_Y") . "_paid_auto_noti.xls";
			header("Content-disposition: attachment; filename=" . $file . ";");
			header("Content-type: application/vnd.ms-excel");
			echo $excel_output;
			exit;
		} else {
			$excel_output = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="clear:both; display:table; width:100%; border-left:1px solid #eee;" >';
			$excel_output.='<tr>
			  <th align="center" colspan="15"><h2>Paid Auto Notification</h2></th>
			  </tr>';
			  
			$excel_output.='<tr style="border:1px solid #000;">
				<td style="border:1px solid #000;" align="center"><strong>S.no.</strong></td>
				<td style="border:1px solid #000;" align="center"><strong>Message</strong></td>
				<td style="border:1px solid #000;" align="center"><strong>Created At</strong></td>
			</tr>';
			
			$excel_output.='<tr style="" >
				<th align="center" colspan="3">No records available.</th>
			</tr>';
			
			$excel_output.='</table>';
			$file = date("M_d_Y") . "_paid_auto_noti.xls";
			header("Content-disposition: attachment; filename=" . $file . ";");
			header("Content-type: application/vnd.ms-excel");
			echo $excel_output;
			exit;
		}
	}
	
	public function unpaid_excel_report() {
		$notifications = DB::table('auto_notifications')->where('message_for', 2)->orderby('id', 'DESC')->get();
		
		if (!empty($notifications)) {
			$excel_output = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="clear:both; display:table; width:100%; border-left:1px solid #eee;" >';
			$excel_output.='<tr>
			  <th align="center" colspan="15"><h2>Unpaid Auto Notification</h2></th>
			  </tr>';
			  
			$excel_output.='<tr style="border:1px solid #000;">
				<td style="border:1px solid #000;" align="center"><strong>S.no.</strong></td>
				<td style="border:1px solid #000;" align="center"><strong>Message</strong></td>
				<td style="border:1px solid #000;" align="center"><strong>Created At</strong></td>
			</tr>';
			 
			$i = 1;
			foreach ($notifications as $notification) {
				$excel_output.='<tr style="border:1px solid #000;" >
					<td style="border:1px solid #000;" align="center">' . $i  . '</td>
					<td style="border:1px solid #000;" align="center">' . $notification->message .'</td>
					<td style="border:1px solid #000;" align="center">' . $notification->created_at.'</td>
				</tr>';
				$i++;
			}
			
			$excel_output.='</table>';
			$file = date("M_d_Y") . "_unpaid_auto_noti.xls";
			header("Content-disposition: attachment; filename=" . $file . ";");
			header("Content-type: application/vnd.ms-excel");
			echo $excel_output;
			exit;
		} else {
			$excel_output = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="clear:both; display:table; width:100%; border-left:1px solid #eee;" >';
			$excel_output.='<tr>
			  <th align="center" colspan="15"><h2>Unpaid Auto Notification</h2></th>
			  </tr>';
			  
			$excel_output.='<tr style="border:1px solid #000;">
				<td style="border:1px solid #000;" align="center"><strong>S.no.</strong></td>
				<td style="border:1px solid #000;" align="center"><strong>Message</strong></td>
				<td style="border:1px solid #000;" align="center"><strong>Created At</strong></td>
			</tr>';
			
			$excel_output.='<tr style="" >
				<th align="center" colspan="3">No records available.</th>
			</tr>';
			
			$excel_output.='</table>';
			$file = date("M_d_Y") . "_unpaid_auto_noti.xls";
			header("Content-disposition: attachment; filename=" . $file . ";");
			header("Content-type: application/vnd.ms-excel");
			echo $excel_output;
			exit;
		}
	}
}
