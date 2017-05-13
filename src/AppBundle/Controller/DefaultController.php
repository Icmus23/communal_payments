<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    const HOT_WATER_RATE            = 83.1;    // куб гарячей воды
    const COLD_WATER_RATE           = 6.84;    // куб холодной воды
    const WATER_OUTFALL             = 6.93;    // водоотвод
    const ELECTRICITY_LESS_THAN_100 = 0.9;   // электричество до 100 кВТ
    const ELECTRICITY_MORE_THAN_100 = 1.68;    // электричество больше 100 кВТ
    const FLAT_RATE                 = 98.12;  // содержание придворовых территорий
    const INTERCOM_RATE             = 15.60;   // домофон
    const HEATING_RATE              = 6.66; // отопление

    const HUNDRED                   = 100;

    /**
     * @Route("/", name="homepage")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $hotWaterCubicMeters = 3;
        $coldWaterCubicMeters = 3;
        $electricityKilowatts = 90;

        $hotWaterAmount = $this->calculateHotWaterAmount($hotWaterCubicMeters);
        $coldWaterAmount = $this->calculateColdWaterAmount($coldWaterCubicMeters);
        $electricityAmount = $this->calculateElectricityAmount($electricityKilowatts);
        $sum =
            $hotWaterAmount +
            $coldWaterAmount +
            $electricityAmount +
            self::FLAT_RATE +
            self::INTERCOM_RATE +
            self::HEATING_RATE;

        return $this->render('default/index.html.twig', [
            'hotWaterRate' => self::HOT_WATER_RATE,
            'coldWaterRate' => self::COLD_WATER_RATE,
            'waterOutfall' => self::WATER_OUTFALL,
            'electricityLessThan100Rate' => self::ELECTRICITY_LESS_THAN_100,
            'electricityMoreThan100Rate' => self::ELECTRICITY_MORE_THAN_100,
            'hotWaterCubicMeters' => $hotWaterCubicMeters,
            'coldWaterCubicMeters' => $coldWaterCubicMeters,
            'electricityKilowatts' => $electricityKilowatts,
            'hotWaterAmount' => $hotWaterAmount,
            'coldWaterAmount' => $coldWaterAmount,
            'electricityAmount' => $electricityAmount,
            'flatRate' => self::FLAT_RATE,
            'intercomRate' => self::INTERCOM_RATE,
            'heatingRate' => self::HEATING_RATE,
            'sum' => $sum,
            'hundred' => self::HUNDRED,
        ]);
    }

    /**
     * Calculates hot water amount
     *
     * @param $cubicMeters
     *
     * @return float
     */
    private function calculateHotWaterAmount($cubicMeters)
    {
        return $cubicMeters * (self::HOT_WATER_RATE + self::WATER_OUTFALL);
    }

    /**
     * Calculates cold water amount
     *
     * @param $cubicMeters
     *
     * @return float
     */
    private function calculateColdWaterAmount($cubicMeters)
    {
        return $cubicMeters * (self::COLD_WATER_RATE + self::WATER_OUTFALL);
    }

    /**
     * Calculates electricity amount
     *
     * @param $kilowatts
     *
     * @return float
     */
    private function calculateElectricityAmount($kilowatts)
    {
        if ($kilowatts > self::HUNDRED) {
            return self::HUNDRED *self::ELECTRICITY_LESS_THAN_100 +
                ($kilowatts - self::HUNDRED) * self::ELECTRICITY_MORE_THAN_100;
        } else {
            return $kilowatts * self::ELECTRICITY_LESS_THAN_100;
        }
    }
}
