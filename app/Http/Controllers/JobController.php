<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Http\Requests\JobRequest;
use App\Traits\AttributeTrait;
use Auth;

class JobController extends Controller
{
    use AttributeTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = $request->search;
        $from = $request->from;
        $to = $request->to;
        $jobs = Job::orderBy('created_at', 'desc');
        if(!empty($sort_search))
            $jobs = $jobs->where('title', 'like', '%'.$sort_search.'%')
                         ->orwhere('content', 'like', '%'.$sort_search.'%');
        if(!empty($from))
            $jobs = $jobs->where('created_at', '>=', $from);
        if(!empty($to))
            $jobs = $jobs->where('created_at', '<=', $to);
        $jobs = $jobs->paginate(15);
        return view('backend.jobs.index', compact('jobs', 'sort_search', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobRequest $request)
    {
        $job= new Job;
        $job->title = $request->title;
        $job->slug = preg_replace('/\s+/', '-', $request->title) . '-' . $job->id;
        $job->excerpt = $request->excerpt;
        $job->shop_id = $request->shop_id;
        $job->content = $request->content;
        if($job->save()){
            flash(translate('New job was created successfully.'))->success();
            if(auth()->user()->isAdmin() || auth()->user()->isStaff()){
                return redirect()->route('admin.jobs.index');
            }
            return redirect()->route('seller.jobs');
        }else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   \Illuminate\Http\JobRequest  $request
     * @param   \App\Models\Job $job
     * @param   \Illuminate\Http\Request $id
     * @return  \Illuminate\Http\Response
     */
    public function update(JobRequest $request, $id)
    {

        $job= Job::findOrFail($id);
        $updated_attributes = $request->except(['_method', '_token', 'title', 'description', 'user_id', 'upload_id']);

        $this->updateAttributes($updated_attributes, $job, "App\Models\Job", $id);

        $job->title = $request->title;
        $job->slug = preg_replace('/\s+/', '-', $request->title) . '-' . $job->id;
        $job->excerpt = $request->excerpt;
        $job->content = $request->content;

        if($job->save()){
            flash(translate('Event has been updated successfully'))->success();
            if(auth()->user()->isAdmin() || auth()->user()->isStaff()){
                return redirect()->route('admin.jobs.index');
            }
            else{
                return redirect()->route('seller.jobs');
            }
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job, $id)
    {
        $job->destroy($id);
        flash(translate('Event has been deleted successfully'))->success();
        if(auth()->user()->isAdmin() || auth()->user()->isStaff()){
            return redirect()->route('admin.jobs.index');
        }
        else{
            return redirect()->route('seller.jobs');
        }
    }

    /**
     * Display Seller Job
     *
     * @return \Illuminate\Http\Response
     */
    public function seller_jobs(Job $job)
    {
        $jobs = $job->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.user.seller.jobs.index', compact('jobs'));
    }

    /**
     * Display Seller Job Create page
     *
     * @return \Illuminate\Http\Response
     */
    public function seller_jobs_create()
    {
        return view('frontend.user.seller.jobs.job_upload');
    }

    /**
     * Display Seller Job Edit page
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function seller_jobs_edit(Job $job, $id)
    {
        $job = $job->findOrFail($id);
        $content_type = 'App\Models\Job';
        $attributes = Attribute::where('content_type', $content_type)->orderBy('created_at', 'desc')->get();

        return view('frontend.user.seller.jobs.job_edit', compact(['job', 'attributes']));
    }
}
