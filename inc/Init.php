<?php
/**
 * @package castawaystravel
 * 
 * Init plugin
 */

namespace Inc;

final class Init{

    public static function get_services(){
        return [
			Base\General\EnqueueController::class,
			Base\General\ExpireGroupTripsController::class,
			Base\General\AdminSettingsController::class,

			Base\MyGroupTrips\MyGroupTripsController::class,
			Base\MyGroupTrips\MyGroupTripsCustomFields::class,
			Base\MyGroupTrips\MyGroupTripsFrontPageController::class,
			Base\MyDeals\MyDealController::class,
			Base\MyDeals\MyDealCustomFields::class,
        ];
    }

    public static function register_services(){
        foreach (self::get_services() as $class){
			$service = self::instantiate($class);
			if(method_exists($service, 'register')){
				$service->register();
			}
        }
    }

	private static function instantiate($class){
		$service = new $class;
		return $service;
	}

}