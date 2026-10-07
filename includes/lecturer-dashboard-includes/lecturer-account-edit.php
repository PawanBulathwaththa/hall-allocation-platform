<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<!-- BEGIN CONTENT AREA -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <div class="row layout-spacing ">
                <!-- Content -->
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto layout-top-spacing">
                    <div class="user-profile">
                        <div class="widget-content widget-content-area">
                            <div class="d-flex justify-content-between">
                                <h3 class="">Change Profile Settings</h3>
                            </div>
                            <div class="text-center user-info mb-3">
                                <img src="src/assets/img/profile-30.png" alt="avatar">
                            </div>
                            <div class="user-info-list">
                                <label for="input1">User name</label>
                                <div class="form-group mb-3">
                                    <input type="text" id="input1" class="form-control" placeholder="<?php echo htmlspecialchars($_SESSION['lecturer_name']); ?>">
                                </div>
                                <label for="input2">Email</label>
                                <div class="form-group">
                                    <input type="text" id="input2" class="form-control" placeholder="<?php echo htmlspecialchars($_SESSION['lecturer_email']); ?>">
                                </div>
                                <button class="btn btn-secondary mt-4 me-4" onclick="showPasswordModal()">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Prompt Modal (Hidden by Default) -->
<div id="passwordModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
    <div style="background:#fff; margin:auto; padding:20px; max-width:400px; position:relative;">
        <span style="position:absolute; top:10px; right:15px; cursor:pointer;" onclick="closeModal()">&times;</span>
        <h4>Enter Password to Confirm Changes</h4>
        <input type="password" id="passwordInput" class="form-control" placeholder="Enter your password">
        <button class="btn btn-primary mt-3" onclick="confirmPassword()">Confirm</button>
    </div>
</div>

<script>
    function showPasswordModal() {
        document.getElementById('passwordModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('passwordModal').style.display = 'none';
    }

    function confirmPassword() {
        const password = document.getElementById('passwordInput').value;
        const name = document.getElementById('input1').value;
        const email = document.getElementById('input2').value;

        fetch('update_lecturer.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ password, name, email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Details updated successfully');
                closeModal();
            } else {
                alert(data.message || 'Password incorrect');
            }
        });
    }
</script>



<?php
session_start();
include 'connection.php';

$data = json_decode(file_get_contents("php://input"));
$password = $data->password;
$newName = $data->name;
$newEmail = $data->email;
$lecturer_id = $_SESSION['lecturer_id'];

// Verify the password
$query = $conn->prepare("SELECT password FROM lecturer WHERE id = ?");
$query->bind_param("i", $lecturer_id);
$query->execute();
$result = $query->get_result();
$lecturer = $result->fetch_assoc();

if ($password === $lecturer['password']) {  // Assuming password is stored in plain text as per your setup
    $update = $conn->prepare("UPDATE lecturer SET lecturer_name = ?, email = ? WHERE id = ?");
    $update->bind_param("ssi", $newName, $newEmail, $lecturer_id);
    
    if ($update->execute()) {
        $_SESSION['lecturer_name'] = $newName;
        $_SESSION['lecturer_email'] = $newEmail;
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Update failed"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Incorrect password"]);
}
?>
