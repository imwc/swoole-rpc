<?php

$configs = array();

$configs['host'] = '127.0.0.1';
$configs['port'] = 9701;

// swoole
$configs['swoole']['worker_num'] = 2; // Worker进程数量
$configs['swoole']['daemonize'] = false; // 是否守护进程化
$configs['swoole']['max_request'] = 5000; // 每个Worker最大处理任务数，之后会重启worker，避免内存泄漏