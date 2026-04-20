<?php
    require 'admin_auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard – Digitract</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <!-- SB Admin 2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css">
    <style>
        :root {
            --navy: #0b1437;
            --blue: #1a3a8f;
            --accent: #2f80ed;
            --gold: #f5a623;
        }
        body { font-family: 'DM Sans', sans-serif; background: #f4f6fc; }

        /* Sidebar overrides */
        .sidebar {
            background: linear-gradient(180deg, #0b1437 0%, #1a3a8f 100%) !important;
        }
        .sidebar .nav-item .nav-link { color: rgba(255,255,255,.65) !important; font-family: 'DM Sans', sans-serif; }
        .sidebar .nav-item .nav-link:hover,
        .sidebar .nav-item.active .nav-link { color: #fff !important; background: rgba(47,128,237,.25) !important; border-radius: 8px; }
        .sidebar-brand { background: rgba(0,0,0,.15) !important; }
        .sidebar-brand-text {
            font-family: 'Syne', sans-serif !important; font-weight: 800 !important;
            font-size: 20px !important;
        }
        .sidebar-brand-text span { color: var(--gold); }
        .sidebar-divider { border-top: 1px solid rgba(255,255,255,.1) !important; }
        .sidebar-heading { color: rgba(255,255,255,.4) !important; font-size: 10px !important; letter-spacing: 1.5px; }

        /* Topbar */
        .topbar { box-shadow: 0 2px 12px rgba(0,0,0,.07); background: #fff !important; }
        .topbar-divider { border-right: 1px solid #e5e7eb !important; }

        /* Cards */
        .card { border: none !important; border-radius: 14px !important; box-shadow: 0 2px 16px rgba(0,0,0,.07) !important; }
        .card-header { border-radius: 14px 14px 0 0 !important; border-bottom: 1px solid rgba(0,0,0,.06) !important; }

        .stat-card { border-radius: 14px; padding: 24px; color: #fff; position: relative; overflow: hidden; }
        .stat-card .icon {
            position: absolute; right: 20px; top: 50%; transform: translateY(-50%);
            font-size: 52px; opacity: .15;
        }
        .stat-card h2 { font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 800; margin-bottom: 4px; }
        .stat-card p { font-size: 13px; opacity: .8; margin: 0; }
        .stat-card .trend { font-size: 12px; margin-top: 8px; }
        .stat-card .trend.up { color: #a7f3d0; }
        .stat-card .trend.down { color: #fca5a5; }

        .bg-grad-blue   { background: linear-gradient(135deg, #2f80ed, #1a6fd8); }
        .bg-grad-green  { background: linear-gradient(135deg, #10b981, #059669); }
        .bg-grad-orange { background: linear-gradient(135deg, #f5a623, #e08e10); }
        .bg-grad-red    { background: linear-gradient(135deg, #ef4444, #dc2626); }

        /* Table */
        .table thead th { font-family: 'Syne', sans-serif; font-size: 12px; letter-spacing: .8px; color: #718096; border-bottom: 2px solid #f0f0f0; background: #fafafa; }
        .table td { vertical-align: middle; font-size: 14px; }
        .badge-status { padding: 5px 12px; border-radius: 100px; font-size: 12px; font-weight: 600; }
        .badge-active   { background: #d1fae5; color: #065f46; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }

        /* Progress */
        .progress { border-radius: 100px; height: 8px; background: #f0f4ff; }
        .progress-bar { border-radius: 100px; }

        /* Page heading */
        .page-heading h1 {
            font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; color: #0b1437;
        }
        .page-heading p { color: #718096; font-size: 14px; }

        /* Chart placeholder */
        .chart-area-placeholder {
            width: 100%; height: 260px;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0ff 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #93c5fd; font-size: 14px; gap: 8px;
        }

        /* Avatar */
        .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; color: #fff; flex-shrink: 0;
        }
    </style>
</head>
<body id="page-top">

<!-- PAGE WRAPPER -->
<div id="wrapper">

    <!-- ═══ SIDEBAR ═══ -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
            <div class="sidebar-brand-icon">🚗</div>
            <div class="sidebar-brand-text mx-3">Digi<span>tract</span></div>
        </a>
        <hr class="sidebar-divider my-0">

        <!-- Nav items -->
        <li class="nav-item active">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Management</div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSchedule">
                <i class="fas fa-fw fa-calendar-days"></i><span>Schedules</span>
            </a>
            <div id="collapseSchedule" class="collapse" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="schedules.php">View All Schedules</a>
                    <a class="collapse-item" href="workload.php">Workload Distribution</a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="instructors.php">
                <i class="fas fa-fw fa-chalkboard-user"></i>
                <span>Instructors</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="students.php">
                <i class="fas fa-fw fa-user-graduate"></i>
                <span>Students</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Monitoring</div>

        <li class="nav-item">
            <a class="nav-link" href="compliance.php">
                <i class="fas fa-fw fa-certificate"></i>
                <span>Compliance</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="performance.php">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Performance</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="feedback.php">
                <i class="fas fa-fw fa-comments"></i>
                <span>Feedback</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Reports</div>

        <li class="nav-item">
            <a class="nav-link" href="reports.php">
                <i class="fas fa-fw fa-file-chart-column"></i>
                <span>Generate Reports</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <!-- ═══ END SIDEBAR ═══ -->

    <!-- CONTENT WRAPPER -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- ═══ TOPBAR ═══ -->
            <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search instructors, students…" style="border-radius:8px 0 0 8px;">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" style="border-radius:0 8px 8px 0;">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown">
                            <i class="fas fa-bell fa-fw"></i>
                            <span class="badge badge-danger badge-counter">3</span>
                        </a>
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in">
                            <h6 class="dropdown-header">Notifications</h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3"><div class="icon-circle bg-warning"><i class="fas fa-certificate text-white"></i></div></div>
                                <div><div class="small text-gray-500">Today</div>2 instructor licenses expiring soon</div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3"><div class="icon-circle bg-success"><i class="fas fa-calendar text-white"></i></div></div>
                                <div><div class="small text-gray-500">Today</div>5 new bookings this morning</div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                        </div>
                    </li>
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= htmlspecialchars($admin_name) ?></span>
                            <div class="avatar" style="background: linear-gradient(135deg,#2f80ed,#1a6fd8);">
                                <?= strtoupper(substr($admin_name,0,1)) ?>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                            <a class="dropdown-item" href="profile.php"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile</a>
                            <a class="dropdown-item" href="settings.php"><i class="fas fa-cog fa-sm fa-fw mr-2 text-gray-400"></i> Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- ═══ END TOPBAR ═══ -->

            <!-- MAIN CONTENT -->
            <div class="container-fluid">

                <!-- Page heading -->
                <div class="page-heading mb-4">
                    <h1>Admin Dashboard</h1>
                    <p>Welcome back, <?= htmlspecialchars($admin_name) ?> &nbsp;·&nbsp; <?= date('l, F j, Y') ?></p>
                </div>

                <!-- ── STAT CARDS ── -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card bg-grad-blue">
                            <p>Total Instructors</p>
                            <h2>24</h2>
                            <div class="trend up"><i class="fas fa-arrow-up"></i> +2 this month</div>
                            <div class="icon"><i class="fas fa-chalkboard-user"></i></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card bg-grad-green">
                            <p>Active Students</p>
                            <h2>187</h2>
                            <div class="trend up"><i class="fas fa-arrow-up"></i> +14 this week</div>
                            <div class="icon"><i class="fas fa-user-graduate"></i></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card bg-grad-orange">
                            <p>Lessons This Week</p>
                            <h2>312</h2>
                            <div class="trend up"><i class="fas fa-arrow-up"></i> +8% vs last week</div>
                            <div class="icon"><i class="fas fa-calendar-check"></i></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card bg-grad-red">
                            <p>Compliance Issues</p>
                            <h2>3</h2>
                            <div class="trend down"><i class="fas fa-exclamation-triangle"></i> Needs attention</div>
                            <div class="icon"><i class="fas fa-certificate"></i></div>
                        </div>
                    </div>
                </div>

                <!-- ── CHARTS ROW ── -->
                <div class="row mb-4">
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold" style="font-family:'Syne',sans-serif; color:#0b1437;">
                                    <i class="fas fa-chart-line text-primary mr-2"></i>Weekly Lesson Overview
                                </h6>
                                <small class="text-muted">Last 7 days</small>
                            </div>
                            <div class="card-body">
                                <div class="chart-area-placeholder">
                                    <i class="fas fa-chart-area fa-2x"></i>
                                    <span>Chart renders here (Chart.js integration)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="font-family:'Syne',sans-serif; color:#0b1437;">
                                    <i class="fas fa-chart-pie text-warning mr-2"></i>Instructor Status
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area-placeholder" style="height:200px;">
                                    <i class="fas fa-chart-pie fa-2x"></i>
                                    <span>Pie chart here</span>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-1"><span class="small">Active</span><strong>18</strong></div>
                                    <div class="progress mb-2"><div class="progress-bar bg-success" style="width:75%"></div></div>
                                    <div class="d-flex justify-content-between mb-1"><span class="small">On Leave</span><strong>4</strong></div>
                                    <div class="progress mb-2"><div class="progress-bar bg-warning" style="width:17%"></div></div>
                                    <div class="d-flex justify-content-between mb-1"><span class="small">Inactive</span><strong>2</strong></div>
                                    <div class="progress"><div class="progress-bar bg-danger" style="width:8%"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── INSTRUCTOR TABLE ── -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold" style="font-family:'Syne',sans-serif; color:#0b1437;">
                                    <i class="fas fa-chalkboard-user text-primary mr-2"></i>Instructor Overview
                                </h6>
                                <a href="instructors.php" class="btn btn-sm btn-primary" style="border-radius:8px;">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>INSTRUCTOR</th>
                                                <th>SPECIALIZATION</th>
                                                <th>STUDENTS</th>
                                                <th>LESSONS THIS WEEK</th>
                                                <th>LICENSE STATUS</th>
                                                <th>PERFORMANCE</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $instructors = [
                                                ['name'=>'Juan dela Cruz','spec'=>'Manual & Automatic','students'=>12,'lessons'=>18,'status'=>'active','score'=>92],
                                                ['name'=>'Maria Santos','spec'=>'Defensive Driving','students'=>9,'lessons'=>14,'status'=>'active','score'=>87],
                                                ['name'=>'Roberto Reyes','spec'=>'Manual Transmission','students'=>7,'lessons'=>10,'status'=>'pending','score'=>74],
                                                ['name'=>'Ana Villanueva','spec'=>'Automatic Transmission','students'=>11,'lessons'=>16,'status'=>'active','score'=>95],
                                                ['name'=>'Carlos Mendoza','spec'=>'Highway Driving','students'=>5,'lessons'=>6,'status'=>'inactive','score'=>61],
                                            ];
                                            foreach ($instructors as $i):
                                                $badge = ['active'=>'badge-active','pending'=>'badge-pending','inactive'=>'badge-inactive'][$i['status']];
                                                $barColor = $i['score'] >= 85 ? 'bg-success' : ($i['score'] >= 70 ? 'bg-warning' : 'bg-danger');
                                                $initials = implode('', array_map(fn($w)=>strtoupper($w[0]), explode(' ', $i['name'])));
                                                $colors = ['#2f80ed','#10b981','#f5a623','#8b5cf6','#ef4444'];
                                                $color = $colors[array_search($i, $instructors) % 5];
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar mr-2" style="background:<?= $color ?>;"><?= $initials ?></div>
                                                        <strong><?= $i['name'] ?></strong>
                                                    </div>
                                                </td>
                                                <td class="text-muted"><?= $i['spec'] ?></td>
                                                <td><?= $i['students'] ?></td>
                                                <td><?= $i['lessons'] ?></td>
                                                <td><span class="badge-status <?= $badge ?>"><?= ucfirst($i['status']) ?></span></td>
                                                <td style="min-width:120px;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="progress flex-grow-1" style="width:80px;">
                                                            <div class="progress-bar <?= $barColor ?>" style="width:<?= $i['score'] ?>%"></div>
                                                        </div>
                                                        <small><?= $i['score'] ?>%</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="instructor_view.php?id=1" class="btn btn-sm btn-outline-primary" style="border-radius:6px; font-size:12px;">View</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- END MAIN CONTENT -->
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span style="font-size:13px; color:#9ca3af;">© <?= date('Y') ?> <strong style="color:#0b1437;">Digitract</strong> — Instructor Scheduling & Performance Monitoring System</span>
                </div>
            </div>
        </footer>
    </div>
    <!-- END CONTENT WRAPPER -->
</div>
<!-- END PAGE WRAPPER -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>
</body>
</html>
