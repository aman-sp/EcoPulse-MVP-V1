<?php

class AdminGuard {
    public static function handle() {
        Auth::requireRole('admin');
    }
}
