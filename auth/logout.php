<?php
session_unset();
session_destroy();
header("Location: http://localhost/math_gineer/index.php?page=login");
exit;
