<?php

class HospitalGuard {
    public static function handle() {
        Auth::requireRole('hospital');
    }
}
