@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
  <main class="content">

      <!-- STAT CARDS -->
      <div class="stats-grid">

        <!-- Total Users -->
        <div class="stat-card blue">
          <div class="stat-header">
            <div class="stat-icon blue">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
            </div>
            <div class="stat-trend up">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/>
              </svg>
              12%
            </div>
          </div>
          <div class="stat-value">1,284</div>
          <div class="stat-label">Total Users</div>
          <div class="stat-sub">↑ 138 users baru bulan ini</div>
          <div class="sparkline">
            <div class="sparkline-bar" style="height:40%; background:#BFDBFE;"></div>
            <div class="sparkline-bar" style="height:55%; background:#93C5FD;"></div>
            <div class="sparkline-bar" style="height:45%; background:#BFDBFE;"></div>
            <div class="sparkline-bar" style="height:70%; background:#60A5FA;"></div>
            <div class="sparkline-bar" style="height:60%; background:#93C5FD;"></div>
            <div class="sparkline-bar" style="height:80%; background:#3B82F6;"></div>
            <div class="sparkline-bar" style="height:100%; background:#2563EB;"></div>
          </div>
        </div>

        <!-- Total Bookings -->
        <div class="stat-card green">
          <div class="stat-header">
            <div class="stat-icon green">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
              </svg>
            </div>
            <div class="stat-trend up">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/>
              </svg>
              8%
            </div>
          </div>
          <div class="stat-value">3,892</div>
          <div class="stat-label">Total Bookings</div>
          <div class="stat-sub">↑ 289 booking bulan ini</div>
          <div class="sparkline">
            <div class="sparkline-bar" style="height:50%; background:#A7F3D0;"></div>
            <div class="sparkline-bar" style="height:65%; background:#6EE7B7;"></div>
            <div class="sparkline-bar" style="height:75%; background:#34D399;"></div>
            <div class="sparkline-bar" style="height:60%; background:#6EE7B7;"></div>
            <div class="sparkline-bar" style="height:85%; background:#10B981;"></div>
            <div class="sparkline-bar" style="height:70%; background:#34D399;"></div>
            <div class="sparkline-bar" style="height:100%; background:#059669;"></div>
          </div>
        </div>

        <!-- Active Bookings -->
        <div class="stat-card yellow">
          <div class="stat-header">
            <div class="stat-icon yellow">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
              </svg>
            </div>
            <div class="stat-trend flat">— 0%</div>
          </div>
          <div class="stat-value">142</div>
          <div class="stat-label">Active Bookings</div>
          <div class="stat-sub">Sedang berjalan sekarang</div>
          <div class="sparkline">
            <div class="sparkline-bar" style="height:80%; background:#FDE68A;"></div>
            <div class="sparkline-bar" style="height:70%; background:#FCD34D;"></div>
            <div class="sparkline-bar" style="height:90%; background:#FBBF24;"></div>
            <div class="sparkline-bar" style="height:75%; background:#F59E0B;"></div>
            <div class="sparkline-bar" style="height:85%; background:#FBBF24;"></div>
            <div class="sparkline-bar" style="height:70%; background:#FCD34D;"></div>
            <div class="sparkline-bar" style="height:80%; background:#F59E0B;"></div>
          </div>
        </div>

        <!-- Pending -->
        <div class="stat-card red">
          <div class="stat-header">
            <div class="stat-icon red">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
              </svg>
            </div>
            <div class="stat-trend down">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="7" y1="7" x2="17" y2="17"/><polyline points="17 7 17 17 7 17"/>
              </svg>
              3%
            </div>
          </div>
          <div class="stat-value">28</div>
          <div class="stat-label">Pending</div>
          <div class="stat-sub">Perlu tindakan segera</div>
          <div class="sparkline">
            <div class="sparkline-bar" style="height:60%; background:#FECACA;"></div>
            <div class="sparkline-bar" style="height:80%; background:#FCA5A5;"></div>
            <div class="sparkline-bar" style="height:50%; background:#FECACA;"></div>
            <div class="sparkline-bar" style="height:90%; background:#F87171;"></div>
            <div class="sparkline-bar" style="height:70%; background:#EF4444;"></div>
            <div class="sparkline-bar" style="height:55%; background:#FCA5A5;"></div>
            <div class="sparkline-bar" style="height:65%; background:#EF4444;"></div>
          </div>
        </div>

      </div>

      <!-- CONTENT GRID -->
      <div class="content-grid">

        <!-- ACTIVITY TABLE -->
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Aktivitas Terbaru</div>
              <div class="card-subtitle">Riwayat aksi 24 jam terakhir</div>
            </div>
            <a class="card-action" href="#">Lihat semua →</a>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>User</th>
                  <th>Action</th>
                  <th>Status</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-avatar ua-blue">AR</div>
                      <div>
                        <div class="user-name">Ari Ramadhan</div>
                        <div class="user-email">ari@example.com</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="action-text">Login ke sistem</div>
                    <div class="action-sub">via browser Chrome</div>
                  </td>
                  <td><span class="badge badge-success">Sukses</span></td>
                  <td><span class="date-text">10:24 AM</span></td>
                </tr>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-avatar ua-green">BW</div>
                      <div>
                        <div class="user-name">Budi Wicaksono</div>
                        <div class="user-email">budi@example.com</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="action-text">Tambah booking baru</div>
                    <div class="action-sub">Ruang Meeting A — 2 jam</div>
                  </td>
                  <td><span class="badge badge-info">Pending</span></td>
                  <td><span class="date-text">09:58 AM</span></td>
                </tr>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-avatar ua-violet">SR</div>
                      <div>
                        <div class="user-name">Siti Rahayu</div>
                        <div class="user-email">siti@example.com</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="action-text">Update profil user</div>
                    <div class="action-sub">Role diubah ke staff</div>
                  </td>
                  <td><span class="badge badge-success">Sukses</span></td>
                  <td><span class="date-text">09:31 AM</span></td>
                </tr>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-avatar ua-orange">DK</div>
                      <div>
                        <div class="user-name">Dika Kurniawan</div>
                        <div class="user-email">dika@example.com</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="action-text">Batalkan booking</div>
                    <div class="action-sub">ID #BK-2047</div>
                  </td>
                  <td><span class="badge badge-danger">Dibatalkan</span></td>
                  <td><span class="date-text">08:47 AM</span></td>
                </tr>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-avatar ua-pink">ML</div>
                      <div>
                        <div class="user-name">Maya Lestari</div>
                        <div class="user-email">maya@example.com</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="action-text">Export laporan</div>
                    <div class="action-sub">Bulanan — April 2026</div>
                  </td>
                  <td><span class="badge badge-warning">Proses</span></td>
                  <td><span class="date-text">08:12 AM</span></td>
                </tr>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-avatar ua-blue">RH</div>
                      <div>
                        <div class="user-name">Raka Hidayat</div>
                        <div class="user-email">raka@example.com</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="action-text">Tambah user baru</div>
                    <div class="action-sub">role: staff</div>
                  </td>
                  <td><span class="badge badge-success">Sukses</span></td>
                  <td><span class="date-text">07:55 AM</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- ROLE DISTRIBUTION -->
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Distribusi Role</div>
              <div class="card-subtitle">Dari total 1,284 users</div>
            </div>
          </div>

          <div class="donut-wrap">
            <div class="donut">
              <div class="donut-center">
                <div class="donut-total">1,284</div>
                <div class="donut-label">Total</div>
              </div>
            </div>
          </div>

          <div class="role-dist">
            <div class="role-item">
              <div class="role-dot" style="background:#3B82F6;"></div>
              <div class="role-info">
                <div class="role-name">
                  Admin
                  <span class="role-count">384 users · 30%</span>
                </div>
                <div class="role-bar">
                  <div class="role-bar-fill" style="width:30%; background:linear-gradient(90deg,#3B82F6,#60A5FA);"></div>
                </div>
              </div>
            </div>

            <div class="role-item">
              <div class="role-dot" style="background:#10B981;"></div>
              <div class="role-info">
                <div class="role-name">
                  Staff
                  <span class="role-count">385 users · 30%</span>
                </div>
                <div class="role-bar">
                  <div class="role-bar-fill" style="width:30%; background:linear-gradient(90deg,#10B981,#34D399);"></div>
                </div>
              </div>
            </div>

            <div class="role-item">
              <div class="role-dot" style="background:#94A3B8;"></div>
              <div class="role-info">
                <div class="role-name">
                  User
                  <span class="role-count">515 users · 40%</span>
                </div>
                <div class="role-bar">
                  <div class="role-bar-fill" style="width:40%; background:linear-gradient(90deg,#94A3B8,#CBD5E1);"></div>
                </div>
              </div>
            </div>

            <div style="height:1px; background:var(--border); margin:16px 0;"></div>

            <div style="display:flex; gap:8px; flex-direction:column;">
              <div style="display:flex; justify-content:space-between; font-size:12px; color:var(--text-muted);">
                <span>Users aktif hari ini</span>
                <strong style="color:var(--text-primary);">89</strong>
              </div>
              <div style="display:flex; justify-content:space-between; font-size:12px; color:var(--text-muted);">
                <span>Login terakhir (1 jam)</span>
                <strong style="color:var(--text-primary);">23</strong>
              </div>
              <div style="display:flex; justify-content:space-between; font-size:12px; color:var(--text-muted);">
                <span>User baru minggu ini</span>
                <strong style="color:var(--success);">+41</strong>
              </div>
            </div>
          </div>
        </div>

      </div>

    </main>
@endsection