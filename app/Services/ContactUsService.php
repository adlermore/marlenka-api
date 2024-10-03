<?php


namespace App\Services;


use App\Models\ContactUs;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class ContactUsService
{
    public function show($request){

        if ($request->ajax()) {

            $contactUs = new ContactUs();

            $contactUs = $contactUs->paginate($request->perPage);
            return Response::json($contactUs);
        }
        return view('home');

    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            if ($request->has('id') AND $request->id)
                $contactUs = ContactUs::find($request->id);
            else
                return Response::json(["message" => "Error"], 400);
            $contactUs->phone_number = $request->phone_number;
            $contactUs->whatsapp = $request->whatsapp;
            $contactUs->email = $request->email;
            $contactUs->facebook_link = $request->facebook_link;
            $contactUs->instagram_link = $request->instagram_link;
            $contactUs->gmail_link = $request->gmail_link;
            $contactUs->linkedin_link = $request->linkedin_link;
            $contactUs->twitter_link = $request->twitter_link;
            $contactUs->youtube_link = $request->youtube_link;
            $contactUs->save();

            DB::commit();
            return Response::json(['isSuccess' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(["message" => $e->getMessage()], 400);
        }
    }

}
