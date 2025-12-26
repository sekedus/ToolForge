<?php
// Core Router

// 1. Configuration & Initialization
error_reporting(0);

function log_tool_usage($tool_name) {
    $log_file = __DIR__ . '/logs/usage.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[{$timestamp}] {$tool_name}\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

$page = $_GET['page'] ?? 'home';

// 2. Define available tools and their files
$tools = ['base64', 'whois', 'json_validator', 'meta_analyzer', 'port_scanner', 'keyword_density', 'url_encoder', 'http_header_analyzer', 'word_character_counter', 'unix_timestamp_converter', 'ip_lookup', 'robots_txt_generator', 'hash_generator', 'password_generator', 'url_redirect_checker', 'number_base_converter', 'dns_lookup', 'html_minifier', 'lorem_ipsum_generator', 'port_status_checker', 'serp_simulator', 'cron_job_generator', 'ip_blacklist_checker', 'broken_link_checker', 'regex_tester', 'ping_tool', 'traceroute_tool', 'subdomain_finder', 'qr_code_generator', 'ip_location_map', 'sitemap_generator','ssl_checker'];
$templates = ['home', 'security', 'seo', 'developer', 'stats', 'contact', 'network']; // Added 'network' page

// 3. Route the request
if (in_array($page, $tools)) {
    log_tool_usage($page); // Log tool usage
    // If the page is a tool, load its specific PHP file

    $tool_file = __DIR__ . "/tools/{$page}.php";
    if (file_exists($tool_file)) {
        require $tool_file;
    } else {
        http_response_code(404);
        require __DIR__ . '/templates/404.php';
    }
} elseif (in_array($page, $templates)) {
    // If the page is a standard template, load it directly
    include __DIR__ . '/templates/header.php';
    include __DIR__ . "/templates/{$page}.php";
    include __DIR__ . '/templates/footer.php';
} else {
    // 404 for anything else
    http_response_code(404);
    require __DIR__ . '/templates/404.php';
}
