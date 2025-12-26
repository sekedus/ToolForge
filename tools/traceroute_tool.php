<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['host'] ?? '');

    if (empty($host)) {
        $error = 'Please enter a host or IP address.';
    } elseif (!function_exists('shell_exec')) {
        $error = 'Traceroute unavailable: shell_exec is disabled on this server.';
    } else {
        // Use shell_exec for traceroute command
        // Note: This might be disabled or restricted on shared hosting environments.
        $command = 'tracert ' . escapeshellarg($host); // Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') { // Linux
            $command = 'traceroute ' . escapeshellarg($host);
        }

        $output = shell_exec($command);

        if ($output === null) {
            $error = 'Traceroute command failed or is disabled on this server.';
        } else {
            $result = $output;
        }
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-diagram-3 me-2"></i>Traceroute Tool</h5>
        <p class="card-text">Trace the network path (hops) to a target host. Note: This tool may be slow or blocked on shared hosting.</p>
        <form method="POST" action="?page=traceroute_tool">
            <div class="mb-3">
                <label for="host" class="form-label">Host / IP Address</label>
                <input type="text" class="form-control" id="host" name="host" value="<?php echo htmlspecialchars($_POST['host'] ?? ''); ?>" placeholder="example.com or 8.8.8.8" required>
            </div>
            <button type="submit" class="btn btn-primary">Trace Route</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Traceroute Result</label>
                <div class="input-group">
                    <textarea class="form-control" id="tracerouteResult" rows="15" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="tracerouteResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
