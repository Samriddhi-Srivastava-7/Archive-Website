<?php

require_once "../includes/auth-check.php";
require_once "../config/db.php";

$sql = "SELECT * FROM certificates ORDER BY created_at DESC";
$query = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Certificates</title>

    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="../assets/js/manage-documents.js" defer></script>
</head>

<body>

<div class="admin-layout">

    <aside class="sidebar" id="sidebar">

        <div class="brand">
            <div class="brand-icon">CA</div>
            <div class="brand-text">
                <h2>Certificate Archive</h2>
                <p>Admin Panel</p>
            </div>
        </div>

        <button class="sidebar-toggle" id="toggleSidebar">☰</button>

        <nav class="sidebar-nav">

            <a href="dashboard.php" class="nav-link">
                <span class="nav-icon">▣</span>
                <span class="nav-text">Dashboard</span>
            </a>

            <a href="upload-document.php" class="nav-link">
                <span class="nav-icon">↑</span>
                <span class="nav-text">Upload Certificate</span>
            </a>

            <a href="manage-documents.php" class="nav-link active">
                <span class="nav-icon">☷</span>
                <span class="nav-text">Manage Certificates</span>
            </a>

            <div class="nav-section-title">Account</div>

            <a href="logout.php" class="nav-link logout-link">
                <span class="nav-icon">↳</span>
                <span class="nav-text">Logout</span>
            </a>

        </nav>

    </aside>

    <main class="admin-main" id="main">

        <section class="dashboard-heading">
            <p>Certificate Records</p>
            <h1>Manage Certificates</h1>
        </section>

        <section class="data-panel">

            <div class="data-panel-header">
                <div>
                    <h2>All Certificates</h2>
                    <p>Search, edit, publish, revoke and manage certificate records.</p>
                </div>

                <div class="table-tools">
                    <input type="text" id="searchInput" placeholder="Search certificate...">
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

                    <tbody id="certificateTable">

                        <?php while ($row = mysqli_fetch_assoc($query)): ?>

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