<?php


class RecommendationEngine {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Get recommendations based on the sustainability score.
     * 
     * @param int $score The sustainability score (0-100)
     * @return array List of recommendations
     */
    public function getRecommendations(int $score): array {
        $recommendations = [];
        
        // Basic rule-based recommendations
        if ($score < 30) {
            $recommendations[] = "Significant improvements needed. Consider switching to renewable energy sources.";
            $recommendations[] = "Reduce transport emissions by carpooling, biking, or using public transport.";
        } elseif ($score < 70) {
            $recommendations[] = "Good effort, but there is room for improvement in your daily habits.";
            $recommendations[] = "Try to reduce energy consumption by turning off appliances when not in use.";
        } else {
            $recommendations[] = "Excellent sustainability score! Keep up the great work.";
        }

        // Database-driven recommendations
        try {
            $stmt = $this->pdo->prepare("SELECT recommendation_text FROM recommendations WHERE min_score <= :score AND max_score >= :score ORDER BY RAND() LIMIT 3");
            $stmt->execute(['score' => $score]);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recommendations[] = $row['recommendation_text'];
            }
        } catch (PDOException $e) {
            // Log error or silently fallback to basic recommendations if table doesn't exist
            // error_log("RecommendationEngine Error: " . $e->getMessage());
        }

        return $recommendations;
    }
}
