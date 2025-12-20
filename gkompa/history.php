<?php
// history.php
// --- DATABASE CONFIGURATION ---
$host = 'localhost';
$db   = 'kompa'; 
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
// ------------------------------

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Seat row starting points
$row_starts = [1, 17, 33, 49, 65, 81, 97, 113, 129, 145];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>History - Advent GKI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* ===============================
                BASE STYLES
        ================================= */

        #history-controls {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        
        #area-box-history {
            display: block;
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(7.5px);
            padding: 40px;
            border-radius: 8px;
            margin: 1% auto 0;
            width: 60%;
        }
        
        .history-header {
            justify-content: space-between;
            display: flex;
            flex-direction: row;
            align-items: center;
            color: white;
        }

        .result-history {
            width: auto;
            display: block;
            margin: 0;
            color: white;
        }

        .seat-map-history {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 40px;
            gap: 25px;
        }

        .seat-button.seat-history {
            pointer-events: none;
            cursor: default;
        }

        /* ===============================
                RESPONSIVE FIXES
        ================================= */

        /* MatePad 11 / 12 / 12.6 (large tablets) */
        @media screen and (max-width: 1400px) {

            #area-box-history {
                width: 70%;
                padding: 23px;
                margin:0px auto;
            }

            .history-header {
                flex-direction: row;
                justify-content: space-between;
                width: auto;
                margin-bottom: 5px;
            }

            #history-controls {
                width: auto;
                display: flex;
                gap: 10px;
            }
            .result-history {
                width: auto;
                display: block;
                margin: 0;

            }
            .d-flex {
                padding:3px;
            }
            .seat-map-history {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 25px;
                padding: 0;
            }
        }

        /* Medium tablets */
        @media screen and (max-width: 1200px) {

            #area-box-history {
                width: 90%;
            }

            #history-controls {
                flex-wrap: wrap;
                width: 100%;
                gap: 10px;
            }

            #history-controls input[type="date"],
            #viewHistoryBtn {
                width: 100%;
            }

            .seat-map-history {
                flex-direction: column;
            }
        }

        /* Mobile phones */
        @media screen and (max-width: 768px) {

            #area-box-history {
                width: 100%;
                padding: 15px;
            }

            .history-header {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            #history-controls {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }

            #history-controls input[type="date"],
            #viewHistoryBtn {
                width: 100%;
            }

            .seat-map-history {
                flex-direction: column;
                gap: 20px;
            }

            .seat-section {
                display: grid;
                grid-template-columns: repeat(8, 32px);
                gap: 6px;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <div id="area-box-history">

        <!-- ▬▬▬ HEADER ▬▬▬ -->
        <div class="history-header">
            <a href="index.php" class="btn btn-secondary">← Ada yang datang lagi nih</a>

            <h3>History</h3>

            <!-- FIXED STRUCTURE -->
            <div id="history-controls">
                <input type="date" id="select_date" class="form-control"
                       value="<?php echo date('Y-m-d'); ?>">
                <button id="viewHistoryBtn" class="btn btn-primary">View</button>
            </div>
        </div>

        <div id="history-results"></div>
    </div>

<script>
$(document).ready(function() {

    const initializeTooltips = () => {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
    };

    const generateSeatHtml = (seat) => {
        const isTaken = seat.is_taken == 1;
        const className = isTaken ? 'seat-taken' : 'seat-available';
        const seatName = seat.seat_name;

        let tooltipData = '';
        if (isTaken) {
            const name = seat.visitor_name || 'N/A';
            const type = seat.visitor_type_name || 'N/A';
            const time = seat.booking_time || 'N/A';
            tooltipData = `data-bs-toggle="tooltip" title="Taken by: ${name} | Type: ${type} | Time: ${time}"`;
        }

        return `<button class="seat-button ${className} seat-history" ${tooltipData}>${seatName}</button>`;
    };

    $('#viewHistoryBtn').on('click', function() {

        const selectedDate = $('#select_date').val();
        const resultsDiv = $('#history-results');

        resultsDiv.html('<p>Loading history...</p>');

        if (!selectedDate) {
            resultsDiv.html('<p class="text-danger">Please select a date.</p>');
            return;
        }

        $.ajax({
            url: 'fetch_history.php',
            type: 'GET',
            data: { date: selectedDate },
            dataType: 'json',

            success: function(response) {
                if (!response.success) {
                    if (response.message.includes("not been marked as finished")) {
                        resultsDiv.html('<p class="alert alert-info">Cek tanggal nya.</p>');
                    } else {
                        resultsDiv.html(`<p class="alert alert-danger">${response.message}</p>`);
                    }
                    return;
                }

                const seatMap = response.data;
                const rowStarts = <?php echo json_encode($row_starts); ?>;
                let col1 = '', col2 = '';

                rowStarts.forEach(startId => {
                    for (let id = startId; id <= startId + 7; id++) {
                        if (seatMap[id]) col1 += generateSeatHtml(seatMap[id]);
                    }
                });

                rowStarts.forEach(startId => {
                    for (let id = startId + 8; id <= startId + 15; id++) {
                        if (seatMap[id]) col2 += generateSeatHtml(seatMap[id]);
                    }
                });

                const html = `
                    <div class="result-history">
                        <div id="screen-display">Mimbar</div>

                        <div class="d-flex justify-content-between flex-wrap" style="gap: 15px;">
                            
                            <div class="d-flex align-items-center">
                                <span>Seat Taken: ${response.total_bookings}</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="me-2" style="width:20px;height:20px;border:1px solid green;background:white;"></div>
                                <span>Seat Available</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="me-2 bg-success" style="width:20px;height:20px;border:1px solid #198754;"></div>
                                <span>Seat Taken</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <span>Selected Date: ${response.selected_date}</span>
                            </div>
                        </div>

                        <div class="seat-map-history">
                            <div class="seat-section">${col1}</div>
                            <div class="seat-section">${col2}</div>
                        </div>
                    </div>
                `;

                resultsDiv.html(html);
                initializeTooltips();
            },

            error: function() {
                $('#history-results').html('<p class="alert alert-danger">Error fetching data.</p>');
            }
        });
    });

});
</script>

</body>
</html>
