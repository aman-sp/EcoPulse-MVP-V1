<?php

class SustainabilityScorer {
    /**
     * Calculate a sustainability score (0-100) based on the carbon footprint.
     * 
     * @param float $carbonFootprint Total carbon footprint in kg CO2e.
     * @return int Sustainability score (100 is best).
     */
    public static function calculateScore(float $carbonFootprint): int {
        // Simple scoring logic: 0 to 100, where 100 is excellent (0 footprint)
        // Assuming 2000 kg is a baseline for 0 score
        $score = 100 - ($carbonFootprint / 20);
        
        if ($score < 0) {
            return 0;
        }
        
        if ($score > 100) {
            return 100;
        }
        
        return (int) $score;
    }
}
