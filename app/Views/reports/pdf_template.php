<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }
        .header h1 {
            text-transform: uppercase;
            font-size: 24px;
            margin: 0;
            padding: 0;
            color: #000;
        }
        .header .subject {
            font-size: 16px;
            font-weight: bold;
            color: #666;
            margin-top: 5px;
        }
        .header .meta {
            margin-top: 10px;
            font-style: italic;
            font-weight: bold;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f2f2f2;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #ddd;
            padding: 8px;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .summary {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #eee;
            width: 100%;
        }
        .summary-item {
            display: inline-block;
            width: 24%;
            text-align: center;
        }
        .summary-label {
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
            color: #999;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
        .on-time { color: #2ecc71; }
        .late { color: #f1c40f; }
        .incomplete { color: #e74c3c; }
        
        .footer {
            margin-top: 50px;
            font-size: 9px;
            color: #999;
            font-style: italic;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Attendance Report</h1>
        <div class="subject"><?= $report['subject']['name'] ?> (<?= $report['subject']['code'] ?>)</div>
        <div class="meta">
            Section: <?= $report['section']['name'] ?> &nbsp;&nbsp;|&nbsp;&nbsp; Date: <?= date('F d, Y', strtotime($report['date'])) ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No.</th>
                <th>Student Name</th>
                <th width="80" class="text-center">Time In</th>
                <th width="80" class="text-center">Time Out</th>
                <th width="100" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($report['records'] as $record): ?>
            <tr>
                <td class="text-center"><?= $i++ ?></td>
                <td class="font-bold"><?= $record['last_name'] ?>, <?= $record['first_name'] ?></td>
                <td class="text-center">
                    <?= $record['time_in'] ? date('h:i A', strtotime($record['time_in'])) : '---' ?>
                </td>
                <td class="text-center">
                    <?= $record['time_out'] ? date('h:i A', strtotime($record['time_out'])) : '---' ?>
                </td>
                <td class="text-center">
                    <b>
                        <?php 
                            if ($record['is_manual']) echo "Manual";
                            else if ($record['status'] == 'on_time') echo "On Time";
                            else if ($record['status'] == 'late') echo "Late";
                            else if ($record['status'] == 'incomplete') echo "Incomplete";
                        ?>
                    </b>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total Students</div>
            <div class="summary-value"><?= $report['summary']['total'] ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label">On Time</div>
            <div class="summary-value on-time"><?= $report['summary']['on_time'] ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Late</div>
            <div class="summary-value late"><?= $report['summary']['late'] ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Incomplete</div>
            <div class="summary-value incomplete"><?= $report['summary']['incomplete'] ?></div>
        </div>
    </div>

    <div class="footer">
        Generated on: <?= date('Y-m-d H:i:s') ?> by RFID Attendance System
    </div>
</body>
</html>
