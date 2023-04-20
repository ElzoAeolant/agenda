<?php

namespace App\Http\Controllers;

use App\FamilySchool;
use Illuminate\Http\Request;

class FamilySchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FamilySchool  $familySchool
     * @return \Illuminate\Http\Response
     */
    public function show(FamilySchool $familySchool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FamilySchool  $familySchool
     * @return \Illuminate\Http\Response
     */
    public function edit(FamilySchool $familySchool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FamilySchool  $familySchool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FamilySchool $familySchool)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FamilySchool  $familySchool
     * @return \Illuminate\Http\Response
     */
    public function destroy(FamilySchool $familySchool)
    {
        //
    }

    /**
     * Send statements about unattendance.
     *
     */
    public function send()
    {
        return view('familyschools.send')
        ->withStatus('warning-'.trans('Esta operación no ha sido habilitada.'));
    }
    /**
     * Show webcam qr scanner.
     *
     */
    public function showscan()
    {
        return view('familyschools.showscan');
    }
}
