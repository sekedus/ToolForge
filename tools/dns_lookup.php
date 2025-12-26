<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = trim($_POST['domain'] ?? '');
    $record_type = $_POST['record_type'] ?? 'ANY';

    if (!empty($domain)) {
        $records = @dns_get_record($domain, constant('DNS_' . strtoupper($record_type)));

        if ($records !== false && !empty($records)) {
            $formatted_result = [];
            foreach ($records as $record) {
                $line = "";
                foreach ($record as $key => $value) {
                    $line .= ucfirst($key) . ": " . (is_array($value) ? implode(', ', $value) : $value) . "; ";
                }
                $formatted_result[] = trim($line, '; ');
            }
            $result = implode("\n", $formatted_result);
        } else {
            $error = "No {$record_type} records found for '{$domain}' or domain does not exist.";
        }
    } else {
        $error = 'Please enter a domain name.';
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-globe me-2"></i>DNS Lookup</h5>
        <p class="card-text">Retrieve DNS records (A, MX, NS, etc.) for a given domain name.</p>
        <form method="POST" action="?page=dns_lookup">
            <div class="mb-3">
                <label for="domain" class="form-label">Domain Name</label>
                <input type="text" class="form-control" id="domain" name="domain" value="<?php echo htmlspecialchars($_POST['domain'] ?? ''); ?>" placeholder="example.com" required>
            </div>
            <div class="mb-3">
                <label for="record_type" class="form-label">Record Type</label>
                <select class="form-select" id="record_type" name="record_type">
                    <option value="ALL" <?php echo (isset($_POST['record_type']) && $_POST['record_type'] === 'ALL') ? 'selected' : ''; ?>>ALL</option>
                    <option value="A" <?php echo (isset($_POST['record_type']) && $_POST['record_type'] === 'A') ? 'selected' : ''; ?>>A (Address)</option>
                    <option value="AAAA" <?php echo (isset($_POST['record_type']) && $_POST['record_type'] === 'AAAA') ? 'selected' : ''; ?>>AAAA (IPv6 Address)</option>
                    <option value="MX" <?php echo (isset($_POST['record_type']) && $_POST['record_type'] === 'MX') ? 'selected' : ''; ?>>MX (Mail Exchange)</option>
                    <option value="NS" <?php echo (isset($_POST['record_type']) && $_POST['record_type'] === 'NS') ? 'selected' : ''; ?>>NS (Name Server)</option>
                    <option value="TXT" <?php echo (isset($_POST['record_type']) && $_POST['record_type'] === 'TXT') ? 'selected' : ''; ?>>TXT (Text)</option>
                    <option value="CNAME" <?php echo (isset($_POST['record_type']) && $_POST['record_type'] === 'CNAME') ? 'selected' : ''; ?>>CNAME (Canonical Name)</option>
                    <option value="PTR" <?php echo (isset($_POST['record_type']) && $_POST['record_type'] === 'PTR') ? 'selected' : ''; ?>>PTR (Pointer)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Lookup DNS</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">DNS Records for <?php echo htmlspecialchars($_POST['domain'] ?? ''); ?></label>
                <div class="input-group">
                    <textarea class="form-control" id="dnsLookupResult" rows="10" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="dnsLookupResult">Copy</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
