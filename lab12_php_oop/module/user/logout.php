<?php
session_destroy();
header("Location: index.php?mod=user&page=login");
exit;
