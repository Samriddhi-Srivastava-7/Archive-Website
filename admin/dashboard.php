<?php
require_once "../includes/auth-check.php";
require_once "../config/db.php";

$totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM certificates");
$totalCertificates = mysqli_fetch_assoc($totalQuery)["total"];

$publishedQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM certificates WHERE status='Published'");
$publishedCertificates = mysqli_fetch_assoc($publishedQuery)["total"];

$draftQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM certificates WHERE status='Draft'");
$draftCertificates = mysqli_fetch_assoc($draftQuery)["total"];

$revokedQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM certificates WHERE status='Revoked'");
$revokedCertificates = mysqli_fetch_assoc($revokedQuery)["total"];

$recentQuery = mysqli_query($conn, "SELECT * FROM certificates ORDER BY created_at DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Archive</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="../assets/js/admin-dashboard.js" defer></script>
</head>

<body>

<div class="admin-layout">

    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <img src="../assets/images/logo-square.png" alt="Rigel Foundation Logo" class="admin-mini-logo">
            </div>
            <div class="brand-text">
                <h2>Archive</h2>
                <p>Rigel Foundation</p>
            </div>
        </div>

        <button class="sidebar-toggle" id="toggleSidebar">☰</button>

        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-link active">
                <span class="nav-icon">▣</span>
                <span class="nav-text">Dashboard</span>
            </a>

            <a href="upload-document.php" class="nav-link">
                <span class="nav-icon">↑</span>
                <span class="nav-text">Upload Certificate</span>
            </a>

            <a href="manage-documents.php" class="nav-link">
                <span class="nav-icon">☷</span>
                <span class="nav-text">Manage Certificates</span>
            </a>

            <a href="#" class="nav-link">
                <span class="nav-icon">◻</span>
                <span class="nav-text">Draft Certificates</span>
            </a>

            <a href="#" class="nav-link">
                <span class="nav-icon">✓</span>
                <span class="nav-text">Published Certificates</span>
            </a>

            <a href="#" class="nav-link">
                <span class="nav-icon">×</span>
                <span class="nav-text">Revoked Certificates</span>
            </a>

            <div class="nav-section-title">System</div>

            <a href="#" class="nav-link">
                <span class="nav-icon">●</span>
                <span class="nav-text">Notifications</span>
            </a>

            <a href="#" class="nav-link">
                <span class="nav-icon">👥</span>
                <span class="nav-text">Interns</span>
            </a>

            <a href="#" class="nav-link">
                <span class="nav-icon">⚙</span>
                <span class="nav-text">Settings</span>
            </a>

            <a href="#" class="nav-link">
                <span class="nav-icon">≡</span>
                <span class="nav-text">Activity Logs</span>
            </a>

            <div class="nav-section-title">Account</div>

            <a href="logout.php" class="nav-link logout-link">
                <span class="nav-icon">↳</span>
                <span class="nav-text">Logout</span>
            </a>
        </nav>

        <div class="admin-mini-card">
            <div class="admin-avatar">A</div>
            <div>
                <p>Logged in as</p>
                <strong><?php echo $_SESSION["admin_username"]; ?></strong>
                <span>Online</span>
            </div>
        </div>
    </aside>

    <main class="admin-main" id="main">

        <section class="dashboard-heading">
            <p>Admin Overview</p>
            <h1>Dashboard</h1>
        </section>

        <section class="stats-grid">
            <div class="stat-card">
                <span class="stat-dot blue"></span>
                <h3>Total Certificates</h3>
                <p><?php echo $totalCertificates; ?></p>
            </div>

            <div class="stat-card">
                <span class="stat-dot green"></span>
                <h3>Published Certificates</h3>
                <p><?php echo $publishedCertificates; ?></p>
            </div>

            <div class="stat-card">
                <span class="stat-dot orange"></span>
                <h3>Draft Certificates</h3>
                <p><?php echo $draftCertificates; ?></p>
            </div>

            <div class="stat-card">
                <span class="stat-dot red"></span>
                <h3>Revoked Certificates</h3>
                <p><?php echo $revokedCertificates; ?></p>
            </div>
        </section>

        <section class="data-panel">
            <div class="data-panel-header">
                <div>
                    <h2>Recent Certificates</h2>
                    <p>Review, edit, publish and manage records.</p>
                </div>

                <div class="table-tools">
                    <input type="text" id="dashboardSearch" placeholder="Search by ID, name, course">
                    <a href="upload-document.php" class="primary-btn">Upload</a>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Certificate ID</th>
                            <th>Student Name</th>
                            <th>Type</th>
                            <th>Course</th>
                            <th>Issue Date</th>
                            <th>Status</th>
                            <th>PDF</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="dashboardTable">
                        <?php while ($row = mysqli_fetch_assoc($recentQuery)): ?>
                            <tr>
                                <td class="cert-id"><?php echo $row["certificate_uid"]; ?></td>
                                <td><?php echo $row["student_name"]; ?></td>
                                <td><?php echo $row["certificate_type"]; ?></td>
                                <td><?php echo $row["course"]; ?></td>
                                <td><?php echo $row["issue_date"]; ?></td>

                                <td>
                                    <span class="status-badge status-<?php echo strtolower($row["status"]); ?>">
                                        <?php echo $row["status"]; ?>
                                    </span>
                                </td>

                                <td>
                                    <a class="pdf-link" href="../<?php echo $row["file_path"]; ?>" target="_blank">
                                        View PDF
                                    </a>
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a href="edit-certificate.php?id=<?php echo $row['id']; ?>" class="action-btn edit">
                                            Edit
                                        </a>

                                        <?php if ($row["status"] !== "Published"): ?>
                                            <a href="publish-certificate.php?id=<?php echo $row['id']; ?>" class="action-btn publish">
                                                Publish
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($row["status"] !== "Revoked"): ?>
                                            <a href="revoke-certificate.php?id=<?php echo $row['id']; ?>" class="action-btn revoke">
                                                Revoke
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</div>

</body>
</html>