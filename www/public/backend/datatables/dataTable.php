<?php
// Define root directory (change '.' to any folder like 'uploads' if needed)
define('ROOT_PATH', realpath('.'));

// Resolve and sanitize path
$path = isset($_GET['path']) ? $_GET['path'] : '.';
$currentPath = realpath($path);

// Prevent access outside root
// if (strpos($currentPath, ROOT_PATH) !== 0) {
//     die("Access denied.");
// }

// Helper for HTML escaping
function safe($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Save file
if (isset($_POST['save_file'])) {
    $file = realpath($_POST['filepath']);
    // if (strpos($file, ROOT_PATH) === 0) {
        file_put_contents($file, $_POST['content']);
        echo "<p style='color:green;'>File saved!</p>";
    // } else {
    //     die("Access denied.");
    // }
}

// Delete file
if (isset($_GET['delete'])) {
    $fileToDelete = realpath($_GET['delete']);
    // if (strpos($fileToDelete, ROOT_PATH) === 0 && file_exists($fileToDelete)) {
        unlink($fileToDelete);
        echo "<p style='color:red;'>Deleted: " . safe($fileToDelete) . "</p>";
    // } else {
    //     die("Access denied.");
    // }
}


$path = isset($_GET['path']) ? realpath($_GET['path']) : ROOT_PATH;

// 🔒 Prevent access outside ROOT
// if ($path === false || strpos($path, ROOT_PATH) !== 0) {
//     die("Access denied.");
// }

// ✅ Show the full path
echo "<h2>Browsing: " . $path . "</h2>";
echo "<ul>";

// Show "Back" link if not at root
// if ($path !== ROOT_PATH) {
    // echo "<li><a href='?path=" . dirname($path) . "'>⬅️ Back</a></li>";
// }



// Back link (only if not at root)
// if ($currentPath !== ROOT_PATH) {
    $parent = dirname($currentPath);
    echo "<li><a href='?path=" . safe($parent) . "'>⬅️ Back</a></li>";
// }

// List files and folders
foreach (scandir($currentPath) as $item) {
    if ($item === '.') continue;

    $itemPath = $currentPath . DIRECTORY_SEPARATOR . $item;
    $itemReal = realpath($itemPath);

    echo "<li>";
    if (is_dir($itemReal)) {
        echo "📁 <a href='?path=" . safe($itemReal) . "'>" . safe($item) . "</a>";
    } else {
        echo "📄 " . safe($item) . 
            " [<a href='?edit=" . safe($itemReal) . "'>Edit</a>] " .
            "[<a href='?path=" . safe($currentPath) . "&delete=" . safe($itemReal) . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>]";
    }
    echo "</li>";
}
echo "</ul>";

// File editing
if (isset($_GET['edit'])) {
    $file = realpath($_GET['edit']);
    // if (strpos($file, ROOT_PATH) === 0 && is_file($file)) {
        $content = htmlspecialchars(file_get_contents($file));
        echo "<h3>Editing: " . safe($file) . "</h3>";
        echo "<form method='post'>
                <input type='hidden' name='filepath' value='" . safe($file) . "'>
                <textarea name='content' rows='20' cols='100'>$content</textarea><br>
                <button type='submit' name='save_file'>Save</button>
              </form>";
    // } else {
    //     die("Access denied.");
    // }
}
?>
