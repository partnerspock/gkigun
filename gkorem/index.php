<?php
// index.php
// --- DATABASE CONFIGURATION ---
$host = 'localhost';
$db   = 'korem'; 
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
// ------------------------------

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

// =============================================================
// >> DATA FETCHING LOGIC (UPDATED FOR isDone = 0) <<
// =============================================================

$current_date = date('Y-m-d');
$total_undone_bookings = 0;

// A seat is considered "taken" only if it has a record in dday for TODAY 
// AND isDone is 0 (the day is not yet finished).
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
        AND d.isDone = 0 -- Filter: Only show active bookings for today
    LEFT JOIN visitor v ON d.id_visitor = v.id_visitor
    LEFT JOIN visitor_type vt ON v.id_type = vt.id_type
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

// Helper function to generate seats
function generate_seat_section($start_id, $end_id, $seat_map) {
    $output = '';
    for ($id = $start_id; $id <= $end_id; $id++) {
        if (!isset($seat_map[$id])) continue;
       
        $seat = $seat_map[$id];
        $is_taken = $seat['is_taken'];
        $class = $is_taken ? 'seat-taken' : 'seat-available';

        $output .= '<button 
                            class="seat-button ' . $class . '" 
                            data-id-seat="' . $seat['id_seat'] . '"
                            data-is-taken="' . $is_taken . '"
                            data-seat-name="' . htmlspecialchars($seat['seat_name']) . '"
                            data-visitor-name="' . htmlspecialchars($seat['visitor_name'] ?? '') . '"
                            data-visitor-type="' . htmlspecialchars($seat['visitor_type_name'] ?? '') . '"
                            data-booking-time="' . htmlspecialchars($seat['booking_time'] ?? '') . '"
                        >' . htmlspecialchars($seat['seat_name']) . '</button>';
    }
    return $output;
}

$row_starts = [1, 17, 33, 49, 65, 81, 97, 113, 129, 145];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advent GKI</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="area-box">
        <div class="button-group">
        <img src="img/gunsa.png" alt="Movie Poster 1" class="logo">
        <span class="blinking"><h1 style="font-weight: 600; color: white;">00.00.00 - See the Light</h1></span>
        <img src="img/kompa.jpg" alt="Movie Poster 1" class="logo" style="border-radius: 99px;">
        </div>

    <a href="cinema_listings.php" style="text-decoration:none;"><div id="screen-display">Mimbar</div><a>
    <div class="d-flex justify-content-center" style="width: 100%; gap:120px; padding: 25px 0px;">
    <div class="d-flex align-items-center">
        <div class="me-2 rounded" style="width: 30px; height: 30px; background-color: white; border: 2px solid #198754;"></div>
        <span class="seat-info">Seat is Available</span>
    </div>
    <?php if ($total_undone_bookings > 0): ?>
    <div class="d-flex"><button id="finishDayBtn" type="button" class="seat-info" data-bs-toggle="button" aria-pressed="true"><span>(<?php echo $total_undone_bookings; ?> Seats Taken Today)</span></button></div>
    <?php else: ?>
    <div class="d-flex">
        <button type="button" id="stats" class="seat-info"><span>Happy Sunday</span></button>
    </div>
    <?php endif; ?>
    <div class="d-flex align-items-center">
        <div class="me-2 rounded bg-success" style="width: 30px; height: 30px; border: 2px solid white;"></div>
        <span class="seat-info">Seat Has Been taken</span>
    </div>
    </div>
    <div class="seat-container"> 
        <div class="seat-section">
            <?php 
                foreach ($row_starts as $start_id) {
                    echo generate_seat_section($start_id, $start_id + 7, $seat_map); 
                }
            ?>
        </div>

        <div class="seat-section">
            <?php 
                foreach ($row_starts as $start_id) {
                    echo generate_seat_section($start_id + 8, $start_id + 15, $seat_map); 
                }
            ?>
        </div>
    </div>
    <div class="d-flex justify-content-center" style="width: 100%; gap:10px; padding-top: 50px;">
        <button id="bookSelectedBtn" class="btn btn-primary btn-md" disabled>
            Take Selected Seats - <span id="selected-count">0</span>
        </button>
        <button id="clearSelection" type="button" class="btn btn-danger clear-button btn-md" disabled>
            Clear Selection
        </button>
    </div>
        
    </div>
</div> 
    <div id="finishDayModal" class="modal">
        <div class="modal-content">
            <h3>Confirm Day Completion</h3>
            <p>Hari ini ada <?php echo $total_undone_bookings; ?> yang hadir, Yakin mau diselesaikan?</p>
            <div class="d-flex" style="gap: 12px;">
            <button id="confirmFinish" class="btn btn-outline-success" style="width: 12%;"><i class="fa-solid fa-check"></i></button>
            <button type="button" id="cancelFinish" class="btn btn-danger" style="width: 72%; padding: 12px 0px;">Tidak jadi</button>
            <button type="button" id="stats" class="btn btn-link" style="width: 12%; border: 1px solid blue;"><i class="fa-solid fa-chart-simple"></i></button>
            </div>
            
        </div>
    </div>
        <div id="seatModal" class="modal">
        <div class="modal-content">
            <h2 id="modal-title"></h2>
           
                        <form id="bookingForm" autocomplete="off" style="display: none;">
                <div id="selectedSeatsDisplay"></div>                 <input type="hidden" id="modal_id_seats" name="id_seats[]">                
                <label for="name">Name: (Optional)</label>
                <input type="text" id="name" name="name" placeholder="e.g. John Doe">
               
                <div class="d-flex align-items-center justify-content-between">
                    <?php foreach ($visitor_types as $type): ?>
                    <div class="form-check" style="flex: 1 1 0;">
                        <input type="radio" id="type_<?php echo $type['id_type']; ?>" name="id_type" value="<?php echo $type['id_type']; ?>" class="form-check-input">
                        <label for="type_<?php echo $type['id_type']; ?>" class="form-check-label"><?php echo htmlspecialchars($type['name_type']); ?></label>
                    </div>
                <?php endforeach; ?>
                </div>
               
                <div class="d-flex" style="gap: 12px;">
                    <button type="submit" id="Button" class="btn btn-success" style="width: 82%; padding: 12px 0px;">Confirm Seats</button>
                    <button type="button" id="closeModalIcon" title="Close" class="btn btn-outline-danger" style="width: 14%; padding: 12px 17px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
           
                <div id="deleteConfirmation" style="display: none;">
                <input type="hidden" id="delete_id_seat"> 
                <div id="booking-details-display">
                    </div>
                <p>Sudah ada yang pilih, Yakin mau dihapus?</p>
                <div class="d-fleX" style="gap: 12px;">
                    <button id="deleteButton" class="btn btn-outline-danger" style="width: 14%; padding: 12px 0px;"><i class="fa-solid fa-trash"></i></button>
                    <button type="button" id="closeModalIconDelete" title="Close" class="btn btn-success" style="width: 82%; padding: 12px 0px;">Ga jadi hapus deh
                    </button>
                </div>
            </div>
           
            <div id="message" style="margin-top: 10px;"></div>
        </div>
    </div>

<script>
// Global array to hold selected seat IDs
let selectedSeatIDs = [];

function updateDateTime() {
    const now = new Date();
    const formattedDate = now.toLocaleString('en-US', { 
        weekday: 'short', 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit', 
        hour12: false,
        timeZoneName: 'short'
    });
    $('#datetime-display').text(formattedDate);
}

function updateSelectedSeatsUI() {
    $('#selected-count').text(selectedSeatIDs.length);
    $('#bookSelectedBtn').prop('disabled', selectedSeatIDs.length === 0);
}


$(document).ready(function() {
    updateDateTime();
    setInterval(updateDateTime, 1000); 
    // Add this new handler within your $(document).ready function:
// 7. Handle 'Clear Selection' Button Click
$('.clear-button').on('click', function() {
    // 1. Remove the 'seat-selected' class from all elements that currently have it
    $('.seat-button.seat-selected').removeClass('seat-selected');

    // 2. Reset the global array of selected seat IDs
    selectedSeatIDs = [];

    // 3. Update the UI to reflect the change (count and main button state)
    updateSelectedSeatsUI();
});

let selectedSeats = [];

function updateSelectionUI() {
    const count = selectedSeats.length;

    if (count === 0) {
        $("#bookSelectedBtn").prop("disabled", true).text("Choose Your Seats");
        $("#clearSelection").prop("disabled", true);
    } else {
        $("#bookSelectedBtn").prop("disabled", false)
            .text(`Take ${count} Selected Seats`);
        $("#clearSelection").prop("disabled", false);
    }
}

// When clicking seats
$(".seat-button").on("click", function() {
    const id = $(this).data("id-seat");

    if ($(this).hasClass("selected")) {
        $(this).removeClass("selected");
        selectedSeats = selectedSeats.filter(x => x !== id);
    } else {
        $(this).addClass("selected");
        selectedSeats.push(id);
    }

    updateSelectionUI();
});

// Clear selection button
$("#clearSelection").on("click", function() {
    selectedSeats = [];
    $(".seat-button.selected").removeClass("selected");
    updateSelectionUI();
});

// Initialize correctly on page load
updateSelectionUI();


    // 1. Handle Seat Button Click (Select/Deselect or Open Delete Modal)
    $('.seat-button').on('click', function() {
        const $this = $(this);
        const isTaken = $this.data('is-taken');
        const idSeat = $this.data('id-seat').toString();
        const seatName = $this.data('seat-name');

        $('#message').text('').css('color', 'red'); 
       
        if (isTaken == 1) { 
            // OPEN DELETE MODAL
            const visitorName = $this.data('visitor-name');
            const visitorType = $this.data('visitor-type');
            const bookingTime = $this.data('booking-time');
           
            const detailsHtml = `
                <p><strong>Nama nya:</strong> ${visitorName}</p>
                <p><strong>Tipe nya:</strong> ${visitorType}</p>
                <p><strong>Waktu datang nya:</strong> ${bookingTime}</p>
            `;
           
            $('#booking-details-display').html(detailsHtml);
            $('#modal-title').text('Seat ' + seatName);
            $('#delete_id_seat').val(idSeat); // Set hidden field for deletion
            $('#bookingForm').hide();
            $('#deleteConfirmation').show();
            $('#seatModal').fadeIn(200);
        } else {
            // SELECT/DESELECT LOGIC
            $this.toggleClass('seat-selected');
            const index = selectedSeatIDs.indexOf(idSeat);

            if (index > -1) {
                selectedSeatIDs.splice(index, 1); // Deselect
            } else {
                selectedSeatIDs.push(idSeat); // Select
            }
            updateSelectedSeatsUI();
        }
    });

    // 2. Handle 'Book Selected Seats' button click (Opens Multi-Seat Booking Modal)
    $('#bookSelectedBtn').on('click', function() {
        if (selectedSeatIDs.length === 0) return;

        // Prepare seat names for display
        const seatNames = selectedSeatIDs.map(id => $(`[data-id-seat="${id}"]`).data('seat-name'));
        
        $('#modal-title').text('Take ' + selectedSeatIDs.length + ' Seat(s)');
        $('#selectedSeatsDisplay').html('<p>Selected Seats: <strong>' + seatNames.join(', ') + '</strong></p>');
        
        // Populate the hidden fields in the form (remove existing, add new)
        $('#bookingForm input[name="id_seats[]"]').remove();
        selectedSeatIDs.forEach(id => {
            $('<input>').attr({
                type: 'hidden',
                name: 'id_seats[]',
                value: id
            }).appendTo('#bookingForm');
        });

        $('#bookingForm').show();
        $('#deleteConfirmation').hide();
        $('#seatModal').fadeIn(200);

        // Reset name field and pre-select first radio button
        $('#name').val('');
        $('#type_1').prop('checked', true); // Assuming type 1 is default
    });

    // 3. Handle Confirm Booking (Submits to process.php)
    $('#bookingForm').on('submit', function(e) {
        e.preventDefault();
        
        // Ensure at least one seat is selected (should be handled by button disable, but a check is safer)
        if (selectedSeatIDs.length === 0) {
            $('#message').text('Please select at least one seat.').css('color', 'red');
            return;
        }

        $.ajax({
            url: 'process.php', 
            type: 'POST',
            // Serializes the entire form, including the hidden id_seats[] inputs
            data: $(this).serialize() + '&action=book',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#message').text(response.message).css('color', 'green'); 
                    window.location.reload(); // Reload to update seat map and button count
                } else {
                    $('#message').text('Error: ' + response.message).css('color', 'red');
                }
            },
            error: function() {
                $('#message').text('Terjadi kesalahan saat pemesanan.').css('color', 'red'); 
            }
        });
    });
   
    // 4. Handle Delete Booking (Submits to process.php) - Remains for single seat deletion
    $('#deleteButton').on('click', function() {
        const idSeat = $('#delete_id_seat').val(); // Use the new single seat ID field
        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: { action: 'delete', id_seat: idSeat },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#message').text('Berhasil dihapus').css('color', 'green'); 
                    window.location.reload(); 
                } else {
                    $('#message').text('Error: ' + response.message).css('color', 'red');
                }
            },
            error: function() {
                $('#message').text('Error delete this seat, please call the Admin.').css('color', 'red'); 
            }
        });
    });
   
    // 5. Finish the Day (Unchanged)
    // ... (logic for finish_day and stats) ...
    const $finishDayModal = $('#finishDayModal');
    const $finishDayBtn = $('#finishDayBtn');
    const $confirmFinishBtn = $('#confirmFinish');

    $finishDayBtn.on('click', function() {
        if (!$(this).prop('disabled')) {
            $finishDayModal.fadeIn(200);
        }
    });

    $('#cancelFinish').on('click', function() {
        $finishDayModal.fadeOut(200);
    });
   
    $confirmFinishBtn.on('click', function() {
        $finishDayModal.fadeOut(200);

        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: { action: 'finish_day' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('🎉 Happy Sunday You All');
                    window.location.reload();
                } else {
                    alert('Error finishing day: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred during the "Finish Day" request.');
            }
        });
    });

    $('#stats').on('click', function() {
        window.location.href = 'history.php';
    });

    // 6. Handle Modal Closures (Resets selection if booking modal is closed via close icon/button)
    $('#closeModalIcon, #closeModalIconDelete, #seatModal').on('click', function(event) {
        if ($(event.target).is('#closeModalIcon') || $(event.target).is('#seatModal') || $(event.target).is('#closeModalIconDelete')) {
            // Only reset selection if the booking form was visible
            if ($('#bookingForm').is(':visible')) {
                // Clear selection state and array on dismiss
                $('.seat-button.seat-selected').removeClass('seat-selected');
                selectedSeatIDs = [];
                updateSelectedSeatsUI();
            }
            $('#seatModal').fadeOut(200);
        }
    });

    $(window).on('click', function(event) {
        if ($(event.target).is('#finishDayModal')) {
            $('#finishDayModal').fadeOut(200);
        }
    });
});
</script>
</body>
</html>
