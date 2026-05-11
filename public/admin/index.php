<?php
/**
 * AXIS UPGRADERS - ADMIN PANEL
 * Simple PHP script to edit site-content.json
 */

$content_file = '../../src/data/site-content.json';

// Ensure the file exists
if (!file_exists($content_file)) {
    die("Error: Content file not found at $content_file");
}

// Load content
$content = json_decode(file_get_contents($content_file), true);

// Handle Save
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    // Basic recursion to update the array from posted data
    function update_array_from_post(&$target, $post_data) {
        foreach ($post_data as $key => $value) {
            if (is_array($value) && isset($target[$key])) {
                update_array_from_post($target[$key], $value);
            } else if (isset($target[$key])) {
                $target[$key] = $value;
            }
        }
    }

    update_array_from_post($content, $_POST['data']);
    
    if (file_put_contents($content_file, json_encode($content, JSON_PRETTY_PRINT))) {
        $message = "Changes saved successfully!";
    } else {
        $message = "Error saving changes. Check file permissions.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Axis Admin - Edit Content</title>
    <style>
        :root {
            --primary: #c8a25d;
            --bg: #0f1115;
            --card: #1a1d23;
            --text: #e0e0e0;
            --border: #2d333b;
        }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 20px;
        }
        h1 { color: var(--primary); margin: 0; font-size: 1.5rem; }
        .btn {
            background: var(--primary);
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn:hover { filter: brightness(1.1); }
        .section-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .section-card h2 {
            margin-top: 0;
            font-size: 1.1rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 10px;
            color: var(--primary);
        }
        .form-group {
            margin-bottom: 16px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.85rem;
            color: #888;
        }
        input[type="text"], textarea {
            width: 100%;
            background: #000;
            border: 1px solid var(--border);
            color: white;
            padding: 10px;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 0.9rem;
        }
        textarea { height: 80px; resize: vertical; }
        .alert {
            background: #1e3a2f;
            color: #4ade80;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .row { display: flex; gap: 15px; }
        .col { flex: 1; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>AXIS CONTENT ADMIN</h1>
            <button form="main-form" name="save" class="btn">Save All Changes</button>
        </header>

        <?php if ($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <form id="main-form" method="POST">
            <!-- HERO SECTION -->
            <div class="section-card">
                <h2>Hero Section</h2>
                <div class="form-group">
                    <label>Eyebrow</label>
                    <input type="text" name="data[hero][eyebrow]" value="<?php echo htmlspecialchars($content['hero']['eyebrow']); ?>">
                </div>
                <div class="form-group">
                    <label>Main Title</label>
                    <input type="text" name="data[hero][title]" value="<?php echo htmlspecialchars($content['hero']['title']); ?>">
                </div>
                <div class="form-group">
                    <label>Lead Text</label>
                    <textarea name="data[hero][lead]"><?php echo htmlspecialchars($content['hero']['lead']); ?></textarea>
                </div>
                <div class="row">
                    <div class="col">
                        <label>Primary CTA Text</label>
                        <input type="text" name="data[hero][cta_primary_text]" value="<?php echo htmlspecialchars($content['hero']['cta_primary_text']); ?>">
                    </div>
                    <div class="col">
                        <label>Secondary CTA Text</label>
                        <input type="text" name="data[hero][cta_secondary_text]" value="<?php echo htmlspecialchars($content['hero']['cta_secondary_text']); ?>">
                    </div>
                </div>
            </div>

            <!-- ABOUT SECTION -->
            <div class="section-card">
                <h2>About Section</h2>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="data[about][title]" value="<?php echo htmlspecialchars($content['about']['title']); ?>">
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="data[about][content]"><?php echo htmlspecialchars($content['about']['content']); ?></textarea>
                </div>
            </div>

            <!-- CTA SECTION -->
            <div class="section-card">
                <h2>Call to Action</h2>
                <div class="form-group">
                    <label>CTA Title</label>
                    <input type="text" name="data[cta][title]" value="<?php echo htmlspecialchars($content['cta']['title']); ?>">
                </div>
                <div class="form-group">
                    <label>CTA Lead</label>
                    <textarea name="data[cta][lead]"><?php echo htmlspecialchars($content['cta']['lead']); ?></textarea>
                </div>
            </div>

            <!-- FOOTER SECTION -->
            <div class="section-card">
                <h2>Contact Information</h2>
                <div class="row">
                    <div class="col">
                        <label>Phone</label>
                        <input type="text" name="data[footer][phone]" value="<?php echo htmlspecialchars($content['footer']['phone']); ?>">
                    </div>
                    <div class="col">
                        <label>Email</label>
                        <input type="text" name="data[footer][email]" value="<?php echo htmlspecialchars($content['footer']['email']); ?>">
                    </div>
                </div>
                <div class="form-group" style="margin-top:15px;">
                    <label>Copyright Text</label>
                    <input type="text" name="data[footer][copyright]" value="<?php echo htmlspecialchars($content['footer']['copyright']); ?>">
                </div>
            </div>

            <p style="text-align: center; color: #555; font-size: 0.8rem;">
                Note: Saving will update src/data/site-content.json. A rebuild may be required depending on your environment.
            </p>
        </form>
    </div>
</body>
</html>
