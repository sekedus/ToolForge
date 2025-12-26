<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['host'] ?? '');
    $count = intval($_POST['count'] ?? 4);

    if (empty($host)) {
        $error = 'Please enter a host or IP address.';
    } elseif ($count <= 0 || $count > 10) {
        $error = 'Ping count must be between 1 and 10.';
    } elseif (!function_exists('shell_exec')) {
        $error = 'Ping command is not available: shell_exec is disabled on this server.';
    } else {
        // Use shell_exec for ping command
        // Note: This might be disabled or restricted on shared hosting environments.
        $command = 'ping -n ' . $count . ' ' . escapeshellarg($host);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') { // Linux
            $command = 'ping -c ' . $count . ' ' . escapeshellarg($host);
        }

        $output = shell_exec($command);

        if ($output === null) {
            $error = 'Ping command failed or is disabled on this server.';
        } else {
            $result = $output;
        }
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-arrow-left-right me-2"></i>Ping Tool</h5>
        <p class="card-text">Check host reachability and measure round-trip time to a target host.</p>
        <form method="POST" action="?page=ping_tool">
            <div class="mb-3">
                <label for="host" class="form-label">Host / IP Address</label>
                <input type="text" class="form-control" id="host" name="host" value="<?php echo htmlspecialchars($_POST['host'] ?? ''); ?>" placeholder="example.com or 8.8.8.8" required>
            </div>
            <div class="mb-3">
                <label for="count" class="form-label">Ping Count</label>
                <input type="number" class="form-control" id="count" name="count" value="<?php echo htmlspecialchars($_POST['count'] ?? 4); ?>" min="1" max="10" required>
            </div>
            <button type="submit" class="btn btn-primary">Ping Host</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Ping Result</label>
                <div class="input-group">
                    <textarea class="form-control" id="pingResult" rows="10" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="pingResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
