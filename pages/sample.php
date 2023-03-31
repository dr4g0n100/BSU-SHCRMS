<?php
// Set the path to the folder you want to display
$folder_path = "../logs";

// Get a list of all the files and directories in the folder
$dir_contents = scandir($folder_path);

// Output the data as an HTML table

foreach ($dir_contents as $item) {
    // Skip the special directories . and ..
    if ($item == "." || $item == "..") {
        continue;
    }
    // Check if the item is a file or directory
    if (!is_dir($folder_path . "/" . $item)) {
        // Open the file for reading
        $file = fopen("$folder_path/$item", "r");

        // Discard the first three lines
        for ($i = 0; $i < 3; $i++) {
            fgets($file);
        }

        //get filename as date
        $date = substr($item, 0, strrpos($item, '.'));

        // Read the file contents into a string
        $content = fread($file, filesize("$folder_path/$item"));

        //trim extra white spaces
        $content = trim($content);

        // Split the contents into an array of lines
        $lines = explode("\n", $content);

        // Close the file
        fclose($file);

        // Output the data as an HTML table
        echo "<table border='1'>";
        foreach ($lines as $line) {

            $arrayLine = explode(" - ", $line);

            echo "<tr>";
            echo "<td>" . htmlspecialchars($date) . "</td>";
            echo "<td>" . htmlspecialchars($arrayLine[0]) . "</td>";
            echo "<td>" . htmlspecialchars($arrayLine[1]) . "</td>";
            echo "</tr>";
        }
        
    }
}

?>