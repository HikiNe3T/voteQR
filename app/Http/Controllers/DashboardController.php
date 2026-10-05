<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Participant;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Load event milik user + relasi participant (dipakai untuk fallback badge per-card)
        $events = Event::where('creator_id', $userId)
            ->withCount(['participants as voted_count' => function ($query) {
                $query->where('has_voted', 1);
            }])
            ->with('participants')
            ->latest()
            ->get();

        // Total Event
        $totalEventsCount = $events->count();

        // Total Peserta dari semua event milik user
        $totalParticipantsCount = Participant::whereHas('event', function ($query) use ($userId) {
            $query->where('creator_id', $userId);
        })->count();

        // Total Vote Riil dari peserta yang sudah voting
        $totalVotesCount = Participant::whereHas('event', function ($query) use ($userId) {
            $query->where('creator_id', $userId);
        })->where('has_voted', 1)->count();

        // Jumlah event per status (pakai accessor $event->status)
        $activeEventsCount   = $events->where('status', 'active')->count();
        $upcomingEventsCount = $events->where('status', 'upcoming')->count();
        $finishedEventsCount = $events->where('status', 'finished')->count();

        return view('dashboard', compact(
            'events',
            'totalEventsCount',
            'totalParticipantsCount',
            'totalVotesCount',
            'activeEventsCount',
            'upcomingEventsCount',
            'finishedEventsCount'
        ));
    }

    public function show($id)
    {
        $event = Event::with(['participants', 'candidates'])->findOrFail($id);

        return view('admin-event', compact('event'));
    }
}