<?php

class CarbonCalculator {
    /**
     * Default emission factors aligned to Indian government / agency reference values
     * commonly used for healthcare facility carbon accounting in India.
     */
    private const GRID_ELECTRICITY_FACTOR = 0.82;       // kgCO2/kWh, CEA baseline
    private const DIESEL_FACTOR = 2.68;                  // kgCO2/litre, IPCC / combustion standard
    private const PETROL_FACTOR = 2.31;                  // kgCO2/litre
    private const WATER_FACTOR = 0.344;                  // kgCO2/kL
    private const BIOMEDICAL_WASTE_FACTOR = 0.50;       // kgCO2/kg
    private const MEDICAL_OXYGEN_FACTOR = 0.52;          // kgCO2/m3
    private const NITROUS_OXIDE_FACTOR = 265.0;          // kgCO2e/kg
    private const ANAESTHETIC_GAS_FACTOR = 2540.0;       // kgCO2e/kg
    private const REFRIGERANT_FACTOR = 1430.0;           // kgCO2e/kg

    /**
     * Calculate carbon footprint based on hospital activity data.
     *
     * @param array $data Input data containing electricity, fuel, waste, water, gases, etc.
     * @return float Total carbon footprint in kg CO2e.
     */
    public static function calculate(array $data): float {
        $totalCarbon = 0.0;

        $volumeMap = [
            'electricity_kwh' => self::GRID_ELECTRICITY_FACTOR,
            'grid_electricity_kwh' => self::GRID_ELECTRICITY_FACTOR,
            'diesel_litres' => self::DIESEL_FACTOR,
            'diesel_l' => self::DIESEL_FACTOR,
            'petrol_litres' => self::PETROL_FACTOR,
            'petrol_l' => self::PETROL_FACTOR,
            'water_kl' => self::WATER_FACTOR,
            'municipal_water_kl' => self::WATER_FACTOR,
            'waste_kg' => self::BIOMEDICAL_WASTE_FACTOR,
            'biomedical_waste_kg' => self::BIOMEDICAL_WASTE_FACTOR,
            'medical_oxygen_m3' => self::MEDICAL_OXYGEN_FACTOR,
            'oxygen_m3' => self::MEDICAL_OXYGEN_FACTOR,
            'nitrous_oxide_kg' => self::NITROUS_OXIDE_FACTOR,
            'n2o_kg' => self::NITROUS_OXIDE_FACTOR,
            'anaesthetic_gas_kg' => self::ANAESTHETIC_GAS_FACTOR,
            'desflurane_kg' => self::ANAESTHETIC_GAS_FACTOR,
            'refrigerant_kg' => self::REFRIGERANT_FACTOR,
            'hfc_134a_kg' => self::REFRIGERANT_FACTOR,
        ];

        foreach ($volumeMap as $key => $factor) {
            if (isset($data[$key]) && is_numeric($data[$key])) {
                $totalCarbon += (float) $data[$key] * $factor;
            }
        }

        return $totalCarbon;
    }
}
