<?php
$tools = [
    'whois' => ['icon' => 'bi-person-lines-fill', 'name' => 'Whois Lookup', 'desc' => 'Get ownership information for a domain.'],
    'port_scanner' => ['icon' => 'bi-broadcast', 'name' => 'Port Scanner', 'desc' => 'Check for open ports on a host.'],
    'http_header_analyzer' => ['icon' => 'bi-file-earmark-code', 'name' => 'HTTP Header Analyzer', 'desc' => 'View HTTP response headers of a URL.'],
    'ip_lookup' => ['icon' => 'bi-geo-alt-fill', 'name' => 'IP Information Lookup', 'desc' => 'Get geographical and network info for an IP.'],
    'password_generator' => ['icon' => 'bi-key-fill', 'name' => 'Password Generator', 'desc' => 'Generate strong, random passwords.'],
    'dns_lookup' => ['icon' => 'bi-globe', 'name' => 'DNS Lookup', 'desc' => 'Retrieve DNS records for a domain name.'],
    'ssl_checker' => ['icon' => 'bi-lock-fill', 'name' => 'SSL Certificate Checker', 'desc' => 'Check the details and validity of an SSL certificate for any domain.'],
    'port_status_checker' => ['icon' => 'bi-hdd-network', 'name' => 'Port Status Checker', 'desc' => 'Quickly check if a specific port is open.'],
    'ip_blacklist_checker' => ['icon' => 'bi-shield-fill-exclamation', 'name' => 'IP Blacklist Checker', 'desc' => 'Check if an IP is on spam blacklists.'],
    'ip_location_map' => ['icon' => 'bi-map-fill', 'name' => 'IP Location Map', 'desc' => 'Visualize the geographical location of an IP address.']
];
?>
<h2 class="mb-4">Security Tools</h2>
<p class="lead mb-4">Enhance your digital security posture with our suite of powerful cybersecurity tools. From network analysis to password generation, we've got you covered.</p>
<div class="row">
    <?php foreach ($tools as $page => $details): ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <a href="index.php?page=<?php echo $page; ?>" class="text-decoration-none">
            <div class="card h-100 p-2">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi <?php echo $details['icon']; ?> me-2"></i><?php echo $details['name']; ?></h5>
                    <p class="card-text"><?php echo $details['desc']; ?></p>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>