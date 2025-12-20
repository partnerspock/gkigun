<?php
// index.php - Cinema App Style Seat Selection
// --- DATABASE CONFIGURATION ---
$host = 'localhost';
$db   = 'korem'; 
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$current_date = date('Y-m-d');
$total_undone_bookings = 0;

$stmt = $pdo->prepare("
    SELECT
        s.id_seat,
        s.seat_name,
        (d.id_day IS NOT NULL) AS is_taken,
        v.name AS visitor_name,
        vt.name_type AS visitor_type_name,
        DATE_FORMAT(d.record_timestamp, '%H:%i:%s') AS booking_time
    FROM seat s
    LEFT JOIN dday d ON s.id_seat = d.id_seat 
        AND DATE(d.record_timestamp) = :current_date
        AND d.isDone = 0
    LEFT JOIN visitor v ON d.id_visitor = v.id_visitor
    LEFT JOIN visitor_type vt ON v.id_type = vt.id_type
    WHERE s.isActive = 1
    ORDER BY s.id_seat
");

$stmt->execute(['current_date' => $current_date]);
$seats = $stmt->fetchAll();

$seat_map = [];
foreach ($seats as $seat) { 
    $seat_map[$seat['id_seat']] = $seat;
    if ($seat['is_taken']) {
        $total_undone_bookings++;
    }
}

$stmt_types = $pdo->query("SELECT id_type, name_type FROM visitor_type");
$visitor_types = $stmt_types->fetchAll();

$row_starts = [1, 17, 33, 49, 65, 81, 97, 113, 129, 145];
$row_labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

// add row I & J seat, the isActive need to change into = 1
//$row_starts = [1, 17, 33, 49, 65, 81, 97, 113, 129, 145];
//$row_labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Korem - Seat Selection</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="app-header">
        <!--<div class="header-left">
            <a href="http://localhost:8002" class="back-btn">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
        </div>-->
        <img src="img/gunsa.png" alt="Gunsa Logo" class="logo_header">
        <div class="header-center">
            <h1 class="header-title">00:00:00 See the Light</h1>
            <span class="header-subtitle"><?php echo date('l, d M Y'); ?></span>
        </div>
        <img src="img/kompa.jpg" alt="Kompa Logo" class="logo_header" style="border-radius: 50%;">
        <!--<div class="header-right">
            <a href="history.php" class="header-icon-btn">
                <span class="material-symbols-outlined">history</span>
            </a>
        </div>-->
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Screen/Stage -->
        <a href="cinema_listings.php" class="screen-link">
            <div class="screen">
                <span>MIMBAR</span>
            </div>
            <div class="screen-glow"></div>
        </a>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-item">
                <div class="legend-box available"></div>
                <span>Available</span>
            </div>
            <div class="legend-item">
                <div class="legend-box selected"></div>
                <span>Selected</span>
            </div>
            <div class="legend-item">
                <div class="legend-box taken"></div>
                <span>Taken</span>
            </div>
        </div>

        <!-- Seats Container - Horizontally Scrollable -->
        <div class="seats-wrapper">
            <div class="seats-container">
                <?php foreach ($row_starts as $index => $start_id): ?>
                <div class="seat-row">
                    <div class="row-label"><?php echo $row_labels[$index]; ?></div>
                    
                    <!-- Left Section -->
                    <div class="seat-section">
                        <?php for ($i = $start_id; $i <= $start_id + 7; $i++): 
                            if (!isset($seat_map[$i])) continue;
                            $seat = $seat_map[$i];
                            $is_taken = $seat['is_taken'];
                            $class = $is_taken ? 'seat-taken' : 'seat-available';
                        ?>
                        <button class="seat-button <?php echo $class; ?>"
                            data-id-seat="<?php echo $seat['id_seat']; ?>"
                            data-is-taken="<?php echo $is_taken; ?>"
                            data-seat-name="<?php echo htmlspecialchars($seat['seat_name']); ?>"
                            data-visitor-name="<?php echo htmlspecialchars($seat['visitor_name'] ?? ''); ?>"
                            data-visitor-type="<?php echo htmlspecialchars($seat['visitor_type_name'] ?? ''); ?>"
                            data-booking-time="<?php echo htmlspecialchars($seat['booking_time'] ?? ''); ?>">
                            <?php echo $i - $start_id + 1; ?>
                        </button>
                        <?php endfor; ?>
                    </div>
                    
                    <div class="aisle"></div>
                    
                    <!-- Right Section -->
                    <div class="seat-section">
                        <?php for ($i = $start_id + 8; $i <= $start_id + 15; $i++): 
                            if (!isset($seat_map[$i])) continue;
                            $seat = $seat_map[$i];
                            $is_taken = $seat['is_taken'];
                            $class = $is_taken ? 'seat-taken' : 'seat-available';
                        ?>
                        <button class="seat-button <?php echo $class; ?>"
                            data-id-seat="<?php echo $seat['id_seat']; ?>"
                            data-is-taken="<?php echo $is_taken; ?>"
                            data-seat-name="<?php echo htmlspecialchars($seat['seat_name']); ?>"
                            data-visitor-name="<?php echo htmlspecialchars($seat['visitor_name'] ?? ''); ?>"
                            data-visitor-type="<?php echo htmlspecialchars($seat['visitor_type_name'] ?? ''); ?>"
                            data-booking-time="<?php echo htmlspecialchars($seat['booking_time'] ?? ''); ?>">
                            <?php echo $i - $start_id + 1; ?>
                        </button>
                        <?php endfor; ?>
                    </div>
                    
                    <div class="row-label"><?php echo $row_labels[$index]; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <!-- Bottom Navbar -->
    <nav class="bottom-navbar">
        <button id="refreshBtn" class="nav-btn" title="Refresh">
            <span class="material-symbols-outlined">refresh</span>
        </button>
        <button id="statsBtn" class="nav-btn" title="Summary">
            <span class="material-symbols-outlined">bar_chart</span>
            <?php if ($total_undone_bookings > 0): ?>
            <span class="nav-badge"><?php echo $total_undone_bookings; ?></span>
            <?php endif; ?>
        </button>
        <button id="bookSelectedBtn" class="nav-btn nav-btn-primary" disabled>
            <span class="material-symbols-outlined">event_seat</span>
            <span class="btn-text">Seat(s) <span id="selected-count">0</span></span>
        </button>
        <button id="clearSelection" class="nav-btn nav-btn-danger" disabled title="Clear">
            <span class="material-symbols-outlined">delete</span>
        </button>
    </nav>

    <!-- Booking Modal -->
    <div id="seatModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title"></h2>
                <button id="closeModalIcon" class="modal-close"><span class="material-symbols-outlined">close</span></button>
            </div>
            
            <form id="bookingForm" autocomplete="off" style="display: none;">
                <div id="selectedSeatsDisplay" class="selected-seats-display"></div>
                
                <div class="form-group">
                    <label for="name">Seat Information (Optional)</label>
                    <input type="text" id="name" name="name" placeholder="e.g. John Doe">
                </div>
                
                <div class="form-group">
                    <div class="radio-group">
                        <?php foreach ($visitor_types as $index => $type): ?>
                        <label class="radio-item">
                            <input type="radio" id="type_<?php echo $type['id_type']; ?>" name="id_type" value="<?php echo $type['id_type']; ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
                            <span class="radio-label"><?php echo htmlspecialchars($type['name_type']); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <span class="material-symbols-outlined">check</span>
                    Confirm Booking
                </button>
            </form>
            
            <div id="deleteConfirmation" style="display: none;">
                <div id="booking-details-display" class="booking-details"></div>
                <p class="confirm-text">Remove this seat?</p>
                <div class="modal-actions">
                    <button id="closeModalIconDelete" class="btn-cancel">Cancel</button>
                    <button id="deleteButton" class="btn-delete">
                        <span class="material-symbols-outlined">delete</span>
                        Remove
                    </button>
                </div>
                <input type="hidden" id="delete_id_seat">
            </div>
            
            <div id="message" class="message"></div>
        </div>
    </div>

    <!-- Finish Day Modal -->
    <div id="finishDayModal" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-icon">
                <span class="material-symbols-outlined">celebration</span>
            </div>
            <h3>Complete Today's Service?</h3>
            <p><?php echo $total_undone_bookings; ?> visitors attended today.</p>
            <div class="modal-actions">
                <button id="cancelFinish" class="btn-cancel">Cancel</button>
                <button id="confirmFinish" class="btn-confirm">
                    <span class="material-symbols-outlined">check</span>
                    Complete
                </button>
            </div>
        </div>
    </div>

<script>
let selectedSeatIDs = [];

function updateSelectedSeatsUI() {
    const count = selectedSeatIDs.length;
    $('#selected-count').text(count);
    $('#bookSelectedBtn').prop('disabled', count === 0);
    $('#clearSelection').prop('disabled', count === 0);
}

$(document).ready(function() {
    // Clear Selection
    $('#clearSelection').on('click', function() {
        $('.seat-button.seat-selected').removeClass('seat-selected');
        selectedSeatIDs = [];
        updateSelectedSeatsUI();
    });

    // Seat Click
    $('.seat-button').on('click', function() {
        const $this = $(this);
        const isTaken = $this.data('is-taken');
        const idSeat = $this.data('id-seat').toString();
        const seatName = $this.data('seat-name');

        $('#message').text('');
        
        if (isTaken == 1) {
            const visitorName = $this.data('visitor-name') || 'Unknown';
            const visitorType = $this.data('visitor-type') || 'N/A';
            const bookingTime = $this.data('booking-time') || 'N/A';
            
            $('#booking-details-display').html(`
                <div class="detail-row"><span>Name</span><strong>${visitorName}</strong></div>
                <div class="detail-row"><span>Type</span><strong>${visitorType}</strong></div>
                <div class="detail-row"><span>Time</span><strong>${bookingTime}</strong></div>
            `);
            
            $('#modal-title').text('Seat ' + seatName);
            $('#delete_id_seat').val(idSeat);
            $('#bookingForm').hide();
            $('#deleteConfirmation').show();
            $('#seatModal').fadeIn(200);
        } else {
            $this.toggleClass('seat-selected');
            const index = selectedSeatIDs.indexOf(idSeat);
            if (index > -1) {
                selectedSeatIDs.splice(index, 1);
            } else {
                selectedSeatIDs.push(idSeat);
            }
            updateSelectedSeatsUI();
        }
    });

    // Book Selected
    $('#bookSelectedBtn').on('click', function() {
        if (selectedSeatIDs.length === 0) return;
        const seatNames = selectedSeatIDs.map(id => $(`[data-id-seat="${id}"]`).data('seat-name'));
        
        $('#modal-title').text('Take ' + selectedSeatIDs.length + ' Seat(s)');
        $('#selectedSeatsDisplay').html(seatNames.map(n => `<span class="seat-tag">${n}</span>`).join(''));
        
        $('#bookingForm input[name="id_seats[]"]').remove();
        selectedSeatIDs.forEach(id => {
            $('<input>').attr({ type: 'hidden', name: 'id_seats[]', value: id }).appendTo('#bookingForm');
        });

        $('#bookingForm').show();
        $('#deleteConfirmation').hide();
        $('#seatModal').fadeIn(200);
        $('#name').val('');
    });

    // Submit Booking
    $('#bookingForm').on('submit', function(e) {
        e.preventDefault();
        if (selectedSeatIDs.length === 0) return;

        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: $(this).serialize() + '&action=book',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#message').text(response.message).addClass('success');
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    $('#message').text('Error: ' + response.message).addClass('error');
                }
            },
            error: function() {
                $('#message').text('Booking failed.').addClass('error');
            }
        });
    });

    // Delete Booking
    $('#deleteButton').on('click', function() {
        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: { action: 'delete', id_seat: $('#delete_id_seat').val() },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    $('#message').text('Error: ' + response.message).addClass('error');
                }
            }
        });
    });

    // Refresh
    $('#refreshBtn').on('click', function() {
        window.location.reload();
    });

    // Stats
    $('#statsBtn').on('click', function() {
        window.location.href = 'history.php';
    });

    // Modal Close
    $('#closeModalIcon, #closeModalIconDelete').on('click', function() {
        if ($('#bookingForm').is(':visible')) {
            $('.seat-button.seat-selected').removeClass('seat-selected');
            selectedSeatIDs = [];
            updateSelectedSeatsUI();
        }
        $('#seatModal').fadeOut(200);
    });

    $('#seatModal').on('click', function(e) {
        if (e.target === this) {
            if ($('#bookingForm').is(':visible')) {
                $('.seat-button.seat-selected').removeClass('seat-selected');
                selectedSeatIDs = [];
                updateSelectedSeatsUI();
            }
            $(this).fadeOut(200);
        }
    });

    // Finish Day
    $('#finishDayBtn').on('click', function() {
        $('#finishDayModal').fadeIn(200);
    });
    
    $('#cancelFinish').on('click', function() {
        $('#finishDayModal').fadeOut(200);
    });
    
    $('#confirmFinish').on('click', function() {
        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: { action: 'finish_day' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('🎉 Happy Sunday!');
                    window.location.reload();
                }
            }
        });
    });

    $('#finishDayModal').on('click', function(e) {
        if (e.target === this) $(this).fadeOut(200);
    });
});
</script>
</body>
</html>
