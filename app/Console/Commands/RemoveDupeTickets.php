<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDupeTickets extends Command
{
    protected $signature = 'delete-duplicate-tickets';
    protected $description = 'Delete duplicate tickets';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        
        $duplicateRecords = Ticket::select('title', 'status', DB::raw('MAX(created_at) as latest_created_at'))
            ->groupBy('title', 'status')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicateRecords as $record) {
            Ticket::where('title', $record->title)
                ->where('status', $record->status)
                ->where('created_at', '<', $record->latest_created_at)
                ->delete();
        }

        $this->info('Duplicate records removed successfully.');
    }
    
}