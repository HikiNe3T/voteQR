<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - VoteQR</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    .stat-filter {
      transition: box-shadow 0.15s ease, transform 0.1s ease;
    }
    .stat-filter:hover {
      transform: translateY(-2px);
    }
    .stat-filter--active {
      box-shadow: 0 0 0 2px var(--primary-500);
    }
  </style>
</head>
<body>

  <!-- Header -->
  @include('layouts.header')

  <!-- Main Content -->
  <main class="section page">
    <div class="container">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="section-title">Dashboard</h1>
          <p class="section-subtitle">Kelola event Anda</p>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="stats-grid" id="statsFilterBar">
        <!-- <div class="card stat-filter stat-filter--active" data-filter="all" onclick="filterEvents('all', this)" style="cursor:pointer; border-left: 4px solid var(--neutral-400);">
          <div class="flex items-center gap-3">
            <div class="stat-icon" style="background: var(--neutral-100); color: var(--neutral-600);">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div>
              <div class="stat-value">{{ number_format($totalEventsCount ?? 0) }}</div>
              <div class="stat-label">Semua Event</div>
            </div>
          </div>
        </div> -->

        <div class="card stat-filter" data-filter="active" onclick="filterEvents('active', this)" style="cursor:pointer; border-left: 4px solid var(--primary-500);">
          <div class="flex items-center gap-3">
            <div class="stat-icon" style="background: var(--primary-100); color: var(--primary-600);">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg>
            </div>
            <div>
              <div class="stat-value">{{ number_format($activeEventsCount ?? 0) }}</div>
              <div class="stat-label">Sedang Berlangsung</div>
            </div>
          </div>
        </div>

        <div class="card stat-filter" data-filter="upcoming" onclick="filterEvents('upcoming', this)" style="cursor:pointer; border-left: 4px solid var(--accent-500);">
          <div class="flex items-center gap-3">
            <div class="stat-icon" style="background: var(--accent-100); color: var(--accent-600);">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
              <div class="stat-value">{{ number_format($upcomingEventsCount ?? 0) }}</div>
              <div class="stat-label">Akan Datang</div>
            </div>
          </div>
        </div>

        <div class="card stat-filter" data-filter="finished" onclick="filterEvents('finished', this)" style="cursor:pointer; border-left: 4px solid var(--success-500);">
          <div class="flex items-center gap-3">
            <div class="stat-icon" style="background: var(--success-100); color: var(--success-600);">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div>
              <div class="stat-value">{{ number_format($finishedEventsCount ?? 0) }}</div>
              <div class="stat-label">Selesai</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Event List -->
      <div class="flex items-center justify-between mb-4">
        <h2 style="font-size: var(--font-size-xl); font-weight: 600;">Daftar Event</h2>
      </div>

      <div class="grid-3 stagger-children">
        @forelse($events as $event)
          <div class="event-card" data-status="{{ $event->status }}" onclick="window.location='{{ route('admin.event', $event->id) }}'" style="cursor:pointer;">
            
            <div class="event-card__image">
              @if($event->image_url)
                <img src="{{ asset('storage/events/' . $event->image_url) }}" style="width:100%; height:100%; object-fit:cover;">
              @else
                <div style="background:#6366f1; width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:white;">
                  📊
                </div>
              @endif
            </div>

            <div class="event-card__content">
              <div class="event-card__title">
                {{ $event->name }}
              </div>

              <div class="text-sm mt-2 countdown"
                data-start="{{ $event->start_date }} {{ $event->start_time }}"
                data-end="{{ $event->end_date }} {{ $event->end_time }}">
                ⏳ Loading countdown...
              </div>

              <div class="event-card__meta">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ $event->formatted_start_date }} / {{ $event->formatted_end_date }}
              </div>

              <div class="mt-2 flex flex-wrap gap-1">
                <span class="badge badge--primary"> 
                  {{ $event->voting_type }} 
                </span>

                <span class="badge 
                  @if($event->status == 'upcoming') badge--warning
                  @elseif($event->status == 'active') badge--primary
                  @elseif($event->status == 'finished') badge--success
                  @endif
                  "> {{ $event->status }}
                </span>

                <!-- <span class="badge badge--primary"> 
                  Total Vote: {{ $event->voted_count ?? $event->participants->where('has_voted', 1)->count() }}
                </span> -->
              </div>

              <div class="mt-4 pt-3" style="border-top: 1px solid var(--neutral-200);" onclick="event.stopPropagation();">
                @if($event->status === 'finished')
                <!-- <a href="{{ route('event.topsis', ['event_id' => $event->id]) }}" class="btn btn--primary text-xs" style="width: 100%; text-align: center;">Lihat TOPSIS</a> -->
                @else
                  <button class="btn text-xs" style="width: 100%; opacity: 0.6; cursor: not-allowed; background: var(--neutral-200); color: var(--neutral-600);" disabled title="Event harus selesai terlebih dahulu">
                    🔒 TOPSIS (Belum Selesai)
                  </button>
                @endif
              </div>

            </div>
          </div>
        @empty
          <p class="text-muted">Tidak ada event</p>
        @endforelse
      </div>
    </div>
  </main>

  <!-- Floating Button: Buat Event -->
  <a href="{{ route('create.event') }}" class="btn btn--floating" title="Buat Event">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
  </a>

  <!-- Floating Navigation -->
  <nav class="floating-nav">
    <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'nav-item--active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      <span>Home</span>
    </a>
    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'nav-item--active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <span>Dashboard</span>
    </a>
    <a href="{{ route('list.event') }}" class="nav-item {{ request()->routeIs('list.event') ? 'nav-item--active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="4" cy="6" r="1"/><circle cx="4" cy="12" r="1"/><circle cx="4" cy="18" r="1"/></svg>
      <span>Event</span>
    </a>
  </nav>

  <script>
    function toggleTheme() {
      const html = document.documentElement;
      const current = html.getAttribute('data-theme');
      html.setAttribute('data-theme', current === 'dark' ? 'light' : 'dark');
      localStorage.setItem('theme', current === 'dark' ? 'light' : 'dark');
    }
    const saved = localStorage.getItem('theme');
    if (saved) document.documentElement.setAttribute('data-theme', saved);

    function startCountdown() {
      const elements = document.querySelectorAll('.countdown');

      elements.forEach(el => {
        const start = new Date(el.dataset.start).getTime();
        const end = new Date(el.dataset.end).getTime();

        function update() {
          const now = new Date().getTime();

          let target;
          let label;

          if (now < start) {
            target = start;
            label = "Mulai dalam";
          } else if (now >= start && now <= end) {
            target = end;
            label = "Berakhir dalam";
          } else {
            el.innerHTML = "✅ Event selesai";
            return;
          }

          const distance = target - now;

          const days = Math.floor(distance / (1000 * 60 * 60 * 24));
          const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
          const minutes = Math.floor((distance / (1000 * 60)) % 60);
          const seconds = Math.floor((distance / 1000) % 60);

          el.innerHTML = `
            ⏳ ${label}: 
            ${days}h ${hours}j ${minutes}m ${seconds}d
          `;
        }

        update();
        setInterval(update, 1000);
      });
    }

    document.addEventListener('DOMContentLoaded', startCountdown);

    function filterEvents(status, clickedCard) {
      // toggle highlight card filter yang aktif
      document.querySelectorAll('.stat-filter').forEach(el => {
        el.classList.remove('stat-filter--active');
      });
      clickedCard.classList.add('stat-filter--active');

      // tampilkan/sembunyikan event-card sesuai status
      const cards = document.querySelectorAll('.event-card');
      let visibleCount = 0;

      cards.forEach(card => {
        const matches = (status === 'all' || card.dataset.status === status);
        card.style.display = matches ? '' : 'none';
        if (matches) visibleCount++;
      });

      // tampilkan pesan kalau kosong
      let emptyMsg = document.getElementById('emptyFilterMsg');
      const grid = document.querySelector('.grid-3');

      if (visibleCount === 0) {
        if (!emptyMsg) {
          emptyMsg = document.createElement('p');
          emptyMsg.id = 'emptyFilterMsg';
          emptyMsg.className = 'text-muted';
          emptyMsg.textContent = 'Tidak ada event pada kategori ini';
          grid.after(emptyMsg);
        }
        emptyMsg.style.display = '';
      } else if (emptyMsg) {
        emptyMsg.style.display = 'none';
      }
    }
  </script>
</body>
</html>