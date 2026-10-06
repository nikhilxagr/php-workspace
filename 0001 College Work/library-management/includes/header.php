<?php
// Default title if not explicitly set by the page
if (!isset($page_title)) {
    $page_title = "Library Management System";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?php echo $base_path ?? ''; ?>assets/css/style.css">
</head>
<body>
