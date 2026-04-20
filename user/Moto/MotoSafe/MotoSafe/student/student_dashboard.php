<?php
  //require 'student_auth.php'; comment lg anay, session ni
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MotoSafe – Student Dashboard</title>
  <link href="../assets/img/logo.png" rel="icon">
  <link href="../assets/img/logo.png" rel="apple-touch-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <link href="../assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
  <div class="overlay" id="overlay" onclick="closeSidebar()"></div>
  <div class="app">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <div class="logo">
        <div class="logo-icon">
          <img src="../assets/img/logo.png" alt="MotoSafe">
        </div>
        Moto<span>Safe</span>
      </div>
      <div class="nav-label">Main</div>
      <a class="nav-item active" href="#"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a class="nav-item" href="#"><i class="fa-solid fa-book-open"></i> Courses</a>
      <a class="nav-item" href="#"><i class="fa-solid fa-calendar-days"></i> Schedule</a>
      <a class="nav-item" href="#"><i class="fa-solid fa-chart-bar"></i> Analytics</a>
      <a class="nav-item" href="#"><i class="fa-solid fa-file-pen"></i> Assignments</a>
      <div class="nav-label">Account</div>
      <a class="nav-item" href="#"><i class="fa-solid fa-bell"></i> Notifications</a>
      <a class="nav-item" href="#"><i class="fa-solid fa-gear"></i> Settings</a>
      <a class="nav-item" href="#"><i class="fa-solid fa-circle-question"></i> Help</a>
      <div class="sidebar-bottom">
        <div class="user-row">
          <div class="avatar">JD</div>
          <div>
            <div class="user-name">Jamie Davis</div>
            <div class="user-role">Student · Year 3</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- MAIN -->
    <div class="main">
      <div class="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
          <div class="icon-btn menu-toggle" onclick="openSidebar()"><i class="fa-solid fa-bars"></i></div>
          <div class="page-title">Dashboard</div>
        </div>
        <div class="topbar-right">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search courses, topics…" />
          </div>
          <div class="icon-btn badge-btn"><i class="fa-regular fa-bell"></i></div>
          <div class="icon-btn"><i class="fa-regular fa-comment"></i></div>
          <div class="avatar" style="cursor:pointer;">JD</div>
        </div>
      </div>

      <div class="content">

        <!-- HERO -->
        <div class="hero-strip">
          <div class="hero-text">
            <div class="greeting">Monday, March 2026</div>
            <h2>Good morning, Jamie 👋</h2>
            <p>You have 3 classes today and 2 assignments due this week.</p>
            <!-- Compact stats shown on tablet/mobile instead of side card -->
            <div class="hero-quick">
              <div class="hero-quick-item"><strong>3.87</strong><span>GPA</span></div>
              <div class="hero-quick-item"><strong>94%</strong><span>Attendance</span></div>
              <div class="hero-quick-item" style="display:flex;align-items:center;gap:8px;">
                <div class="session-dot"></div>
                <div><strong style="font-size:13px;display:block;">Live now</strong><span>Adv. Calculus · 45 min left</span></div>
              </div>
            </div>
          </div>
          <!-- Side card hidden below 900px, replaced by hero-quick above -->
          <div class="hero-session">
            <div class="session-dot"></div>
            <div class="session-info">
              <div class="label">Live now</div>
              <div class="title">Advanced Calculus</div>
              <div class="meta">Room 204 · 45 min left</div>
            </div>
            <span class="tag tag-amber"><i class="fa-solid fa-circle" style="font-size:6px;"></i> Live</span>
          </div>
        </div>

        <!-- STATS -->
        <div class="stats-row">
          <div class="stat-card">
            <div class="stat-top"><div class="stat-label">Overall GPA</div><div class="stat-icon"><i class="fa-solid fa-star"></i></div></div>
            <div class="stat-value">3.87</div>
            <div class="stat-sub"><span class="up">↑ +0.12</span> from last semester</div>
          </div>
          <div class="stat-card">
            <div class="stat-top"><div class="stat-label">Attendance</div><div class="stat-icon" style="color:var(--green);"><i class="fa-solid fa-calendar-check"></i></div></div>
            <div class="stat-value">94<span style="font-size:16px;color:var(--muted);">%</span></div>
            <div class="stat-sub"><span class="up">↑ 2%</span> above average</div>
          </div>
          <div class="stat-card">
            <div class="stat-top"><div class="stat-label">Assignments</div><div class="stat-icon" style="color:var(--amber);"><i class="fa-solid fa-file-lines"></i></div></div>
            <div class="stat-value">12</div>
            <div class="stat-sub"><span class="warn">2 pending</span> · 10 completed</div>
          </div>
          <div class="stat-card">
            <div class="stat-top"><div class="stat-label">Study Hours</div><div class="stat-icon" style="color:#c084fc;"><i class="fa-solid fa-clock"></i></div></div>
            <div class="stat-value">38</div>
            <div class="stat-sub">This week · <span class="up">+6h</span> vs last</div>
          </div>
        </div>

        <!-- TABS -->
        <div class="tab-row">
          <div class="tab active">Overview</div>
          <div class="tab">Courses</div>
          <div class="tab">Grades</div>
          <div class="tab">Assignments</div>
          <div class="tab">Resources</div>
        </div>

        <!-- GRID MAIN -->
        <div class="grid-main">
          <div class="panel">
            <div class="panel-head">
              <div><div class="panel-title">Course Performance</div><div class="panel-sub">Current semester progress</div></div>
              <span class="tag tag-blue">Semester 1</span>
            </div>
            <div class="tbl-wrap">
              <table class="tbl">
                <thead><tr><th>Subject</th><th>Grade</th><th>Progress</th><th>Status</th></tr></thead>
                <tbody>
                  <tr><td>Advanced Calculus</td><td><span class="tbl-num">A</span></td><td><div class="prog-val">88%</div><div class="prog-bar"><div class="prog-bar-fill" style="width:88%"></div></div></td><td><span class="tag tag-green">On track</span></td></tr>
                  <tr><td>Data Structures</td><td><span class="tbl-num">A−</span></td><td><div class="prog-val">82%</div><div class="prog-bar"><div class="prog-bar-fill" style="width:82%"></div></div></td><td><span class="tag tag-green">On track</span></td></tr>
                  <tr><td>Physics II</td><td><span class="tbl-num">B+</span></td><td><div class="prog-val">76%</div><div class="prog-bar"><div class="prog-bar-fill amber" style="width:76%"></div></div></td><td><span class="tag tag-amber">Review</span></td></tr>
                  <tr><td>English Lit</td><td><span class="tbl-num">A</span></td><td><div class="prog-val">91%</div><div class="prog-bar"><div class="prog-bar-fill green" style="width:91%"></div></div></td><td><span class="tag tag-green">Excellent</span></td></tr>
                  <tr><td>Statistics</td><td><span class="tbl-num">B</span></td><td><div class="prog-val">69%</div><div class="prog-bar"><div class="prog-bar-fill amber" style="width:69%"></div></div></td><td><span class="tag tag-red">At risk</span></td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="panel">
            <div class="panel-head">
              <div><div class="panel-title">Today's Schedule</div><div class="panel-sub">Monday, March 16</div></div>
              <div class="icon-btn" style="width:28px;height:28px;font-size:11px;"><i class="fa-solid fa-ellipsis"></i></div>
            </div>
            <div class="panel-body">
              <div class="schedule-list">
                <div class="schedule-item">
                  <div class="schedule-time"><div class="t">08:30</div><div class="ampm">AM</div></div>
                  <div class="schedule-divider"></div>
                  <div class="schedule-info"><div class="s-title">Advanced Calculus</div><div class="s-meta">Room 204 · Prof. Martinez</div></div>
                  <span class="tag tag-amber" style="margin-left:auto;">Live</span>
                </div>
                <div class="schedule-item">
                  <div class="schedule-time"><div class="t">11:00</div><div class="ampm">AM</div></div>
                  <div class="schedule-divider"></div>
                  <div class="schedule-info"><div class="s-title">Data Structures</div><div class="s-meta">Lab B · Prof. Chen</div></div>
                </div>
                <div class="schedule-item">
                  <div class="schedule-time"><div class="t">02:00</div><div class="ampm">PM</div></div>
                  <div class="schedule-divider"></div>
                  <div class="schedule-info"><div class="s-title">Study Group</div><div class="s-meta">Library · Statistics</div></div>
                  <span class="tag tag-blue" style="margin-left:auto;">Optional</span>
                </div>
                <div class="schedule-item">
                  <div class="schedule-time"><div class="t">04:30</div><div class="ampm">PM</div></div>
                  <div class="schedule-divider"></div>
                  <div class="schedule-info"><div class="s-title">Physics II</div><div class="s-meta">Room 112 · Prof. Kim</div></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TABLE: ASSIGNMENTS DUE -->
        <div class="panel">
          <div class="panel-head">
            <div><div class="panel-title">Assignments Due</div><div class="panel-sub">Upcoming deadlines — sorted by urgency</div></div>
            <span class="tag tag-red">2 overdue</span>
          </div>
          <div class="tbl-wrap">
            <table class="tbl">
              <thead>
                <tr>
                  <th></th>
                  <th>Assignment</th>
                  <th>Subject</th>
                  <th>Due Date</th>
                  <th>Type</th>
                  <th>Weight</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><span class="dot dot-red"></span></td>
                  <td><strong>Problem Set 4</strong></td>
                  <td>Advanced Calculus</td>
                  <td style="color:var(--red);font-weight:600;">Mar 14</td>
                  <td><span class="tag tag-blue">Problem Set</span></td>
                  <td>15%</td>
                  <td><span class="tag tag-red">Overdue</span></td>
                </tr>
                <tr>
                  <td><span class="dot dot-red"></span></td>
                  <td><strong>Lab Report 3</strong></td>
                  <td>Physics II</td>
                  <td style="color:var(--red);font-weight:600;">Mar 15</td>
                  <td><span class="tag tag-purple">Lab Report</span></td>
                  <td>20%</td>
                  <td><span class="tag tag-red">Overdue</span></td>
                </tr>
                <tr>
                  <td><span class="dot dot-amber"></span></td>
                  <td><strong>Essay: Modernism</strong></td>
                  <td>English Lit</td>
                  <td>Mar 18</td>
                  <td><span class="tag tag-amber">Essay</span></td>
                  <td>25%</td>
                  <td><span class="tag tag-amber">In Progress</span></td>
                </tr>
                <tr>
                  <td><span class="dot dot-amber"></span></td>
                  <td><strong>Binary Trees Implementation</strong></td>
                  <td>Data Structures</td>
                  <td>Mar 21</td>
                  <td><span class="tag tag-blue">Coding</span></td>
                  <td>30%</td>
                  <td><span class="tag tag-blue">Started</span></td>
                </tr>
                <tr>
                  <td><span class="dot dot-blue"></span></td>
                  <td><strong>Statistics Project</strong></td>
                  <td>Statistics</td>
                  <td>Mar 28</td>
                  <td><span class="tag tag-purple">Project</span></td>
                  <td>40%</td>
                  <td><span class="tag tag-blue">Not started</span></td>
                </tr>
                <tr>
                  <td><span class="dot dot-green"></span></td>
                  <td><strong>Problem Set 3</strong></td>
                  <td>Advanced Calculus</td>
                  <td style="color:var(--muted);">Mar 10</td>
                  <td><span class="tag tag-blue">Problem Set</span></td>
                  <td>15%</td>
                  <td><span class="tag tag-green">Submitted</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- TABLE: EXAM SCHEDULE -->
        <div class="panel">
          <div class="panel-head">
            <div><div class="panel-title">Upcoming Exams</div><div class="panel-sub">Midterm &amp; final examination schedule</div></div>
            <span class="tag tag-amber">4 upcoming</span>
          </div>
          <div class="tbl-wrap">
            <table class="tbl">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Subject</th>
                  <th>Type</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Room</th>
                  <th>Coverage</th>
                  <th>Prep Progress</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="color:var(--muted-2);font-size:12px;">01</td>
                  <td><strong>Advanced Calculus</strong><div style="font-size:11px;color:var(--muted);">Prof. Martinez</div></td>
                  <td><span class="tag tag-red">Midterm</span></td>
                  <td style="font-weight:600;color:var(--amber);">Mar 20</td>
                  <td>09:00 AM</td>
                  <td>Hall A · R12</td>
                  <td style="font-size:12px;color:var(--muted);">Ch 1–6, Integration</td>
                  <td><div class="prog-val">72%</div><div class="prog-bar"><div class="prog-bar-fill amber" style="width:72%"></div></div></td>
                </tr>
                <tr>
                  <td style="color:var(--muted-2);font-size:12px;">02</td>
                  <td><strong>Physics II</strong><div style="font-size:11px;color:var(--muted);">Prof. Kim</div></td>
                  <td><span class="tag tag-red">Midterm</span></td>
                  <td style="font-weight:600;color:var(--amber);">Mar 22</td>
                  <td>02:00 PM</td>
                  <td>Room 204</td>
                  <td style="font-size:12px;color:var(--muted);">Electromagnetism, Waves</td>
                  <td><div class="prog-val">55%</div><div class="prog-bar"><div class="prog-bar-fill amber" style="width:55%"></div></div></td>
                </tr>
                <tr>
                  <td style="color:var(--muted-2);font-size:12px;">03</td>
                  <td><strong>Statistics</strong><div style="font-size:11px;color:var(--muted);">Prof. Wong</div></td>
                  <td><span class="tag tag-amber">Quiz</span></td>
                  <td style="font-weight:600;">Mar 25</td>
                  <td>10:30 AM</td>
                  <td>Room 109</td>
                  <td style="font-size:12px;color:var(--muted);">Probability Distributions</td>
                  <td><div class="prog-val">38%</div><div class="prog-bar"><div class="prog-bar-fill red" style="width:38%"></div></div></td>
                </tr>
                <tr>
                  <td style="color:var(--muted-2);font-size:12px;">04</td>
                  <td><strong>Data Structures</strong><div style="font-size:11px;color:var(--muted);">Prof. Chen</div></td>
                  <td><span class="tag tag-blue">Final</span></td>
                  <td style="font-weight:600;">Apr 10</td>
                  <td>09:00 AM</td>
                  <td>Hall B · R03</td>
                  <td style="font-size:12px;color:var(--muted);">Full semester</td>
                  <td><div class="prog-val">20%</div><div class="prog-bar"><div class="prog-bar-fill red" style="width:20%"></div></div></td>
                </tr>
                <tr>
                  <td style="color:var(--muted-2);font-size:12px;">05</td>
                  <td><strong>English Lit</strong><div style="font-size:11px;color:var(--muted);">Prof. Adeyemi</div></td>
                  <td><span class="tag tag-blue">Final</span></td>
                  <td style="font-weight:600;">Apr 14</td>
                  <td>01:00 PM</td>
                  <td>Room 301</td>
                  <td style="font-size:12px;color:var(--muted);">Full semester</td>
                  <td><div class="prog-val">85%</div><div class="prog-bar"><div class="prog-bar-fill green" style="width:85%"></div></div></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- GRID BOTTOM -->
        <div class="grid-bottom">
          <div class="panel">
            <div class="panel-head">
              <div><div class="panel-title">Announcements</div><div class="panel-sub">Recent updates</div></div>
              <span class="tag tag-red">3 new</span>
            </div>
            <div class="announce-list">
              <div class="announce-item">
                <div class="announce-top"><div class="announce-title">Calculus Midterm Rescheduled</div><span class="tag tag-amber">Urgent</span></div>
                <div class="announce-body">Moved to March 20. Extra review session added Friday at 3 PM.</div>
                <div class="announce-time"><i class="fa-regular fa-clock"></i> 2 hours ago</div>
              </div>
              <div class="announce-item">
                <div class="announce-top"><div class="announce-title">Library Hours Extended</div><span class="tag tag-blue">Info</span></div>
                <div class="announce-body">Open until midnight during finals week starting March 25.</div>
                <div class="announce-time"><i class="fa-regular fa-clock"></i> Yesterday</div>
              </div>
              <div class="announce-item">
                <div class="announce-top"><div class="announce-title">Scholarship Applications Open</div><span class="tag tag-green">Opportunity</span></div>
                <div class="announce-body">Merit-based scholarships for Q2 now open. Deadline April 1.</div>
                <div class="announce-time"><i class="fa-regular fa-clock"></i> 2 days ago</div>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-head">
              <div><div class="panel-title">Grade Trend</div><div class="panel-sub">Last 6 months</div></div>
              <span class="tag tag-green">↑ Improving</span>
            </div>
            <div class="panel-body">
              <div class="chart-wrap"><canvas id="gradeChart"></canvas></div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-head">
              <div><div class="panel-title">Skills Progress</div><div class="panel-sub">Competency tracker</div></div>
            </div>
            <div class="skills-grid">
              <div class="skill-item">
                <div class="ring-wrap"><svg width="44" height="44" viewBox="0 0 44 44"><circle class="ring-bg" cx="22" cy="22" r="18" stroke-width="4"/><circle class="ring-fill" cx="22" cy="22" r="18" stroke-width="4" stroke="#3d7fff" stroke-dasharray="113.1" stroke-dashoffset="23"/></svg><div class="ring-label" style="color:#7daeff;">80%</div></div>
                <div class="skill-text"><div class="s-name">Problem Solving</div><div class="s-val">Advanced</div></div>
              </div>
              <div class="skill-item">
                <div class="ring-wrap"><svg width="44" height="44" viewBox="0 0 44 44"><circle class="ring-bg" cx="22" cy="22" r="18" stroke-width="4"/><circle class="ring-fill" cx="22" cy="22" r="18" stroke-width="4" stroke="#f59e0b" stroke-dasharray="113.1" stroke-dashoffset="40"/></svg><div class="ring-label" style="color:#fbbf5a;">65%</div></div>
                <div class="skill-text"><div class="s-name">Research</div><div class="s-val">Intermediate</div></div>
              </div>
              <div class="skill-item">
                <div class="ring-wrap"><svg width="44" height="44" viewBox="0 0 44 44"><circle class="ring-bg" cx="22" cy="22" r="18" stroke-width="4"/><circle class="ring-fill" cx="22" cy="22" r="18" stroke-width="4" stroke="#10b981" stroke-dasharray="113.1" stroke-dashoffset="13"/></svg><div class="ring-label" style="color:#5dddb0;">88%</div></div>
                <div class="skill-text"><div class="s-name">Critical Thinking</div><div class="s-val">Expert</div></div>
              </div>
              <div class="skill-item">
                <div class="ring-wrap"><svg width="44" height="44" viewBox="0 0 44 44"><circle class="ring-bg" cx="22" cy="22" r="18" stroke-width="4"/><circle class="ring-fill" cx="22" cy="22" r="18" stroke-width="4" stroke="#c084fc" stroke-dasharray="113.1" stroke-dashoffset="57"/></svg><div class="ring-label" style="color:#c084fc;">50%</div></div>
                <div class="skill-text"><div class="s-name">Presentation</div><div class="s-val">Growing</div></div>
              </div>
            </div>
            <div style="border-top:1px solid var(--border);margin-top:4px;">
              <div class="panel-head" style="border-bottom:none;padding-bottom:8px;"><div class="panel-title" style="font-size:13px;">Recent Fees</div></div>
              <div class="pay-list">
                <div class="pay-item"><div><div class="pay-name">Tuition Q1</div><div class="pay-ref">#TXN-00821</div></div><span class="tag tag-green">Paid</span><div class="pay-amount">$1,200</div></div>
                <div class="pay-item"><div><div class="pay-name">Lab Materials</div><div class="pay-ref">#TXN-00904</div></div><span class="tag tag-amber">Due</span><div class="pay-amount">$85</div></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    function openSidebar()  { document.getElementById('sidebar').classList.add('open');    document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

    new Chart(document.getElementById('gradeChart').getContext('2d'), {
      type: 'line',
      data: {
        labels: ['Oct','Nov','Dec','Jan','Feb','Mar'],
        datasets: [{ label: 'GPA', data: [3.5,3.6,3.55,3.7,3.75,3.87], borderColor: '#3d7fff', backgroundColor: 'rgba(61,127,255,0.08)', borderWidth: 2, pointBackgroundColor: '#3d7fff', pointRadius: 4, pointHoverRadius: 6, tension: 0.4, fill: true }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#6b7fa3', font: { size: 11 } }, border: { display: false } },
          y: { min: 3.0, max: 4.0, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#6b7fa3', font: { size: 11 }, stepSize: 0.25 }, border: { display: false } }
        }
      }
    });
  </script>
</body>
</html>