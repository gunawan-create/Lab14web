<?php
class Form {

    public function input($name, $value = '') {
        echo "<input type='text' name='$name' value='$value' required><br><br>";
    }

    public function textarea($name, $value = '') {
        echo "<textarea name='$name' required>$value</textarea><br><br>";
    }

    public function submit($text) {
        echo "<button type='submit'>$text</button>";
    }
}
