<?php
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = trim($_POST['domain'] ?? '');
    if (!empty($domain) && filter_var(gethostbyname($domain), FILTER_VALIDATE_IP)) {
        if (function_exists('shell_exec')) {
            // Note: shell_exec might be disabled on shared hosting.
            $result = shell_exec('whois ' . escapeshellarg($domain));
            if (empty($result)) {
                $error = "Could not retrieve Whois information for '{$domain}'. The domain may not exist, or the Whois server is unavailable.";
            }
        } else {
            $error = "Whois lookup is unavailable: shell_exec is disabled on this server.";
        }
    } else {
        $error = 'Please enter a valid domain name.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-person-lines-fill me-2"></i>Whois Lookup</h5>
        <p class="card-text">Enter a domain name to retrieve its Whois information. This data includes registration details, nameservers, and expiration dates.</p>
        <form method="POST" action="?page=whois">
            <div class="mb-3">
                <label for="domain" class="form-label">Domain Name</label>
                <input type="text" class="form-control" id="domain" name="domain" value="<?php echo htmlspecialchars($_POST['domain'] ?? ''); ?>" placeholder="example.com" required>
            </div>
            <button type="submit" class="btn btn-primary">Lookup</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Result</label>
                <div class="input-group">
                    <textarea class="form-control" id="whoisResult" rows="15" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="whoisResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
