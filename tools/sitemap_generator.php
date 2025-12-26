<?php
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $urls_input = trim($_POST['urls_input'] ?? '');
    $changefreq = $_POST['changefreq'] ?? 'monthly';
    $priority = floatval($_POST['priority'] ?? 0.5);

    if (empty($urls_input)) {
        $error = 'Please enter at least one URL.';
    } else {
        $urls = array_filter(array_map('trim', explode("\n", $urls_input)));
        
        $xml = new DOMDocument('1.0', 'UTF-8');
        $urlset = $xml->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->appendChild($urlset);

        foreach ($urls as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $url_node = $xml->createElement('url');
                
                $loc = $xml->createElement('loc', htmlspecialchars($url));
                $url_node->appendChild($loc);
                
                $lastmod = $xml->createElement('lastmod', date('Y-m-d'));
                $url_node->appendChild($lastmod);
                
                $changefreq_node = $xml->createElement('changefreq', $changefreq);
                $url_node->appendChild($changefreq_node);
                
                $priority_node = $xml->createElement('priority', sprintf('%.1f', $priority));
                $url_node->appendChild($priority_node);
                
                $urlset->appendChild($url_node);
            }
        }

        $xml->formatOutput = true;
        $result = $xml->saveXML();
    }
}

include __DIR__ . '/../templates/header.php';
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-sitemap-fill me-2"></i>Sitemap Generator</h5>
        <p class="card-text">Generate an XML sitemap for your website to help search engines crawl and index your pages more effectively.</p>
        <form method="POST" action="?page=sitemap_generator">
            <div class="mb-3">
                <label for="urls_input" class="form-label">URLs (one per line)</label>
                <textarea class="form-control" id="urls_input" name="urls_input" rows="8" placeholder="https://example.com/page1&#10;https://example.com/page2" required><?php echo htmlspecialchars($_POST['urls_input'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="changefreq" class="form-label">Change Frequency</label>
                <select class="form-select" id="changefreq" name="changefreq">
                    <option value="always">Always</option>
                    <option value="hourly">Hourly</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly" selected>Monthly</option>
                    <option value="yearly">Yearly</option>
                    <option value="never">Never</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="priority" class="form-label">Priority (0.0 - 1.0)</label>
                <input type="number" step="0.1" min="0.0" max="1.0" class="form-control" id="priority" name="priority" value="<?php echo htmlspecialchars($_POST['priority'] ?? 0.5); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Sitemap</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="mt-4">
                <label class="form-label">Generated Sitemap XML</label>
                <div class="input-group">
                    <textarea class="form-control" id="sitemapResult" rows="15" readonly><?php echo htmlspecialchars($result); ?></textarea>
                    <button class="btn btn-outline-secondary copy-btn" type="button" data-target="sitemapResult">Copy XML</button>
                </div>
                <a href="data:text/xml;charset=utf-8,<?php echo rawurlencode($result); ?>" download="sitemap.xml" class="btn btn-secondary mt-2">Download Sitemap.xml</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
