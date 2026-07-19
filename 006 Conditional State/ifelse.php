<?php

$marks = 72;
$result = ($marks >= 50) ? 'You passed the exam.' : 'You failed the exam.';
$statusClass = ($marks >= 50) ? 'pass' : 'fail';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>If Else Result</title>

	<style>
		* { box-sizing: border-box; }
		body {
			margin: 0;
			font-family: Arial, Helvetica, sans-serif;
			background: linear-gradient(135deg, #e3f2fd, #fce4ec);
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 24px;
		}
		.card {
			width: 100%;
			max-width: 520px;
			background: #fff;
			border-radius: 18px;
			padding: 32px;
			box-shadow: 0 18px 45px rgba(0, 0, 0, 0.12);
			text-align: center;
		}
		h1 { margin: 0 0 10px; font-size: 28px; color: #1f2937; }
		p { margin: 8px 0; color: #4b5563; font-size: 16px; }
		.marks { font-size: 54px; font-weight: 700; margin: 18px 0 8px; color: #111827; }
		.badge {
			display: inline-block;
			padding: 10px 18px;
			border-radius: 999px;
			font-weight: 700;
			font-size: 15px;
			margin-top: 12px;
		}
		.pass { background: #dcfce7; color: #166534; }
		.fail { background: #fee2e2; color: #991b1b; }
		.footer { margin-top: 24px; font-size: 13px; color: #6b7280; }
	</style>
    
</head>
<body>
	<div class="card">
		<h1>Exam Result</h1>
		<p>Conditional statement demo using PHP if/else.</p>
		<div class="marks"><?php echo $marks; ?></div>
		<p>Marks scored out of 100</p>
		<div class="badge <?php echo $statusClass; ?>"><?php echo $result; ?></div>
		<div class="footer">Passing marks: 50</div>
	</div>
</body>
</html>
