<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>


<?php
// Assuming you have already established a connection to your database
include 'connection.php';

// Check if a message needs to be marked as read
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Update the message to mark it as read
    $query = "UPDATE lecturer_req SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $stmt->close();
    exit; // Stop script execution after handling AJAX request
}

// Fetch messages from lecturer_req table
$query = "SELECT * FROM lecturer_req ORDER BY req_date DESC";
$result = mysqli_query($conn, $query);
?>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="middle-content p-0">
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="mail-box-container">
                                <div class="mail-overlay"></div>
                                <div class="tab-title">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-12 text-center mail-btn-container">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-12 mail-categories-container">
                                            <div class="mail-sidebar-scroll ps ps--active-y">
                                                <ul class="nav nav-pills d-block" id="pills-tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link list-actions active" id="mailInbox">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox">
                                                                <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                                                <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                                                            </svg> 
                                                            <span class="nav-names">Inbox</span> 
                                                            <span class="mail-badge badge"><?php echo mysqli_num_rows($result); ?></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="mailbox-inbox" class="accordion mailbox-inbox">
                                    <div class="message-box">
                                        <div class="message-box-scroll ps ps--active-y" id="ct">
                                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                <div class="mail-item mailInbox <?php echo $row['is_read'] ? 'read' : 'unread'; ?>" data-id="<?php echo $row['id']; ?>" data-message="<?php echo htmlspecialchars($row['message'], ENT_QUOTES); ?>">
                                                    <div class="animated animatedFadeInUp fadeInUp">
                                                        <div class="mb-0">
                                                            <div class="mail-item-heading social" role="navigation">
                                                                <div class="mail-item-inner">
                                                                    <div class="d-flex">
                                                                        <div class="f-head">
                                                                            <img src="src/assets/img2/9383583.png" class="user-profile" alt="avatar">
                                                                        </div>
                                                                        <div class="f-body">
                                                                            <div class="meta-mail-time">
                                                                                <p class="user-email" data-mailto="<?php echo $row['lecturer_email']; ?>"><?php echo $row['lecturer_name']; ?></p>
                                                                            </div>
                                                                            <div class="meta-title-tag">
                                                                                <p class="mail-content-excerpt">
                                                                                    <span class="mail-title"><?php echo $row['lecturer_name']; ?></span> has sent a message
                                                                                </p>
                                                                                <p class="meta-time align-self-center"><?php echo date('Y/m/d h:i A', strtotime($row['req_date'] . ' ' . $row['time'])); ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for displaying message -->
                                <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="messageModalLabel">Message</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="messageContent">
                                                <!-- Message content will be loaded here -->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

    <script>
        // jQuery to handle the modal and mark message as read
        $(document).ready(function() {
            $('.mail-item').click(function() {
                var messageId = $(this).data('id');
                var messageContent = $(this).data('message');

                // Display the message in the modal
                $('#messageContent').text(messageContent);
                $('#messageModal').modal('show');

                // Check if the message is unread before marking it
                if ($(this).hasClass('unread')) {
                    // Mark message as read
                    $(this).removeClass('unread');
                    $.ajax({
                        url: '', // Current file will handle marking as read
                        method: 'POST',
                        data: { id: messageId },
                        success: function(response) {
                            console.log('Message marked as read');
                        }
                    });
                }
            });
        });
    </script>
</div>
