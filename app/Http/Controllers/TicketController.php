<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\AppLogs;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
class TicketController extends Controller
{
    protected $agent;

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $tickets = Ticket::all();
        return view('tickets.list', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
       if($request->file('attachment'))
        {
            $ext = $request->file('attachment')->extension();
            $contents = file_get_contents($request->file('attachment'));
            $filename = Str::random(10);
            $path =  "attachments/$filename.$ext";
            Storage::disk('public')->put($path,$contents);
        }

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
            'attachment' => $request->file('attachment') ? $path : null,
        ]);

        $tickets['data'] = $ticket;
        $tickets['status'] = "200";
        $tickets['status-message'] = "Record added successfully";
        
        //Create Log
        $formdata = [];
        $device = $this->agent->getUserAgent();
        $ip = request()->getClientIp();
        $formdata = $request->all();
        $formdata['attachment'] = $request->file('attachment');
        $data['entry_id'] = $ticket->id;
        $data['action'] = 'Create';
        $data['task_title'] = 'Ticket';
        $data['ip_address'] = $ip;
        $data['description'] = json_encode($formdata);
        $data['url'] = request()->root();
        $data['device_details'] = $device;
        $data['action_by'] = auth()->user()->id;
        $newRecord = createLog(AppLogs::class, $data);
        return  redirect()->route('ticket.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
