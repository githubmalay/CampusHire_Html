<?php
session_start();
include('db_connect.php');

// Ensure the user is logged in and has the role of 'student'
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch existing profile data
$sql = "SELECT * FROM profiles WHERE user_id = '$user_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    $row = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | CampusHire</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="styles1.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f8;
        }
      
        .container {
            max-width: 1400px;
            margin-top: 30px;
        }

        /* Profile Sidebar and Header */
        .profile-sidebar {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }
        .profile-sidebar .profile-img {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .profile-sidebar .profile-info {
            text-align: center;
            margin-top: 2px;
        }
        .profile-sidebar .profile-info h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .profile-sidebar .profile-info p {
            font-size: 1.1rem;
            color:#0066cc;
            margin-bottom: 20px;
        }
        .profile-sidebar .edit-btn {
            background-color: #fff;
            color: #0066cc;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease;
            border: 2px solid #0066cc;
        }
        .profile-sidebar .edit-btn:hover {
            background-color: #0077b5;
            color: #fff;
        }

        /* List Group Styles */
        .list-group {
            width: 100%;
            padding: 0;
        }
        .list-group-item {
            border: none;
            background-color: transparent;
            padding: 15px;
            font-size: 1rem;
            color: #333;
            transition: background-color 0.3s ease;
        }
       
        .section-content a {
            text-decoration: none;
            color: #0077b5;
            font-weight: 600;
        }
        .section-content span {
            color: #555;
        }

        /* Main Content */
        .profile-content {
            flex: 3;
            margin-left: 30px;
        }
        .profile-section {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .profile-section .section-title {
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #0066cc;

        }
        .profile-section .section-content {
            font-size: 1rem;
            color: #555;
        }
         /* Apply Modal */
         .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-body {
            padding: 20px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.6);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light px-3">
        <a class="navbar-brand text-light" href="index.php">CampusHire</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user text-light"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                </a>
                <div class="dropdown-menu custom-dropdown" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="profile_view.php"><i class="fas fa-user"></i> Profile</a>
                    <a class="dropdown-item" href="resume_builder.php"><i class="fas fa-file-alt"></i> Resume Builder</a>
                    <a class="dropdown-item" href="student_application_history.php"><i class="fas fa-history"></i> Application history </a>
                </div>
            </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="logout.php">Logout <i class="fa-solid fa-sign-out-alt"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container d-flex">
        <!-- Profile Sidebar and Info -->
        <div class="profile-sidebar" style="flex: 1; max-width: 300px;">
            <!-- Profile Image -->
            <img src="<?= $row && $row['profile_image'] ? 'uploads/' . htmlspecialchars($row['profile_image']) : 'default-profile.png' ?>" alt="Profile Image" class="profile-img">
            <!-- Profile Info -->
            <div class="profile-info">
                <h2><?= htmlspecialchars($row['name']) ?></h2>
                <p>A Student</p>
            </div>
            <!-- Quick Links -->
            <ul class="list-group">
                <!-- <li class="list-group-item"><a href="edit_profile.php" class="edit-btn">Edit Profile</a></li> -->
                <li class="list-group-item"><a href="#" class="edit-btn w-100 text-center" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</a>
                </li>
                <li class="list-group-item">
                    <div class="section-content">
                        <?php if ($row['resume']): ?>
                            <a href="uploads/<?= htmlspecialchars($row['resume']) ?>" target="_blank" class="edit-btn  w-100 text-center">Download Resume</a>
                        <?php else: ?>
                            <span>No resume uploaded.</span>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Main Profile Content -->
        <div class="profile-content" style="flex: 3;">
            <!-- About Section -->
            <div class="profile-section">
                <div class="section-title">About</div>
                <div class="section-content"><?= htmlspecialchars($row['bio']) ? htmlspecialchars($row['bio']) : 'No bio provided.' ?></div>
            </div>

            <!-- Experience Section -->
            <div class="profile-section">
                <div class="section-title">Experience</div>
                <div class="section-content"><?= htmlspecialchars($row['experience']) ? htmlspecialchars($row['experience']) : 'No experience listed.' ?></div>
            </div>

            <!-- Skills Section -->
            <div class="profile-section">
                <div class="section-title">Skills</div>
                <div class="section-content"><?= htmlspecialchars($row['skills']) ? htmlspecialchars($row['skills']) : 'No skills listed.' ?></div>
            </div>

            <!-- Certifications Section -->
            <div class="profile-section">
                <div class="section-title">Certifications</div>
                <div class="section-content"><?= htmlspecialchars($row['certifications']) ? htmlspecialchars($row['certifications']) : 'No certifications listed.' ?></div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Edit Your Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                <form method="POST" action="profile_form.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                        <label>Profile Image</label>
                        <input type="file" class="form-control" name="profile_image">
                    </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                        <label>Upload Resume</label>
                        <input type="file" class="form-control" name="resume" accept=".pdf">
                    </div>
                        </div>
                    </div>
                   
                   
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($row['name'] ?? '') ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                        <label>Summary</label>
                        <textarea class="form-control" name="bio"><?= htmlspecialchars($row['bio'] ?? '') ?></textarea>
                    </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                        <label>Skills</label>
                        <textarea class="form-control" name="skills"><?= htmlspecialchars($row['skills'] ?? '') ?></textarea>
                    </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Experience</label>
                        <textarea class="form-control" name="experience"><?= htmlspecialchars($row['experience'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Certifications</label>
                        <textarea class="form-control" name="certifications"><?= htmlspecialchars($row['certifications'] ?? '') ?></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary w-25">Save Profile</button>
</div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
