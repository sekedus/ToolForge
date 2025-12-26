<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = trim($_POST['domain'] ?? '');

    if (empty($domain)) {
        $error = 'Please enter a domain name.';
    } else {
        $context = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'verify_peer' => false, // Set to true for production, but might cause issues on some hosts
                'verify_peer_name' => false,
            ]
        ]);

        $stream = @stream_socket_client('ssl://' . $domain . ':443', $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);

        if (!$stream) {
            $error = "Could not connect to {$domain} on port 443 (SSL). Error: ({$errno}) {$errstr}";
        } else {
            $params = stream_context_get_params($stream);
            $cert_resource = $params['options']['ssl']['peer_certificate'];
            $cert_info = openssl_x509_parse($cert_resource);

            if ($cert_info) {
                // helper to safely read nested keys from $cert_info
                $get = function($path, $default = 'N/A') use ($cert_info) {
                    $parts = is_array($path) ? $path : explode('.', $path);
                    $v = $cert_info;
                    foreach ($parts as $p) {
                        if (!is_array($v) || !array_key_exists($p, $v)) {
                            return $default;
                        }
                        $v = $v[$p];
                    }
                    return $v;
                };

                $result = [
                    'Subject' => $get('subject.CN'),
                    'Issuer' => $get('issuer.CN'),
                    'Valid From' => date('Y-m-d H:i:s', $get('validFrom_time_t')),
                    'Valid To' => date('Y-m-d H:i:s', $get('validTo_time_t')),
                    'Serial Number' => $get('serialNumber'),
                    'Signature Type' => $get('signatureTypeSN'),
                    'Key Size' => $get('bits'),
                    'SAN (Subject Alternative Names)' => $get('extensions.subjectAltName'),
                ];
            } else {
                $error = 'Could not parse SSL certificate information.';
            }
            fclose($stream);
        }
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-lock-fill me-2"></i>SSL Certificate Checker</h5>
        <p class="card-text">Check the details and validity of an SSL certificate for any domain.</p>
        <form method="POST" action="?page=ssl_checker">
            <div class="mb-3">
                <label for="domain" class="form-label">Domain Name</label>
                <input type="text" class="form-control" id="domain" name="domain" value="<?php echo htmlspecialchars($_POST['domain'] ?? ''); ?>" placeholder="example.com" required>
            </div>
            <button type="submit" class="btn btn-primary">Check SSL</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">SSL Certificate Details for <?php echo htmlspecialchars($_POST['domain'] ?? ''); ?></label>
                <ul class="list-group">
                    <?php foreach($result as $key => $value): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold"><?php echo htmlspecialchars($key); ?></div>
                                <?php echo htmlspecialchars($value); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
