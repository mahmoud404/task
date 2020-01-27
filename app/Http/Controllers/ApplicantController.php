<?php

namespace App\Http\Controllers;

use App\Applicant;
use App\Helpers\UploadImage;
use App\Http\Requests\Admin\ApplicantRequest;

class ApplicantController extends Controller
{
    use UploadImage;

    public function index()
    {
        return view('admin.applicant');
    }

    public function apply(ApplicantRequest $request)
    {
        $applicant =Applicant::create($request->except('cv'));
        if ($request->cv) {
            $this->upload($request->cv,$applicant,'cv');
        }
        if ($request->passport_image) {
            $this->upload($request->passport_image,$applicant,'passport_image');
        }
        return back()->with('msg','Send Successfully');
    }
}
