<?php


namespace App\Services;


use App\Models\ContactUs;
use App\Models\HomeSlider;
use App\Models\User;
use App\Traits\UploadFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class HomeSliderService
{
    use UploadFile;

    public function show($request){

        if ($request->ajax()) {
            $sortOrder = (($request->has('sortDesc') and $request->sortDesc == 'true') ? 'DESC' : 'ASC');
            $sortBy = (($request->has('sortBy') and $request->sortBy != '') ? $request->sortBy : 'id');
            $home = new HomeSlider();

            $home = $home->orderBy($sortBy, $sortOrder)->paginate($request->perPage);

            return Response::json($home);
        }
        return view('home');

    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            if ($request->has('id') AND $request->id)
                $home = HomeSlider::find($request->id);
            else
                return Response::json(["message" => "Error"], 400);

            if ($request->hasFile('image_path')) {
                $image_path = $home->image_path ?? null;
                $image = $request->file('image_path');
                $home->image_path = $this->upload($image, "sliders", $image_path);
            }
            if ($request->hasFile('small_image_path')) {
                $image_path = $home->small_image_path ?? null;
                $image = $request->file('small_image_path');
                $home->small_image_path = $this->upload($image, "sliders", $image_path);
            }

            $contactUs->title = $request->title;
            $contactUs->sescription = $request->sescription;
            $contactUs->product_id = $request->product_id;
            $contactUs->save();

            DB::commit();
            return Response::json(['isSuccess' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(["message" => $e->getMessage()], 400);
        }
    }

}
