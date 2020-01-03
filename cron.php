<?php
$rootPath = __DIR__;
$now = date('Y-m-d H:i:s');

exec("cd {$rootPath} && /usr/bin/git pull");
exec("/usr/bin/php -q {$rootPath}/01_fetch.php");
exec("/usr/bin/php -q {$rootPath}/03_merge.php");
exec("cd {$rootPath} && /usr/bin/git add -A");
exec("cd {$rootPath} && /usr/bin/git commit --author 'auto commit <noreply@localhost>' -m 'auto update @ {$now}'");
exec("cd {$rootPath} && /usr/bin/git push origin master");