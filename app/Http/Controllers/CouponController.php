<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Coupon\AddNewRequest;
use App\Http\Requests\Backend\Coupon\UpdateRequest;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userRoleId = auth()->user()->role_id;
        $instructorId = auth()->user()->instructor_id;
        if($userRoleId == 1){
            $coupon= Coupon::get();
        }
        else{
            $coupon= Coupon::where('instructor_id', $instructorId)->get();
        }
        
        return view('backend.coupon.index', compact('coupon'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userRoleId = auth()->user()->role_id;
        $instructorId = auth()->user()->instructor_id;

        if($userRoleId == 1){
            $course = Course::get();
        }
        else{
            $course= Course::where('instructor_id', $instructorId)->get();
        }
        
        return view('backend.coupon.create', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddNewRequest $request)
    {
        try {
            $coupon = new Coupon;
            $coupon->course_id = $request->course_id;
            $course->instructor_id = $request->instructor_id;
            $coupon->code = $request->code;
            $coupon->discount = $request->discount;
            $coupon->valid_from = $request->valid_from;
            $coupon->valid_until = $request->valid_until;
           
            if($coupon->save())
                return redirect()->route('coupon.index')->with('success','Coupon Saved');
                else 
                return redirect()->back()->withInput()->with('error', 'Please try again');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Please try again');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('backend.coupon.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->course_id = $request->course_id;
            $course->instructor_id = $request->instructor_id;
            $coupon->code = $request->code;
            $coupon->discount = $request->discount;
            $coupon->valid_from = $request->valid_from;
            $coupon->valid_until = $request->valid_until;

            if ($coupon->save())
                return redirect()->route('coupon.index')->with('success', 'Coupon Saved');
            else
                return redirect()->back()->withInput()->with('error', 'Please try again');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Please try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        if($coupon->delete())
        return redirect()->back()->with('error','Data Deleted');
    }
}
